<?php

/**
 * TblSession
 *
 * A class for handling TblSession table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022, 1.1 => January 2023
 * @link        alabiansolutions.com
*/

class TblSessionExpection extends Exception
{
}

class TblSession extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string access_token column */
    protected string $accessToken;

    /** @var string access_token_expiry column */
    protected $accessTokenExpiry;

    /** @var string refresh_token column */
    protected string $refreshToken;

    /** @var string refresh_token_expiry column */
    protected $refreshTokenExpiry;

    /** @var string access_token*/
    public const ACCESS_TOKEN = "access_token";

    /** @var string access_token_expiry*/
    public const ACCESS_TOKEN_EXPIRY = "access_token_expiry";

    /** @var string refresh_token*/
    public const REFRESH_TOKEN = "refresh_token";

    /** @var string refresh_token_expiry*/
    public const REFRESH_TOKEN_EXPIRY = "refresh_token_expiry";

    /** @var string table name*/
    public const TABLE = "session";

    /**
     * instantiation of TblSession
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblSession::TABLE);
    }
    
    /**
     * get access token
     *
     * @return string access token of the session
    */
    public function getAccessToken():string
    {
        return $this->accessToken;
    }

    /**
     * set access token
     *
     * @param string accessToken access token of the session
    */
    public function setAccessToken(string $accessToken = "")
    {
        $errors = [];
        if ($accessToken && strlen($accessToken) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblSessionExpection("TblSession Error: access token issue '".implode(", ", $errors)."'.");
        }

        if (!$accessToken) {
            $accessToken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());
        }
        $this->accessToken = $accessToken;
    }
    
    /**
     * get access token expiry
     *
     * @return string access token expiry of the session
    */
    public function getAccessTokenExpiry():string
    {
        return $this->accessTokenExpiry;
    }

    /**
     * set access token expiry
     *
     * @param DateTime accessTokenExpiry access token expiry of the session
    */
    public function setAccessTokenExpiry(DateTime $accessTokenExpiry = null)
    {
        $errors = [];
        if ($accessTokenExpiry) {
            if ($accessTokenExpiry > new DateTime(SqlType::DATETIME_MAX) || $accessTokenExpiry < new DateTime(SqlType::DATETIME_MIN)) {
                $errors[] = "outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX;
            }
        }
        if ($errors) {
            throw new TblSessionExpection("TblSession Error: access token expiry issue '".implode(", ", $errors)."'.");
        }

        $accessTokenExpiry = (new DateTime())->add(new DateInterval("PT" . Functions::ACCESS_TOKEN_LIMIT . "S"));
        $this->accessTokenExpiry = $accessTokenExpiry->format('Y-m-d H:i:s');
    }

    /**
     * get refresh token
     *
     * @return string refresh token of the session
    */
    public function getRefreshToken():string
    {
        return $this->refreshToken;
    }

    /**
     * set refresh token
     *
     * @param string refreshToken refresh token of the session
    */
    public function setRefreshToken(string $refreshToken = "")
    {
        $errors = [];
        if ($refreshToken && strlen($refreshToken) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblSessionExpection("TblSession Error: refresh token issue '".implode(", ", $errors)."'.");
        }

        if (!$refreshToken) {
            $refreshToken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());
        }
        $this->refreshToken = $refreshToken;
    }
    
    /**
     * set refresh token expiry
     *
     * @return string refresh token expiry of the session
    */
    public function getRefreshTokenExpiry():string
    {
        return $this->refreshTokenExpiry;
    }

    /**
     * set refresh token expiry
     *
     * @param DateTime refreshTokenExpiry refresh token expiry of the session
    */
    public function setRefreshTokenExpiry(DateTime $refreshTokenExpiry = null)
    {
        $errors = [];
        if ($refreshTokenExpiry) {
            if ($refreshTokenExpiry > new DateTime(SqlType::DATETIME_MAX) || $refreshTokenExpiry < new DateTime(SqlType::DATETIME_MIN)) {
                $errors[] = "outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX;
            }
        }
        if ($errors) {
            throw new TblSessionExpection("TblSession Error: refresh token expiry issue '".implode(", ", $errors)."'.");
        }

        $refreshTokenExpiry = (new DateTime())->add(new DateInterval("PT" . Functions::REFRESH_TOKEN_LIMIT . "S"));
        $this->refreshTokenExpiry = $refreshTokenExpiry->format('Y-m-d H:i:s');
    }
    
    /**
     * insert data into session table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        if (!isset($cols[TblSession::LOGGER_ID]) || !isset($cols[TblSession::ACCESS_TOKEN]) || !isset($cols[TblSession::ACCESS_TOKEN_EXPIRY])
            || !isset($cols[TblSession::REFRESH_TOKEN]) || !isset($cols[TblSession::REFRESH_TOKEN_EXPIRY])) {
            $errors = [];
            if (!isset($cols[TblSession::LOGGER_ID])) {
                $errors[] = "user id required";
            }
            if (!isset($cols[TblSession::ACCESS_TOKEN])) {
                $errors[] = "access token required";
            }
            if (!isset($cols[TblSession::ACCESS_TOKEN_EXPIRY])) {
                $errors[] = "access token expiry required";
            }
            if (!isset($cols[TblSession::REFRESH_TOKEN])) {
                $errors[] = "refresh token required";
            }
            if (!isset($cols[TblSession::REFRESH_TOKEN_EXPIRY])) {
                $errors[] = "refresh token expiry required";
            }
            if ($errors) {
                throw new TblSessionExpection("TblSession Error: insert data issue '".implode(", ", $errors)."'.");
            }
        }
        $this->setTblLoggerId($cols[TblSession::LOGGER_ID][0]);
        $cols[TblSession::LOGGER_ID][0] = $this->getTblLoggerId();
        $this->setAccessToken($cols[TblSession::ACCESS_TOKEN][0]);
        $cols[TblSession::ACCESS_TOKEN][0] = $this->getAccessToken();
        $expiry = !($cols[TblSession::ACCESS_TOKEN_EXPIRY][0] instanceof DateTime) ?
            new DateTime($cols[TblSession::ACCESS_TOKEN_EXPIRY][0]) : $cols[TblSession::ACCESS_TOKEN_EXPIRY][0];
        $this->setAccessTokenExpiry($expiry);
        $cols[TblSession::ACCESS_TOKEN_EXPIRY][0] = $this->getAccessTokenExpiry();
        $this->setRefreshToken($cols[TblSession::REFRESH_TOKEN][0]);
        $cols[TblSession::REFRESH_TOKEN][0] = $this->getRefreshToken();
        $expiry = !($cols[TblSession::REFRESH_TOKEN_EXPIRY][0] instanceof DateTime) ?
            new DateTime($cols[TblSession::REFRESH_TOKEN_EXPIRY][0]) : $cols[TblSession::REFRESH_TOKEN_EXPIRY][0];
        $this->setRefreshTokenExpiry($expiry);
        $cols[TblSession::REFRESH_TOKEN_EXPIRY][0] = $this->getRefreshTokenExpiry();
           
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
        if (isset($cols[TblSession::LOGGER_ID][0])) {
            $this->setTblLoggerId($cols[TblSession::LOGGER_ID][0]);
            $cols[TblSession::LOGGER_ID][0] = $this->getTblLoggerId();
        }
        if (isset($cols[TblSession::ACCESS_TOKEN][0])) {
            $this->setAccessToken($cols[TblSession::ACCESS_TOKEN][0]);
            $cols[TblSession::ACCESS_TOKEN][0] = $this->getAccessToken();
        }
        if (isset($cols[TblSession::ACCESS_TOKEN_EXPIRY][0])) {
            $expiry = !($cols[TblSession::ACCESS_TOKEN_EXPIRY][0] instanceof DateTime) ?
                new DateTime($cols[TblSession::ACCESS_TOKEN_EXPIRY][0]) : $cols[TblSession::ACCESS_TOKEN_EXPIRY][0];
            $this->setAccessTokenExpiry($expiry);
            $cols[TblSession::ACCESS_TOKEN_EXPIRY][0] = $this->getAccessTokenExpiry();
        }
        if (isset($cols[TblSession::REFRESH_TOKEN][0])) {
            $this->setRefreshToken($cols[TblSession::REFRESH_TOKEN][0]);
            $cols[TblSession::REFRESH_TOKEN][0] = $this->getRefreshToken();
        }
        if (isset($cols[TblSession::REFRESH_TOKEN_EXPIRY][0])) {
            $expiry = !($cols[TblSession::REFRESH_TOKEN_EXPIRY][0] instanceof DateTime) ?
                new DateTime($cols[TblSession::REFRESH_TOKEN_EXPIRY][0]) : $cols[TblSession::REFRESH_TOKEN_EXPIRY][0];
            $this->setAccessTokenExpiry($expiry);
            $cols[TblSession::REFRESH_TOKEN_EXPIRY][0] = $this->getRefreshTokenExpiry();
        }

        return $cols;
    }
    
    /**
     * create session table in the database
     *
     * @param string table a table name in the database usually 'session'
     * @param array tableStructure an array representing the session table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblSession::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblSessionExpection("TblSession Error: Table '".TblSession::TABLE."' already exist");
        }
        if (!in_array(TblLogger::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblSessionExpection("TblSession Error: Table '".TblSession::TABLE."' does not exist");
        }

        $sql = "START TRANSACTION;";
        if ($tableStructure) {
            //TODO: implementation if table structure is given is coming later
            throw new TblSessionExpection("TblSession Error: creating table from Table structure not done yet");
            foreach ($tableStructure as $row) {
            }
        }

        $sql .= "
            CREATE TABLE ".TblSession::TABLE." (
            ".TblSession::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblSession::LOGGER_ID." int(10) UNSIGNED NOT NULL,
            ".TblSession::ACCESS_TOKEN." varchar(255) NOT NULL,
            ".TblSession::ACCESS_TOKEN_EXPIRY." datetime NOT NULL,
            ".TblSession::REFRESH_TOKEN." varchar(255) NOT NULL,
            ".TblSession::REFRESH_TOKEN_EXPIRY." datetime NOT NULL,
            ".TblSession::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblSession::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblSession::ID."),
            KEY (".TblSession::LOGGER_ID.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
            ALTER TABLE ".TblSession::TABLE."
                ADD CONSTRAINT ".TblSession::TABLE."_ibfk_1 FOREIGN KEY (".TblSession::LOGGER_ID.") REFERENCES ".TblLogger::TABLE." (".TblLogger::ID.") ON DELETE RESTRICT ON UPDATE CASCADE;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating session table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into session table'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $sql = "INSERT INTO ".TblSession::TABLE." (".TblSession::LOGGER_ID.", ".TblSession::ACCESS_TOKEN.", ".TblSession::ACCESS_TOKEN_EXPIRY.",
           ".TblSession::REFRESH_TOKEN.", ".TblSession::REFRESH_TOKEN_EXPIRY.") VALUES";
        for ($i = 0; $i < $noOfRecord ; $i++) {
            $userId = $this->getTblLoggerId($this->setTblLoggerId($this->generateRandomTblLoggerId()));
            $accessToken = $this->getAccessToken($this->setAccessToken());
            $accessTokenExpiry = $this->getAccessTokenExpiry($this->setAccessTokenExpiry());
            $refreshToken = $this->getRefreshToken($this->setRefreshToken());
            $refreshTokenExpiry = $this->getRefreshTokenExpiry($this->setRefreshTokenExpiry());
            $sql .= "($userId, '$accessToken', '$accessTokenExpiry', '$refreshToken', '$refreshTokenExpiry'),";
        }
        $sql = rtrim($sql, ",");
        return $sql;
    }
}
