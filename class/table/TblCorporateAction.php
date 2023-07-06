<?php

/**
 * TblCorporateAction
 *
 * A class for handling CorporateAction table
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => June 2023
 * @link        alabiansolutions.com
 */

class TblCorporateActionExpection extends Exception
{
}

class TblCorporateAction extends AbstractTable implements InterfaceQuery
{
    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var string interim column*/
    protected string $interim;

    /** @var float dividend column*/
    protected float $dividend;

    /** @var string bonus column*/
    protected string $bonus;

    /** @var string closure date column*/
    protected string $closure_date;

    /** @var string agm date column*/
    protected string $agm_date;

    /** @var string payment date column*/
    protected string $payment_date;

    /** @var string table name*/
    public const TABLE = "corporate_action";

    /** @var string  interim*/
    public const INTERIM = "interim";

    /** @var float  dividend*/
    public const DIVIDEND = "dividend";

    /** @var array  collection of interim values*/
    public const INTERIM_VALUES = ['no', 'yes'];

    /** @var string  bonus*/
    public const BONUS = "bonus";

    /** @var string  closure_date*/
    public const CLOSURE_DATE = "closure_date";

    /** @var string  agn_date*/
    public const AGM_DATE = "agm_date";

    /** @var string  payment_date*/
    public const PAYMENT_DATE = "payment_date";

    /**
     * instantiation of TblCorporateAction
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query(TblCorporateAction::TABLE);
    }

    /**
     * get interim
     *
     * @return string interim
     */
    public function getInterim(): string
    {
        return $this->interim;
    }

    /**
     * set interim
     *
     * @param string interim
     */
    public function setInterim(string $interim)
    {
        $errors = [];
        if (!in_array($interim, TblCorporateAction::INTERIM_VALUES)) {
            $errors[] = "'$interim' is not among '" . implode(", ", TblCorporateAction::INTERIM_VALUES) . "'";
        }
        if ($errors) {
            throw new TblCorporateActionExpection("TblCorporateAction Error: status issue '" . implode(", ", $errors) . "'.");
        }

        $this->interim = $interim;
    }

    /**
     * get bonus
     *
     * @return string bonus
     */
    public function getBonus(): string
    {
        return $this->bonus;
    }

    /**
     * set bonus
     *
     * @param string bonus
     */
    public function setBonus(string $bonus)
    {
        $errors = [];
        if (empty($bonus)) {
            $errors[] = "bonus required ";
        }
        if (strlen($bonus) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblCorporateActionExpection("TblCorporateAction Error: bonus issue '" . implode(", ", $errors) . "'.");
        }

        $this->bonus = $bonus;
    }

    /**
     * get dividend
     *
     * @return float dividend
     */
    public function getDividend(): float
    {
        return $this->dividend;
    }

    /**
     * set dividend
     *
     * @param float dividend
     */
    public function setDividend(float $dividend)
    {
        $this->dividend = $dividend;
    }

    /**
     * get closure_date
     *
     * @return string closure_date
     */
    public function getClosureDate(): string
    {
        return $this->closure_date;
    }

    /**
     * set closure_date
     *
     * @param string closure_date
     */
    public function setClosureDate(string $closure_date)
    {
        $errors = [];
        if (empty($closure_date)) {
            $errors[] = "closure_date required ";
        }
        if (strlen($closure_date) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblCorporateActionExpection("TblCorporateAction Error: closure_date issue '" . implode(", ", $errors) . "'.");
        }

        $this->closure_date = $closure_date;
    }

    /**
     * get agm_date
     *
     * @return string agm_date
     */
    public function getAgmDate(): string
    {
        return $this->agm_date;
    }

    /**
     * set agm_date
     *
     * @param string agm_date
     */
    public function setAgmDate(string $agm_date)
    {
        $errors = [];
        if (empty($agm_date)) {
            $errors[] = "agm_date required ";
        }
        if (strlen($agm_date) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblCorporateActionExpection("TblCorporateAction Error: agm_date issue '" . implode(", ", $errors) . "'.");
        }

        $this->agm_date = $agm_date;
    }

    /**
     * get payment_date
     *
     * @return string payment_date
     */
    public function getPaymentDate(): string
    {
        return $this->payment_date;
    }

