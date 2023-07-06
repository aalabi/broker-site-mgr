<?php

/**
 * AbstractTable
 *
 * An abstract class for handling table db operations
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022, 1.1 => January 2023
 * @link        alabiansolutions.com
*/

class AbstractTableExpection extends Exception
{
}

abstract class AbstractTable
{
    /** @var string database table been used by sql  */
    protected string $table;

    /** @var DbConnect an instance of DbConnect  */
    protected DbConnect $dbConnect;

    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var int id column*/
    protected int $id;

    /** @var int logger.id column foreign key from logger table*/
    protected int $loggerId;

    /** @var int profile.id column foreign key from profile table*/
    protected int $profileId;

    /** @var string date column*/
    protected string $date;

    /** @var string year column*/
    protected string $year;

    /** @var string period column*/
    protected string $period;

    /** @var string type column*/
    protected string $type;

    /** @var string file column*/
    protected string $file;

    /** @var string name column*/
    protected string $name;

    /** @var int stockId column*/
    protected int $stockId;

    /** @var string  created at column  */
    protected string $createdAt;

    /** @var string  updated at column  */
    protected string $updatedAt;

    /** @var array array used for where clause of update*/
    protected array $updateWhere;

    /** @var string  id*/
    public const ID = "id";

    /** @var string  logger.id foreign key from logger table*/
    public const LOGGER_ID = "logger";

    /** @var string  profile.id foreign key from profile table*/
    public const PROFILE_ID = "profile";

    /** @var string  date*/
    public const DATE = "date";

    /** @var string  year*/
    public const YEAR = "year";

    /** @var string  period*/
    public const PERIOD = "period";

    /** @var array  collection of period values*/
    public const PERIOD_VALUES = [1, 2, 3, 4, "1"=>1, "2"=>2, "3"=>3, "4"=>4];

    /** @var string  type*/
    public const TYPE = "type";

    /** @var string  file*/
    public const FILE = "file";

    /** @var string  name*/
    public const NAME = "name";

    /** @var int  stock.id foreign key from stock table*/
    public const STOCK_ID = "stock";

    /** @var string created_at  */
    public const CREATED_AT = "created_at";

    /** @var string updated_at*/
    public const UPDATE_AT = "updated_at";

