<?php

/**
 * TblFinancialReport
 *
 * A class for handling FinancialReport table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblFinancialReportExpection extends Exception
{
}

class TblFinancialReport extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string table name*/
    public const TABLE = "financial_report";

    /**
     * instantiation of TblFinancialReport
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblFinancialReport::TABLE);
    }

    /**
     * insert data into FinancialReport table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblFinancialReport::STOCK_ID]) || !isset($cols[TblFinancialReport::PERIOD]) || !isset($cols[TblFinancialReport::YEAR]) || !isset($cols[TblFinancialReport::FILE])) {
            $errors = [];
            if (!isset($cols[TblFinancialReport::STOCK_ID])) {
                $errors[] = "stock_id is required";
            }
            if (!isset($cols[TblFinancialReport::PERIOD])) {
                $errors[] = "period is required";
            }
            if (!isset($cols[TblFinancialReport::YEAR])) {
                $errors[] = "year is required";
            }
            if (!isset($cols[TblFinancialReport::FILE])) {
                $errors[] = "file is required";
            }
        }

        $this->setStockId($cols[TblFinancialReport::STOCK_ID][0]);
        $cols[TblFinancialReport::STOCK_ID][0] = $this->getStockId();
        $this->setPeriod($cols[TblFinancialReport::PERIOD][0]);
        $cols[TblFinancialReport::PERIOD][0] = $this->getPeriod();
        $this->setYear($cols[TblFinancialReport::YEAR][0]);
        $cols[TblFinancialReport::YEAR][0] = $this->getYear();
        $this->setFile($cols[TblFinancialReport::FILE][0]);
        $cols[TblFinancialReport::FILE][0] = $this->getFile();
        
        return $this->query->insert($cols);
    }

    /**
     * generate colums for the update method
     *
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
     * @return array the generated columns
     */
    protected function generateUpdateColumn(array $cols): array
    {
        if (isset($cols[TblFinancialReport::STOCK_ID])) {
            $this->setStockId($cols[TblFinancialReport::STOCK_ID][0]);
            $cols[TblFinancialReport::STOCK_ID][0] = $this->getStockId();
        }
        if (isset($cols[TblFinancialReport::PERIOD])) {
            $this->setPeriod($cols[TblFinancialReport::PERIOD][0]);
            $cols[TblFinancialReport::PERIOD][0] = $this->getPeriod();
        }
        if (isset($cols[TblFinancialReport::YEAR])) {
            $this->setYear($cols[TblFinancialReport::YEAR][0]);
            $cols[TblFinancialReport::YEAR][0] = $this->getYear();
        }
        if (isset($cols[TblFinancialReport::FILE])) {
            $this->setFile($cols[TblFinancialReport::FILE][0]);
            $cols[TblFinancialReport::FILE][0] = $this->getFile();
        }
        return $cols;
    }

    /**
     * create FinancialReport table in the database
     *
     * @param string table a table name in the database usually 'financial_report'
     * @param array tableStructure an array representing the stock table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblFinancialReport::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblFinancialReportExpection("TblFinancialReport Error: Table '" . TblFinancialReport::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblFinancialReport::TABLE . " (
            " . TblFinancialReport::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblFinancialReport::STOCK_ID . " int(10) UNSIGNED NOT NULL,            
            " . TblFinancialReport::PERIOD . " enum('" . implode("','", TblFinancialReport::PERIOD_VALUES) . "') NOT NULL DEFAULT '" . TblFinancialReport::PERIOD_VALUES[1] . "',
            " . TblFinancialReport::YEAR . " datetime NOT NULL,
            " . TblFinancialReport::FILE . " varchar(255) NOT NULL,
            " . TblFinancialReport::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblFinancialReport::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblFinancialReport::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
            ALTER TABLE " . TblFinancialReport::TABLE . "
                ADD CONSTRAINT " . TblFinancialReport::TABLE . "_ibfk_1 FOREIGN KEY (" . TblFinancialReport::STOCK_ID . ") REFERENCES " . TblStock::TABLE . " (" . TblStock::ID . ") ON DELETE RESTRICT ON UPDATE CASCADE;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating FinancialReport table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table = "")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the FinancialReport table. Password = 'password{ID}'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
     */
    public function generateDummyRecords(int $noOfRecord, string $table = ""): string
    {
        $sql = "";
        return $sql;
    }
}