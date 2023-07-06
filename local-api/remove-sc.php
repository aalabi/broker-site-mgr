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

$response = ['deleted' => false];
if (isset($_GET['id']) &&  ($id = filter_var($_GET['id'], FILTER_VALIDATE_INT))) {
    $Salary = new Salary();
    try {
        $Salary->editSalaryComponentToGrade($id, null, null, null, null, [], TblGradeSalaryComponent::STATUS_VALUE[1]);
        $response = ['deleted' => true];
    } catch (\Throwable $th) {
        $response = ['deleted' => false];
    }
}

header('Content-type:application/json;charset=utf-8');
echo json_encode($response);
