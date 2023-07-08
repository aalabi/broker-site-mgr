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
            $month = htmlspecialchars(trim($_POST['month']));
            $year = htmlspecialchars(trim($_POST['year']));
            $month = new DateTime("$year-$month-01");
            $Broker->createMarketReview(TblMarketReview::TYPE_VALUE[2], $_FILES, $month);
            $message = "A new monthly market review was successfully uploaded";
            $responseStatus = WebPage::RESPONSE_POSITIVE;
        } catch(Exception $e) {
            $message = "Monthly market review creation failed: ".$e->getMessage();
            $responseStatus = WebPage::RESPONSE_NEGATIVE;
        }
    }
    if ($_POST['action'] == 'delete') {
        try {
            $Broker->deleteMarketReview($id);
            $message = "Monthly market review was successfully deleted";
            $responseStatus = WebPage::RESPONSE_POSITIVE;
        } catch (Exception $e) {
            $message = "Monthly market review deleting failed: ".$e->getMessage();
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
