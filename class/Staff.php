<?php

/**
 * Staff
 *
 * A class managing staff
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022, 1.1 => February 2023
 * @link        alabiansolutions.com
*/

class StaffExpection extends Exception
{
}

class Staff extends User
{
    /** @var TblStaff an instance of TblStaff  */
    protected TblStaff $tblStaff;

    /**
     * instantiation of Staff
     *
     * @param int $id the profile table id
     */
    public function __construct(int $id)
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query();
        
        $errors = [];
        $this->tblLogger = new TblLogger();
        $this->tblProfile = new TblProfile();
        $this->tblStaff = new TblStaff();
        if ($this->tblProfile->get($id)) {
            if (!Staff::isProfileIdForStaff($id)) {
                $errors[] = "profile id not associated with a staff";
            }
        } else {
            $errors[] = "invalid profile ";
        }
                
        if ($errors) {
            throw new StaffExpection("Staff instantiation error: ".implode(", ", $errors));
        }
        
        $this->id = $id;
    }

    /**
     * for creating a new user
     *
     * @param string $name name of the staff
     * @param string $profileSubType the profile type sub type
     * @param string $password unhashed password (min 8 characters, at least one alphabet and one digit)
     * @param string $identity unique identifier value either the email, phone or username
     * @param string $identityType unique identifier type either email, phone or username
     * @param int $gradeId must be 0 bcos staff dont have grade
     * @param string $profileType staff profile type which is 'staff'
     * @return array info of the new created user ['logger'=>$l, 'profile'=>$p, 'staff'=>$s]
    */
    public static function create(
        string $name,
        string $profileSubType,
        string $password,
        string $identity,
        string $identityType,
        int $gradeId = 0,
        string $profileType = TblStaff::TABLE
    ):array {
        $userInfo = parent::create($name, $profileSubType, $password, $identity, $identityType, 0, TblStaff::TABLE);

        try {
            $TblStaff = new TblStaff();
            $columns = [
                TblStaff::PROFILE_ID=>[$userInfo['profile'][TblProfile::ID], 'isValue'],
                TblStaff::TYPE=>[$profileSubType, 'isValue'],
            ];
            $staffId = $TblStaff->insert($columns);

            $staffInfo = [
                'logger'=>$userInfo['logger'],
                'profile'=>$userInfo['profile'],
                'staff'=>$TblStaff->get($staffId)
            ];
                
            $body = "
                <p style='margin-bottom:10px; margin-top:10px;'>Good Day $name</p>
                <p style='margin-bottom:10px;'>
                    We will like to notify you that an account has been created for you on our system. Your account details are below
                </p>
                <p style='margin-bottom:10px;'>
                    Username: $identity<br/>
                    Password: $password <small>(pls change your password on first successful login)</small><br/>
                    Profile Type: $profileSubType<br/>                
                </p>            
                <p style='margin-bottom:60px;'>
                    If you did not request for this account please ignore this mail
                </p>
            ";
            $Notification = new Notification();
            $to = ["$name"=>$identity];
            $settings = (new Settings(SETTING_FILE, true))->getDetails();
            $from = [$settings->sitename=>"{$settings->emails[0]}@{$settings->domain}"];
            $Notification->sendMail(['to'=>$to, 'from'=>$from], 'Account Creation', $body);
        } catch (Exception $e) {
            (new TblProfile())->deleteById($userInfo['profile'][TblProfile::ID]);
            (new TblLogger())->deleteById($userInfo['logger'][TblLogger::ID]);
            throw new StaffExpection($e->getMessage());
        }

        return $staffInfo;
    }

    /**
     * for changing a staff sub profile
     *
     * @param string $subProfileType sub profile type to be changed to
     * @return void
     */
    public function changeSubProfileType(string $subProfileType)
    {
        $cols  = [TblStaff::TYPE => [$subProfileType, 'isValue']];
        $where = [TblStaff::PROFILE_ID => ['=', $this->id, 'isValue']];
        $this->tblStaff->update($cols, $where);
    }

    /**
     * for retrieving several staff information
     *
     * @param integer $start the staff.id to start the staff info from, $start been inclusive
     * @param integer $count the number of users info to retrieve
     * @param string $type the staff sub profile type
     * @return array
     */
    public static function getAllUserInfo(int $start = 0, int $count = 10000, $type = ""):array
    {
        $usersInfo = [];
        $Query = new Query();
        $where = "";
        if($type) {
            if(!in_array($type, (new ProfileTypeMgr)->getAllProfileTypes()[TblStaff::TABLE])) {
                throw new StaffExpection("invalid staff sub profile type");
            }
            $where = " WHERE " . TblStaff::TYPE. " = '$type' ";
        }
        $sql = "SELECT ".TblStaff::ID.", ".TblStaff::PROFILE_ID." 
            FROM ".TblStaff::TABLE." 
            $where 
            ORDER BY ".TblStaff::ID." DESC LIMIT $count OFFSET $start";
        if ($result = $Query->executeSql($sql)['rows']) {
            $query = new Query();
            foreach ($result as $aResult) {
                $aUser = [];
                $sql = "SELECT ".TblLogger::TABLE.".* FROM ".TblLogger::TABLE." INNER JOIN ".TblProfile::TABLE." ON ".TblLogger::TABLE.".".TblLogger::ID." =
                    ".TblProfile::TABLE.".".TblProfile::LOGGER_ID." WHERE ".TblProfile::TABLE.".".TblProfile::ID." = :id";
                $bind = ['id' => $aResult[TblStaff::PROFILE_ID]];
                $aUser['logger'] = $query->executeSql($sql, $bind)['rows'][0];
                $aUser['profile'] = (new TblProfile())->get($aResult[TblStaff::PROFILE_ID]);
                $aUser['staff'] = (new TblStaff())->get($aResult[TblStaff::ID]);
                $usersInfo[] = $aUser;
            }
        }
        return $usersInfo;
    }

    /**
     * for editing staff information
     *
     * @param array $columns ['logger'=>[], 'profile'=>[], 'staff'=>[]] an array of staff information
     * @return void
     */
    public function edit(array $columns)
    {
        if (!isset($columns['logger']) || !isset($columns['profile']) || !isset($columns['staff'])) {
            $errors = [];
            if (!isset($columns['logger'])) {
                $errors[] = "logger index is missing in column ";
            }
            if (!isset($columns['profile'])) {
                $errors[] = "profile index is missing in column ";
            }
            if (!isset($columns['staff'])) {
                $errors[] = "staff index is missing in column ";
            }
            if ($errors) {
                throw new StaffExpection("Staff editing error: ".implode(", ", $errors));
            }
        }
        $loggerCol = [];
        if (isset($columns['logger'][TblLogger::PASSWORD])) {
            $loggerCol[TblLogger::PASSWORD] = [$columns['logger'][TblLogger::PASSWORD], 'isValue'];
        }
        if (isset($columns['logger'][TblLogger::EMAIL])) {
            $loggerCol[TblLogger::EMAIL] = [$columns['logger'][TblLogger::EMAIL], 'isValue'];
        }
        if (isset($columns['logger'][TblLogger::PHONE])) {
            $loggerCol[TblLogger::PHONE] = [$columns['logger'][TblLogger::PHONE], 'isValue'];
        }
        if (isset($columns['logger'][TblLogger::USERNAME])) {
            $loggerCol[TblLogger::USERNAME] = [$columns['logger'][TblLogger::USERNAME], 'isValue'];
        }
        if ($loggerCol) {
            $this->tblLogger->updateById($loggerCol, User::loginIdFrmProfileId($this->id));
        }

        $profileCol = [];
        if (isset($columns['profile'][TblProfile::NAME])) {
            $profileCol[TblProfile::NAME] = [$columns['profile'][TblProfile::NAME], 'isValue'];
        }
        if (isset($columns['profile'][TblProfile::EMAILS])) {
            $profileCol[TblProfile::EMAILS] = [$columns['profile'][TblProfile::EMAILS], 'isValue'];
        }
        if (isset($columns['profile'][TblProfile::PHONES])) {
            $profileCol[TblProfile::PHONES] = [$columns['profile'][TblProfile::PHONES], 'isValue'];
        }
        if (isset($columns['profile'][TblProfile::GENDER])) {
            $profileCol[TblProfile::GENDER] = [$columns['profile'][TblProfile::GENDER], 'isValue'];
        }
        if (isset($columns['profile'][TblProfile::BIRTHDAY])) {
            $profileCol[TblProfile::BIRTHDAY] = [$columns['profile'][TblProfile::BIRTHDAY], 'isValue'];
        }
        if (isset($columns['profile'][TblProfile::ADDRESS])) {
            $profileCol[TblProfile::ADDRESS] = [$columns['profile'][TblProfile::ADDRESS], 'isValue'];
        }
        if ($profileCol) {
            $this->tblProfile->updateById($profileCol, $this->id);
        }

        $typeCol = [];
        if (isset($columns['staff'][TblStaff::TYPE])) {
            $typeCol[TblStaff::TYPE] = [$columns['staff'][TblStaff::TYPE], 'isValue'];
        }
        if ($typeCol) {
            $this->tblStaff->update($typeCol, [TblStaff::PROFILE_ID => ['=', $this->id, 'isValue']]);
        }
    }

    /**
     * for checking if the id is for a staff
     *
     * @param int $id the profile
     * @return void
     */
    public static function isProfileIdForStaff(int $id):bool
    {
        $yesItIs = false;
        $sql = "SELECT ".$id." FROM";
        $sql = "SELECT ".TblProfile::TABLE.".".TblProfile::ID." FROM ".TblProfile::TABLE." INNER JOIN ".TblProfileType::TABLE."
            ON ".TblProfile::TABLE.".".TblProfile::PROFILE_TYPE." = ".TblProfileType::TABLE.".".TblProfileType::ID." WHERE 
            ".TblProfile::TABLE.".".TblProfile::ID." = :id AND ".TblProfileType::TABLE.".".TblProfileType::NAME." = :name";
        $bind = ['id'=>$id, 'name'=>TblStaff::TABLE];
        $Query  = new Query();
        if ($Query->executeSql($sql, $bind)['rows']) {
            $yesItIs = true;
        }
        return $yesItIs;
    }
}
