<?php

/**
 * User
 *
 * A class managing users
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022, 1.1 => February 2023
 * @link        alabiansolutions.com
*/

class UserExpection extends Exception
{
}

class User
{
    /** @var DbConnect an instance of DbConnect  */
    protected DbConnect $dbConnect;

    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var TblLogger an instance of TblLogger  */
    protected TblLogger $tblLogger;

    /** @var TblProfile an instance of TblProfile  */
    protected TblProfile $tblProfile;

    /** @var int profile table id*/
    protected int $id;

    /**
     * instantiation of User
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
        if (!$this->tblProfile->get($id)) {
            $errors[] = "invalid profile ";
        }
                
        if ($errors) {
            throw new UserExpection("User instantiation error: ".implode(", ", $errors));
        }
        
        $this->id = $id;
    }

    /**
     * get a all collection of all user's identityType
     *
     * @return array
     */
    public static function getAllIdentityType():array
    {
        return [TblLogger::ID, TblLogger::EMAIL, TblLogger::PHONE, TblLogger::USERNAME];
    }
    
    /**
     * for creating a new user
     *
     * @param string name name of the user
     * @param string profileSubType the profile type sub type
     * @param string $password unhashed password (min 8 characters, at least one alphabet and one digit)
     * @param string $identity unique identifier value either the email, phone or username
     * @param string $identityType unique identifier type either email, phone or username
     * @param int $gradeId no use just pass zero
     * @param string profileType the profile type of this profile
     * @return array info of the newly created user ['logger'=>$l, 'profile'=>$p]
     */
    protected static function create(
        string $name,
        string $profileSubType,
        string $password,
        string $identity,
        string $identityType,
        int $gradeId,
        string $profileType,
    ):array {
        $userInfo = $loggerInfo = [];
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        
        try {
            $Query = new Query(TblLogger::TABLE);
            $DbConnect->beginTransaction();
            if ($password && $identity && $identityType) {
                if (!in_array($identityType, self::getAllIdentityType())) {
                    throw new UserExpection("invalid identity type");
                }
                $columnName = "$identityType , ".TblLogger::PASSWORD.", ".TblLogger::STATUS.", ".TblLogger::ACTIVATION_TOKEN.", ".TblLogger::ACTIVATION_TIME;
                $columnValue = ":$identityType , :".TblLogger::PASSWORD.", :".TblLogger::STATUS.", :".TblLogger::ACTIVATION_TOKEN.", :".TblLogger::ACTIVATION_TIME;
                $resultSql = "INSERT INTO " . TblLogger::TABLE . "($columnName) VALUES ($columnValue)";
                $TblLogger = new TblLogger();
                $password = $TblLogger->getPassword($TblLogger->setPassword($password));
                $activationToken = $TblLogger->getActivationToken($TblLogger->setActivationToken());
                $activationTokenTime = $TblLogger->getActivationTime($TblLogger->setActivationTime());
                if ($identityType == TblLogger::EMAIL) {
                    $identity = $TblLogger->getEmail($TblLogger->setEmail($identity));
                }
                if ($identityType == TblLogger::PHONE) {
                    $identity = $TblLogger->getPhone($TblLogger->setPhone($identity));
                }
                if ($identityType == TblLogger::USERNAME) {
                    $identity = $TblLogger->getUsername($TblLogger->setUsername($identity));
                }
                $resultBind = [
                    $identityType=>$identity, TblLogger::PASSWORD=>$password, TblLogger::STATUS=>TblLogger::STATUS_VALUES[1],
                    TblLogger::ACTIVATION_TOKEN=>$activationToken, TblLogger::ACTIVATION_TIME=>$activationTokenTime];
                $loggerId = $Query->executeSql($resultSql, $resultBind)['lastInsertId'];
                $loggerInfo = (new TblLogger())->get($loggerId);
            }

            $ProfileTypeMgr = new ProfileTypeMgr();
            $allProfileTypes = $ProfileTypeMgr->getAllProfileTypes();
            if (!isset($allProfileTypes[$profileType])) {
                throw new UserExpection("invalid profile type");
            } else {
                if (!in_array($profileSubType, $allProfileTypes[$profileType])) {
                    throw new UserExpection("invalid sub profile type");
                }
            }
            
            $Query->setTable(TblProfile::TABLE);
            $allProfileTypeIds  = $ProfileTypeMgr->getAllProfileTypeIds();
            $TblProfile = new TblProfile();
            $name = $TblProfile->getName($TblProfile->setName($name));
            $columnName = TblProfile::PROFILE_TYPE.", ".TblProfile::NAME;
            $columnValue = ":".TblProfile::PROFILE_TYPE.", :".TblProfile::NAME;
            $resultBind = [TblProfile::PROFILE_TYPE=>$allProfileTypeIds[$profileType], TblProfile::NAME=>$name];
            if ($loggerInfo) {
                $columnName .= ", ".TblProfile::LOGGER_ID;
                $columnValue .= ", :".TblProfile::LOGGER_ID;
                $resultBind[TblProfile::LOGGER_ID] = $loggerId;
            }
            $resultSql = "INSERT INTO " . TblProfile::TABLE . "($columnName) VALUES ($columnValue)";
            $profileId = $Query->executeSql($resultSql, $resultBind)['lastInsertId'];
            
            $DbConnect->commit();
        } catch (Exception $e) {
            $DbConnect->rollBack();
            throw new UserExpection($e->getMessage());
        }

        $userInfo = [
            'logger'=>$loggerInfo,
            'profile'=>$TblProfile->get($profileId),
        ];
        return $userInfo;
    }

