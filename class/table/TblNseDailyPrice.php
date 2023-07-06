<?php

/**
 * TblNseDailyPrice
 *
 * A class for handling nse-daily-price table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
*/

class TblNseDailyPriceExpection extends Exception
{
}

class TblNseDailyPrice extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var float asi column*/
    protected float $asi;

    /** @var int deals column*/
    protected int $deals;

    /** @var float market_volume column*/
    protected float $market_volume;

    /** @var float market_value column*/
    protected float $market_value;

    /** @var float market_cap column*/
    protected float $market_cap;

    /** @var float  asi*/
    public const ASI = "asi";

    /** @var int  deals*/
    public const DEALS = "deals";

    /** @var float market_volume*/
    public const MARKET_VOLUME = "market_volume";

    /** @var float market_value*/
    public const MARKET_VALUE = "market_value";

    /** @var float  market_cap*/
    public const MARKET_CAP = "market_cap";

    /** @var string table name*/
    public const TABLE = "nse_daily_price";

    /**
     * instantiation of TblNseDailyPrice
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblNseDailyPrice::TABLE);
    }

    /**
     * get ASI
     *
     * @return float $asi
     */
    public function getAsi(): float
    {
        return $this->asi;
    }
    /**
     * set ASI
     *
     * @param float $asi
     */
    public function setAsi(float $asi)
    {
        $errors = [];
        if (empty($asi)) {
            $errors[] = "asi is required ";
        }
        if ($errors) {
            throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: asi issue '" . implode(", ", $errors) . "'.");
        }

        return $this->asi = $asi;
    }

    /**
     * get DEALS
     *
     * @return int $deals
     */
    public function getDeals(): int
    {
        return $this->deals;
    }
    /**
     * set DEALS
     *
     * @param int $deals
     */
    public function setDeals(int $deals)
    {
        $errors = [];
        if (empty($deals)) {
            $errors[] = "deals is required ";
        }
        if ($errors) {
            throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: deals issue '" . implode(", ", $errors) . "'.");
        }

        return $this->deals = $deals;
    }

    /**
     * get market_volume
     *
     * @return float $market_volume
     */
    public function getMarketVolume(): float
    {
        return $this->market_volume;
    }
    /**
     * set market_volume
     *
     * @param float $market_volume
     */
    public function setMarketVolume(float $market_volume)
    {
        $errors = [];
        if (empty($market_volume)) {
            $errors[] = "market_volume is required ";
        }
        if ($errors) {
            throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: market volume issue '" . implode(", ", $errors) . "'.");
        }

        return $this->market_volume = $market_volume;
    }

    /**
     * get MARKET VALUE
     *
     * @return float $market_value
     */
    public function getMarketValue(): float
    {
        return $this->market_value;
    }
    /**
     * set market_value
     *
     * @param float $market_value
     */
    public function setMarketValue(float $market_value)
    {
        $errors = [];
        if (empty($market_value)) {
            $errors[] = "market_value is required ";
        }
        if ($errors) {
            throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: market value issue '" . implode(", ", $errors) . "'.");
        }

        return $this->market_value = $market_value;
    }

    /**
     * get market cap
     *
     * @return float $market_cap
     */
    public function getMarketCap(): float
    {
        return $this->market_cap;
    }
    /**
     * set market_cap
     *
     * @param float $market_cap
     */
    public function setMarketCap(float $market_cap)
    {
        $errors = [];
        if (empty($market_cap)) {
            $errors[] = "market_cap is required ";
        }
        if ($errors) {
            throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: market cap issue '" . implode(", ", $errors) . "'.");
        }

        $this->market_cap = $market_cap;
    }

    
    /**
     * insert data into nse-daily-price table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int
    {
        if (!isset($cols[TblNseDailyPrice::FILE]) || !isset($cols[TblNseDailyPrice::DATE]) || !isset($cols[TblNseDailyPrice::ASI]) || !isset($cols[TblNseDailyPrice::DEALS]) || !isset($cols[TblNseDailyPrice::MARKET_VOLUME]) || !isset($cols[TblNseDailyPrice::MARKET_VALUE]) || !isset($cols[TblNseDailyPrice::MARKET_CAP])) {
            $errors = [];
            if (!isset($cols[TblNseDailyPrice::FILE])) {
                $errors[] = "file is required";
            }
            if (!isset($cols[TblNseDailyPrice::DATE])) {
                $errors[] = "date is required";
            }
            if (!isset($cols[TblNseDailyPrice::ASI])) {
                $errors[] = "asi is required";
            }
            if (!isset($cols[TblNseDailyPrice::DEALS])) {
                $errors[] = "deal is required";
            }
            if (!isset($cols[TblNseDailyPrice::MARKET_VOLUME])) {
                $errors[] = "market volume is required";
            }
            if (!isset($cols[TblNseDailyPrice::MARKET_VALUE])) {
                $errors[] = "market value is required";
            }
            if (!isset($cols[TblNseDailyPrice::MARKET_CAP])) {
                $errors[] = "market cap is required";
            }
            if ($errors) {
                throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: insert data issue '".implode(", ", $errors)."'.");
            }
        }

        $this->setFile($cols[TblNseDailyPrice::FILE][0]);
        $cols[TblNseDailyPrice::FILE][0] = $this->getFile();
        $this->setDate($cols[TblNseDailyPrice::DATE][0]);
        $cols[TblNseDailyPrice::DATE][0] = $this->getDate();
        $this->setAsi($cols[TblNseDailyPrice::ASI][0]);
        $cols[TblNseDailyPrice::ASI][0] = $this->getAsi();
        $this->setDeals($cols[TblNseDailyPrice::DEALS][0]);
        $cols[TblNseDailyPrice::DEALS][0] = $this->getDeals();
        $this->setMarketVolume($cols[TblNseDailyPrice::MARKET_VOLUME][0]);
        $cols[TblNseDailyPrice::MARKET_VOLUME][0] = $this->getMarketVolume();
        $this->setMarketValue($cols[TblNseDailyPrice::MARKET_VALUE][0]);
        $cols[TblNseDailyPrice::MARKET_VALUE][0] = $this->getMarketValue();
        $this->setMarketCap($cols[TblNseDailyPrice::MARKET_CAP][0]);
        $cols[TblNseDailyPrice::MARKET_CAP][0] = $this->getMarketCap();
       


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
        if (isset($cols[TblNseDailyPrice::FILE])) {
            $this->setFile($cols[TblNseDailyPrice::FILE][0]);
            $cols[TblNseDailyPrice::FILE][0] = $this->getFile();
        }
        if (isset($cols[TblNseDailyPrice::DATE])) {
            $this->setDate($cols[TblNseDailyPrice::DATE][0]);
            $cols[TblNseDailyPrice::DATE][0] = $this->getDate();
        }
        if (isset($cols[TblNseDailyPrice::ASI])) {
            $this->setAsi($cols[TblNseDailyPrice::ASI][0]);
            $cols[TblNseDailyPrice::ASI][0] = $this->getAsi();
        }
        if (isset($cols[TblNseDailyPrice::DEALS])) {
            $this->setDeals($cols[TblNseDailyPrice::DEALS][0]);
            $cols[TblNseDailyPrice::DEALS][0] = $this->getDeals();
        }
        if (isset($cols[TblNseDailyPrice::MARKET_VOLUME])) {
            $this->setMarketVolume($cols[TblNseDailyPrice::MARKET_VOLUME][0]);
            $cols[TblNseDailyPrice::MARKET_VOLUME][0] = $this->getMarketVolume();
        }
        if (isset($cols[TblNseDailyPrice::MARKET_VALUE])) {
            $this->setMarketValue($cols[TblNseDailyPrice::MARKET_VALUE][0]);
            $cols[TblNseDailyPrice::MARKET_VALUE][0] = $this->getMarketValue();
        }
        if (isset($cols[TblNseDailyPrice::MARKET_CAP])) {
            $this->setMarketCap($cols[TblNseDailyPrice::MARKET_CAP][0]);
            $cols[TblNseDailyPrice::MARKET_CAP][0] = $this->getMarketCap();
        }
      
        return $cols;
    }

    /**
     * create NseDailyPrice table in the database
     *
     * @param string table a table name in the database usually 'TblNseDailyPrice'
     * @param array tableStructure an array representing the stock table struture
    */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblNseDailyPrice::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblNseDailyPriceExpection("TblNseDailyPrice Error: Table '".TblNseDailyPrice::TABLE."' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE ".TblNseDailyPrice::TABLE." (
            ".TblNseDailyPrice::ID." int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ".TblNseDailyPrice::FILE." varchar(255) NOT NULL,
            ".TblNseDailyPrice::DATE." DateTime NOT NULL,
            ".TblNseDailyPrice::ASI." float NOT NULL,
            ".TblNseDailyPrice::DEALS." int NOT NULL,
            ".TblNseDailyPrice::MARKET_VOLUME." float NOT NULL,
            ".TblNseDailyPrice::MARKET_VALUE." float NOT NULL,
            ".TblNseDailyPrice::MARKET_CAP." float NOT NULL,
            ".TblNseDailyPrice::CREATED_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ".TblNseDailyPrice::UPDATE_AT." datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (".TblNseDailyPrice::ID.")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating NseDailyPrice table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table="")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the NseDailyPrice table. Password = 'password{ID}'
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