<?php
require_once '../config.php';
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

$pageTitle = "Research Company";
$subPageTitle = "Financial Report";
$HomePage = new WebPage($settings->sitename.":: $pageTitle", $_SESSION[Authentication::LOGGER_ID]);
$headTag = $HomePage->head();
$headerTag = $HomePage->mastHead();
$jsFiles = [];
$aSideMenuTag = $HomePage->createSideBar();
$footerCredit = $HomePage->footerCredit();
$chatTags = $HomePage->chatTag();
$footerScriptTag = $HomePage->footerFiles($jsFiles);

$responseTag = "";
if ($formResponse = WebPage::getResponse()) {
    $responseTag = WebPage::responseTag($formResponse['title'], $formResponse['messages'][0], $formResponse['status']);
}

$Broker = new Broker();

$companyOption = "<option value=''>Select Company</option>";
$allStock = $Broker->someStockInfo();
foreach ($allStock as $aStock) {
    $companyOption .= "<option value='{$aStock[TblStock::ID]}'>{$aStock[TblStock::NAME]}</option>";
}

$yearOption = "<option value=''>Select Year</option>";
$thisYear = date("Y");
for ($year = $thisYear; $year < 1960 ; $year--) {
    $yearOption = "<option value='$year'>$year</option>";
}

$periodOption = "<option value=''>Select Period</option>";
$periodCollection = $Broker->periodNames();
foreach ($periodCollection as $ordinal => $name) {
    $periodOption = "<option value='$ordinal'>$name</option>";
}
