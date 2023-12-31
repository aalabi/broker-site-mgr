<?php

/**
 * Broker
 *
 * A class managing Broker
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => July 2023
 * @link        alabiansolutions.com
 */

class BrokerExpection extends Exception
{
}

class Broker
{
    /** @var DbConnect an instance of DbConnect  */
    protected DbConnect $dbConnect;

    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var int Limit with value 5000*/
    public const LIMIT = 5000;

    /** @var TblStock an instance of TblStock  */
    protected TblStock $tblStock;

    /** @var TblNseDailyPrice an instance of TblNseDailyPrice  */
    protected TblNseDailyPrice $tblNseDailyPrice;

    /** @var TblMarketReview an instance of TblMarketReview  */
    protected TblMarketReview $tblMarketReview;

    /** @var TblCorporateAction an instance of TblCorporateAction  */
    protected TblCorporateAction $tblCorporateAction;

    /** @var TblFinancialReport an instance of TblFinancialReport  */
    protected TblFinancialReport $tblFinancialReport;

    /** @var TblDocument an instance of TblDocument  */
    protected TblDocument $tblDocument;

    /** @var TblDailyNews an instance of TblDailyNews  */
    protected TblDailyNews $tblDailyNews;

    /** @var TblNews an instance of TblNews  */
    protected TblNews $tblNews;

    /** @var TblNewsletter an instance of TblNewsletter */
    protected TblNewsletter $tblNewsletter;

    /** @var string document type prefix for downloads */
    protected const DOWNLOADS_PREFIX = "do";

    /** @var string document type prefix for daily nse summary */
    protected const DAILY_NSE_PREFIX = "dn";

    /** @var string document type prefix for financial report */
    protected const FINANCIAL_REPORT_PREFIX = "fi";

    /** @var string document type prefix for market review */
    protected const MARKET_REVIEW_PREFIX = "ma";

    /**
     * instantiation of Broker
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query();
        $this->tblStock = new TblStock();
        $this->tblNseDailyPrice = new TblNseDailyPrice();
        $this->tblMarketReview = new TblMarketReview();
        $this->tblCorporateAction = new TblCorporateAction();
        $this->tblFinancialReport = new TblFinancialReport();
        $this->tblDocument = new TblDocument();
        $this->tblDailyNews = new TblDailyNews();
        $this->tblNews = new TblNews();
        $this->tblNewsletter = new TblNewsletter();
    }
    
    /**
     * for uploading files
     *
     * @param array $file the equivalent of $_FILES array from HTML
     * @param string $doctype a indication of the document type been uploaded
     * @param array $extCollection a collection of permitted extensions
     * @return string|boolean derived name of the file upload or false if upload fails
     */
    protected function uploadDocument(array $file, string $doctype, array $extCollection = ['pdf']): string|bool
    {
        $error = [];
        if (is_array($file)) {
            $file = $file[array_keys($file)[0]];
            if(!isset($file['name']) || !isset($file['type']) || !isset($file['tmp_name']) || !isset($file['size'])) {
                $error[] = 'invalid $_FILES';
            }
             
            if (!$file['error']) {
                $extention = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $filename = $doctype.time().".$extention";
                $filePath = Functions::getDocDirectoryPath(true).$filename;

                if (in_array($extention, $extCollection)) {
                    if ($file['size'] < 5_000_000) {
                        move_uploaded_file($file['tmp_name'], $filePath);
                    } else {
                        $error[] = "larger file";
                    }
                } else {
                    $error[] = "unsupported extension";
                }
            } else {
                $error[] = $file['error'];
            }
        } else {
            $error[] = 'invalid $_FILES';
        }

        if($error) {
            throw new BrokerExpection("cannot upload: " . implode($error));
        }
        return $filename;
    }

    /**
     * for deleting files
     *
     * @param string $file the file to be delete
     * @return void
     */
    protected function deleteUploadedDocument(string $file)
    {
        $filePath = Functions::getDocDirectoryPath(true).$file;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }


