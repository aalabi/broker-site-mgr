<?php

/**
 * TblProfileType
 *
 * A class for handling TblProfileType table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022
 * @link        alabiansolutions.com
*/

class TblProfileTypeExpection extends Exception
{
}

class TblProfileType extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string name column */
    protected string $name;

    /** @var string subs column */
    protected $subs;

    /** @var string description column */
    protected $description;
 
    /** @var string name*/
    public const NAME = "name";

    /** @var string subs*/
    public const SUBS = "subs";

    /** @var array description */
    public const DESCRIPTION = 'description';

    /** @var string table name*/
    public const TABLE = "profile_type";

    /**
     * instantiation of TblProfileType
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblProfileType::TABLE);
    }
    
    /**
     * get name
     *
     * @return string name of the profileType
    */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * set name
     *
     * @param string name of the profileType
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
            throw new TblProfileTypeExpection("TblProfileType Error: name issue '".implode(", ", $errors)."'.");
        }

        $this->name = $name;
    }
    
    /**
     * get subs
     *
     * @return string subs of the profileType
    */
    public function getSubs():string
    {
        return $this->subs;
    }

    /**
     * set subs
     *
     * @param array subs of the profileType
    */
    public function setSubs(array $subs)
    {
        $errors = [];
        if (!$subs) {
            $errors[] = " sub required ";
        }

        if ($errors) {
            throw new TblProfileTypeExpection("TblProfileType Error: subs issue '".implode(", ", $errors)."'.");
        }

        $this->subs = json_encode($subs);
    }

    /**
     * get description of profile type
     *
     * @return string description
    */
    public function getDescription():string
    {
        return $this->description;
    }

    /**
     * set description of profile type
     *
     * @param string description
    */
    public function setDescription(string $description)
    {
        $errors = [];
        if (empty($description)) {
            $errors[] = "description is required";
        }

        if (strlen($description) > SqlType::TEXT_LENGTH) {
            $errors[] = "max length ".SqlType::TEXT_LENGTH;
        }
        if ($errors) {
            throw new TblProfileTypeExpection("TblProfileType Error: address issue '".implode(", ", $errors)."'.");
        }

        $this->description = $description;
    }

    /**
     * get a all profile types from setting.json file
     *
     * @return array all collection of all profile types [type1=>subTypes1, type2=>subTypes2...]
     */
    private function getAllTypes():array
    {
        $allSubs = [];
        $Settings = new Settings(SETTING_FILE, false);
        foreach ($Settings->getDetails()['profileType'] as $type => $aSubType) {
            $allSubs[$type] = $aSubType;
        }
        return $allSubs;
    }
    
    /**
     * insert data into profileType table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        $errors = [];
        if (!isset($cols[TblProfileType::NAME])) {
            $errors[] = "name required";
        }
        if ($errors) {
            throw new TblProfileTypeExpection("TblProfileType Error: insert data issue '".implode(", ", $errors)."'.");
        }
        $this->setName($cols[TblProfileType::NAME][0]);
        $cols[TblProfileType::NAME][0] = $this->getName();
        if (isset($cols[TblProfileType::SUBS])) {
            $this->setSubs($cols[TblProfileType::SUBS][0]);
            $cols[TblProfileType::SUBS][0] = $this->getSubs();
        }
        if (isset($cols[TblProfileType::DESCRIPTION])) {
            $this->setDescription($cols[TblProfileType::DESCRIPTION][0]);
            $cols[TblProfileType::DESCRIPTION][0] = $this->getDescription();
        }
                   
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
        if (isset($cols[TblProfileType::NAME][0])) {
            $this->setName($cols[TblProfileType::NAME][0]);
            $cols[TblProfileType::NAME][0] = $this->getName();
        }
        if (isset($cols[TblProfileType::SUBS][0])) {
            $this->setSubs($cols[TblProfileType::SUBS][0]);
            $cols[TblProfileType::SUBS][0] = $this->getSubs();
        }
        if (isset($cols[TblProfileType::DESCRIPTION][0])) {
            $this->setDescription($cols[TblProfileType::DESCRIPTION][0]);
            $cols[TblProfileType::DESCRIPTION][0] = $this->getDescription();
        }
        return $cols;
    }
    
    /**
     * create profileType table in the database
     *
     * @param string table a table name in the database usually 'profileType'
     * @param array tableStructure an array representing the profileType table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblProfileType::TABLE, $MyQuery->getTablesInDb())) {
            throw new QueryExpection("TblProfileType Error: Table '".TblProfileType::TABLE."' already exist");
        }

        $sql = "START TRANSACTION;";
        if ($tableStructure) {
            //TODO: implementation if table structure is given is coming later
            throw new TblProfileTypeExpection("TblProfileType Error: creating table from Table structure not done yet");
            foreach ($tableStructure as $row) {
            }
        }
        
        $sql .= "
            CREATE TABLE ".TblProfileType::TABLE." (
            ".TblProfileType::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblProfileType::NAME." varchar(255) NOT NULL,            
            ".TblProfileType::SUBS." JSON NULL COMMENT '[sub1, sub2, ...] a collection of sub types',
            ".TblProfileType::DESCRIPTION." TEXT NULL,
            ".TblProfileType::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblProfileType::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblProfileType::ID."),
            UNIQUE KEY (".TblProfileType::NAME.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating profileType table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord = 0, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into profileType table'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $sql = "INSERT INTO ".TblProfileType::TABLE." (".TblProfileType::NAME.", ".TblProfileType::SUBS.") VALUES";
        $types = $this->getAllTypes();
        foreach ($types as $aType=>$aSubType) {
            $name = $this->getName($this->setName($aType));
            $subs = $this->getSubs($this->setSubs($aSubType));
            $sql .= "('$name', '$subs'),";
        }
        $sql = rtrim($sql, ",");
        return $sql;
    }
}
