<?php

/**
 * Functions
 * This class is used to to supply some commonly used functions
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @version     1.0 => August 2021
 * @link        alabiansolutions.com
 */

class SqlType
{
    /** @var int max SQL unsighted tiny int */
    public const TINY_INT_MAX = 255;

    /** @var int min SQL unsighted tiny int */
    public const TINY_INT_MIN = 0;

    /** @var int max SQL sighted tiny int */
    public const S_TINY_INT_MAX = 127;

    /** @var int min SQL sighted tiny int */
    public const S_TINY_INT_MIN = -128;
    
    /** @var int max SQL unsighted small int */
    public const SMALL_INT_MAX = 65535;

    /** @var int min SQL unsighted small int */
    public const SMALL_INT_MIN = 0;

    /** @var int max SQL sighted small int */
    public const S_SMALL_INT_MAX = 32767;

    /** @var int min SQL sighted small int */
    public const S_SMALL_INT_MIN = -32768;
    
    /** @var int max SQL unsighted medium int */
    public const MEDIUM_INT_MAX = 16777215;

    /** @var int min SQL unsighted medium int */
    public const MEDIUM_INT_MIN = 0;

    /** @var int max SQL sighted medium int */
    public const S_MEDIUM_INT_MAX = 8388607;

    /** @var int min SQL sighted medium int */
    public const S_MEDIUM_INT_MIN = -8388608;
    
    /** @var int max SQL unsighted int */
    public const INT_MAX = 4294967295;

    /** @var int min SQL unsighted int */
    public const INT_MIN = 0;

    /** @var int max SQL sighted int */
    public const S_INT_MAX = 2147483647;

    /** @var int min SQL sighted int */
    public const S_INT_MIN = -2147483648;
    
    /** @var int max SQL unsighted big int */
    public const BIG_INT_MAX = 18446744073709551615;

    /** @var int min SQL unsighted big int */
    public const BIG_INT_MIN = 0;

    /** @var int max SQL sighted big int */
    public const S_BIG_INT_MAX = 9223372036854775807;

    /** @var int min SQL sighted big int */
    public const S_BIG_INT_MIN = -9223372036854775808;

    /** @var string max SQL DateTime */
    public const DATETIME_MAX = '9999-12-31 23:59:59';

    /** @var string min SQL DateTime */
    public const DATETIME_MIN = '1000-01-01 00:00:00';

    /** @var int max varchar string length */
    public const VARCHAR_LENGTH = 255;

    /** @var int max char string length */
    public const CHAR_LENGTH = 255;

    /** @var int max text string length */
    public const TEXT_LENGTH = 65535;
}