    /**
     * for getting a user login id from their profile id
     *
     * @param integer $profileId the user profile id
     * @return int the user login id
     */
    public static function loginIdFrmProfileId(int $profileId):int
    {
        $loginId = (new TblProfile())->get($profileId)[TblProfile::LOGGER_ID];
        return $loginId;
    }

    /**
     * for getting a user profile id from their login id
     *
     * @param integer $loginId the user login id
     * @return int the user login id
     */
    public static function profileIdFrmLoginId(int $loginId):int
    {
        $profileId = (new TblProfile())->select([TblProfile::ID], [TblProfile::LOGGER_ID => ['=', $loginId, 'isValue']])[0][TblProfile::ID];
        return $profileId;
    }

    /**
     * for changing a user's login email
     *
     * @param string $name the user's email
     * @return void
     */
    public function changeEmail(string $email)
    {
        $columns = [TblLogger::EMAIL=>[$email, 'isValue']];
        $this->tblLogger->update($columns, [TblLogger::ID=>['=', User::loginIdFrmProfileId($this->id), 'isValue']]);
    }

    /**
     * for changing a user's login phone
     *
     * @param string $name the user's phone
     * @return void
     */
    public function changePhone(string $phone)
    {
        $columns = [TblLogger::PHONE=>[$phone, 'isValue']];
        $this->tblLogger->update($columns, [TblLogger::ID=>['=', User::loginIdFrmProfileId($this->id), 'isValue']]);
    }

    /**
     * for changing a user's login username
     *
     * @param string $name the user's username
     * @return void
     */
    public function changeUsername(string $username)
    {
        $columns = [TblLogger::USERNAME=>[$username, 'isValue']];
        $this->tblLogger->update($columns, [TblLogger::ID=>['=', User::loginIdFrmProfileId($this->id), 'isValue']]);
    }