    /**
     * set payment_date
     *
     * @param string payment_date
     */
    public function setPaymentDate(string $payment_date)
    {
        $errors = [];
        if (empty($payment_date)) {
            $errors[] = "payment_date required ";
        }
        if (strlen($payment_date) > SqlType::VARCHAR_LENGTH) {
            $errors[] = "max length " . SqlType::VARCHAR_LENGTH;
        }
        if ($errors) {
            throw new TblCorporateActionExpection("TblCorporateAction Error: payment_date issue '" . implode(", ", $errors) . "'.");
        }

        $this->payment_date = $payment_date;
    }

    /**
     * insert data into CorporateAction table
     * @param array cols an array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
     */
    public function insert(array $cols, ?string $table = null): int
    {
        if (!isset($cols[TblCorporateAction::STOCK_ID]) || !isset($cols[TblCorporateAction::YEAR]) || !isset($cols[TblCorporateAction::PERIOD]) || !isset($cols[TblCorporateAction::DIVIDEND]) || !isset($cols[TblCorporateAction::INTERIM]) || !isset($cols[TblCorporateAction::BONUS]) || !isset($cols[TblCorporateAction::CLOSURE_DATE]) || !isset($cols[TblCorporateAction::AGM_DATE]) || !isset($cols[TblCorporateAction::PAYMENT_DATE])) {
            $errors = [];
            if (!isset($cols[TblCorporateAction::STOCK_ID])) {
                $errors[] = "stock_id is required";
            }
            if (!isset($cols[TblCorporateAction::YEAR])) {
                $errors[] = "year is required";
            }
            if (!isset($cols[TblCorporateAction::PERIOD])) {
                $errors[] = "period is required";
            }
            if (!isset($cols[TblCorporateAction::DIVIDEND])) {
                $errors[] = "dividend is required";
            }
            if (!isset($cols[TblCorporateAction::INTERIM])) {
                $errors[] = "interim is required";
            }
            if (!isset($cols[TblCorporateAction::BONUS])) {
                $errors[] = "bonus is required";
            }
            if (!isset($cols[TblCorporateAction::CLOSURE_DATE])) {
                $errors[] = "closure_date is required";
            }
            if (!isset($cols[TblCorporateAction::AGM_DATE])) {
                $errors[] = "agm_date is required";
            }
            if (!isset($cols[TblCorporateAction::PAYMENT_DATE])) {
                $errors[] = "payment_date is required";
            }
            if ($errors) {
                throw new TblCorporateActionExpection("TblCorporateAction Error: insert data issue '" . implode(", ", $errors) . "'.");
            }
        }

        $this->setStockId($cols[TblCorporateAction::STOCK_ID][0]);
        $cols[TblCorporateAction::STOCK_ID][0] = $this->getStockId();
        $this->setYear($cols[TblCorporateAction::YEAR][0]);
        $cols[TblCorporateAction::YEAR][0] = $this->getYear();
        $this->setPeriod($cols[TblCorporateAction::PERIOD][0]);
        $cols[TblCorporateAction::PERIOD][0] = $this->getPeriod();
        $this->setDividend($cols[TblCorporateAction::DIVIDEND][0]);
        $cols[TblCorporateAction::DIVIDEND][0] = $this->getDividend();
        $this->setInterim($cols[TblCorporateAction::INTERIM][0]);
        $cols[TblCorporateAction::INTERIM][0] = $this->getInterim();
        $this->setBonus($cols[TblCorporateAction::BONUS][0]);
        $cols[TblCorporateAction::BONUS][0] = $this->getBonus();
        $this->setClosureDate($cols[TblCorporateAction::CLOSURE_DATE][0]);
        $cols[TblCorporateAction::CLOSURE_DATE][0] = $this->getClosureDate();
        $this->setAgmDate($cols[TblCorporateAction::AGM_DATE][0]);
        $cols[TblCorporateAction::AGM_DATE][0] = $this->getAgmDate();
        $this->setPaymentDate($cols[TblCorporateAction::PAYMENT_DATE][0]);
        $cols[TblCorporateAction::PAYMENT_DATE][0] = $this->getPaymentDate();

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
        if (isset($cols[TblCorporateAction::STOCK_ID])) {
            $this->setStockId($cols[TblCorporateAction::STOCK_ID][0]);
            $cols[TblCorporateAction::STOCK_ID][0] = $this->getStockId();
        }
        if (isset($cols[TblCorporateAction::YEAR])) {
            $this->setYear($cols[TblCorporateAction::YEAR][0]);
            $cols[TblCorporateAction::YEAR][0] = $this->getYear();
        }
        if (isset($cols[TblCorporateAction::PERIOD])) {
            $this->setPeriod($cols[TblCorporateAction::PERIOD][0]);
            $cols[TblCorporateAction::PERIOD][0] = $this->getPeriod();
        }
        if (isset($cols[TblCorporateAction::DIVIDEND])) {
            $this->setDividend($cols[TblCorporateAction::DIVIDEND][0]);
            $cols[TblCorporateAction::DIVIDEND][0] = $this->getDividend();
        }
        if (isset($cols[TblCorporateAction::INTERIM])) {
            $this->setInterim($cols[TblCorporateAction::INTERIM][0]);
            $cols[TblCorporateAction::INTERIM][0] = $this->getInterim();
        }
        if (isset($cols[TblCorporateAction::BONUS])) {
            $this->setBonus($cols[TblCorporateAction::BONUS][0]);
            $cols[TblCorporateAction::BONUS][0] = $this->getBonus();
        }
        if (isset($cols[TblCorporateAction::CLOSURE_DATE])) {
            $this->setClosureDate($cols[TblCorporateAction::CLOSURE_DATE][0]);
            $cols[TblCorporateAction::CLOSURE_DATE][0] = $this->getClosureDate();
        }
        if (isset($cols[TblCorporateAction::AGM_DATE])) {
            $this->setAgmDate($cols[TblCorporateAction::AGM_DATE][0]);
            $cols[TblCorporateAction::AGM_DATE][0] = $this->getAgmDate();
        }
        if (isset($cols[TblCorporateAction::PAYMENT_DATE])) {
            $this->setPaymentDate($cols[TblCorporateAction::PAYMENT_DATE][0]);
            $cols[TblCorporateAction::PAYMENT_DATE][0] = $this->getPaymentDate();
        }
        return $cols;
    }

