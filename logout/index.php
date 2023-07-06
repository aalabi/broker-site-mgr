<?php
require_once '../config.php';
$Settings = new Settings(SETTING_FILE);
if (isset($_SESSION[Authentication::LOGGER_ID])) {
    $Authentication = new Authentication($_SESSION[Authentication::LOGGER_ID], TblLogger::ID);
    $Authentication->webPageLock($_SESSION[Authentication::SESSION_ID], $_SESSION[Authentication::FINGERPRINT], $Settings->getDetails()->machine->url);
    $Authentication->logout($_SESSION[Authentication::SESSION_ID]);
} else {
    header('Location:'.$Settings->getDetails()->machine->url);
}
