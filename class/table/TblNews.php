<?php

/**
 * TblNews
 *
 * A class for handling News table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblNewsExpection extends Exception
{
}

class TblNews extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string title column*/
    protected string $title;

    /** @var string body column*/
    protected string $body;

    /** @var string source column*/
    protected string $source;

    /** @var string  title*/
    public const TITLE = "title";

    /** @var string  body*/
    public const BODY = "body";

    /** @var string  source*/
    public const SOURCE = "source";

    /** @var string table name*/
    public const TABLE = "news";

    /**
     * instantiation of TblNews
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblNews::TABLE);
    }

    /**
     * set title
     *
     * @param string title of the news
    */
    public function setTitle(string $title)
    {
        if (empty($title)) {
            throw new TblNewsExpection("TblNews Error: blank title");
        }
        $this->title = $title;
    }

    /**
     * get title
     *
     * @return string title
    */
    public function getTitle():string
    {
        return $this->title;
    }

    /**
     * set body
     *
     * @param string body of the news
    */
    public function setBody(string $body)
    {
        if (empty($body)) {
            throw new TblNewsExpection("TblNews Error: blank body");
        }
        $this->body = $body;
    }

    /**
     * get body
     *
     * @return string body
    */
    public function getBody():string
    {
        return $this->body;
    }

    /**
     * set source
     *
     * @param string source of the news
    */
    public function setSource(string $source)
    {
        if (empty($source)) {
            throw new TblNewsExpection("TblNews Error: blank source");
        }
        $this->source = $source;
    }

    /**
     * get source
     *
     * @return string source
    */
    public function getSource():string
    {
        return $this->source;
    }

    /**
     * insert data into news table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblNews::TITLE]) || !isset($cols[TblNews::BODY]) || !isset($cols[TblNews::SOURCE])) {
            $errors = [];
            if (!isset($cols[TblNews::TITLE])) {
                $errors[] = "title is required";
            }
            if (!isset($cols[TblNews::BODY])) {
                $errors[] = "body is required";
            }
            if (!isset($cols[TblNews::SOURCE])) {
                $errors[] = "source is required";
            }
            if ($errors) {
                throw new TblNewsExpection("TblNews Error: insert data issue '" . implode(", ", $errors) . "'.");
            }
        }

        $this->setTitle($cols[TblNews::TITLE][0]);
        $cols[TblNews::TITLE][0] = $this->getTitle();
        $this->setBody($cols[TblNews::BODY][0]);
        $cols[TblNews::BODY][0] = $this->getBody();
        $this->setSource($cols[TblNews::SOURCE][0]);
        $cols[TblNews::SOURCE][0] = $this->getSource();

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
        if (isset($cols[TblNews::TITLE])) {
            $this->setTitle($cols[TblNews::TITLE][0]);
            $cols[TblNews::TITLE][0] = $this->getTitle();
        }
        if (isset($cols[TblNews::BODY])) {
            $this->setBody($cols[TblNews::BODY][0]);
            $cols[TblNews::BODY][0] = $this->getBody();
        }
        if (isset($cols[TblNews::SOURCE])) {
            $this->setSource($cols[TblNews::SOURCE][0]);
            $cols[TblNews::SOURCE][0] = $this->getSource();
        }

        return $cols;
    }

    /**
     * create news table in the database
     *
     * @param string table a table name in the database usually 'news'
     * @param array tableStructure an array representing the stock table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblNews::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblNewsExpection("TblNews Error: Table '" . TblNews::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblNews::TABLE . " (
            " . TblNews::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblNews::TITLE . " varchar(255) NOT NULL,
            " . TblNews::BODY . " varchar(255) NOT NULL,
            " . TblNews::SOURCE . " varchar(255) NOT NULL,
            " . TblNews::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblNews::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblNews::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating news table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table = "")
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
    public function generateDummyRecords(int $noOfRecord, string $table = ""): string
    {
        $sql = "";
        return $sql;
    }
}
