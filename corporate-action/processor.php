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
$permittedTypes = ['staff'=>['staff']];
$thisUserType = ['staff'=>$thisUserInfo['staff'][TblStaff::TYPE]];
if (!$Authentication->gateAccess($thisUserType, $permittedTypes)) {
    header("Location: ".$settings->machine->url);
    exit;
}

if (
    isset($_POST[Functions::getCsrfTokenSessionName()])
    && Functions::checkCSRFToken($_POST[Functions::getCsrfTokenSessionName()])
) {
    $errors = [];
    if (isset($_POST['id'])) {
        if (!($id = filter_var(trim($_POST['id']), FILTER_VALIDATE_INT))) {
            $errors[] = 'invalid id';
        }
    }
    if ($errors) {
        WebPage::setResponse('Result Processing Error', [implode(",", $errors)], WebPage::RESPONSE_NEGATIVE);
        header("Location: .");
        exit();
    }

    $title = "";
    $Broker = new Broker();
    if ($_POST['action'] == 'create') {
        try {
            $company = htmlspecialchars(trim($_POST['company']));
            $year = htmlspecialchars(trim($_POST['year']));
            $period = htmlspecialchars(trim($_POST['period']));
            $dividend = htmlspecialchars(trim($_POST['dividend']));
            $interim = htmlspecialchars(trim($_POST['interim']));
            $bonus = htmlspecialchars(trim($_POST['bonus']));
            $closureDate = htmlspecialchars(trim($_POST['closureDate']));
            $agmDate = htmlspecialchars(trim($_POST['agmDate']));
            $pymtDate = htmlspecialchars(trim($_POST['pymtDate']));
            $Broker->createCorporateAction($company, new DateTime($year), $period, $dividend, $interim, $bonus, $closureDate, $agmDate, $pymtDate);
            $message = "A corporate action was successfully uploaded";
            $responseStatus = WebPage::RESPONSE_POSITIVE;
        } catch(Exception $e) {
            $message = "Corporate action creation failed: ".$e->getMessage();
            $responseStatus = WebPage::RESPONSE_NEGATIVE;
        }
    }
    if ($_POST['action'] == 'delete') {
        try {
            $Broker->deleteCorporateAction($id);
            $message = "A corporate action was successfully deleted";
            $responseStatus = WebPage::RESPONSE_POSITIVE;
        } catch (Exception $e) {
            $message = "A corporate action deleting failed: ".$e->getMessage();
            $responseStatus = WebPage::RESPONSE_NEGATIVE;
        }
    }
    WebPage::setResponse($title, [$message], $responseStatus);
    header("Location: .");
    exit();
} else {
    new ErrorLog('Invalid CSRF Token', __FILE__, __LINE__);
    WebPage::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        WebPage::RESPONSE_NEGATIVE
    );
    header("Location: .");
    exit();
}