    /**
     * create TblCorporateAction table in the database
     *
     * @param string table a table name in the database usually 'corporate_action'
     * @param array tableStructure an array representing the stock table struture
     */
    public static function createTable(string $table = "", array $tableStructure = [])
    {
        $MyQuery = new Query("", false);
        if (in_array(TblCorporateAction::TABLE, $MyQuery->getTablesInDb())) {
            throw new TblCorporateActionExpection("TblCorporateAction Error: Table '" . TblCorporateAction::TABLE . "' already exist");
        }

        $sql = "START TRANSACTION;";
        $sql .= "
            CREATE TABLE " . TblCorporateAction::TABLE . " (
            " . TblCorporateAction::ID . " int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            " . TblCorporateAction::STOCK_ID . " int(10) UNSIGNED NOT NULL,
            " . TblCorporateAction::YEAR . " datetime NOT NULL,
            " . TblCorporateAction::PERIOD . " enum('" . implode("','", TblCorporateAction::PERIOD_VALUES) . "') NOT NULL DEFAULT '" . TblCorporateAction::PERIOD_VALUES[1] . "',
            " . TblCorporateAction::DIVIDEND . " float NOT NULL,
            " . TblCorporateAction::INTERIM . " enum('" . implode("','", TblCorporateAction::INTERIM_VALUES) . "') NOT NULL DEFAULT '" . TblCorporateAction::INTERIM_VALUES[1] . "',
            " . TblCorporateAction::BONUS . " varchar(255) NOT NULL,
            " . TblCorporateAction::CLOSURE_DATE . " varchar(255) NOT NULL,
            " . TblCorporateAction::AGM_DATE . " varchar(255) NOT NULL,
            " . TblCorporateAction::PAYMENT_DATE . " varchar(255) NOT NULL,
            " . TblCorporateAction::CREATED_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            " . TblCorporateAction::UPDATE_AT . " datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (" . TblCorporateAction::ID . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
            ALTER TABLE " . TblCorporateAction::TABLE . "
                ADD CONSTRAINT " . TblCorporateAction::TABLE . "_ibfk_1 FOREIGN KEY (" . TblCorporateAction::STOCK_ID . ") REFERENCES " . TblStock::TABLE . " (" . TblStock::ID . ") ON DELETE RESTRICT ON UPDATE CASCADE;
        ";
        $sql .= "COMMIT;";
        $MyQuery->getDbConnect()->exec($sql);
    }

    /**
     * for populating CorporateAction table with dummy records
     *
     * @param int noOfRecord no of records to be generated
     * @param string table a table name in the database    */
    public function populateTable(int $noOfRecord, string $table = "")
    {
        $this->query->executeSql($this->generateDummyRecords($noOfRecord));
    }

    /**
     * generate sql statement for inserting dummy records into the TblCorporateAction table. Password = 'password{ID}'
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
