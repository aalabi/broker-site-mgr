<?php

/**
 * TblMarketReview
 *
 * A class for handling MarketReview table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblMarketReviewExpection extends Exception
{
}

class TblMarketReview extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string sub_type column*/
    protected string $sub_type;

    /** @var string end_date column*/
    protected string $end_date;

    /** @var string  end_date*/
    public const END_DATE = "end_date";

    /** @var string  sub_type*/
    public const SUB_TYPE = "sub_type";

    /** @var array  collection of sub_type values*/
    public const SUB_TYPE_VALUE = ['review', 'pricelist'];

    /** @var string table name*/
    public const TABLE = "market_review";

    /**
     * instantiation of TblMarketReview
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblMarketReview::TABLE);
    }

    /**
     * get sub_type
     *
     * @return string sub_type
     */
    public function getSubType(): string
    {
        return $this->sub_type;
    }

    /**
     * set sub_type
     *
     * @param string sub_type
     */
    public function setSubType(string $sub_type)
    {
        $errors = [];
        if (!in_array($sub_type, TblMarketReview::SUB_TYPE_VALUE)) {
            $errors[] = "'$sub_type' is not among '" . implode(", ", TblMarketReview::SUB_TYPE_VALUE) . "'";
        }
        if ($errors) {
            throw new TblMarketReviewExpection("TblMarketReview Error: status issue '" . implode(", ", $errors) . "'.");
        }

        $this->sub_type = $sub_type;
    }

    /**
     * set end_date
     *
     * @param DateTime $end_date
     */
    public function setEndDate(DateTime $end_date)
    {
        if ($end_date > new DateTime(SqlType::DATETIME_MAX) || $end_date < new DateTime(SqlType::DATETIME_MIN)) {
            throw new TblMarketReviewExpection("TblMarketReview Error: end date at is outside range of  " . SqlType::DATETIME_MIN . " to " . SqlType::DATETIME_MAX);
        }
        $this->end_date = $end_date->format('Y-m-d');
    }

    /**
     * get end_date
     *
     * @return string $end_date
     */
    public function getEndDate(): string
    {
        return $this->end_date;
    }

    /**
     * insert data into MarketReview table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblMarketReview::TYPE]) || !isset($cols[TblMarketReview::FILE]) || !isset($cols[TblMarketReview::DATE])) {
            $errors = [];
            if (!isset($cols[TblMarketReview::TYPE])) {
                $errors[] = "type is required";
            }
            if (!isset($cols[TblMarketReview::FILE])) {
                $errors[] = "file is required";
            }
            if (!isset($cols[TblMarketReview::DATE])) {
                $errors[] = "date is required";
            }

            if ($errors) {
                throw new TblMarketReviewExpection("TblMarketReview Error: insert data issue '" . implode(", ", $errors) . "'.");
            }
        }

        $this->setType($cols[TblMarketReview::TYPE][0]);
        $cols[TblMarketReview::TYPE][0] = $this->getType();
        $this->setFile($cols[TblMarketReview::FILE][0]);
        $cols[TblMarketReview::FILE][0] = $this->getFILE();
        $this->setDate($cols[TblMarketReview::DATE][0]);
        $cols[TblMarketReview::DATE][0] = $this->getDate();
        $this->setEndDate($cols[TblMarketReview::END_DATE][0]);
        $cols[TblMarketReview::END_DATE][0] = $this->getEndDate();
        $this->setSubType($cols[TblMarketReview::SUB_TYPE][0]);
        $cols[TblMarketReview::SUB_TYPE][0] = $this->getSubType();


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
        if (isset($cols[TblMarketReview::TYPE])) {
            $this->setType($cols[TblMarketReview::TYPE][0]);
            $cols[TblMarketReview::TYPE][0] = $this->getType();
        }
        if (isset($cols[TblMarketReview::FILE])) {
            $this->setFile($cols[TblMarketReview::FILE][0]);
            $cols[TblMarketReview::FILE][0] = $this->getFile();
        }
        if (isset($cols[TblMarketReview::DATE])) {
            $this->setDate($cols[TblMarketReview::DATE][0]);
            $cols[TblMarketReview::DATE][0] = $this->getDate();
        }
        if (isset($cols[TblMarketReview::END_DATE])) {
            $this->setEndDate($cols[TblMarketReview::END_DATE][0]);
            $cols[TblMarketReview::END_DATE][0] = $this->getEndDate();
        }
        if (isset($cols[TblMarketReview::SUB_TYPE])) {
            $this->setSubType($cols[TblMarketReview::SUB_TYPE][0]);
            $cols[TblMarketReview::SUB_TYPE][0] = $this->getSubType();
        }
        return $cols;
    }

    /**
     * create MarketReview table in the database
     *
     * @param string table a table name in the database usually 'market_review'
     * @param array tableStructure an array representing the stock table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblMarketReview::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblMarketReviewExpection("TblMarketReview Error: Table '" . TblMarketReview::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblMarketReview::TABLE . " (
            " . TblMarketReview::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblMarketReview::TYPE . " varchar(255) NOT NULL,
            " . TblMarketReview::FILE . " varchar(255) NOT NULL,
            " . TblMarketReview::DATE . " DateTime NOT NULL,
            " . TblMarketReview::END_DATE . " DateTime NULL,
            " . TblMarketReview::SUB_TYPE . " enum('" . implode("','", TblMarketReview::SUB_TYPE_VALUE) . "') NOT NULL DEFAULT '" . TblMarketReview::SUB_TYPE_VALUE[1] . "',
            " . TblMarketReview::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblMarketReview::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblMarketReview::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating MarketReview table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table = "")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the MarketReview table. Password = 'password{ID}'
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
