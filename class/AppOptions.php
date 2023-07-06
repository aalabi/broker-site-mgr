<?php

/**
 * AppOptions
 *
 * A class managing app options
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => March 2023
 * @link        alabiansolutions.com
*/

class AppOptionsExpection extends Exception
{
}

class AppOptions
{
    /** @var DbConnect an instance of DbConnect  */
    protected DbConnect $dbConnect;

    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var TblAppOptions an instance of TblAppOptions  */
    protected TblAppOptions $tblAppOptions;

    /** @var array collection of app status */
    const APP_STATUS = ['active', 'inactive', 'active'=>'active', 'inactive'=>'inactive'];

    /**
     * instantiation of Salary
     *
     */
    public function __construct()
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query();
        $this->tblAppOptions = new TblAppOptions();
    }

    /**
     * for retriving organisation name
     *
     * @return string|null the organisation name
    */
    public function organisationName():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::NAME, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing an organisation name
     *
     * @param string $organisationName the name of the organisation
     * @return void
    */
    public function changeOrganisationName(string $organisationName):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::NAME, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationName, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving organisation address
     *
     * @return string|null the organisation address
    */
    public function organisationAddress():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::ADDRESS, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing an organisation address
     *
     * @param string $organisationAddress the address of the organisation
     * @return void
    */
    public function changeOrganisationAddress(string $organisationAddress):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::ADDRESS, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationAddress, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving organisation logo
     *
     * @return string|null the organisation logo
    */
    public function organisationLogo():string|null
    {
        $logo = "default-logo.png";
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::LOGO, 'isValue']];
        if($foundLogo = $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE]) {
            $logo = $foundLogo;
        }
        return $logo;
    }

    /**
     * for changing an organisation logo
     *
     * @param string $organisationLogo the logo of the organisation
     * @return void
    */
    public function changeOrganisationLogo(string $organisationLogo):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::LOGO, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationLogo, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving organisation email
     *
     * @return string|null the organisation email
    */
    public function organisationEmail():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::EMAIL, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing an organisation email
     *
     * @param string $organisationEmail the email of the organisation
     * @return void
    */
    public function changeOrganisationEmail(string $organisationEmail):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::EMAIL, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationEmail, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving organisation phone
     *
     * @return string|null the organisation phone
    */
    public function organisationPhone():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::PHONE, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing an organisation phone
     *
     * @param string $organisationPhone the phone of the organisation
     * @return void
    */
    public function changeOrganisationPhone(string $organisationPhone):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::PHONE, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationPhone, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving organisation complain phone
     *
     * @return string|null the organisation complain phone
    */
    public function organisationComplainPhone():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::COMPLAIN_PHONE, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing an organisation complain phone
     *
     * @param string $organisationPhone the complain phone of the organisation
     * @return void
    */
    public function changeOrganisationComplainPhone(string $organisationComplainPhone):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::COMPLAIN_PHONE, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationComplainPhone, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving organisation complain email
     *
     * @return string|null the organisation complain email
    */
    public function organisationComplainEmail():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::CONPLAIN_EMAIL, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing an organisation complain phone
     *
     * @param string $organisationPhone the complain phone of the organisation
     * @return void
    */
    public function changeOrganisationComplainEmail(string $organisationComplainEmail):void
    {
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::CONPLAIN_EMAIL, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$organisationComplainEmail, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for retriving the app status
     *
     * @return string|null the app status
    */
    public function appStatus():string|null
    {
        $status = null;
        $settings = (new Settings(SETTING_FILE, true))->getDetails();
        $baseEndPoint= $settings->walletEndpoint;
        $curl = curl_init();
        $curlOptions = [
            CURLOPT_URL => "{$baseEndPoint}apps/id/{$this->appId()}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$this->appPassKey()}", "Content-Type: application/json"],
        ];
        curl_setopt_array($curl, $curlOptions);
        if($response = curl_exec($curl)) {
            $response = json_decode($response, true);
            if($response && $response['success']) {
                $status = $response['data']['status'];
            }
        }
        curl_close($curl);
        return $status;
    }

    /**
     * for retriving the app id
     *
     * @return string|null the app id
    */
    public function appId():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::APP_ID, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for retriving the app passkey
     *
     * @return string|null the app passkey
    */
    public function appPassKey():string|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::APP_PASSKEY, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for changing the app passkey
     *
     * @param string $appPassKey the app
     * @param int $bytes no of bytes to generated by openssl
     * @return void
    */
    public function changeAppPassKey(string $appPassKey = "", int $bytes = 32):void
    {
        if (!$appPassKey) {
            $appPassKey = base64_encode(bin2hex(openssl_random_pseudo_bytes($bytes).time()));
        }
        $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::APP_PASSKEY, 'isValue']];
        $column = [TblAppOptions::OPTION_VALUE => [$appPassKey, 'isValue']];
        $this->tblAppOptions->update($column, $where);
    }

    /**
     * for registering app with master app
     *
     * @return bool true if registeration is successful
    */
    public function registerApp():bool
    {
        $registered = false;
        $settings = (new Settings(SETTING_FILE, true))->getDetails();
        $endpointBaseUrl = $settings->walletEndpoint;
        $curl = curl_init();
        $data = json_encode([
            "name" => $this->organisationName(),
            "address" => $this->organisationAddress(),
            "emails" => [$this->organisationEmail()],
            "phones"=> [$this->organisationPhone()]
        ]);
        $curlOptions = [
            CURLOPT_URL => "{$endpointBaseUrl}apps",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ];
        curl_setopt_array($curl, $curlOptions);
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if($response['success']) {
            $registered = true;
            
            $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::APP_ID, 'isValue']];
            $column = [TblAppOptions::OPTION_VALUE => [$response['data']['appId'], 'isValue']];
            $this->tblAppOptions->update($column, $where);

            $this->changeAppPassKey($response['data']['passkey']);

            $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::WORKER_COST, 'isValue']];
            $column = [TblAppOptions::OPTION_VALUE => [$response['data']['workerCost'], 'isValue']];
            $this->tblAppOptions->update($column, $where);

            $where = [TblAppOptions::OPTION_NAME => ['=', TblAppOptions::TRANSFER_CHRGS, 'isValue']];
            $column = [TblAppOptions::OPTION_VALUE => [$response['data']['transferChrgs'], 'isValue']];
            $this->tblAppOptions->update($column, $where);
        }
        return $registered;
    }

    /**
     * for retriving the worker cost
     *
     * @return string|null the worker cost
    */
    public function workerCost():float|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::WORKER_COST, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }

    /**
     * for retriving the transfer charges
     *
     * @return string|null the transfer charges
    */
    public function transferChrgs():float|null
    {
        $where = [TblAppOptions::OPTION_NAME=>['=', TblAppOptions::TRANSFER_CHRGS, 'isValue']];
        return $this->tblAppOptions->select([TblAppOptions::OPTION_VALUE], $where)[0][TblAppOptions::OPTION_VALUE];
    }
}
