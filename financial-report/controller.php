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
for ($year = $thisYear; $year >= 1960 ; $year--) {
    $yearOption .= "<option value='$year'>$year</option>";
}

$periodOption = "<option value=''>Select Period</option>";
$periodCollection = $Broker->periodNames();
foreach ($periodCollection as $ordinal => $name) {
    $periodOption .= "<option value='$ordinal'>$name</option>";
}

$tr = $deleteModal = "";
if($financialReportCollection = $Broker->someFinancialReportInfo()) {
    $counter = 1;
    $companyCollection = (new TblStock)->getColumnByIndex(TblStock::ID, TblStock::NAME);
    foreach ($financialReportCollection as $aFinancialReport) {
        $href = Functions::getDocUrl(true).$aFinancialReport[TblFinancialReport::FILE];
        $tr .= "            
            <tr>
                <td>".($counter++)."</td>
                <td>{$companyCollection[$aFinancialReport[TblFinancialReport::STOCK_ID]]}</td>
                <td>{$Broker->periodNames()[$aFinancialReport[TblFinancialReport::PERIOD]]} {$aFinancialReport[TblFinancialReport::YEAR]}</td>

                <td>
                <a href='$href' class='text-white btn btn-primary' data-toggle='tooltip' data-original-title='View' target='_blank'>
                    <i class='ti-eye' aria-hidden='true'></i>
                </a>
                <a class='text-white btn btn-danger' data-toggle='modal' data-target='#modal-delete-center-$counter'>
                    <i class='ti-trash' data-toggle='tooltip' data-original-title='Delete' aria-hidden='true'></i>
                </a>
                </td>
            </tr>
        ";

        $deleteModal .= "
            <!-- Modal -->
            <div class='modal center-modal fade' id='modal-delete-center-$counter' tabindex='-1'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title'>Are you sure?</h5>
                            <button type='button' class='close' data-dismiss='modal'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <form action='processor.php' method='post' id='delete-$counter'>
                                ".WebPage::getCSRFTokenInputTag()."
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='id' value='{$aFinancialReport[TblFinancialReport::ID]}'>
                                <p>You are about to delete '{$companyCollection[$aFinancialReport[TblFinancialReport::STOCK_ID]]}' financial report? This action is not reversible</p>
                            </form>
                        </div>
                        <div class='modal-footer modal-footer-uniform'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                Close
                            </button>
                            <button type='submit' form='delete-$counter' class='btn btn-danger float-right'>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        ";
    }
}
