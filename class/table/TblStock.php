<?php

/**
 * TblStock
 *
 * A class for handling stock table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
*/

class TblStockExpection extends Exception
{
}

class TblStock extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string table name*/
    public const TABLE = "stock";

    /**
     * instantiation of TblStock
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblStock::TABLE);
    }
    
    /**
     * insert data into stock table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        $errors = [];
        if (!isset($cols[TblStock::NAME])) {
            $errors[] = "name required";
            if ($errors) {
                throw new TblStockExpection("TblStock Error: insert data issue '".implode(", ", $errors)."'.");
            }
        }

        $this->setName($cols[TblStock::NAME][0]);
        $cols[TblStock::NAME][0] = $this->getName();
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
        if (isset($cols[TblStock::NAME])) {
            $this->setName($cols[TblStock::NAME][0]);
            $cols[TblStock::NAME][0] = $this->getName();
        }
        return $cols;
    }

    /**
     * create stock table in the database
     *
     * @param string table a table name in the database usually 'stock'
     * @param array tableStructure an array representing the stock table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblStock::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblStockExpection("TblStock Error: Table '".TblStock::TABLE."' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE ".TblStock::TABLE." (
            ".TblStock::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblStock::NAME." varchar(255) NOT NULL,
            ".TblStock::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblStock::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblStock::ID.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating stock table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the stock table. Password = 'password{ID}'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $sql = "";
        return $sql;
    }
}