    /**
     * for changing a user's name
     *
     * @param string $name the profile's name
     * @return void
     */
    public function changeName(string $name)
    {
        $columns = [TblProfile::NAME=>[$name, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * for changing a user's picture file and removing it to the server
     *
     * @param string $picture the path to the profile's picture file
     * @param string $ext the extension of the picture file [png, jpeg, gif, jpg] permitted
     * @return void
     */
    public function changePicture(string $picture, string $ext)
    {
        $filename = "p_{$this->id}.$ext";
        if (!copy($picture, Functions::getAvatarDirectoryPath().$filename)) {
            throw new UserExpection("User picture update failed");
        }
        $exts = ['png', 'jpeg', 'gif', 'jpg'];
        if (!in_array(strtolower($ext), $exts)) {
            throw new UserExpection("only png, jpeg, gif or jpg are permitted");
        }
        $columns = [TblProfile::PICTURE=>[$filename, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * for changing a user's email collection
     *
     * @param array $email the profile's emails array
     * @return void
     */
    public function changeEmails(array $emails)
    {
        $columns = [TblProfile::EMAILS=>[$emails, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * for changing a user's password
     *
     * @param string $password in plain text
     * @return void
     */
    public function changePassword(string $password)
    {
        try {
            $columns = [TblLogger::PASSWORD=>[$password, 'isValue']];
            $this->tblLogger->update($columns, [TblLogger::ID=>['=', $this->getLoginId($this->id), 'isValue']]);
        } catch (\Throwable $th) {
            throw new UserExpection($th->getMessage());
        }
    }

    /**
     * for changing a user's phone collection
     *
     * @param array $phones the profile's phones array
     * @return void
     */
    public function changePhones(array $phones)
    {
        $columns = [TblProfile::PHONES=>[$phones, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * for changing a user's gender
     *
     * @param array $gender the profile's gender
     * @return void
     */
    public function changeGender(string $gender)
    {
        $columns = [TblProfile::GENDER=>[$gender, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * for changing a user's birthday
     *
     * @param array $birthday the profile's birthday
     * @return void
     */
    public function changeBirthday(DateTime $birthday)
    {
        $columns = [TblProfile::BIRTHDAY=>[$birthday, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * for changing a user's address
     *
     * @param array $address the profile's address
     * @return void
     */
    public function changeAddress(string $address)
    {
        $columns = [TblProfile::ADDRESS=>[$address, 'isValue']];
        $this->tblProfile->update($columns, [TblProfile::ID=>['=',$this->id, 'isValue']]);
    }

    /**
     * get a user login id from the profile id
     *
     * @param integer $profileId profile id
     * @return int the login id
     */
    private function getLoginId(int $profileId):int
    {
        $loginId = $this->tblProfile->get($profileId)[TblProfile::LOGGER_ID];
        $loginId = $loginId ? $loginId : 0;
        return $loginId;
    }

    /**
     * for deactivating a user login ability
     *
     * @param boolean $notify if true the user will be notified via mail or sms
     * @return void
     */
    public function deactivate(bool $notify = false)
    {
        if ($this->getLoginId($this->id)) {
            $columns = [TblLogger::STATUS=>[TblLogger::STATUS_VALUES[0], 'isValue']];
            $this->tblLogger->update($columns, [TblLogger::ID=>['=', $this->getLoginId($this->id), 'isValue']]);

            if ($notify) {
                //TODO implement mail is sent to user after disactivation
            }
        }
    }

    /**
     * for reactivating a user login ability
     *
     * @param string $activationCode if activationCode is given then is check before activation
     * @param boolean $checkTime if the activation time is checked before activation
     * @param boolean $notify if true the user will be notified via mail or sms
     * @return array an array of reactivated status ['reactivated'=>$r, 'reason'=>$re];
     */
    public function reactivate(string $activationCode="", bool $checkTime=false, bool $notify = false):array
    {
        $reactivated = true;
        $reasons = [];
        
        if ($this->getLoginId($this->id)) {
            $userTableInfo = $this->tblLogger->get($this->id);
            if ($activationCode) {
                if ($activationCode != $userTableInfo[TblLogger::ACTIVATION_TOKEN]) {
                    $reactivated = false;
                    $reasons[] = "invalid activation code";
                }
            }
            if ($checkTime && new DateTime() > new DateTime($userTableInfo[TblLogger::ACTIVATION_TIME])) {
                $reactivated = false;
                $reasons[] = "expired activation code";
            }

            if ($reactivated) {
                $columns = [TblLogger::STATUS=>[TblLogger::STATUS_VALUES[1], 'isValue']];
                $this->tblLogger->update($columns, [TblLogger::ID=>['=', $this->getLoginId($this->id), 'isValue']]);
            }

            if ($notify) {
                //TODO implement mail is sent to user after reactivation
            }
        }
        
        return ['reactivated'=>$reactivated, 'reasons'=>implode(", ", $reasons)];
    }

    /**
     * for deleting a user
     *
     * @param boolean $notify if true the user will be notified via mail or sms
     * @param boolean $force if true all user info will be removed from other tables to avoid SQL Integrity Violation
     * @return void
     */
    public function remove(bool $notify = false, bool $force = false)
    {
        if ($force) {
            //TODO remove all user id from all related tables
            throw new UserExpection("force removal not supported");
        }

        try {
            $where = [TblProfile::ID => ['=', $this->id, 'isValue']];
            $result = $this->tblProfile->select([], $where)[0];
            $picture = $result[TblProfile::PICTURE];
            $userTypeTable = (new ProfileTypeMgr())->getInfo($result[TblProfile::PROFILE_TYPE])[TblProfileType::NAME];
            
            $sqlCollection = [];
            $sql = "DELETE FROM $userTypeTable WHERE ".TblStaff::PROFILE_ID." = :profileId";
            $bind = ['profileId' => $this->id];
            $sqlCollection[] = ['sql'=>$sql, 'bind' => $bind];
            $sql = "DELETE FROM ".TblProfile::TABLE." WHERE ".TblProfile::ID." = :profileId";
            $bind = ['profileId' => $this->id];
            $sqlCollection[] = ['sql'=>$sql, 'bind' => $bind];
            $sql = "DELETE FROM ".TblLogger::TABLE." WHERE ".TblLogger::ID." = :loggerId";
            $bind = ['loggerId' => User::loginIdFrmProfileId($this->id)];
            $sqlCollection[] = ['sql'=>$sql, 'bind' => $bind];
            if ($this->query->executeTransaction($sqlCollection)) {
                if ($picture != Functions::DEFAULT_AVATAR && $picture != Functions::DEFAULT_AVATAR_MALE && $picture != Functions::DEFAULT_AVATAR_FEMALE) {
                    unlink(Functions::getAvatarDirectoryPath().$picture);
                }
                    
                if ($notify) {
                    //TODO implement mail is sent to user after removal
                }
            } else {
                throw new UserExpection("failed");
            }
        } catch (\Throwable $th) {
            throw new UserExpection("user removal failed: ".$th->getMessage());
        }
    }

    /**
     * for retrieving a user information
     *
     * @param bool removePassword if true password will be removed from the user information
     * @return array info of the user ['l'=>$u, 'profile'=>$p, 'profileSub'=>$ps]
    */
    public function getInfo(bool $removePassword = true):array
    {
        $loginInfo = [];
        if ($loginId = $this->getLoginId($this->id)) {
            $loginInfo  = $this->tblLogger->get($loginId);
            if ($removePassword) {
                unset($loginInfo[TblLogger::PASSWORD]);
            }
        }
        
        $profileInfo = $this->tblProfile->get($this->id);
        $TblProfileType = new TblProfileType();
        $profileTypeName = $TblProfileType->get($profileInfo[TblProfile::PROFILE_TYPE])[TblProfileType::NAME];
        $this->query->setTable($profileTypeName);
        $profileTypeInfo = $this->query->select([], [TblProfile::TABLE=>['=', $this->id, 'isValue']]);

        $userInfo['logger'] = $loginInfo;
        $userInfo['profile'] = $profileInfo;
        $userInfo[$profileTypeName] = $profileTypeInfo[0];
        return $userInfo;
    }

    /**
     * for retrieving several user information
     *
     * @param integer $start the user.id to start the user info from, $start been inclusive
     * @param integer $count the number of users info to retrieve
     * @return array
     */
    public static function getAllUserInfo(int $start = 0, int $count = 10000):array
    {
        $usersInfo = [];
        $Query = new Query();
        $TblLogger = new TblLogger();
        $ProfileTypeMgr  = new ProfileTypeMgr();
        $allProfileTypes = $ProfileTypeMgr->getAllProfileTypeIds();
        $allProfileTypes = array_flip($allProfileTypes);
        $sql = "SELECT * FROM ".TblProfile::TABLE." ORDER BY ".TblProfile::ID." DESC LIMIT $count OFFSET $start";
        if ($result = $Query->executeSql($sql)['rows']) {
            foreach ($result as $aResult) {
                $aUser = [];
                $aUser['logger'] = $aResult[TblProfile::LOGGER_ID] ? $TblLogger->get($aResult[TblProfile::LOGGER_ID]) : [];
                $aUser['profile'] = $aResult;
                $Query->setTable($allProfileTypes[$aResult[TblProfile::PROFILE_TYPE]]);
                $aUser[$allProfileTypes[$aResult[TblProfile::PROFILE_TYPE]]] = $Query->select([], [TblProfile::TABLE=>['=', $aResult[TblProfile::ID], 'isValue']]);
                $usersInfo[] = $aUser;
            }
        }
        return $usersInfo;
    }
}
