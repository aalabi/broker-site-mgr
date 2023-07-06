<?php
require_once "../config.php";
$settings = (new Settings(SETTING_FILE, true))->getDetails();

if (!isset($_SESSION[Authentication::SESSION_ID]) || !isset($_SESSION[Authentication::FINGERPRINT])) {
    header("Location: ".$settings->machine->url);
    exit;
}

$Authentication = new Authentication($_SESSION[Authentication::LOGGER_ID], TblLogger::ID);
$thisUserInfo = (new User(User::profileIdFrmLoginId($_SESSION[Authentication::LOGGER_ID])))->getInfo();
$Authentication->webPageLock($_SESSION[Authentication::SESSION_ID], $_SESSION[Authentication::FINGERPRINT], $settings->machine->url);

$response = ['accountName' => "nil"];
if (isset($_GET['account']) && isset($_GET['sortCode'])) {
    $Paystack = new Paystack();
    if ($result = $Paystack->verifyAccountNo($_GET['account'], $_GET['sortCode'])) {
        $response['accountName'] =  $result['accountName'];
    }
}

header('Content-type:application/json;charset=utf-8');
echo json_encode($response);
