<?php

/**
 * Functions
 * This class is used to to supply some commonly used functions
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @version     1.0 => August 2021
 * @link        alabiansolutions.com
 */

class Functions
{
    /** @var int indicate when an integer does not exist */
    public const NO_INT_VALUE = 9223372036854775807;

    /** @var int indicate when an integer does not exist */
    public const INFINITE = 9223372036854775807;

    /** @var int length of reset time in seconds - 12 hours */
    public const RESET_TIME_LIMIT = 12 * 60 * 60;

    /** @var int length of activation in seconds  - 12 hours */
    public const ACTIVATION_TIME_LIMIT = 12 * 60 * 60;

    /** @var int length of access token time in seconds - 0.5 hour */
    public const ACCESS_TOKEN_LIMIT = 0.5 * 60 * 60;

    /** @var int length of refresh token in seconds  - 15 days */
    public const REFRESH_TOKEN_LIMIT = 15 * 24 * 60 * 60;

    /** @var string app logo */
    public const LOGO = 'logo.png';

    /** @var string app favicon */
    public const FAVICON = 'favicon.ico';

    /** @var string directory name where avatars are stored */
    public const AVATAR_DIRECTORY = 'avatars/';

    /** @var string directory name where worker uploaded are stored */
    public const WORKER_UPLOAD_DIRECTORY = 'worker-creation/';

    /** @var string default profile avatar */
    public const DEFAULT_AVATAR = 'default.png';

    /** @var string default profile avatar for male */
    public const DEFAULT_AVATAR_MALE = 'default-male.png';

    /** @var string default profile avatar female */
    public const DEFAULT_AVATAR_FEMALE = 'default-female.png';

    /** @var string directory where css files are stored */
    public const CSS_DIRECTORY = 'assets/css/';

    /** @var string directory where css files are stored */
    public const ASSET_DIRECTORY = 'assets/';

    /** @var string directory where js files are stored */
    public const JS_DIRECTORY = 'assets/js/';

    /** @var string directory where image files are stored */
    public const IMAGE_DIRECTORY = 'assets/image/';

    /** @var string directory where video files are stored */
    public const VIDEO_DIRECTORY = 'assets/video/';

    /** @var string directory where audio are stored */
    public const AUDIO_DIRECTORY = 'assets/audio/';

    /** @var string document directory where files are stored */
    public const DOCUMENT_DIRECTORY = 'document/';


    /**
     * get the name of the CSRF token
     *
     * @return string $string the CSRF token name
     */
    public static function getCsrfTokenSessionName(): string
    {
        $Settings = new Settings(SETTING_FILE);
        $internalName = $Settings->getDetails()->sitenameInternal;
        return $internalName."_csrf_token";
    }

    /**
     * get the directory path to where document are stored
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the path to the directory where avatar are store
     */
    public static function getDocDirectoryPath(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        $path = $Settings->getDetails()->machine->path.$backend.$Settings->getDetails()->documentFolder.DIRECTORY_SEPARATOR;
        return $path;
    }