    /**
     * for creating a new Stock
     *
     * @param string $name name of the stock
     *
     * @return int id of the newly created stock
     */
    public function createStock(string $name): int
    {
        try {
            $cols = [tblStock::NAME => [$name, 'isValue']];
            $id = $this->tblStock->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating stock: " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for changing or editing a stock
     *
     * @param int $id the stock id
     * @param string $name name of the stock
     * @return void
     */
    public function changeStock(int $id, string|null $name = null)
    {
        if (!$name) {
            throw new BrokerExpection("name must be provided");
        }
        if ($name) {
            $cols[tblStock::NAME] = [$name, 'isValue'];
        }
        $this->tblStock->updateById($cols, $id);
    }

    /**
     * for deleting exiting stock that has no dependence
     *
     * @param int $id the stock id
     */
    public function deleteStock(int $id) //Check for dependency
    {
        try {
            $this->tblStock->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this stock has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a grade
     *
     * @param int $id the grade id
     * @param bool $addOtherInfo if true other information is added
     * @return array an array containing the grade information
     */
    public function stockInfo(int $id): array
    {
        return $this->tblStock->get($id);
    }

    /**
     * for getting info of some stock (all defaults to first 5,000 records)
     *
     * @param int $id
     * @return array an array containing the grade information
     */
    public function someStockInfo(string|null $name = null, int $count = Broker::LIMIT): array
    {
        $info = $bind = [];
        $where = "";
        if ($name) {
            $where = " WHERE " . TblStock::NAME . " = :name ";
            $bind = ['name' => $name];
        }
        $sql = "SELECT " . tblStock::ID . " FROM " . tblStock::TABLE . " $where ORDER BY " . tblStock::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->stockInfo($aResult[tblStock::ID]);
            }
            return $info;
        }
    }

    /**
     * for creating a news
     *
     * @param string $title title of the news
     * @param string $body body of the news
     * @param string $source source of the news
     *
     * @return int id of the newly created news
     */
    public function createNews(string $title, $body, $source): int
    {
        try {
            $cols = [
                TblNews::TITLE => [$title, 'isValue'],
                TblNews::BODY => [$body, 'isValue'],
                TblNews::SOURCE => [$source, 'isValue']
                ];
            $id = $this->tblNews->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating news: " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for changing or editing news
     *
     * @param int $id the stock id
     * @param string $title title of the news
     * @param string $body body of the news
     * @param string $source source of the news
     * @return void
     */
    public function changeNews(int $id, string|null $title = null, string|null $body = null, string|null $source = null)
    {
        if (!$title && !$body && !$source) {
            throw new BrokerExpection("title, body or source must be provided");
        }
        if ($title) {
            $cols[TblNews::TITLE] = [$title, 'isValue'];
        }
        if ($body) {
            $cols[TblNews::BODY] = [$body, 'isValue'];
        }
        if ($source) {
            $cols[TblNews::SOURCE] = [$source, 'isValue'];
        }
        $this->tblNews->updateById($cols, $id);
    }

    /**
     * for deleting exiting news that has no dependence
     *
     * @param int $id the news id
     */
    public function deleteNews(int $id)
    {
        try {
            $this->tblNews->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this news has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about news
     *
     * @param int $id the news id
     * @param bool $addOtherInfo if true other information is added
     * @return array an array containing the news information
     */
    public function newsInfo(int $id): array
    {
        return $this->tblNews->get($id);
    }

    /**
     * for getting info of some news (all defaults to first 5,000 records)
     *
     * @param string $title title of the news
     * @param string $body body of the news
     * @param string $source source of the news
     * @return array an array containing the news information
     */
    public function someNewsInfo(string|null $title = null, string|null $body = null, string|null $source = null, int $count = Broker::LIMIT): array
    {
        $info = $bind = [];
        $where = "";
        if ($title) {
            $where = " WHERE " . TblNews::TITLE . " = :title ";
            $bind = ['title' => $title];
        }
        if ($body) {
            $where = " WHERE " . TblNews::BODY . " = :body ";
            $bind = ['body' => $body];
        }
        if ($title) {
            $where = " WHERE " . TblNews::TITLE . " = :title ";
            $bind = ['title' => $title];
        }
        $sql = "SELECT " . TblNews::ID . " FROM " . TblNews::TABLE . " $where ORDER BY " . TblNews::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->newsInfo($aResult[TblNews::ID]);
            }
        }
        return $info;
    }
    
    //=====================================================================================
    /**
     * for creating Corporate Action
     *
     * @param int $stockId a foreign key that establish a relationship with the stock table
     * @param DateTime $year
     * @param int $period
     * @param float $dividend
     * @param string $interim
     * @param string $bonus
     * @param string $closureDate
     * @param string $agmDate
     * @param string $paymentDate
     * @return int id of the newly created CorporateAction
     */
    public function createCorporateAction(
        int $stockId,
        DateTime $year,
        int $period,
        float $dividend,
        string $interim = "no",
        string $bonus = "",
        string $closureDate = "",
        string $agmDate = "",
        string $paymentDate = ""
    ): int {
        try {
            $cols = [
                tblCorporateAction::STOCK_ID => [$stockId, 'isValue'], tblCorporateAction::YEAR => [$year, 'isValue'],
                tblCorporateAction::PERIOD => [$period, 'isValue'], tblCorporateAction::DIVIDEND => [$dividend, 'isValue'],
                tblCorporateAction::INTERIM => [$interim, 'isValue']
            ];
            if($bonus) {
                $cols[tblCorporateAction::BONUS] = [$bonus, 'isValue'];
            }
            if($closureDate) {
                $cols[tblCorporateAction::CLOSURE_DATE] = [$closureDate, 'isValue'];
            }
            if($agmDate) {
                $cols[tblCorporateAction::AGM_DATE] = [$agmDate, 'isValue'];
            }
            if($paymentDate) {
                $cols[tblCorporateAction::PAYMENT_DATE] = [$paymentDate, 'isValue'];
            }
            $id = $this->tblCorporateAction->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating Corporate Action : " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for Editing Corporate Action
     * @param int $id
     * @param int|null $stockId a foreign key that establish a relationship with the stock table
     * @param DateTime|null $year
     * @param int|null $period
     * @param float|null $dividend
     * @param string|null $interim
     * @param string|null $bonus
     * @param string|null $closureDate
     * @param string|null $agmDate
     * @param string|null $paymentDate
     *
     */
    public function changeCorporateAction(
        int $id,
        int|null $stockId = null,
        DateTime|null $year = null,
        int|null $period = null,
        float|null $dividend = null,
        string|null $interim = null,
        string|null $bonus = null,
        string|null $closureDate = null,
        string|null $agmDate = null,
        string|null $paymentDate = null,
    ) {
        if (!$stockId && !$year && !$period && !$dividend && !$interim && !$bonus && !$closureDate && !$agmDate && !$paymentDate) {
            throw new BrokerExpection("either stock_id, year, period, dividend, interim, bonus, closure_date, agm_date or payment_date must be provided");
        }
        if ($stockId) {
            $cols[tblCorporateAction::STOCK_ID] = [$stockId, 'isValue'];
        }
        if ($year) {
            $cols[tblCorporateAction::YEAR] = [$year, 'isValue'];
        }
        if ($period) {
            $cols[tblCorporateAction::PERIOD] = [$period, 'isValue'];
        }
        if ($dividend) {
            $cols[tblCorporateAction::DIVIDEND] = [$dividend, 'isValue'];
        }
        if ($interim) {
            $cols[tblCorporateAction::INTERIM] = [$interim, 'isValue'];
        }
        if ($bonus) {
            $cols[tblCorporateAction::BONUS] = [$bonus, 'isValue'];
        }
        if ($closureDate) {
            $cols[tblCorporateAction::CLOSURE_DATE] = [$closureDate, 'isValue'];
        }
        if ($agmDate) {
            $cols[tblCorporateAction::AGM_DATE] = [$agmDate, 'isValue'];
        }
        if ($paymentDate) {
            $cols[tblCorporateAction::PAYMENT_DATE] = [$paymentDate, 'isValue'];
        }
        $this->tblCorporateAction->updateById($cols, $id);
    }

    /**
     * for deleting existing Corporate Action
     *
     * @param int $id the Corporate Action
     */
    public function deleteCorporateAction(int $id)
    {
        try {
            $this->tblCorporateAction->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this Corporate Action has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a Corporate Action
     *
     * @param int $id the corporate action id
     * @return array an array containing the corporate action information
     */
    public function corporateActionInfo(int $id): array
    {
        return $this->tblCorporateAction->get($id);
    }

    /**
     * for getting info of some corporate action (all defaults to first 5,000 records)
     * @param int|null $stockId a foreign key that establish a relationship with the stock table
     * @param DateTime|null $year
     * @param int|null $period
     * @param float|null $dividend
     * @param string|null $interim
     * @param string|null $bonus
     * @param string|null $closureDate
     * @param string|null $agmDate
     * @param string|null $paymentDate
     *
     */
    public function someCorporateActionInfo(
        int|null $stockId = null,
        DateTime|null $year = null,
        int|null $period = null,
        float|null $dividend = null,
        string|null $interim = null,
        string|null $bonus = null,
        string|null $closureDate = null,
        string|null $agmDate = null,
        string|null $paymentDate = null,
    ): array {
        $info = $bind = [];
        $where = "";
        if ($stockId) {
            $where .= " WHERE " . tblCorporateAction::STOCK_ID . " = :stockId";
            $bind['stockId'] = $stockId;
        }
        if ($year) {
            $where .= $where ?  " AND " . tblCorporateAction::YEAR . " = :year " : " WHERE " . tblCorporateAction::YEAR . " = :year ";
            $bind['year'] = $year->format('Y');
        }
        if ($period) {
            $where .= $where ?  " AND " . tblCorporateAction::PERIOD . " = :period " : " WHERE " . tblCorporateAction::PERIOD . " = :period ";
            $bind['period'] = $period;
        }
        if ($dividend) {
            $where .= $where ?  " AND " . tblCorporateAction::DIVIDEND . " = :dividend " : " WHERE " . tblCorporateAction::DIVIDEND . " = :dividend ";
            $bind['dividend'] = $dividend;
        }
        if ($interim) {
            $where .= $where ?  " AND " . tblCorporateAction::INTERIM . " = :interim " : " WHERE " . tblCorporateAction::INTERIM . " = :interim ";
            $bind['interim'] = $interim;
        }
        if ($bonus) {
            $where .= $where ?  " AND " . tblCorporateAction::BONUS . " = :bonus " : " WHERE " . tblCorporateAction::BONUS . " = :bonus ";
            $bind['bonus'] = $bonus;
        }
        if ($closureDate) {
            $where .= $where ?  " AND " . tblCorporateAction::CLOSURE_DATE . " = :closureDate " : " WHERE " . tblCorporateAction::CLOSURE_DATE . " = :closureDate ";
            $bind['closureDate'] = $closureDate;
        }
        if ($agmDate) {
            $where .= $where ?  " AND " . tblCorporateAction::AGM_DATE . " = :agmDate " : " WHERE " . tblCorporateAction::AGM_DATE . " = :agmDate ";
            $bind['agmDate'] = $agmDate;
        }
        if ($paymentDate) {
            $where .= $where ?  " AND " . tblCorporateAction::PAYMENT_DATE . " = :paymentDate " : " WHERE " . tblCorporateAction::PAYMENT_DATE . " = :paymentDate ";
            $bind['paymentDate'] = $paymentDate;
        }
        $sql = "SELECT " . tblCorporateAction::ID . " FROM " . tblCorporateAction::TABLE . " $where ORDER BY " . tblCorporateAction::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->corporateActionInfo($aResult[tblCorporateAction::ID]);
            }
        }
        return $info;
    }
    
    /**
     * for creating NGX Daily market summary
     *
     * @param string $file a csv file containing NGX daily pricelist
     * @param string $date the date of the NGX market summary
     * @param float $asi the value of ASI for the summary
     * @param int $deals the no of deals for summary
     * @param float $marketVolume the market volume for the day
     * @param float $marketValue the market value for the day
     * @param float $market_cap the market capitalisation for the day
     * @return int id of the newly created NGX Daily Price
     */
    public function createNseDailyPrice(
        array $file,
        string $date,
        float $asi,
        int $deals,
        float $marketVolume,
        float $marketValue,
        float $market_cap
    ): int {

        try {
            $filename = $this->uploadDocument($file, Broker::DAILY_NSE_PREFIX, ["csv"]);
            $cols = [
                tblNseDailyPrice::FILE => [$filename, 'isValue'], tblNseDailyPrice::DATE => [new DateTime($date), 'isValue'],
                tblNseDailyPrice::ASI => [$asi, 'isValue'], tblNseDailyPrice::DEALS => [$deals, 'isValue'],
                tblNseDailyPrice::MARKET_VOLUME => [$marketVolume, 'isValue'], tblNseDailyPrice::MARKET_VALUE => [$marketValue, 'isValue'],
                tblNseDailyPrice::MARKET_CAP => [$market_cap, 'isValue']
            ];
            $id = $this->tblNseDailyPrice->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating NGX Daily Price : " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for Editing Nse Daily Price
     * @param int $id
     * @param string|null $file
     * @param string|null $date
     *
     */
    public function changeNseDailyPrice(
        int $id,
        string|null $file = null,
        string|null $date = null,
        float|null $asi = null,
        int|null $deals = null,
        float|null $market_volume = null,
        float|null $market_value = null,
        float|null $market_cap = null,
    ) {
        if (!$file && !$date && !$asi && !$deals && !$market_volume && !$market_value && !$market_cap) {
            throw new BrokerExpection("either file, date, asi, deals, market_volume, market_value or market_cap must be provided");
        }
        if ($file) {
            $cols[tblNseDailyPrice::FILE] = [$file, 'isValue'];
        }
        if ($date) {
            $cols[tblNseDailyPrice::DATE] = [new DateTime($date), 'isValue'];
        }
        if ($asi) {
            $cols[tblNseDailyPrice::ASI] = [$asi, 'isValue'];
        }
        if ($deals) {
            $cols[tblNseDailyPrice::DEALS] = [$deals, 'isValue'];
        }
        if ($market_volume) {
            $cols[tblNseDailyPrice::MARKET_VOLUME] = [$market_volume, 'isValue'];
        }
        if ($market_value) {
            $cols[tblNseDailyPrice::MARKET_VALUE] = [$market_value, 'isValue'];
        }
        if ($market_cap) {
            $cols[tblNseDailyPrice::MARKET_CAP] = [$market_cap, 'isValue'];
        }

        $this->tblNseDailyPrice->updateById($cols, $id);
    }

    /**
     * for deleting existing Nse Daily Price
     *
     * @param int $id of the Nse Daily Price
     */
    public function deleteNseDailyPrice(int $id)
    {
        try {
            $nseInfo = $this->nseDailyPriceInfo($id);
            $this->deleteUploadedDocument($nseInfo[TblNseDailyPrice::FILE]);
            $this->tblNseDailyPrice->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this NGX Daily Price has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a Nse Daily Price
     *
     * @param int $id the Nse Daily Price id
     * @return array an array containing the Nse Daily Price information
     */
    public function nseDailyPriceInfo(int $id): array
    {
        return $this->tblNseDailyPrice->get($id);
    }

    /**
     * for getting info of some Nse Daily Price (all defaults to first 5,000 records)
     * @param string|null $file
     * @param string|null $date
     *
     */
    public function someNseDailyPriceInfo(
        string|null $file = null,
        string|null $date = null,
        float|null $asi = null,
        int|null $deals = null,
        float|null $market_volume = null,
        float|null $market_value = null,
        float|null $market_cap = null,
    ): array {
        $info = $bind = [];
        $where = "";
        if ($file) {
            $where .= " WHERE " . tblNseDailyPrice::FILE . " = :file";
            $bind['file'] = $file;
        }
        if ($date) {
            $where .= $where ?  " AND " . tblNseDailyPrice::DATE . " = :date " : " WHERE " . tblNseDailyPrice::DATE . " = :date ";
            $bind['date'] = $date;
        }
        if ($asi) {
            $where .= $where ?  " AND " . tblNseDailyPrice::ASI . " = :asi " : " WHERE " . tblNseDailyPrice::ASI . " = :asi ";
            $bind['asi'] = $asi;
        }
        if ($deals) {
            $where .= $where ?  " AND " . tblNseDailyPrice::DEALS . " = :deals " : " WHERE " . tblNseDailyPrice::DEALS . " = :deals ";
            $bind['deals'] = $deals;
        }
        if ($market_volume) {
            $where .= $where ?  " AND " . tblNseDailyPrice::MARKET_VOLUME . " = :market_volume " : " WHERE " . tblNseDailyPrice::MARKET_VOLUME . " = :market_volume ";
            $bind['market_volume'] = $market_volume;
        }
        if ($market_value) {
            $where .= $where ?  " AND " . tblNseDailyPrice::MARKET_VALUE . " = :market_value " : " WHERE " . tblNseDailyPrice::MARKET_VALUE . " = :market_value ";
            $bind['market_value'] = $market_value;
        }
        if ($market_cap) {
            $where .= $where ?  " AND " . tblNseDailyPrice::MARKET_CAP . " = :market_cap " : " WHERE " . tblNseDailyPrice::MARKET_CAP . " = :market_cap ";
            $bind['market_cap'] = $market_cap;
        }
        $sql = "SELECT " . tblNseDailyPrice::ID . " FROM " . tblNseDailyPrice::TABLE . " $where ORDER BY " . tblNseDailyPrice::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->nseDailyPriceInfo($aResult[tblNseDailyPrice::ID]);
            }
        }
        return $info;
    }
    
    /**
     * for creating Market Review
     *
     * @param string $type
     * @param string $file
     * @param DateTime $date
     * @param DateTime $endDate
     * @param string $subType
     */
    public function createMarketReview(
        string $type,
        array $file,
        DateTime $date,
        DateTime|null $endDate = null,
        string|null $subType = null
    ): int {
        try {
            $filename = $this->uploadDocument($file, $type.Broker::MARKET_REVIEW_PREFIX, ["pdf"]);
            $cols = [
                TblMarketReview::TYPE => [$type, 'isValue'], TblMarketReview::FILE => [$filename, 'isValue'], TblMarketReview::DATE => [$date, 'isValue']
            ];
            if($endDate) {
                $cols[TblMarketReview::END_DATE] =  [$endDate, 'isValue'];
            }
            if($subType) {
                $cols[TblMarketReview::SUB_TYPE] =  [$subType, 'isValue'];
            }
            $id = $this->tblMarketReview->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating Market Review : " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for Editing Market Review
     * @param int $id
     * @param string|null $type
     * @param string|null $file
     * @param string|null $date
     * @param string|null $end_date
     * @param string|null $sub_type
     *
     */
    public function changeMarketReview(
        int $id,
        string|null $type = null,
        string|null $file = null,
        string|null $date = null,
        string|null $end_date = null,
        string|null $sub_type = null
    ) {
        if (!$type && !$file && !$date && !$sub_type) {
            throw new BrokerExpection("either type, file, date or sub_type must be provided");
        }
        if ($type) {
            $cols[TblMarketReview::TYPE] = [$type, 'isValue'];
        }
        if ($file) {
            $cols[TblMarketReview::FILE] = [$file, 'isValue'];
        }
        if ($date) {
            $cols[TblMarketReview::DATE] = [$date, 'isValue'];
        }
        if ($end_date) {
            $cols[TblMarketReview::END_DATE] = [$end_date, 'isValue'];
        }
        if ($sub_type) {
            $cols[TblMarketReview::SUB_TYPE] = [$sub_type, 'isValue'];
        }

        $this->tblMarketReview->updateById($cols, $id);
    }

    /**
     * for deleting existing Market Review
     *
     * @param int $id of the Market Review
     */
    public function deleteMarketReview(int $id)
    {
        try {
            $marketReviewInfo = $this->marketReviewInfo($id);
            $this->deleteUploadedDocument($marketReviewInfo[TblMarketReview::FILE]);
            $this->tblMarketReview->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this Market Review has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a Market Review
     *
     * @param int $id the Market Review id
     * @return array an array containing the Market Review information
     */
    public function marketReviewInfo(int $id): array
    {
        return $this->tblMarketReview->get($id);
    }

    /**
     * for getting info of some Market Review (all defaults to first 5,000 records)
     * @param string|null $type
     * @param string|null $file
     * @param DateTime|null $date
     * @param DateTime|null $endDate
     * @param string|null $subType
     *
     */
    public function someMarketReviewInfo(
        string|null $type = null,
        string|null $file = null,
        DateTime|null $date = null,
        DateTime|null $endDate = null,
        string|null $subType = null
    ): array {
        $info = $bind = [];
        $where = "";
        if ($type) {
            $where .= " WHERE " . TblMarketReview::TYPE . " = :type";
            $bind['type'] = $type;
        }
        if ($file) {
            $where .= $where ?  " AND " . TblMarketReview::FILE . " = :file " : " WHERE " . TblMarketReview::FILE . " = :file ";
            $bind['file'] = $file;
        }
        if ($date) {
            $where .= $where ?  " AND " . TblMarketReview::DATE . " = :date " : " WHERE " . TblMarketReview::DATE . " = :date ";
            $bind['date'] = $date->format('Y-m-d');
        }
        if ($endDate) {
            $where .= $where ?  " AND " . TblMarketReview::END_DATE . " = :end_date " : " WHERE " . TblMarketReview::END_DATE . " = :end_date ";
            $bind['end_date'] = $endDate->format('Y-m-d');
        }
        if ($subType) {
            $where .= $where ?  " AND " . TblMarketReview::SUB_TYPE . " = :sub_type " : " WHERE " . TblMarketReview::SUB_TYPE . " = :sub_type ";
            $bind['sub_type'] = $subType;
        }

        $sql = "SELECT " . TblMarketReview::ID . " FROM " . TblMarketReview::TABLE . " $where ORDER BY " . TblMarketReview::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->marketReviewInfo($aResult[TblMarketReview::ID]);
            }
        }
        return $info;
    }
    
    /**
     * for creating Financial Report
     *
     * @param int $stockId a foreign key column
     * @param int $period
     * @param DateTime $year
     * @param array $file
     */
    public function createFinancialReport(int $stockId, int $period, DateTime $year, array $file): int
    {
        try {
            $filename = $this->uploadDocument($file, Broker::FINANCIAL_REPORT_PREFIX, ["pdf"]);
            $cols = [
                tblFinancialReport::STOCK_ID => [$stockId, 'isValue'], tblFinancialReport::PERIOD => [$period, 'isValue'], tblFinancialReport::YEAR => [$year, 'isValue'],
                tblFinancialReport::FILE => [$filename, 'isValue']
            ];
            $id = $this->tblFinancialReport->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating Financial Report : " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for Editing Financial Report
     * @param int $id
     * @param int|null $stockId
     * @param int|null $period
     * @param DateTime|null $year
     * @param string|null $file
     *
     */
    public function changeFinancialReport(
        int $id,
        int|null $stockId = null,
        int|null $period = null,
        DateTime|null $year = null,
        string|null $file
    ) {
        if (!$stockId && !$period && !$year && !$file) {
            throw new BrokerExpection("either stockId, period, year or file must be provided");
        }
        if ($stockId) {
            $cols[tblFinancialReport::STOCK_ID] = [$stockId, 'isValue'];
        }
        if ($period) {
            $cols[tblFinancialReport::PERIOD] = [$period, 'isValue'];
        }
        if ($year) {
            $cols[tblFinancialReport::YEAR] = [$year, 'isValue'];
        }
        if ($file) {
            $cols[tblFinancialReport::FILE] = [$file, 'isValue'];
        }

        $this->tblFinancialReport->updateById($cols, $id);
    }

    /**
     * for deleting existing Financial Report
     *
     * @param int $id of the Financiel Report
     */
    public function deleteFinancialReport(int $id)
    {
        try {
            $docInfo = $this->financialReportInfo($id);
            $this->deleteUploadedDocument($docInfo[TblFinancialReport::FILE]);
            $this->tblFinancialReport->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this Fianancial Report has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a Financial Report
     *
     * @param int $id the Financial Report id
     * @return array an array containing the Financial Report information
     */
    public function financialReportInfo(int $id): array
    {
        return $this->tblFinancialReport->get($id);
    }

    /**
     * for getting info of some Financial Report (all defaults to first 5,000 records)
     * @param int|null $stockId
     * @param int|null $period
     * @param DateTime|null $year
     * @param string|null $file
     */
    public function someFinancialReportInfo(
        int|null $stockId = null,
        int|null $period = null,
        DateTime|null $year = null,
        string|null $file = null
    ): array {
        $info = $bind = [];
        $where = "";
        if ($stockId) {
            $where .= " WHERE " . tblFinancialReport::STOCK_ID . " = :stockId";
            $bind['stockId'] = $stockId;
        }
        if ($period) {
            $where .= $where ?  " AND " . tblFinancialReport::PERIOD . " = :period " : " WHERE " . tblFinancialReport::PERIOD . " = :period ";
            $bind['period'] = $period;
        }
        if ($year) {
            $where .= $where ?  " AND " . tblFinancialReport::YEAR . " = :year " : " WHERE " . tblFinancialReport::YEAR . " = :year ";
            $bind['year'] = $year;
        }
        if ($file) {
            $where .= $where ?  " AND " . tblFinancialReport::FILE . " = :file " : " WHERE " . tblFinancialReport::FILE . " = :file ";
            $bind['file'] = $file;
        }


        $sql = "SELECT " . tblFinancialReport::ID . " FROM " . tblFinancialReport::TABLE . " $where ORDER BY " . tblFinancialReport::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->financialReportInfo($aResult[tblFinancialReport::ID]);
            }
        }
        return $info;
    }
    
    /**
     * for creating Document
     *
     * @param string $name
     * @param string $type
     * @param int $priority
     * @param array $file
     * @return int id of the newly created Document
     */
    public function createDocument(string $name, string $type, int $priority, $file): int
    {
        try {
            $filename = $this->uploadDocument($file, Broker::DOWNLOADS_PREFIX, ["pdf"]);
            $cols = [
                tblDocument::NAME => [$name, 'isValue'], tblDocument::TYPE => [$type, 'isValue'],
                tblDocument::PRIORITY => [$priority, 'isValue'], tblDocument::FILE => [$filename, 'isValue']
            ];
            $id = $this->tblDocument->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating Document : " . $e->getMessage());
        }
          
        return $id;
    }

    /**
     * for Editing Document
     * @param int $id
     * @param string|null $name
     * @param string|null $type
     * @param int|null $priority
     *
     */
    public function changeDocument(
        int $id,
        string|null $name = null,
        string|null $type = null,
        int|null $priority = null
    ) {
        if (!$name && !$type) {
            throw new BrokerExpection("either name or type must be provided");
        }
        if ($name) {
            $cols[tblDocument::NAME] = [$name, 'isValue'];
        }
        if ($type) {
            $cols[tblDocument::TYPE] = [$type, 'isValue'];
        }
        if ($priority) {
            $cols[tblDocument::PRIORITY] = [$priority, 'isValue'];
        }

        $this->tblDocument->updateById($cols, $id);
    }

    /**
     * for deleting existing Document
     *
     * @param int $id of the Document
     */
    public function deleteDocument(int $id)
    {
        try {
            $docInfo = $this->documentInfo($id);
            $this->deleteUploadedDocument($docInfo[TblDocument::FILE]);
            $this->tblDocument->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this document has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a Document
     *
     * @param int $id the Document id
     * @return array an array containing the Document information
     */
    public function documentInfo(int $id): array
    {
        return $this->tblDocument->get($id);
    }

    /**
     * for getting info of some Document (all defaults to first 5,000 records)
     * @param string|null $name
     * @param string|null $type
     * @param int|null $priority
     *
     */
    public function someDocumentInfo(string|null $name = null, string|null $type = null, int|null $priority = null): array
    {
        $info = $bind = [];
        $where = "";
        if ($name) {
            $where .= " WHERE " . tblDocument::NAME . " = :name";
            $bind['name'] = $name;
        }
        if ($type) {
            $where .= $where ?  " AND " . tblDocument::TYPE . " = :type " : " WHERE " . tblDocument::TYPE . " = :type ";
            $bind['type'] = $type;
        }
        if ($priority) {
            $where .= $where ?  " AND " . tblDocument::PRIORITY . " = :priority " : " WHERE " . tblDocument::PRIORITY . " = :priority ";
            $bind['priority'] = $priority;
        }
        $sql = "SELECT " . tblDocument::ID . " FROM " . tblDocument::TABLE . " $where ORDER BY " . tblDocument::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->documentInfo($aResult[tblDocument::ID]);
            }
        }
        return $info;
    }
    // ==========================================================================
    /**
     * for creating Daily News
     *
     * @param string|null $title
     * @param string|null $body
     * @param string|null $source
     */
    public function createDailyNews(string $title, string $body, string $source): int
    {
        try {
            $cols = [
                tblDailyNews::TITLE => [$title, 'isValue'], tblDailyNews::BODY => [$body, 'isValue'],
                tblDailyNews::SOURCE => [$source, 'isValue']
            ];
            $id = $this->tblDailyNews->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating Daily News : " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for Editing Daily News
     * @param int $id
     * @param string|null $title
     * @param string|null $body
     * @param string|null $source
     *
     */
    public function changeDailyNews(
        int $id,
        string|null $title = null,
        string|null $body = null,
        string|null $source = null,
    ) {
        if (!$title && !$body && !$source) {
            throw new BrokerExpection("either title, body or source must be provided");
        }
        if ($title) {
            $cols[tblDailyNews::TITLE] = [$title, 'isValue'];
        }
        if ($body) {
            $cols[tblDailyNews::BODY] = [$body, 'isValue'];
        }
        if ($source) {
            $cols[tblDailyNews::SOURCE] = [$source, 'isValue'];
        }
        $this->tblDailyNews->updateById($cols, $id);
    }

    /**
     * for deleting existing Daily News
     *
     * @param int $id of the Daily News
     */
    public function deleteDailyNews(int $id)
    {
        try {
            $this->tblDailyNews->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this Daily News has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a Daily News
     *
     * @param int $id the Daily News id
     * @return array an array containing the Daily News information
     */
    public function dailyNewsInfo(int $id): array
    {
        return $this->tblDailyNews->get($id);
    }

    /**
     * for getting info of some Daily News (all defaults to first 5,000 records)
     * @param string|null $title
     * @param string|null $body
     * @param string|null $source
     *
     */
    public function someDailyNewsInfo(
        string|null $title = null,
        string|null $body = null,
        string|null $source = null
    ): array {
        $info = $bind = [];
        $where = "";
        if ($title) {
            $where .= " WHERE " . tblDailyNews::TITLE . " = :title";
            $bind['title'] = $title;
        }
        if ($body) {
            $where .= $where ?  " AND " . tblDailyNews::BODY . " = :body " : " WHERE " . tblDailyNews::BODY . " = :body ";
            $bind['body'] = $body;
        }
        if ($source) {
            $where .= $where ?  " AND " . tblDailyNews::SOURCE . " = :source " : " WHERE " . tblDailyNews::SOURCE . " = :source ";
            $bind['source'] = $source;
        }

        $sql = "SELECT " . tblDailyNews::ID . " FROM " . tblDailyNews::TABLE . " $where ORDER BY " . tblDailyNews::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->dailyNewsInfo($aResult[tblDailyNews::ID]);
            }
        }
        return $info;
    }

    /**
     * for creating newsletter subscription
     *
     * @param string $email subscriber email
     * @param string|null $name subscriber name
     * @return int the id of the new subscription
     */
    public function createNewsletter(string $email, string|null $name = null):int
    {
        try {
            $cols[TblNewsletter::EMAIL] = [$email, 'isValue'];
            if($name) {
                $cols[TblNewsletter::NAME] =  [$name, 'isValue'];
            }
            $id = $this->tblNewsletter->insert($cols);
        } catch (Exception $e) {
            throw new BrokerExpection("Error creating Newsletter : " . $e->getMessage());
        }
        return $id;
    }

    /**
     * for change newsletter subscription
     *
     * @param int $id the id of the subscriber
     * @param string|null $email subscriber email
     * @param string|null $name subscriber name
     * @return void
     */
    public function changeNewsletter(int $id, string|null $email=null, string|null $name=null)
    {
        if (!$email && !$name) {
            throw new BrokerExpection("either email or name must be provided");
        }
        if ($email) {
            $cols[TblMarketReview::TYPE] = [$email, 'isValue'];
        }
        if ($name) {
            $cols[TblMarketReview::FILE] = [$name, 'isValue'];
        }

        $this->tblNewsletter->updateById($cols, $id);
    }

    /**
     * for deleting existing newsletter subscription
     *
     * @param int $id of the newsletter subscription
     */
    public function deleteNewsletter(int $id)
    {
        try {
            $this->tblNewsletter->deleteById($id);
        } catch (Exception $e) {
            throw new BrokerExpection("this Newsletter has dependence: " . $e->getMessage());
        }
    }

    /**
     * for getting info about a newsletter subscription
     *
     * @param int $id the newsletter subscription id
     * @return array an array containing the newsletter subscription information
     */
    public function newsletterInfo(int $id): array
    {
        return $this->tblNewsletter->get($id);
    }

    /**
     * for getting info of some  newsletter subscription
     *
     * @param string|null $email subscriber email
     * @param string|null $name subscriber name
     * @return array
     */
    public function someNewsletter(string|null $email=null, string|null $name=null):array
    {
        $info = $bind = [];
        $where = "";
        if ($email) {
            $where .= " WHERE " . TblNewsletter::EMAIL . " = :email";
            $bind['email'] = $email;
        }
        if ($name) {
            $where .= $where ?  " AND " . TblNewsletter::NAME . " = :name " : " WHERE " . TblNewsletter::NAME . " = :name ";
            $bind['name'] = $name;
        }

        $sql = "SELECT " . TblNewsletter::ID . " FROM " . TblNewsletter::TABLE . " $where ORDER BY " . TblNewsletter::ID . " DESC LIMIT " . Broker::LIMIT;
        $result = $bind ? $this->query->executeSql($sql, $bind) : $this->query->executeSql($sql);
        if ($result['rows']) {
            foreach ($result['rows'] as $aResult) {
                $info[] = $this->newsletterInfo($aResult[TblNewsletter::ID]);
            }
        }
        return $info;
    }

    /**
     * getting name of periods
     *
     * @return array a collection of periods
     */
    public function periodNames(): array
    {
        $names = ["1st Quarter", "2nd Quarter", "3rd Quarter", "4th Quarter", "Full Year"];
        $periods = array_combine(AbstractTable::PERIOD_VALUES, $names);
        return $periods;
    }

}
