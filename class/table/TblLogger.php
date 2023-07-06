<?php

/**
 * TblLogger
 *
 * A class for handling logger table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022, 1.1 => January 2023
 * @link        alabiansolutions.com
*/

class TblLoggerExpection extends Exception
{
}

class TblLogger extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string|null email column  */
    protected string|null $email;

    /** @var string phone column  */
    protected string|null $phone;

    /** @var string|null username column */
    protected string|null $username;

    /** @var string status column */
    protected string $status;

    /** @var string password column */
    protected string $password;

    /** @var string|null reset_token column */
    protected string|null $resetToken;

    /** @var string|null reset time column */
    protected string|null $resetTime;

    /** @var string|null activation token column*/
    protected string|null $activationToken;

    /** @var string|null activation column*/
    protected string|null $activationTime;

    /** @var array array used for where clause of update*/
    protected array $updateWhere;

    /** @var string email*/
    public const EMAIL = "email";

    /** @var string phone*/
    public const PHONE = "phone";

    /** @var string username*/
    public const USERNAME = "username";

    /** @var string status*/
    public const STATUS = "status";

    /** @var array status values */
    public const STATUS_VALUES = ['inactive','active'];

    /** @var string password*/
    public const PASSWORD = "password";

    /** @var string reset_token*/
    public const RESET_TOKEN = "reset_token";

    /** @var string reset_time*/
    public const RESET_TIME = "reset_time";

    /** @var string activation_token*/
    public const ACTIVATION_TOKEN = "activation_token";

    /** @var string activation_time*/
    public const ACTIVATION_TIME = "activation_time";

    /** @var string table name*/
    public const TABLE = "logger";

    /**
     * instantiation of TblLogger
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblLogger::TABLE);
    }

    /**
     * get email
     *
     * @return string email of the logger
    */
    public function getEmail():string
    {
        return $this->email;
    }

    /**
     * set email
     *
     * @param string email email of the logger
    */
    public function setEmail(string $email)
    {
        $errors = [];
        if ($this->select([TblLogger::EMAIL], [TblLogger::EMAIL => ['=', $email, 'isValue']])) {
            $errors[] = "'$email' already taken";
        }
        if (strlen($email) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "'$email' is too long (max 255 characters)";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "'$email' invalid email";
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: email issue '".implode(", ", $errors)."'.");
        }
        
        $this->email = $email;
    }

    /**
     * get phone
     *
     * @return string phone of the logger
     */
    public function getPhone():string
    {
        return $this->phone;
    }

    /**
     * set phone
     *
     * @param string phone phone no of the logger
     */
    public function setPhone(string $phone)
    {
        $errors = [];
        if ($this->select([TblLogger::PHONE], [TblLogger::PHONE => ['=', $phone, 'isValue']])) {
            $errors[] = "'$phone' already taken";
        }
        if (strlen($phone) > 255) {
            $errors[] = "'$phone' is too long (max 255 characters)";
        }
        if (empty($phone)) {
            $errors[] = "blank phone no";
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: phone issue '".implode(", ", $errors)."'.");
        }

        $this->phone = $phone;
    }

    /**
     * get username
     *
     * @return string username of the logger
    */
    public function getUsername():string
    {
        return $this->username;
    }

    /**
     * set username
     *
     * @param string username username of the logger
    */
    public function setUsername(string $username)
    {
        $errors = [];
        if ($this->select([TblLogger::USERNAME], [TblLogger::USERNAME => ['=', $username, 'isValue']])) {
            $errors[] = "'$username' already taken";
        }
        if (strlen($username) > 255) {
            $errors[] = "'$username' is too long (max 255 characters)";
        }
        if (empty($username)) {
            $errors[] = "blank username";
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: username issue '".implode(", ", $errors)."'.");
        }

        $this->username = $username;
    }

    /**
     * get status
     *
     * @return string status of the logger
    */
    public function getStatus():string
    {
        return $this->status;
    }

    /**
     * set status
     *
     * @param string status status of the logger
    */
    public function setStatus(string $status)
    {
        $errors = [];
        if (!in_array($status, TblLogger::STATUS_VALUES)) {
            $errors[] = "'$status' is not among '" .implode(", ", TblLogger::STATUS_VALUES) ."'";
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: status issue '".implode(", ", $errors)."'.");
        }

        $this->status = $status;
    }

    /**
     * get password
     *
     * @return string the hashed password of the logger
    */
    public function getPassword():string
    {
        return $this->password;
    }

    /**
     * set password
     *
     * @param string password the plain password of the logger(min 8 characters, at least one alphabet and one digit)
    */
    public function setPassword(string $password)
    {
        $errors = [];
        if (empty($password)) {
            $errors[] = "blank password";
        }
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{8,}$/", $password)) {
            $errors[] = "failed password rule (min 8 characters, at least one alphabet and one digit)";
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: password issue '".implode(", ", $errors)."')");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * get reset token
     *
     * @return string|null reset token of the logger
    */
    public function getResetToken():string|null
    {
        return $this->resetToken;
    }

    /**
     * set reset token
     *
     * @param string|null resetToken reset token of the logger
    */
    public function setResetToken(string $resetToken = null)
    {
        $errors = [];
        if ($resetToken && strlen($resetToken) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: reset token issue '".implode(", ", $errors)."'.");
        }

        if (!$resetToken) {
            $resetToken = implode("", Functions::asciiCollection(8));
        }
        $this->resetToken = $resetToken;
    }

    /**
     * get reset time
     *
     * @return string|null reset time of the logger
    */
    public function getResetTime():string|null
    {
        return $this->resetTime;
    }

    /**
     * set reset time
     *
     * @param DateTime|null resetTime reset time of the logger
    */
    public function setResetTime(DateTime $resetTime = null)
    {
        $errors = [];
        if ($resetTime) {
            if ($resetTime > new DateTime(SqlType::DATETIME_MAX) || $resetTime < new DateTime(SqlType::DATETIME_MIN)) {
                $errors[] = "outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX;
            }
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: reset time issue '".implode(", ", $errors)."'.");
        }

        $resetTime = (new DateTime())->add(new DateInterval("PT" . Functions::RESET_TIME_LIMIT . "S"));
        $this->resetTime = $resetTime->format('Y-m-d H:i:s');
    }

    /**
     * get activation token
     *
     * @return string|null activation token of the logger
    */
    public function getActivationToken():string|null
    {
        return $this->activationToken;
    }

    /**
     * set activation token
     *
     * @param string|null activationToken activation token of the logger
    */
    public function setActivationToken(string $activationToken = null)
    {
        $errors = [];
        if ($activationToken && strlen($activationToken) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: activation token issue '".implode(", ", $errors)."'.");
        }

        if (!$activationToken) {
            $activationToken = implode("", Functions::asciiCollection(8));
        }
        $this->activationToken = $activationToken;
    }

    /**
     * set activation time
     *
     * @return string|null activationTime activation time of the logger
    */
    public function getActivationTime():string|null
    {
        return $this->activationTime;
    }

    /**
     * set activation time
     *
     * @param DateTime|null activationTime activation time of the logger
    */
    public function setActivationTime(DateTime $activationTime = null)
    {
        $errors = [];
        if ($activationTime) {
            if ($activationTime > new DateTime(SqlType::DATETIME_MAX) || $activationTime < new DateTime(SqlType::DATETIME_MIN)) {
                $errors[] = "outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX;
            }
        }
        if ($errors) {
            throw new TblLoggerExpection("TblLogger Error: activation time issue '".implode(", ", $errors)."'.");
        }

        $activationTime = (new DateTime())->add(new DateInterval("PT" . Functions::RESET_TIME_LIMIT . "S"));
        $this->activationTime = $activationTime->format('Y-m-d H:i:s');
    }
    
    /**
     * insert data into logger table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        $errors = [];
        if (!isset($cols[TblLogger::EMAIL]) || !isset($cols[TblLogger::PHONE]) || !isset($cols[TblLogger::USERNAME])) {
            $errors[] = "either email, phone and username is required";
        }
        if (!isset($cols[TblLogger::STATUS])) {
            $errors[] = "status required";
        }
        if (!isset($cols[TblLogger::PASSWORD])) {
            $errors[] = "password required";
        }

        if (isset($cols[TblLogger::EMAIL][0])) {
            $this->setEmail($cols[TblLogger::EMAIL][0]);
            $cols[TblLogger::EMAIL][0] = $this->getEmail();
        }
        if (isset($cols[TblLogger::PHONE][0])) {
            $this->setPhone($cols[TblLogger::PHONE][0]);
            $cols[TblLogger::PHONE][0] = $this->getPhone();
        }
        if (isset($cols[TblLogger::USERNAME][0])) {
            $this->setUsername($cols[TblLogger::USERNAME][0]);
            $cols[TblLogger::USERNAME][0] = $this->getUsername();
        }
        $this->setStatus($cols[TblLogger::STATUS][0]);
        $cols[TblLogger::STATUS][0] = $this->getStatus();
        $this->setPassword($cols[TblLogger::PASSWORD][0]);
        $cols[TblLogger::PASSWORD][0] = $this->getPassword();
        if (isset($cols[TblLogger::RESET_TOKEN][0])) {
            $this->setResetToken();
            $cols[TblLogger::RESET_TOKEN] = [$this->getResetToken(), 'isValue'];
        }
        if (isset($cols[TblLogger::RESET_TIME][0])) {
            $this->setResetTime();
            $cols[TblLogger::RESET_TIME] = [$this->getResetTime(), 'isValue'];
        }
        if (isset($cols[TblLogger::ACTIVATION_TOKEN][0])) {
            $this->setActivationToken($cols[TblLogger::ACTIVATION_TOKEN][0]);
            $cols[TblLogger::ACTIVATION_TOKEN][0] = $this->getActivationToken();
        } else {
            $this->setActivationToken();
            $cols[TblLogger::ACTIVATION_TOKEN] = [$this->getActivationToken(), 'isValue'];
        }
        if (isset($cols[TblLogger::ACTIVATION_TIME][0])) {
            $this->setActivationTime($cols[TblLogger::ACTIVATION_TIME][0]);
            $cols[TblLogger::ACTIVATION_TIME][0] = $this->getActivationTime();
        } else {
            $this->setActivationTime();
            $cols[TblLogger::ACTIVATION_TIME] = [$this->getActivationTime(), 'isValue'];
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
        $loggerInfo = $this->select([TblLogger::EMAIL, TblLogger::PHONE, TblLogger::USERNAME], $this->updateWhere);
        if ($loggerInfo) {
            if (isset($cols[TblLogger::EMAIL][0]) && $loggerInfo[0][TblLogger::EMAIL] != $cols[TblLogger::EMAIL][0]) {
                if (isset($cols[TblLogger::EMAIL][0])) {
                    $this->setEmail($cols[TblLogger::EMAIL][0]);
                    $cols[TblLogger::EMAIL][0] = $this->getEmail();
                }
            }
            if (isset($cols[TblLogger::PHONE][0]) && $loggerInfo[0][TblLogger::PHONE] != $cols[TblLogger::PHONE][0]) {
                if (isset($cols[TblLogger::PHONE][0])) {
                    $this->setPhone($cols[TblLogger::PHONE][0]);
                    $cols[TblLogger::PHONE][0] = $this->getPhone();
                }
            }
            if (isset($cols[TblLogger::USERNAME][0]) && $loggerInfo[0][TblLogger::USERNAME] != $cols[TblLogger::USERNAME][0]) {
                if (isset($cols[TblLogger::USERNAME][0])) {
                    $this->setUsername($cols[TblLogger::USERNAME][0]);
                    $cols[TblLogger::USERNAME][0] = $this->getUsername();
                }
            }
        }
        if (isset($cols[TblLogger::STATUS][0])) {
            $this->setStatus($cols[TblLogger::STATUS][0]);
            $cols[TblLogger::STATUS][0] = $this->getStatus();
        }
        if (isset($cols[TblLogger::PASSWORD][0])) {
            $this->setPassword($cols[TblLogger::PASSWORD][0]);
            $cols[TblLogger::PASSWORD][0] = $this->getPassword();
        }
        if (isset($cols[TblLogger::RESET_TOKEN][0])) {
            $this->setResetToken();
            $cols[TblLogger::RESET_TOKEN] = [$this->getResetToken(), 'isValue'];
        }
        if (isset($cols[TblLogger::RESET_TIME][0])) {
            $this->setResetTime();
            $cols[TblLogger::RESET_TIME] = [$this->getResetTime(), 'isValue'];
        }
        if (isset($cols[TblLogger::ACTIVATION_TOKEN][0])) {
            $this->setActivationToken($cols[TblLogger::ACTIVATION_TOKEN][0]);
            $cols[TblLogger::ACTIVATION_TOKEN][0] = $this->getActivationToken();
        }
        if (isset($cols[TblLogger::ACTIVATION_TIME][0])) {
            $this->setActivationTime($cols[TblLogger::ACTIVATION_TIME][0]);
            $cols[TblLogger::ACTIVATION_TIME][0] = $this->getActivationTime();
        }

        return $cols;
    }

    /**
     * create logger table in the database
     *
     * @param string table a table name in the database usually 'logger'
     * @param array tableStructure an array representing the logger table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblLogger::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblLoggerExpection("TblLogger Error: Table '".TblLogger::TABLE."' already exist");
        }

        $sql = "START TRANSACTION;";
        if ($tableStructure) {
            //TODO: implementation if table structure is given is coming later
            throw new TblLoggerExpection("TblLogger Error: creating table from Table structure not done yet");
            foreach ($tableStructure as $row) {
            }
        }

        $sql .= "
            CREATE TABLE ".TblLogger::TABLE." (
            ".AbstractTable::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblLogger::EMAIL." varchar(255) DEFAULT NULL,
            ".TblLogger::PHONE." varchar(255) DEFAULT NULL,
            ".TblLogger::USERNAME." varchar(255) DEFAULT NULL,
            ".TblLogger::PASSWORD." varchar(255) NOT NULL,
            ".TblLogger::STATUS." enum('".implode("','", TblLogger::STATUS_VALUES)."') NOT NULL DEFAULT '".TblLogger::STATUS_VALUES[1]."',
            ".TblLogger::RESET_TOKEN." varchar(255) DEFAULT NULL,
            ".TblLogger::RESET_TIME." datetime DEFAULT NULL,
            ".TblLogger::ACTIVATION_TOKEN." varchar(255) NOT NULL,
            ".TblLogger::ACTIVATION_TIME." datetime NOT NULL,
            ".AbstractTable::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".AbstractTable::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".AbstractTable::ID."),
            UNIQUE KEY (".TblLogger::EMAIL."),
            UNIQUE KEY (".TblLogger::PHONE."),
            UNIQUE KEY (".TblLogger::USERNAME.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating logger table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the logger table. Password = 'password{ID}'
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    public function generateDummyRecords(int $noOfRecord, string $table=""):string
    {
        $table = TblLogger::TABLE;
        $sql = "INSERT INTO $table (".TblLogger::EMAIL.", ".TblLogger::PHONE.", ".TblLogger::USERNAME.",".TblLogger::PASSWORD.", ".TblLogger::STATUS.", 
            ".TblLogger::ACTIVATION_TOKEN.", ".TblLogger::ACTIVATION_TIME.") VALUES";
        $lastID = ($lastTblLogger = $this->getLast()) ? $lastTblLogger[TblLogger::ID] : 0;
        for ($i = 0; $i < $noOfRecord ; $i++) {
            ++$lastID;
            $email = $this->getEmail($this->setEmail("email{$lastID}@domain.com"));
            $phone = $this->getPhone($this->setPhone(str_pad("080".$lastID.rand(1, 5), 11, rand(4, 9), STR_PAD_RIGHT)));
            $username = $this->getUsername($this->setUsername("username{$lastID}"));
            $password = $this->getPassword($this->setPassword("password{$lastID}"));
            $status = $this->getStatus($this->setStatus(TblLogger::STATUS_VALUES[1]));
            $activationToken = $this->getActivationToken($this->setActivationToken());
            $activationTime = $this->getActivationTime($this->setActivationTime());
            $sql .= "('$email', '$phone', '$username', '$password', '$status', '$activationToken', '$activationTime'),";
        }
        $sql = rtrim($sql, ",");
        return $sql;
    }
}
