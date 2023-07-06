<?php

/**
 * TblStaff
 *
 * A class for handling TblStaff table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => January 2023
 * @link        alabiansolutions.com
*/

class TblStaffExpection extends Exception
{
}

class TblStaff extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string type column */
    protected string $type;

    /** @var string type*/
    public const TYPE = "type";

    /** @var string table name*/
    public const TABLE = "staff";

    /**
     * instantiation of TblStaff
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblStaff::TABLE);
    }

    /**
     * set profile.id the foreign key from profile table
     *
     * @param int $profileId the profile.id from profile table
     * @param int id
     */
    public function setTblProfileId(int $profileId)
    {
        $TblProfile = new TblProfile();
        if ($info = $TblProfile->get($profileId)) {
            $TblProfileType = new TblProfileType();
            $where = [TblProfileType::NAME=>['=', TblStaff::TABLE, 'isValue']];
            $staffTypeId = $TblProfileType->select([TblProfileType::ID], $where)[0][TblProfileType::ID];
            if ($staffTypeId != $info[TblProfile::PROFILE_TYPE]) {
                throw new TblStaffExpection("TblStaff Error: profile id '$profileId' is not an staff");
            }
            if ($this->select([], [TblStaff::PROFILE_ID=>['=',$profileId, 'isValue']])) {
                throw new TblStaffExpection("TblStaff Error: logger id '$profileId' is already on staff table");
            }
        } else {
            throw new TblStaffExpection("TblStaff Error: invalid profile.id '$profileId'");
        }
        $this->profileId = $profileId;
    }

    /**
     * get type values
     *
     * @return array an array types of staff
    */
    public static function getTypeValues():array
    {
        $Settings = new Settings(SETTING_FILE, false);
        $settingInfo = $Settings->getAllDetails();
        return $settingInfo['profileType'][TblStaff::TABLE];
    }

    /**
     * get type
     *
     * @return string type of staff
    */
    public function getType():string
    {
        return $this->type;
    }

    /**
     * set type
     *
     * @param string type of staff
    */
    public function setType(string $type)
    {
        $errors = [];
        if (!in_array($type, $this->getTypeValues())) {
            $errors[] = "invalid value";
        }
        if ($errors) {
            throw new TblStaffExpection("TblStaff Error: type '".implode(", ", $errors)."'.");
        }

        $this->type = $type;
    }

    /**
     * generate some dummy position data
     *
     * @return array the generated name
     */
    private function generateDummyData():array
    {
        $dummyData = [
            ["logger"=>5, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>6, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>7, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>8, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>9, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>10, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>13, 'type'=>$this->getTypeValues()[0]],
            ["logger"=>14, 'type'=>$this->getTypeValues()[0]],
        ];
        return $dummyData;
    }
    
    /**
     * insert data into agent table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        if (!isset($cols[TblStaff::PROFILE_ID]) || !isset($cols[TblStaff::TYPE])) {
            $errors = [];
            if (!isset($cols[TblStaff::PROFILE_ID])) {
                $errors[] = "profile id required";
            }
            if (!isset($cols[TblStaff::TYPE])) {
                $errors[] = "type required";
            }
            if ($errors) {
                throw new TblStaffExpection("TblStaff Error: insert data issue '".implode(", ", $errors)."'.");
            }
        }
        $this->setTblProfileId($cols[TblStaff::PROFILE_ID][0]);
        $cols[TblStaff::PROFILE_ID][0] = $this->getTblProfileId();
        $this->setType($cols[TblStaff::TYPE][0]);
        $cols[TblStaff::TYPE][0] = $this->getType();
        
        return $this->query->insert($cols);
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
        if (isset($cols[TblStaff::PROFILE_ID])) {
            $this->setTblProfileId($cols[TblStaff::PROFILE_ID][0]);
            $cols[TblStaff::PROFILE_ID][0] = $this->getTblProfileId();
        }
        if (isset($cols[TblStaff::TYPE])) {
            $this->setType($cols[TblStaff::TYPE][0]);
            $cols[TblStaff::TYPE][0] = $this->getType();
        }
        return $cols;
    }
    
    /**
     * create agent table in the database
     *
     * @param string table a table name in the database usually 'agent'
     * @param array tableStructure an array representing the agent table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblStaff::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblStaffExpection("TblStaff Error: Table '".TblStaff::TABLE."' already exist");
        }
        if (!in_array(TblProfile::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblStaffExpection("TblStaff Error: Table '".TblProfile::TABLE."' does not exist");
        }

        $sql = "START TRANSACTION;";
        if ($tableStructure) {
            //TODO: implementation if table structure is given is coming later
            throw new TblStaffExpection("TblStaff Error: creating table from Table structure not done yet");
            foreach ($tableStructure as $row) {
            }
        }
        
        $sql .= "
            CREATE TABLE ".TblStaff::TABLE." (
            ".TblStaff::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblStaff::PROFILE_ID." int(10) UNSIGNED NOT NULL,
            ".TblStaff::TYPE." enum('".implode("','", self::getTypeValues())."') NOT NULL DEFAULT '".self::getTypeValues()[0]."',
            ".TblStaff::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblStaff::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblStaff::ID."),
            UNIQUE KEY (".TblStaff::PROFILE_ID.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
            ALTER TABLE ".TblStaff::TABLE."
                ADD CONSTRAINT ".TblStaff::TABLE."_ibfk_1 FOREIGN KEY (".TblStaff::PROFILE_ID.") REFERENCES ".TblProfile::TABLE." (".TblProfile::ID.") ON DELETE RESTRICT ON UPDATE CASCADE;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating agent table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord=0, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into agent table'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $sql = "INSERT INTO ".TblStaff::TABLE." (".TblStaff::PROFILE_ID.", ".TblStaff::TYPE.") VALUES";
        foreach ($this->generateDummyData() as $aData) {
            $loggerId = $this->getTblProfileId($this->setTblProfileId($aData['logger']));
            $type = $this->getType($this->setType($aData['type']));
            $sql .= "($loggerId, '$type'),";
        }
        $sql = rtrim($sql, ",");
        return $sql;
    }
}
