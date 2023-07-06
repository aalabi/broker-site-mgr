<?php

/**
 * TblDocument
 *
 * A class for handling Document table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblDocumentExpection extends Exception
{
}

class TblDocument extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var int priority column*/
    protected int $priority;

    /** @var array  collection of period values*/
    public const TYPE_VALUES = ['client service form','registrar form','public offer form'];

    /** @var int  priority*/
    public const PRIORITY = "priority";

    /** @var string table name*/
    public const TABLE = "document";

    /**
     * instantiation of TblDocument
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblDocument::TABLE);
    }

    /**
     * set priority
     *
     * @param int priority
    */
    public function setPriority(int $priority)
    {
        if ($priority > SqlType::INT_MAX || $priority < SqlType::INT_MIN) {
            throw new TblDocumentExpection("TblDocument Error: priority is outside range of  ". SqlType::INT_MIN ." to ".SqlType::INT_MAX);
        }
        $this->priority = $priority;
    }
    /**
     * get priority
     *
     * @return int priority
    */
    public function getPriority():int
    {
        return $this->priority;
    }

    /**
     * get type
     *
     * @return string type
    */
    public function getType():string
    {
        return $this->type;
    }

    /**
     * set type
     *
     * @param string type
    */
    public function setType(string $type)
    {
        if(!in_array($type, TblDocument::TYPE_VALUES)) {
            throw new TblDocumentExpection("TblDocument Error: invalid doc type'");
        }

        $this->type = $type;
    }

    /**
     * insert data into Document table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblDocument::NAME]) || !isset($cols[TblDocument::TYPE]) || !isset($cols[TblDocument::FILE])) {
            $errors = [];
            if (!isset($cols[TblDocument::NAME])) {
                $errors[] = "name is required";
            }
            if (!isset($cols[TblDocument::TYPE])) {
                $errors[] = "type is required";
            }
            if (!isset($cols[TblDocument::FILE])) {
                $errors[] = "file is required";
            }
            if ($errors) {
                throw new TblDocumentExpection("TblDocument Error: insert data issue '" . implode(", ", $errors) . "'.");
            }
        }

        $this->setName($cols[TblDocument::NAME][0]);
        $cols[TblDocument::NAME][0] = $this->getName();
        $this->setType($cols[TblDocument::TYPE][0]);
        $cols[TblDocument::TYPE][0] = $this->getType();
        $this->setPriority($cols[TblDocument::PRIORITY][0]);
        $cols[TblDocument::PRIORITY][0] = $this->getPriority();
        $this->setFile($cols[TblDocument::FILE][0]);
        $cols[TblDocument::FILE][0] = $this->getFile();

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
        if (isset($cols[TblDocument::NAME])) {
            $this->setName($cols[TblDocument::NAME][0]);
            $cols[TblDocument::NAME][0] = $this->getName();
        }
        if (isset($cols[TblDocument::TYPE])) {
            $this->setType($cols[TblDocument::TYPE][0]);
            $cols[TblDocument::TYPE][0] = $this->getType();
        }
        if (isset($cols[TblDocument::PRIORITY])) {
            $this->setPriority($cols[TblDocument::PRIORITY][0]);
            $cols[TblDocument::PRIORITY][0] = $this->getPriority();
        }
        if (isset($cols[TblDocument::PRIORITY])) {
            $cols[TblDocument::FILE][0] = $this->getFile();
            $this->setFile($cols[TblDocument::FILE][0]);
        }

        return $cols;
    }

    /**
     * create Document table in the database
     *
     * @param string table a table name in the database usually 'document'
     * @param array tableStructure an array representing the stock table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblDocument::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblDocumentExpection("TblDocument Error: Table '" . TblDocument::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblDocument::TABLE . " (
            " . TblDocument::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblDocument::NAME . " varchar(255) NOT NULL,
            " . TblDocument::TYPE . " varchar(255) NOT NULL,
            " . TblDocument::PRIORITY . " int(11) NULL,
            " . TblDocument::FILE . " varchar(255) NOT NULL,
            " . TblDocument::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblDocument::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblDocument::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating Document table with dummy records
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
