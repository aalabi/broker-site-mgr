<?php

/**
 * TblDailyNews
 *
 * A class for handling DailyNews table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblDailyNewsExpection extends Exception
{
}

class TblDailyNews extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string title column*/
    protected string $title;

    /** @var string body column*/
    protected string $body;

    /** @var string source column*/
    protected string $source;

    /** @var string table name*/
    public const TABLE = "daily_news";

    /** @var string  title*/
    public const TITLE = "title";

    /** @var string  body*/
    public const BODY = "body";

    /** @var string  source*/
    public const SOURCE = "source";

    /**
     * instantiation of TblDailyNews
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblDailyNews::TABLE);
    }

    /**
     * get title
     *
     * @return string title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * set title
     *
     * @param string title
     */
    public function setTitle(string $title)
    {
        $errors = [];
        if (empty($title)) {
            $errors[] = "title is required ";
        }
        if (strlen($title) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblDailyNewsExpection("TblDailyNews Error: title issue '" . implode(", ", $errors) . "'.");
        }

        $this->title = $title;
    }

    /**
     * get body
     *
     * @return string body
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * set body
     *
     * @param string body
     */
    public function setBody(string $body)
    {
        $errors = [];
        if (empty($body)) {
            $errors[] = "body is required ";
        }
        if (strlen($body) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblDailyNewsExpection("TblDailyNews Error: body issue '" . implode(", ", $errors) . "'.");
        }

        $this->body = $body;
    }

    /**
     * get source
     *
     * @return string source
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * set source
     *
     * @param string source
     */
    public function setSource(string $source)
    {
        $errors = [];
        if (empty($source)) {
            $errors[] = "source is required ";
        }
        if (strlen($source) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblDailyNewsExpection("TblDailyNews Error: source issue '" . implode(", ", $errors) . "'.");
        }

        $this->source = $source;
    }

    /**
     * insert data into DailyNews table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblDailyNews::TITLE]) || !isset($cols[TblDailyNews::BODY]) || !isset($cols[TblDailyNews::SOURCE])) {
            $errors = [];
            if (!isset($cols[TblDailyNews::TITLE])) {
                $errors[] = "title is required";
            }
            if (!isset($cols[TblDailyNews::BODY])) {
                $errors[] = "body is required";
            }
            if (!isset($cols[TblDailyNews::SOURCE])) {
                $errors[] = "source is required";
            }
        }

        $this->setTitle($cols[TblDailyNews::TITLE][0]);
        $cols[TblDailyNews::TITLE][0] = $this->getTitle();
        $this->setBody($cols[TblDailyNews::BODY][0]);
        $cols[TblDailyNews::BODY][0] = $this->getBody();
        $this->setSource($cols[TblDailyNews::SOURCE][0]);
        $cols[TblDailyNews::SOURCE][0] = $this->getSource();
       
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
        if (isset($cols[TblDailyNews::TITLE])) {
            $this->setTitle($cols[TblDailyNews::TITLE][0]);
            $cols[TblDailyNews::TITLE][0] = $this->getTitle();
        }
        if (isset($cols[TblDailyNews::BODY])) {
            $this->setBody($cols[TblDailyNews::BODY][0]);
            $cols[TblDailyNews::BODY][0] = $this->getBody();
        }
        if (isset($cols[TblDailyNews::SOURCE])) {
            $this->setSource($cols[TblDailyNews::SOURCE][0]);
            $cols[TblDailyNews::SOURCE][0] = $this->getSource();    
        }
        
        return $cols;
    }

    /**
     * create DailyNews table in the database
     *
     * @param string table a table name in the database usually 'daily_news'
     * @param array tableStructure an array representing the DailyNews table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblDailyNews::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblDailyNewsExpection("TblDailyNews Error: Table '" . TblDailyNews::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblDailyNews::TABLE . " (
            " . TblDailyNews::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblDailyNews::TITLE . " varchar(255) NOT NULL,
            " . TblDailyNews::BODY . " varchar(255) NOT NULL,
            " . TblDailyNews::SOURCE . " varchar(255) NOT NULL,
            " . TblDailyNews::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblDailyNews::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblDailyNews::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating DailyNews table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table = "")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the DailyNews table. Password = 'password{ID}'
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