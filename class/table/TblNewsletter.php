<?php

/**
 * TblNewsletter
 *
 * A class for handling Newsletter table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblNewsletterExpection extends Exception
{
}

class TblNewsletter extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string name column*/
    protected string $name;

    /** @var string email column*/
    protected string $email;

    /** @var string  name*/
    public const NAME = "name";

    /** @var string  email*/
    public const EMAIL = "email";

    /** @var string table name*/
    public const TABLE = "newsletter";

    /**
     * instantiation of TblNewsletter
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblNewsletter::TABLE);
    }

    /**
     * set name
     *
     * @param string name of the newsletter
    */
    public function setName(string $name)
    {
        if (empty($name)) {
            throw new TblNewsletterExpection("TblNewsletter Error: blank name");
        }
        $this->name = $name;
    }

    /**
     * get name
     *
     * @return string name
    */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * set email
     *
     * @param string email of the newsletter
    */
    public function setEmail(string $email)
    {
        $errors = [];

        if (empty($email)) {
            $errors[] = "blank email";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "invalid email";
        }
        $where = [TblNewsletter::EMAIL=>["=", $email, "isValue"]];
        if ($this->getColumnByIndex(TblNewsletter::ID, TblNewsletter::EMAIL, $where)) {
            $errors[] = "email associated with another subscriber";
        }
        if($errors) {
            throw new TblNewsletterExpection("TblNewsletter Error: email issues ".implode(', ', $errors));
        }
        $this->email = $email;
    }

    /**
     * get email
     *
     * @return string email
    */
    public function getEmail():string
    {
        return $this->email;
    }

    /**
     * insert data into newsletter table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblNewsletter::EMAIL])) {
            throw new TblNewsletterExpection("TblNewsletter Error: insert data issue 'email is required'");
        }

        if (isset($cols[TblNewsletter::NAME])) {
            $this->setName($cols[TblNewsletter::NAME][0]);
            $cols[TblNewsletter::NAME][0] = $this->getName();
        }
        $this->setEmail($cols[TblNewsletter::EMAIL][0]);
        $cols[TblNewsletter::EMAIL][0] = $this->getEmail();

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
        if (isset($cols[TblNewsletter::NAME])) {
            $this->setName($cols[TblNewsletter::NAME][0]);
            $cols[TblNewsletter::NAME][0] = $this->getName();
        }
        if (isset($cols[TblNewsletter::EMAIL])) {
            $this->setEmail($cols[TblNewsletter::EMAIL][0]);
            $cols[TblNewsletter::EMAIL][0] = $this->getEmail();
        }

        return $cols;
    }

    /**
     * create newsletter table in the database
     *
     * @param string table a table name in the database usually 'newsletter'
     * @param array tableStructure an array representing the stock table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblNewsletter::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblNewsletterExpection("TblNewsletter Error: Table '" . TblNewsletter::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblNewsletter::TABLE . " (
            " . TblNewsletter::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblNewsletter::NAME . " varchar(255) NULL,
            " . TblNewsletter::EMAIL . " varchar(255) NOT NULL,
            " . TblNewsletter::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblNewsletter::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblNewsletter::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating newsletter table with dummy records
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
