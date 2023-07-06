<?php

/**
 * TblProfile
 *
 * A class for handling TblProfile table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022, 1.1 => January 2023
 * @link        alabiansolutions.com
*/

class TblProfileExpection extends Exception
{
}

class TblProfile extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string table name*/
    public const TABLE = "profile";

    /**
     * instantiation of TblProfile
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblProfile::TABLE);
    }

    /** @var int profile type column */
    protected int $profileType;

    /** @var string name column */
    protected string $name;

    /** @var string picture column */
    protected string $picture;

    /** @var string emails column */
    protected string $emails;

    /** @var string phones column */
    protected string $phones;

    /** @var string gender column */
    protected string $gender;

    /** @var string birthday column */
    protected string $birthday;

    /** @var string address column */
    protected $address;

    /** @var string profile type*/
    public const PROFILE_TYPE = "profile_type";

    /** @var string name*/
    public const NAME = "name";

    /** @var string picture*/
    public const PICTURE = "picture";

    /** @var string emails*/
    public const EMAILS = "emails";

    /** @var string phones*/
    public const PHONES = "phones";

    /** @var string gender*/
    public const GENDER = "gender";

    /** @var array gender values*/
    public const GENDER_VALUES = ['male', 'female'];

    /** @var string birthday*/
    public const BIRTHDAY = "birthday";

    /** @var string address*/
    public const ADDRESS = "address";

    /**
     * set type
     *
     * @param int profile type id of this profile
    */
    public function setProfileType(int $profileType)
    {
        $errors = [];
        $ProfileType = new TblProfileType();
        if (!$ProfileType->get($profileType)) {
            $errors = ["invalid profile type.id '$profileType'"];
        }
        if ($errors) {
            throw new TblProfileExpection("TblProfile Error: profile type issue '".implode(", ", $errors)."'.");
        }

        $this->profileType = $profileType;
    }

    /**
     * get type
     *
     * @return string profile type id of this profile
    */
    public function getProfileType():int
    {
        return $this->profileType;
    }

    /**
     * get name
     *
     * @return string name of the profile
    */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * set name
     *
     * @param string name of the profile
    */
    public function setName(string $name)
    {
        $errors = [];
        if (empty($name)) {
            $errors[] = "name required ";
        }
        if (strlen($name) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblProfileExpection("TblProfile Error: name issue '".implode(", ", $errors)."'.");
        }

        $this->name = $name;
    }

    /**
     * get picture
     *
     * @return string picture of the profile
    */
    public function getPicture():string
    {
        return $this->picture;
    }

    /**
     * set picture
     *
     * @param string picture of the profile
    */
    public function setPicture(string $picture)
    {
        $errors = [];
        if ($picture) {
            if (strlen($picture) > SqlType::VARCHAR_LENGTH) {
                $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
            }
        }
        if ($errors) {
            throw new TblProfileExpection("TblProfile Error: picture '".implode(", ", $errors)."'.");
        }

        $this->picture = $picture;
    }

    /**
     * get emails
     *
     * @return string emails of the profile
    */
    public function getEmails():string
    {
        return $this->emails;
    }

    /**
     * set emails
     *
     * @param array emails of the profile
    */
    public function setEmails(array $emails)
    {
        $errors = [];
        if (!$emails) {
            $errors[] = "email(s) is required ";
        } else {
            foreach ($emails as $anEmail) {
                if (!filter_var(trim($anEmail), FILTER_VALIDATE_EMAIL)) {
                    $errors[] = " invalid email $emails ";
                    break;
                }
            }
        }
        if ($errors) {
            throw new TblProfileExpection("TblProfile Error: emails issue '".implode(", ", $errors)."'.");
        }

        $this->emails = json_encode($emails);
    }

    /**
     * get phones
     *
     * @return string phones of the profile
    */
    public function getPhones():string
    {
        return $this->phones;
    }

    /**
     * set phones
     *
     * @param array phones of the profile
    */
    public function setPhones(array $phones)
    {
        $errors = [];
        if (!$phones) {
            $errors[] = "phone(s) is required ";
        }
        if ($errors) {
            throw new TblProfileExpection("TblProfile Error: phones issue '".implode(", ", $errors)."'.");
        }

        $this->phones = json_encode($phones);
    }
    
    /**
     * set gender
     *
     * @param string gender of the profile
    */
    public function setGender(string $gender)
    {
        $errors = [];
        if (!in_array(strtolower($gender), TblProfile::GENDER_VALUES)) {
            $errors[] = "invalid gender '$gender'";
        }
        if ($errors) {
            throw new TblProfileExpection("TblProfile Error: gender issue '".implode(", ", $errors)."'.");
        }

        $this->gender = strtolower($gender);
    }

    /**
     * get gender
     *
     * @return string gender of the profile
    */
    public function getGender():string
    {
        return $this->gender;
    }
    
    /**
     * set birthday
     * @param DateTime birthday of the profile
    */
    public function setBirthday(DateTime $birthday)
    {
        if ($birthday > new DateTime(SqlType::DATETIME_MAX) || $birthday < new DateTime(SqlType::DATETIME_MIN)) {
            throw new AbstractTableExpection("AbstractTable Error: created at is outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX);
        }
        $this->birthday = $birthday->format('Y-m-d');
    }

    /**
     * get birthday
     * @return string birthday of the profile
    */
    public function getBirthday():string
    {
        return $this->birthday;
    }

    /**
     * get address
     *
     * @return string address
    */
    public function getAddress():string
    {
        return $this->address;
    }

    /**
     * set address
     *
     * @param string address
    */
    public function setAddress(string $address)
    {
        $errors = [];
        if (empty($address)) {
            $errors[] = "address is required";
        }

        if (strlen($address) > SqlType::TEXT_LENGTH) {
            $errors[] = "max length ".SqlType::TEXT_LENGTH;
        }
        if ($errors) {
            throw new TblProfileExpection("Profile Error: address issue '".implode(", ", $errors)."'.");
        }

        $this->address = $address;
    }
    
    /**
     * insert data into profile table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        if (!isset($cols[TblProfile::PROFILE_TYPE]) || !isset($cols[TblProfile::NAME])) {
            $errors = [];
            if (!isset($cols[TblProfile::PROFILE_TYPE])) {
                $errors[] = "type required";
            }
            if (!isset($cols[TblProfile::NAME])) {
                $errors[] = "name required";
            }
            if ($errors) {
                throw new TblProfileExpection("TblProfile Error: insert data issue '".implode(", ", $errors)."'.");
            }
        }
        if (isset($cols[TblProfile::LOGGER_ID])) {
            if ($this->select([TblProfile::ID], [TblProfile::LOGGER_ID=>['=', $cols[TblProfile::LOGGER_ID][0], 'isValue']])) {
                throw new TblProfileExpection("TblProfile Error: logger id '{$cols[TblProfile::LOGGER_ID][0]}' already has a profile");
            }
        
            $this->setTblLoggerId($cols[TblProfile::LOGGER_ID][0]);
            $cols[TblProfile::LOGGER_ID][0] = $this->getTblLoggerId();
        }
        $this->setTblLoggerId($cols[TblProfile::LOGGER_ID][0]);
        $cols[TblProfile::LOGGER_ID][0] = $this->getTblLoggerId();
        $this->setProfileType($cols[TblProfile::PROFILE_TYPE][0]);
        $cols[TblProfile::PROFILE_TYPE][0] = $this->getProfileType();
        $this->setName($cols[TblProfile::NAME][0]);
        $cols[TblProfile::NAME][0] = $this->getName();
        if (isset($cols[TblProfile::PICTURE])) {
            $this->setPicture($cols[TblProfile::PICTURE][0]);
            $cols[TblProfile::PICTURE][0] = $this->getPicture();
        }
        if (isset($cols[TblProfile::EMAILS])) {
            $this->setEmails($cols[TblProfile::EMAILS][0]);
            $cols[TblProfile::EMAILS][0] = $this->getEmails();
        }
        if (isset($cols[TblProfile::PHONES])) {
            $this->setPhones($cols[TblProfile::PHONES][0]);
            $cols[TblProfile::PHONES][0] = $this->getPhones();
        }
        if (isset($cols[TblProfile::GENDER])) {
            $this->setGender($cols[TblProfile::GENDER][0]);
            $cols[TblProfile::GENDER][0] = $this->getGender();
        }
        if (isset($cols[TblProfile::BIRTHDAY])) {
            $this->setBirthday($cols[TblProfile::BIRTHDAY][0]);
            $cols[TblProfile::BIRTHDAY][0] = $this->getBirthday();
        }
        if (isset($cols[TblProfile::ADDRESS])) {
            $this->setAddress($cols[TblProfile::ADDRESS][0]);
            $cols[TblProfile::ADDRESS][0] = $this->getAddress();
        }
        
        return $this->query->insert($cols);
    }

    /**
     * update data in profile table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string table a table name in the database
     * @param int the no of updated rows
    */
    public function update(array $cols, array $where, ?string $table = null):int
    {
        if (isset($cols[TblProfile::LOGGER_ID])) {
            $this->setTblLoggerId($cols[TblProfile::LOGGER_ID][0]);
            $cols[TblProfile::LOGGER_ID][0] = $this->getTblLoggerId();
        }
        if (isset($cols[TblProfile::PROFILE_TYPE])) {
            $this->setProfileType($cols[TblProfile::PROFILE_TYPE][0]);
            $cols[TblProfile::PROFILE_TYPE][0] = $this->getProfileType();
        }
        if (isset($cols[TblProfile::NAME])) {
            $this->setName($cols[TblProfile::NAME][0]);
            $cols[TblProfile::NAME][0] = $this->getName();
        }
        if (isset($cols[TblProfile::PICTURE])) {
            $this->setPicture($cols[TblProfile::PICTURE][0]);
            $cols[TblProfile::PICTURE][0] = $this->getPicture();
        }
        if (isset($cols[TblProfile::EMAILS])) {
            $this->setEmails($cols[TblProfile::EMAILS][0]);
            $cols[TblProfile::EMAILS][0] = $this->getEmails();
        }
        if (isset($cols[TblProfile::PHONES])) {
            $this->setPhones($cols[TblProfile::PHONES][0]);
            $cols[TblProfile::PHONES][0] = $this->getPhones();
        }
        if (isset($cols[TblProfile::GENDER])) {
            $this->setGender($cols[TblProfile::GENDER][0]);
            $cols[TblProfile::GENDER][0] = $this->getGender();
        }
        if (isset($cols[TblProfile::BIRTHDAY])) {
            $this->setBirthday($cols[TblProfile::BIRTHDAY][0]);
            $cols[TblProfile::BIRTHDAY][0] = $this->getBirthday();
        }
        if (isset($cols[TblProfile::ADDRESS])) {
            $this->setAddress($cols[TblProfile::ADDRESS][0]);
            $cols[TblProfile::ADDRESS][0] = $this->getAddress();
        }
        
        return $this->query->update($cols, $where);
    }
    
    /**
     * generate colums for the update method
     *
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
     * @return array the generated columns
     */
    protected function generateUpdateColumn(array $cols):array
    {
        if (isset($cols[TblProfile::LOGGER_ID])) {
            $this->setTblLoggerId($cols[TblProfile::LOGGER_ID][0]);
            $cols[TblProfile::LOGGER_ID][0] = $this->getTblLoggerId();
        }
        if (isset($cols[TblProfile::PROFILE_TYPE])) {
            $this->setProfileType($cols[TblProfile::PROFILE_TYPE][0]);
            $cols[TblProfile::PROFILE_TYPE][0] = $this->getProfileType();
        }
        if (isset($cols[TblProfile::NAME])) {
            $this->setName($cols[TblProfile::NAME][0]);
            $cols[TblProfile::NAME][0] = $this->getName();
        }
        if (isset($cols[TblProfile::PICTURE])) {
            $this->setPicture($cols[TblProfile::PICTURE][0]);
            $cols[TblProfile::PICTURE][0] = $this->getPicture();
        }
        if (isset($cols[TblProfile::EMAILS])) {
            $this->setEmails($cols[TblProfile::EMAILS][0]);
            $cols[TblProfile::EMAILS][0] = $this->getEmails();
        }
        if (isset($cols[TblProfile::PHONES])) {
            $this->setPhones($cols[TblProfile::PHONES][0]);
            $cols[TblProfile::PHONES][0] = $this->getPhones();
        }
        if (isset($cols[TblProfile::GENDER])) {
            $this->setGender($cols[TblProfile::GENDER][0]);
            $cols[TblProfile::GENDER][0] = $this->getGender();
        }
        if (isset($cols[TblProfile::BIRTHDAY])) {
            $this->setBirthday($cols[TblProfile::BIRTHDAY][0]);
            $cols[TblProfile::BIRTHDAY][0] = $this->getBirthday();
        }
        if (isset($cols[TblProfile::ADDRESS])) {
            $this->setAddress($cols[TblProfile::ADDRESS][0]);
            $cols[TblProfile::ADDRESS][0] = $this->getAddress();
        }
        return $cols;
    }

    /**
     * create profile table in the database
     *
     * @param string table a table name in the database usually 'profile'
     * @param array tableStructure an array representing the profile table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblProfile::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblProfileExpection("TblProfile Error: Table '".TblProfile::TABLE."' already exist");
        }
        if (!in_array(TblLogger::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblProfileExpection("TblProfile Error: Table '".TblLogger::TABLE."' does not exist");
        }
        
        $sql = "START TRANSACTION;";
        if ($tableStructure) {
            //TODO: implementation if table structure is given is coming later
            throw new TblProfileExpection("TblProfile Error: creating table from Table structure not done yet");
            foreach ($tableStructure as $row) {
            }
        }
        
        $sql .= "
            CREATE TABLE ".TblProfile::TABLE." (
            ".TblProfile::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblProfile::LOGGER_ID." int(10) UNSIGNED NULL,            
            ".TblProfile::PROFILE_TYPE." int(10) UNSIGNED NOT NULL,            
            ".TblProfile::NAME." varchar(255) NOT NULL,
            ".TblProfile::PICTURE." varchar(255) NULL  DEFAULT 'default.png',
            ".TblProfile::EMAILS." JSON NULL COMMENT '[email1, email2, ...] a collection of emails',
            ".TblProfile::PHONES." JSON NULL COMMENT '[phones1, phones2, ...] a collection of phones',
            ".TblProfile::GENDER." enum('".implode("','", TblProfile::GENDER_VALUES)."') NOT NULL DEFAULT '".TblProfile::GENDER_VALUES[1]."',
            ".TblProfile::BIRTHDAY." DATE NULL,
            ".TblProfile::ADDRESS." TEXT NULL,            
            ".TblProfile::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblProfile::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblProfile::ID."),
            UNIQUE KEY (".TblProfile::LOGGER_ID."),
            KEY (".TblProfile::PROFILE_TYPE.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
            ALTER TABLE ".TblProfile::TABLE."
                ADD CONSTRAINT ".TblProfile::TABLE."_ibfk_1 FOREIGN KEY (".TblProfile::LOGGER_ID.") REFERENCES ".TblLogger::TABLE." (".TblLogger::ID.") ON DELETE RESTRICT ON UPDATE CASCADE;
            ALTER TABLE ".TblProfile::TABLE."
                ADD CONSTRAINT ".TblProfile::TABLE."_ibfk_2 FOREIGN KEY (".TblProfile::PROFILE_TYPE.") REFERENCES ".TblProfileType::TABLE." (".TblProfileType::ID.") ON DELETE RESTRICT ON UPDATE CASCADE;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating profile table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord=0, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * for generating some random name
     *
     * @return array nameCollection an array of some names
    */
    private function generateDummyName():array
    {
        $nameCollection = [
            "Tunde Peter", "Abiodun Rashford", "Abiola Martins", "Ronaldo Muhummadu",
            "Busayo Jonathan", "Benedict Opeyemi", "Joshua Bayo Joseph", "Ahamed Olu",
            "Idris Emeka","Ebele Yemisi","Kunle Aford Johnson", "Akeem Obinna Louis"];
        return $nameCollection;
    }

    /**
     * generate sql statement for inserting dummy records into profile table'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $Query = new Query(TblLogger::TABLE);
        if ($loggerIds = $Query->select([TblLogger::ID])) {
            $TblAgentQuery = new Query(TblStaff::TABLE);
            foreach ($loggerIds as $loggerId) {
                $name = $this->generateDummyName()[rand(0, count($this->generateDummyName()) - 1)];
                $profileType = $TblAgentQuery->get($loggerId[TblLogger::ID]) ? 1 : 2;
                $dummyData[] =
                    [TblProfile::LOGGER_ID=>$loggerId[TblLogger::ID], TblProfile::PROFILE_TYPE=>$profileType, TblProfile::NAME=>$name, TblProfile::GENDER=>TblProfile::GENDER_VALUES[0]];
            }
        }
        $sql = "INSERT INTO ".TblProfile::TABLE." (".TblProfile::LOGGER_ID.", ".TblProfile::PROFILE_TYPE." , ".TblProfile::NAME." , ".TblProfile::GENDER.") VALUES";
        
        foreach ($dummyData as $aData) {
            $loggerId = $aData[TblProfile::LOGGER_ID];
            $profileType = $aData[TblProfile::PROFILE_TYPE];
            $name = $aData[TblProfile::NAME];
            $gender = $aData[TblProfile::GENDER];
            $sql .= "($loggerId, '$profileType', '$name', '$gender'),";
            ;
        }
        $sql = rtrim($sql, ",");
        return $sql;
    }
}
