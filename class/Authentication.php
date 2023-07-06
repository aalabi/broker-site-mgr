<?php

/**
 * Authentication
 *
 * A class managing users' authentication
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022
 * @link        alabiansolutions.com
*/

class AuthenticationExpection extends Exception
{
}

class Authentication
{
    /** @var DbConnect an instance of DbConnect  */
    protected DbConnect $dbConnect;

    /** @var Query an instance of Query  */
    protected Query $query;

    /** @var TblLogger an instance of TblLogger  */
    protected TblLogger $tblLogger;

    /** @var TblProfile an instance of TblProfile  */
    protected TblProfile $tblProfile;

    /** @var int user table id*/
    protected int $id;

    /** @var string|null user table email*/
    protected string|null $email;

    /** @var string|null user table phone*/
    protected string|null $phone;

    /** @var string|null user table username*/
    protected string|null $username;

    /** @var string field's value used in identifying a User (User.email, User.phone or User.username) */
    protected string $identity;

    /** @var string field's name used in identifying a User (User.email, User.phone or User.username) */
    public string $identityType;

    /** @var string fingerprint */
    public const FINGERPRINT = 'fingerprint';

    /** @var string loggerId */
    public const LOGGER_ID = 'loggerId';

    /** @var string sessionId */
    public const SESSION_ID = 'sessionId';

    /**
     * instantiation of Authentication
     *
     * @param string|int identity the value of either id, email, phone or username
     * @param string identityType the type of either id, email, phone or username
     */
    public function __construct(string|int $identity, string $identityType = TblLogger::EMAIL)
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->dbConnect = $DbConnect;
        $this->query = new Query();
        
        $errors = [];
        $this->tblLogger = new TblLogger();
        $this->tblProfile = new TblProfile();
        if (!in_array($identityType, $this->getAllIdentityType())) {
            $errors = "invalid identityType parameter";
        }
       
