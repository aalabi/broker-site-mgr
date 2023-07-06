<?php

/**
 * TblAppOptions
 *
 * A class for handling TblAppOptions table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => March 2023
 * @link        alabiansolutions.com
*/

class TblAppOptionsExpection extends Exception
{
}

class TblAppOptions extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string option_name column */
    protected string $optionName;

    /** @var string option_value column */
    protected string $optionValue;

    /** @var string option_name*/
    public const OPTION_NAME = "option_name";

    /** @var string option_value*/
    public const OPTION_VALUE = "option_value";

    /** @var string organisation_name*/
    public const NAME = "organisation_name";

    /** @var string organisation_address*/
    public const ADDRESS = "organisation_address";

    /** @var string organisation_logo*/
    public const LOGO = "organisation_logo";

    /** @var string organisation_email*/
    public const EMAIL = "organisation_email";

    /** @var string organisation_phone*/
    public const PHONE = "organisation_phone";

    /** @var string organisation_complain_phone*/
    public const COMPLAIN_PHONE = "organisation_complain_phone";

    /** @var string organisation_complain_email*/
    public const CONPLAIN_EMAIL = "organisation_complain_email";

    /** @var string app_id*/
    public const APP_ID = "app_id";

    /** @var string app_passkey*/
    public const APP_PASSKEY = "app_passkey";

    /** @var string worker_cost*/
    public const WORKER_COST = "worker_cost";

    /** @var string transfer_chrgs*/
    public const TRANSFER_CHRGS = "transfer_chrgs";

    /** @var string table name*/
    public const TABLE = "app_options";

    /**
     * instantiation of TblAppOptions
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblAppOptions::TABLE);
    }

    /**
     * get option name
     *
     * @return string option name of app options
    */
    public function getOptionName():string
    {
        return $this->optionName;
    }

    /**
     * set option name
     *
     * @param string $optionName option name of app options
    */
    public function setOptionName(string $optionName)
    {
        $errors = [];
        if (empty($optionName)) {
            $errors[] = "option name cannot be empty";
        }
        if ($errors) {
            throw new TblAppOptionsExpection("TblAppOptions Error: type '".implode(", ", $errors)."'.");
        }

        $this->optionName = $optionName;
    }

    /**
     * get option value
     *
     * @return string option value of app options
    */
    public function getOptionValue():string
    {
        return $this->optionValue;
    }

    /**
     * set option value
     *
     * @param string $optionValue option value of app options
    */
    public function setOptionValue(string $optionValue)
    {
        $errors = [];
        if (empty($optionValue)) {
            $errors[] = "option value cannot be empty";
        }
        if ($errors) {
            throw new TblAppOptionsExpection("TblAppOptions Error: type '".implode(", ", $errors)."'.");
        }

        $this->optionValue = $optionValue;
    }

    /**
     * generate some dummy position data
     *
     * @return array the generated data
     */
    private function generateDummyData():array
    {
        $dummyData = [
            ["name"=>'organisation_name', 'value'=>'Sample Company Limited'],
            ["name"=>'organisation_address', 'value'=>'Plot 15 Nelson Mandela Road Abuja'],
            ["name"=>'organisation_logo', 'value'=>'logo-company.png'],
            ["name"=>'organisation_email', 'value'=>'info@samplecoy.com'],
            ["name"=>'organisation_phone', 'value'=>'08098765432'],
            ["name"=>'organisation_complain_phone', 'value'=>'08098765432'],
            ["name"=>'organisation_complain_email', 'value'=>'info@samplecoy.com']
        ];
        return $dummyData;
    }

    /**
     * select data from app options table
     * @param array cols an array whose elements are columns in table [col1, col2, ...coln]
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string table a table name in the database
     * @return array 2 dimensional array of selected rows or empty array if no match
    */
    public function select(array $cols = [], array $where = [], ?string $table = null):array
    {
        return $this->query->select($cols, $where);
    }
    
    /**
     * insert data into app options table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        if (!isset($cols[TblAppOptions::OPTION_NAME]) || !isset($cols[TblAppOptions::OPTION_VALUE])) {
            $errors = [];
            if (!isset($cols[TblAppOptions::OPTION_NAME])) {
                $errors[] = "option name required";
            }
            if (!isset($cols[TblAppOptions::OPTION_VALUE])) {
                $errors[] = "option value required";
            }
            if ($errors) {
                throw new TblAppOptionsExpection("TblAppOptions Error: insert data issue '".implode(", ", $errors)."'.");
            }
        }
        $this->setOptionName($cols[TblAppOptions::OPTION_NAME][0]);
        $cols[TblAppOptions::OPTION_NAME][0] = $this->getOptionName();
        $this->setOptionValue($cols[TblAppOptions::OPTION_VALUE][0]);
        $cols[TblAppOptions::OPTION_VALUE][0] = $this->getOptionValue();
        
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
        if (isset($cols[TblAppOptions::OPTION_NAME])) {
            $this->setOptionName($cols[TblAppOptions::OPTION_NAME][0]);
            $cols[TblAppOptions::OPTION_NAME][0] = $this->getOptionName();
        }
        if (isset($cols[TblAppOptions::OPTION_VALUE])) {
            $this->setOptionValue($cols[TblAppOptions::OPTION_VALUE][0]);
            $cols[TblAppOptions::OPTION_VALUE][0] = $this->getOptionValue();
        }
        return $cols;
    }

    /**
     * create app options table in the database
     *
     * @param string table a table name in the database usually 'app options'
     * @param array tableStructure an array representing the app options table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        $sql = "START TRANSACTION;";
        if ($tableStructure) {
            //TODO: implementation if table structure is given is coming later
            throw new TblAppOptionsExpection("TblAppOptions Error: creating table from Table structure not done yet");
            foreach ($tableStructure as $row) {
            }
        }
        
        $sql .= "
            CREATE TABLE ".TblAppOptions::TABLE." (
            ".TblAppOptions::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblAppOptions::OPTION_NAME." varchar(255) NOT NULL,
            ".TblAppOptions::OPTION_VALUE." varchar(255) NULL,
            ".TblAppOptions::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblAppOptions::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblAppOptions::ID."),
            UNIQUE KEY (".TblAppOptions::OPTION_NAME.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating app options table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord=0, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into app options table'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $sql = "INSERT INTO ".TblAppOptions::TABLE." (".TblAppOptions::OPTION_NAME.", ".TblAppOptions::OPTION_VALUE.") VALUES ";
        foreach ($this->generateDummyData() as $aData) {
            $name = $this->getOptionName($this->setOptionName($aData['name']));
            $value = $this->getOptionValue($this->setOptionValue($aData['value']));
            $sql .= "('$name', '$value'),";
        }
        $sql = rtrim($sql, ",");
        return $sql;
    }
}