    /**
     * instantiation of AbstractTable
     *
     * @param string table default table in the database to be used as the sql query
     */
    public function __construct(string $table)
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query($table);
    }

    /**
     * get the table been used for query
     * @return string the table name
    */
    public function getTable():string
    {
        return $this->table;
    }

    /**
     * get the DbConnect been used for query
     * @return DbConnect the DbConnect
    */
    public function getDbConnect():DbConnect
    {
        return $this->dbConnect;
    }

    /**
     * set the DbConnect been used for query
     * @param DbConnect dbConnect to be used
    */
    public function setDbConnect(DbConnect $dbConnect)
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * get id
     *
     * @return int id
    */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * set id
     *
     * @param int id
    */
    public function setId(int $id)
    {
        if ($id > SqlType::INT_MAX || $id < SqlType::INT_MIN) {
            throw new AbstractTableExpection("AbstractTable Error: id is outside range of  ". SqlType::INT_MIN ." to ".SqlType::INT_MAX);
        }
        $this->id = $id;
    }

    /**
     * get logger.id the foreign key from logger table
     *
     * @return int id
    */
    public function getTblLoggerId():int
    {
        return $this->loggerId;
    }

    /**
     * set logger.id the foreign key from logger table
     *
     * @param int id
    */
    public function setTblLoggerId(int $loggerId)
    {
        $TblUser = new TblLogger();
        if (!$TblUser->get($loggerId)) {
            throw new AbstractTableExpection("AbstractTable Error: invalid logger.id '$loggerId'");
        }
        $this->loggerId = $loggerId;
    }

    /**
     * get profile.id the foreign key from profile table
     *
     * @return int id
    */
    public function getTblProfileId():int
    {
        return $this->profileId;
    }

    /**
     * set profile.id the foreign key from profile table
     *
     * @param int $profileId the profile.id from profile table
     * @param int id
    */
    public function setTblProfileId(int $profileId)
    {
        $TblProfile = new TblProfile();
        if (!$TblProfile->get($profileId)) {
            throw new AbstractTableExpection("AbstractTable Error: invalid profile.id '$profileId'");
        }
        $this->profileId = $profileId;
    }

    /**
     * get date
     *
     * @return string date
    */
    public function getDate():string
    {
        return $this->date;
    }

    /**
     * set date
     *
     * @param DateTime date
    */
    public function setDate(DateTime $date)
    {
        if ($date > new DateTime(SqlType::DATETIME_MAX) || $date < new DateTime(SqlType::DATETIME_MIN)) {
            throw new AbstractTableExpection("AbstractTable Error: date at is outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX);
        }
        $this->date = $date->format('Y-m-d');
    }

    /**
     * get year
     *
     * @return string year
    */
    public function getYear():string
    {
        return $this->year;
    }

    /**
     * set year
     *
     * @param DateTime year
    */
    public function setYear(DateTime $year)
    {
        if ($year > new DateTime(SqlType::DATETIME_MAX) || $year < new DateTime(SqlType::DATETIME_MIN)) {
            throw new AbstractTableExpection("AbstractTable Error: date at is outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX);
        }
        $this->year = $year->format('Y');
    }

    /**
     * get period
     *
     * @return string period
    */
    public function getPeriod():string
    {
        return $this->period;
    }

    /**
     * set period
     *
     * @param string period
    */
    public function setPeriod(string $period)
    {
        $errors = [];
        if (!in_array($period, AbstractTable::PERIOD_VALUES)) {
            $errors[] = "'$period' is not among '" .implode(", ", AbstractTable::PERIOD_VALUES) ."'";
        }
        if ($errors) {
            throw new AbstractTableExpection("AbstractTable Error: status issue '".implode(", ", $errors)."'.");
        }

        $this->period = $period;
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
        $this->type = $type;
    }

    /**
     * get file
     *
     * @return string file
    */
    public function getFile():string
    {
        return $this->file;
    }

    /**
     * set file
     *
     * @param string file
    */
    public function setFile(string $file)
    {
        $errors = [];
        if (empty($file)) {
            $errors[] = "file required ";
        }
        if (strlen($file) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new AbstractTableExpection("AbstractTable Error: file issue '".implode(", ", $errors)."'.");
        }

        $this->file = $file;
    }

    /**
     * get name
     *
     * @return string name of the profile
    */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * set name
     *
     * @param string name of the profile
    */
    public function setName(string $name)
    {
        $errors = [];
        if (empty($name)) {
            $errors[] = "name required ";
        }
        if (strlen($name) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length ".SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new AbstractTableExpection("AbstractTable Error: name issue '".implode(", ", $errors)."'.");
        }

        $this->name = $name;
    }

    /**
     * get stock.id the foreign key from stock table
     *
     * @return int stock.id
    */
    public function getStockId():int
    {
        return $this->stockId;
    }

    /**
     * set stock.id the foreign key from stock table
     *
     * @param int id
    */
    public function setStockId(int $stockId)
    {
        $TblStock = new TblStock();
        if (!$TblStock->get($stockId)) {
            throw new AbstractTableExpection("AbstractTable Error: invalid stock.id '$stockId'");
        }
        $this->stockId = $stockId;
    }

    /**
     * set created at
     * @param DateTime createdAt
    */
    public function setCreatedAt(DateTime $createdAt)
    {
        if ($createdAt > new DateTime(SqlType::DATETIME_MAX) || $createdAt < new DateTime(SqlType::DATETIME_MIN)) {
            throw new AbstractTableExpection("AbstractTable Error: created at is outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX);
        }
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
    }

    /**
     * generate a random id from logger table
     *
     * @return int a random logger id
     */
    public function generateRandomTblLoggerId():int
    {
        $MyQuery = new Query("", false);
        if (!in_array(TblLogger::TABLE, $MyQuery->getTablesInDb())) {
            throw new AbstractTableExpection("AbstractTable Error: Table '".TblLogger::TABLE."' does not exist");
        }

        $sql = "SELECT ".TblLogger::ID." FROM ".TblLogger::TABLE." ORDER BY RAND() LIMIT 1";
        return $MyQuery->executeSql($sql)['rows'][0][TblLogger::ID];
    }

    /**
     * get created at
     * @return string createdAt
    */
    public function getCreatedAt():string
    {
        return $this->createdAt;
    }

    /**
     * set updated at
     * @param DateTime updatedAt
    */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        if ($updatedAt > new DateTime(SqlType::DATETIME_MAX) || $updatedAt < new DateTime(SqlType::DATETIME_MIN)) {
            throw new AbstractTableExpection("AbstractTable Error: updated at is outside range of  ". SqlType::DATETIME_MIN ." to ".SqlType::DATETIME_MAX);
        }
        $this->updatedAt = $updatedAt->format('Y-m-d H:i:s');
    }

    /**
     * get updated at
     * @return string updatedAt
    */
    public function getUpdatedat():string
    {
        return $this->updatedAt;
    }

    /**
     * select data from table
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
     * select data from table based on an id
     *
     * @param int id value of id column in the table
     * @param array cols an array whose elements are columns in table [col1, col2, ...coln]
     * @param string table a table name in the database
     * @return array an array of selected rows or empty array if no match
    */
    public function selectById(int $id, array $cols = []):array
    {
        return $this->query->select($cols, ['id'=>['=', $id, 'isValue']]);
    }

    /**
     * delete data from table
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string table a table name in the database
     * @param @param int the no of deleted rows
    */
    public function delete(array $where, ?string $table = null):int
    {
        return $this->query->delete($where);
    }

    /**
     * delete data from table based on an id
     *
     * @param int id value of id column in the table
     * @param string table a table name in the database
     * @param @param int the no of deleted rows
    */
    public function deleteById(int $id):int
    {
        return $this->query->delete(['id'=>['=', $id, 'isValue']]);
    }

    /**
     * select data from table based on the id
     * @param int rowId the id of the row to be deleted
     * @param string table a table name in the database
     * @return array an array of selected row or empty array if id is invalid
    */
    public function get(int $rowId, ?string $table = null):array
    {
        return $this->query->get($rowId);
    }

    /**
     * select the first row from table
     * @param string table a table name in the database
     * @return array an array of the first row or empty array if the table is empty
    */
    public function getFirst(?string $table = null):array
    {
        return $this->query->getFirst();
    }

    /**
     * select the latest row from table
     * @param string table a table name in the database
     * @return array an array of the latest row or empty array if the table is empty
    */
    public function getLast(?string $table = null):array
    {
        return $this->query->getLast();
    }

    /**
     * for getting all the data in a column of field of the table
     *
     * @param string $column the column name
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string|null $table a table name in the database
     * @return array
     */
    public function getColumn(string $colName, $where = [], ?string $table = null):array
    {
        $data = [];
        if ($result = $this->query->select([$colName], $where)) {
            foreach ($result as $aResult) {
                $data[] = $aResult[$colName];
            }
        }
        return $data;
    }

    /**
     * for getting all the data in a column of field of the table indexed by another column
     *
     * @param string $colIndex the column for indexing
     * @param string $colValue the column for value
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @return array
     */
    public function getColumnByIndex(string $colIndex, string $colValue, $where = []):array
    {
        $records = [];
        $result = $where ? $this->query->select([$colIndex, $colValue], [$where[0] => ['=', $where[1], 'isValue']]) : $this->query->select([$colIndex, $colValue]);
        if ($result) {
            foreach ($result as $aResult) {
                $records[$aResult[$colIndex]] = $aResult[$colValue];
            }
        }
        return $records;
    }

    /**
     * update data in table
     *
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
        * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
        *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
        * @param string table a table name in the database
        * @param int the no of updated rows
     */
    public function update(array $cols, array $where, ?string $table = null):int
    {
        $this->updateWhere = $where;
        return $this->query->update($this->generateUpdateColumn($cols), $where);
    }

    /**
     * update data in table based on an id
     *
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
     * @param int id value of id column in the table
     * @param string table a table name in the database
     * @param int the no of updated rows
     */
    public function updateById(array $cols, int $id, ?string $table = null):int
    {
        $this->updateWhere = $where = ['id' => ['=', $id, 'isValue']];
        return $this->query->update($this->generateUpdateColumn($cols), $where);
    }

    /**
     * generate colums for the update method
     *
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
     * @return array the generated columns
     */
    abstract protected function generateUpdateColumn(array $colums);

    /**
     * create an sql table
     *
     * @param string table a table name in the database
     * @param array tableStructure an array representing the table struture
    */
    abstract public static function createTable(string $table = "", array $tableStructure = []);

    /**
     * for populating a table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
    */
    abstract public function populateTable(int $noOfRecord, string $table="");

    /**
     * generate sql statement for inserting dummy records into a table
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database
     * @return string an sql statement for inserting records into table
    */
    abstract public function generateDummyRecords(int $noOfRecord, string $table=""):string;
}