        if ($identityType == TblLogger::EMAIL) {
            if ($info = $this->tblLogger->select([], [TblLogger::EMAIL=>['=', $identity, 'isValue']])) {
                $id = $info[0][TblLogger::ID];
                $email = $info[0][TblLogger::EMAIL];
                $phone = $info[0][TblLogger::PHONE];
                $username = $info[0][TblLogger::USERNAME];
            } else {
                $errors[] = "unknown '".TblLogger::EMAIL."' supplied for identity parameter";
            }
        }
        if ($identityType == TblLogger::PHONE) {
            if ($info = $this->tblLogger->select([], [TblLogger::PHONE=>['=', $identity, 'isValue']])) {
                $id = $info[0][TblLogger::ID];
                $email = $info[0][TblLogger::EMAIL];
                $phone = $info[0][TblLogger::PHONE];
                $username = $info[0][TblLogger::USERNAME];
            } else {
                $errors[] = "unknown '".TblLogger::PHONE."' supplied for identity parameter";
            }
        }
        if ($identityType == TblLogger::USERNAME) {
            if ($info = $this->tblLogger->select([], [TblLogger::USERNAME=>['=', $identity, 'isValue']])) {
                $id = $info[0][TblLogger::ID];
                $email = $info[0][TblLogger::EMAIL];
                $phone = $info[0][TblLogger::PHONE];
                $username = $info[0][TblLogger::USERNAME];
            } else {
                $errors[] = "unknown '".TblLogger::USERNAME."' supplied for identity parameter";
            }
        }
        if ($identityType == TblLogger::ID) {
            if ($info = $this->tblLogger->select([], [TblLogger::ID=>['=', $identity, 'isValue']])) {
                $id = $info[0][TblLogger::ID];
                $email = $info[0][TblLogger::EMAIL];
                $phone = $info[0][TblLogger::PHONE];
                $username = $info[0][TblLogger::USERNAME];
            } else {
                $errors[] = "unknown '".TblLogger::ID."' supplied for identity parameter";
            }
        }
        if ($errors) {
            throw new AuthenticationExpection("Authentication instantiation error: ".implode(", ", $errors));
        }
        $this->setIdentityType($identityType);
        $this->setIdentity($identity);
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->username = $username;
    }

    /**
     * get user identitier which is either user's email, user's phone or user's username
     *
     * @return string either user's email, user's phone or user's username
     */
    public function getIdentity():string
    {
        return $this->identity;
    }

    /**
     * set user identitier which is either user's email, user's phone or user's username
     *
     * @param string $identity either user's email, user's phone or user's username
     * @return void
     */
    public function setIdentity(string $identity)
    {
        $where = [$this->identityType=>['=', $identity, 'isValue']];
        if (!$this->tblLogger->select([], $where)) {
            throw new AuthenticationExpection("invalid user's {$this->identityType}");
        }
        $this->identity = $identity;
    }

    /**
     * get user identity type which is either email, phone or username
     *
     * @return string either email, phone or username
     */
    public function getIdentityType():string
    {
        return $this->identityType;
    }

    /**
     * set user identity type which is either email, phone or username
     *
     * @param string $identityType either email, phone or username
     * @return void
     */
    public function setIdentityType(string $identityType)
    {
        if (!in_array($identityType, self::getAllIdentityType())) {
            throw new AuthenticationExpection("invalid identity type");
        }
        $this->identityType = $identityType;
    }

    /**
     * get a all collection of all User's identityType
     *
     * @return array
     */
    protected static function getAllIdentityType():array
    {
        return [TblLogger::EMAIL, TblLogger::PHONE, TblLogger::USERNAME, TblLogger::ID];
    }

    /**
     * for login a user
     *
     * @param string $password the user's
     * @return array an array of authentication info ['sessionId'=>$s, accessToken'=>$a, accessTokenExpiry'=>$ae, 'refreshToken'=>$r, 'refreshTokenExpiry'=>$re 'userInfo'=>$u, 'error'=>$e];
     */
    public function login(string $password):array
    {
        $sessionId = 0;
        $accessToken = "";
        $accessTokenExpiry = "";
        $refreshToken = "";
        $refreshTokenExpiry = "";
        $userInfo = $errorMsg = [];
        $userTableInfo = (new TblLogger())->get($this->id);
        if ($userTableInfo[TblLogger::STATUS] != TblLogger::STATUS_VALUES[1]) {
            $errorMsg[] = "Inactive user";
        } else {
            if (!password_verify($password, $userTableInfo[TblLogger::PASSWORD])) {
                $errorMsg[] = "Login failed";
            } else {
                $Session = new TblSession();
                $columns = [
                    TblSession::LOGGER_ID=>[$this->id, 'isValue'],
                    TblSession::ACCESS_TOKEN=>[$Session->getAccessToken($Session->setAccessToken()), 'isValue'],
                    TblSession::ACCESS_TOKEN_EXPIRY=>[$Session->getAccessTokenExpiry($Session->setAccessTokenExpiry()), 'isValue'],
                    TblSession::REFRESH_TOKEN=>[$Session->getRefreshToken($Session->setRefreshToken()), 'isValue'],
                    TblSession::REFRESH_TOKEN_EXPIRY=>[$Session->getRefreshTokenExpiry($Session->setRefreshTokenExpiry()), 'isValue'],
                ];
                $sessionId = $Session->insert($columns);
                $sessionInfo = $Session->get($sessionId);
                $accessToken = $sessionInfo[TblSession::ACCESS_TOKEN];
                $accessTokenExpiry = $sessionInfo[TblSession::ACCESS_TOKEN_EXPIRY];
                $refreshToken = $sessionInfo[TblSession::REFRESH_TOKEN];
                $refreshTokenExpiry = $sessionInfo[TblSession::REFRESH_TOKEN_EXPIRY];
                $userInfo = (new User(User::profileIdFrmLoginId($this->id)))->getInfo();
                $_SESSION[Authentication::LOGGER_ID] = $this->id;
                $_SESSION[Authentication::SESSION_ID] = $sessionId;
                $token = (new Settings(SETTING_FILE))->getDetails()->token;
                $_SESSION[Authentication::FINGERPRINT] =  hash('sha512', "{$this->id}{$sessionId}{$userTableInfo[TblLogger::PASSWORD]}{$token}");
            }
        }

        return [
            'sessionId'=>$sessionId, 'accessToken'=>$accessToken, 'refreshToken'=>$refreshToken, 'accessTokenExpiry'=>$accessTokenExpiry,
            'refreshTokenExpiry'=>$refreshTokenExpiry, 'userInfo'=>$userInfo, 'error'=>implode(", ", $errorMsg)];
    }

    /**
     * check if session id is valid for current user
     *
     * @param string $sessionId the session id
     * @param string $fingerprint the current user session's fingerprint
     * @return boolean true if valid
     */
    public function isFingerprintValid(string $sessionId, string $fingerPrint):bool
    {
        $valid = false;
        $thereIsError = false;
        if (!isset($_SESSION[Authentication::LOGGER_ID]) || !isset($_SESSION[Authentication::SESSION_ID]) || !isset($_SESSION[Authentication::FINGERPRINT])) {
            $thereIsError = true;
        }
        $sessionInfo = (new TblSession())->get($sessionId);
        if (!isset($sessionInfo[TblSession::LOGGER_ID])) {
            $thereIsError = true;
        } else {
            $userTableInfo = (new TblLogger())->get($sessionInfo[TblSession::LOGGER_ID]);
            if (isset($_SESSION[Authentication::LOGGER_ID]) &&
                ($_SESSION[Authentication::LOGGER_ID] != $this->id || $_SESSION[Authentication::LOGGER_ID] != $sessionInfo[TblSession::LOGGER_ID])) {
                $thereIsError = true;
            }
        }

        $token = (new Settings(SETTING_FILE))->getDetails()->token;
        if (!$thereIsError) {
            if ($fingerPrint == hash('sha512', "{$this->id}{$sessionId}{$userTableInfo[TblLogger::PASSWORD]}{$token}")) {
                $valid = true;
            }
        }
        
        return $valid;
    }

    /**
     * logout a user if no session id is specified the user is logout out from all active session
     *
     * @param string $sessionId the session ID
     * @param string $reDirect the url to redirect after the user is logged out
     * @return void
     */
    public function logout(string|null $sessionId = null, string $reDirect = "")
    {
        $Session = new TblSession();
        if ($sessionId) {
            $Session->delete([TblSession::ID=>['=', $sessionId,'isValue']]);
        } else {
            $Session->delete([TblSession::LOGGER_ID=>['=', $this->id,'isValue']]);
        }

        $_SESSION = [];
        session_destroy();

        $reDirect = !$reDirect ? (new Settings(SETTING_FILE))->getDetails()->machine->url : $reDirect;
        header('Location: '.$reDirect);
    }

    /**
     * generate the hash version of a password
     *
     * @param string $password the plain text password
     * @return string the hash password
     */
    public static function generatePasswordHash(string $password):string
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    /**
     * for refreshing the access token and generating a new refresh token
     *
     * @param integer $sessionId the session ID
     * @param string $accessToken the access token
     * @param string $refreshToken the refresh token
     * @return array an array [accessToken'=>$a, accessTokenExpiry'=>$ae, 'refreshToken'=>$r, 'refreshTokenExpiry'=>$re, 'error'=>$e];
     */
    public function refreshAccessToken(int $sessionId, string $accessToken, string $refreshToken):array
    {
        $newAccessToken = $newRefreshToken = $acccessTokenExpiry =  $refreshTokenExpiry = "";
        $errorMsg = [];

        $Session = new TblSession();
        $where = [
            TblSession::ID=>['=',$sessionId,'isValue', 'and'],
            TblSession::ACCESS_TOKEN=>['=',$accessToken,'isValue', 'and'],
            TblSession::REFRESH_TOKEN=>['=',$refreshToken,'isValue']
        ];
        if (!($sessionInfo = $Session->select([], $where))) {
            $errorMsg[] = "invalid session id($sessionId) for access token($accessToken) and refresh token($refreshToken)";
        } else {
            $sessionInfo = $sessionInfo[0];
            if (new DateTime() > new DateTime($sessionInfo[TblSession::REFRESH_TOKEN_EXPIRY])) {
                $errorMsg[] = "refresh token($refreshToken) has expired since {$sessionInfo[TblSession::REFRESH_TOKEN_EXPIRY]}";
            }
        }

        if (!$errorMsg) {
            $newAccessToken = $Session->getAccessToken($Session->setAccessToken());
            $acccessTokenExpiry = $Session->getAccessTokenExpiry($Session->setAccessTokenExpiry());
            $newRefreshToken = $Session->getRefreshToken($Session->setRefreshToken());
            $refreshTokenExpiry = $Session->getRefreshTokenExpiry($Session->setRefreshTokenExpiry());

            $columns = [
                TblSession::ACCESS_TOKEN=>[$newAccessToken, 'isValue'],
                TblSession::ACCESS_TOKEN_EXPIRY=>[$acccessTokenExpiry, 'isValue'],
                TblSession::REFRESH_TOKEN=>[$newRefreshToken, 'isValue'],
                TblSession::REFRESH_TOKEN_EXPIRY=>[$refreshTokenExpiry, 'isValue']
            ];
            $Session->update($columns, [TblSession::ID=>['=',$sessionId,'isValue']]);
        }

        return [
            'accessToken'=>$newAccessToken, 'refreshToken'=>$newRefreshToken, 'acccessTokenExpiry'=>$acccessTokenExpiry,
            'refreshTokenExpiry'=>$refreshTokenExpiry, 'error'=>implode(", ", $errorMsg)];
    }

    /**
     * check if the access token has not expired
     *
     * @param integer $sessionId the session ID
     * @param string $accessToken the access token
     * @return boolean true if the access token has not expired or false otherwise
     */
    public function isAccessTokenValid(int $sessionId, string $accessToken):bool
    {
        $isValid = false;
        if ($sessionInfo = (new TblSession())->get($sessionId)) {
            if ($accessToken === $sessionInfo[TblSession::ACCESS_TOKEN] && new DateTime() < new DateTime($sessionInfo[TblSession::ACCESS_TOKEN_EXPIRY])) {
                $isValid = true;
            }
        }
        return $isValid;
    }

    /**
     * check if the access token's refresh token has not expired ie if the access token is still refreshable
     *
     * @param integer $sessionId the session ID
     * @param string $accessToken the access token
     * @return boolean true if the access token can still be refreshed or false otherwise
     */
    public function canAccessTokenBeRefresh(int $sessionId, string $accessToken):bool
    {
        $isValid = false;
        $sessionInfo = (new TblSession())->get($sessionId);
        if ($sessionInfo = (new TblSession())->get($sessionId)) {
            if ($accessToken === $sessionInfo[TblSession::ACCESS_TOKEN] && new DateTime() < new DateTime($sessionInfo[TblSession::REFRESH_TOKEN_EXPIRY])) {
                $isValid = true;
            }
        }
        return $isValid;
    }

    /**
     * change reset code and reset code time for a user
     *
     * @return void
     */
    public function setResetCode()
    {
        $code = $this->tblLogger->getResetToken($this->tblLogger->setResetToken());
        $codeTime = $this->tblLogger->getResetTime($this->tblLogger->setResetTime());
        $columns = [
            TblLogger::RESET_TOKEN =>[$code, 'isValue'],
            TblLogger::RESET_TIME =>[$codeTime, 'isValue'],
        ];
        $this->tblLogger->update($columns, [TblLogger::ID =>['=', $this->id, 'isValue']]);
    }

    /**
     * check if the reset code for a user is valid
     *
     * @param bool $checktime whether to check the reset code expiration time
     * @return boolean
     */
    public function isResetCodeValid(string $resetCode, bool $checkTime = false):bool
    {
        $isValid = false;
        $userTableInfo = $this->tblLogger->get($this->id);
        if ($resetCode === $userTableInfo[TblLogger::RESET_TOKEN]) {
            $isValid = true;
        }
        if ($checkTime) {
            if (new DateTime() > new DateTime($userTableInfo[TblLogger::RESET_TIME])) {
                $isValid = false;
            }
        }
        return $isValid;
    }

    /**
     * for changing a user password
     *
     * @param string $password the new password in plain text
     * @return void
     */
    public function changePassword(string $password)
    {
        $this->tblLogger->update([TblLogger::PASSWORD=>[$password, 'isValue']], [TblLogger::ID=>['=',$this->id, 'isValue']]);
        if ($this->email) {
            //TODO: implement email notification about password change later
        }
    }

    /**
     * for changing a logger status
     *
     * @param string $status the status which is either 'active' or 'inactive'
     * @return void
     */
    public function changeStatus(string $status)
    {
        $this->tblLogger->update([TblLogger::STATUS=>[$status, 'isValue']], [TblLogger::ID=>['=',$this->id, 'isValue']]);
        if ($this->email) {
            //TODO: implement email notification about status change later
        }
    }

    /**
     * ensure only users with a valid session id and fingerprint can access the web page
     *
     * @param string $sessionId the session id
     * @param string $fingerprint the current user session's fingerprint
     * @param string $reDirectUrl the web page the session id user is redirected to if fingerprint is invalid
     * @return boolean true if valid
     */
    public function webPageLock(int $sessionId, string $fingerPrint, string $reDirectUrl)
    {
        if (!$this->isFingerprintValid($sessionId, $fingerPrint)) {
            header("Location: $reDirectUrl");
            exit;
        }
    }

    /**
     * restrict access by checking if sub profile is among the array of sub profiles
     *
     * @param array $urProfileAndSubType the user sub profile [profileType=>subPT]
     * @param array $allowedProfileTypes array of allowed sub profiles [profileType1=>[subPT11,subPT12..], profileType2=>[subPT21,subPT22..]]
     * @return boolean
     */
    public function gateAccess(array $urProfileAndSubType, array $allowedProfileTypes):bool
    {
        $canAcess = false;
        $ProfileTypeMgr = new ProfileTypeMgr();
        $allProfileTypes = $ProfileTypeMgr->getAllProfileTypes();
        $urProfileType = array_keys($urProfileAndSubType)[0];
        $urProfileSubType = $urProfileAndSubType[$urProfileType];
        if (!isset($allProfileTypes[$urProfileType])) {
            throw new AuthenticationExpection("1st parameter: invalid profile type");
        } else {
            if (array_search($urProfileSubType, $allProfileTypes[$urProfileType]) === false) {
                throw new AuthenticationExpection("1st parameter: invalid profile sub type");
            }
        }
        foreach ($allowedProfileTypes as $allKeys => $allValues) {
            if (!isset($allProfileTypes[$allKeys])) {
                throw new AuthenticationExpection("2nd parameter: invalid profile type");
            } else {
                foreach ($allValues as $aValue) {
                    if (array_search($aValue, $allProfileTypes[$allKeys]) === false) {
                        throw new AuthenticationExpection("2nd parameter: invalid profile sub type");
                    }
                }
            }
        }
        
        foreach ($allowedProfileTypes as $key => $value) {
            if (isset($allProfileTypes[$urProfileType])) {
                if (array_search($urProfileSubType, $value) !== false) {
                    $canAcess = true;
                }
            }
        }

        return $canAcess;
    }
}