    /**
     * get the url to the document directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the asset directory
     */
    public static function getDocUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        $url = $Settings->getDetails()->machine->url.$backend.$Settings->getDetails()->documentFolder."/";
        return $url;
    }

    /**
     * get the directory path to where avatar are stored
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the path to the directory where avatar are store
     */
    public static function getAvatarDirectoryPath(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $path = $Settings->getDetails()->documentPath;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $path.$backend.Functions::AVATAR_DIRECTORY;
    }

    /**
     * get the directory path to where worker creation file is uploaded to
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the path to the directory where \worker creation file is uploaded to
     */
    public static function getWorkerUploadDirectoryPath(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $path = $Settings->getDetails()->documentPath;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $path.$backend.Functions::WORKER_UPLOAD_DIRECTORY;
    }

    /**
     * get the url to the asset directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the asset directory
     */
    public static function getAssetUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::ASSET_DIRECTORY;
    }

    /**
     * get the url to the css directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the css directory
     */
    public static function getCssUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::CSS_DIRECTORY;
    }

    /**
     * get the url to the js directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the js directory
     */
    public static function getJsUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::JS_DIRECTORY;
    }

    /**
     * get the url to the image directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the image directory
     */
    public static function getImageUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::IMAGE_DIRECTORY;
    }

    /**
     * get the directory path to the image directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the path to the image directory
     */
    public static function getImageDirectoryPath(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $path = $Settings->getDetails()->machine->path;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $path.$backend.Functions::IMAGE_DIRECTORY;
    }

    /**
     * get the url to the audio directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the audio directory
     */
    public static function getAudioUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::AUDIO_DIRECTORY;
    }

    /**
     * get the url to the video directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the video directory
     */
    public static function getVideoUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::VIDEO_DIRECTORY;
    }

    /**
     * get the url to the avatar in document directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the avatar document directory
     */
    public static function getDocAvatarsUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::DOCUMENT_DIRECTORY."avatars/";
    }

    /**
     * get the url to the photo in document directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the photo document directory
     */
    public static function getDocPhotosUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::DOCUMENT_DIRECTORY."photos/";
    }

    /**
     * get the url to the video in document directory
     *
     * @param bool $isBackend if the path should redirect to the backend
     * @return string $string the url to the video document directory
     */
    public static function getDocVideosUrl(bool $isBackend = false): string
    {
        $Settings = new Settings(SETTING_FILE);
        $url = $Settings->getDetails()->machine->url;
        $backend = $isBackend ? $Settings->getDetails()->machine->backend."/" : "";
        return $url.$backend.Functions::DOCUMENT_DIRECTORY."videos/";
    }

    /**
     * generate the CSRF token
     *
     * @return string
     */
    public static function getCSRFToken(): string
    {
        if (!isset($_SESSION[Functions::getCsrfTokenSessionName()])) {
            $token = bin2hex(random_bytes(32));
            $_SESSION[Functions::getCsrfTokenSessionName()] = $token;
        } else {
            $token = $_SESSION[Functions::getCsrfTokenSessionName()];
        }
        return $token;
    }

    /**
     * check if the active the CSRF token is ok
     *
     * @param string $token the CSRF token to be checked
     * @return bool
     */
    public static function checkCSRFToken(string $token): bool
    {
        $equal = false;
        $equal = hash_equals($token, Functions::getCSRFToken());
        return $equal;
    }

    /**
     * Generate the ASCII code of digits, alphabet upper & case
     * @return array $array an array that contains ASCII of digits, alphabet upper & lower case
     */
    public static function asciiTableDigitalAlphabet(): array
    {
        $array = array();
        //Digitals
        for ($kanter = 48; $kanter <= 57; $kanter++) {
            $array[] = $kanter;
        }
        //Uppercase
        for ($kanter = 65; $kanter <= 90; $kanter++) {
            $array[] = $kanter;
        }
        //Lowercase
        for ($kanter = 97; $kanter <= 122; $kanter++) {
            $array[] = $kanter;
        }
        shuffle($array);
        return $array;
    }

    /**
     * Generate the ASCII code of digits, alphabet upper & case
     * @param array $ASCIIArray an array that contains ASCII Code
     * @param string $dataFormat the format of the return value of array for array or other value for string
     * @return string $characters an array or string that contains character that matches the ASCII Code supplied
     */
    public static function characterFromASCII(array $ASCIIArray, string $dataFormat = 'array'): string
    {
        $max = count($ASCIIArray);
        for ($kanter = 0; $kanter < $max; $kanter++) {
            $array[] = chr($ASCIIArray[$kanter]);
        }
        if ($dataFormat == 'array') {
            $characters = $array;
        } else {
            $characters = "";
            foreach ($array as $anArrayValue) {
                $characters .= $anArrayValue;
            }
        }
        return $characters;
    }

    /**
     * Generate ASCII code
     *
     * @param int the no of item in the return array
     * @param boolean $onlyDigitAlphabet if true only code of digit, alphabet are return
     * @param array $range an array of start and end of of need ASCII code
     * @param boolean $shuffledIt if true return array is shuffled
     * @param boolean $isChar if true return character other returns the integer code
     * @return array $array an array that contains ASCII code
     */
    public static function asciiCollection(
        int $count = Functions::INFINITE,
        bool $onlyDigitAlphabet = true,
        array $range = [],
        bool $shuffledIt = true,
        bool $isChar = true
    ): array {
        $array = [];
        if ($onlyDigitAlphabet) {
            //Digitals
            for ($kanter = 48; $kanter <= 57; $kanter++) {
                $array[] = $kanter;
            }
            //Uppercase
            for ($kanter = 65; $kanter <= 90; $kanter++) {
                $array[] = $kanter;
            }
            //Lowercase
            for ($kanter = 97; $kanter <= 122; $kanter++) {
                $array[] = $kanter;
            }
        } elseif ($range) {
            for ($kanter = $range[0]; $kanter <= $range[1]; $kanter++) {
                $array[] = $kanter;
            }
        } else {
            for ($kanter = 0; $kanter <= 127; $kanter++) {
                $array[] = $kanter;
            }
        }
        if ($shuffledIt) {
            shuffle($array);
        }
        if ($count != Functions::INFINITE) {
            $array = array_slice($array, 0, ($count));
        }
        if ($isChar) {
            $array = array_map(function ($arr) {
                return chr($arr);
            }, $array);
        }
        return $array;
    }

    /**
     * for validating date
     *
     * @param string $date the date to be validated
     * @return boolean
     */
    public static function isValidDate($date)
    {
        return (strtotime($date) !== false);
    }
}
