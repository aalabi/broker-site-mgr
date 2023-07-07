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

$pageTitle = "NSE Daily Price";
$subPageTitle = "NSE Daily Price";
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

$tr = "";
$deleteModal = "";
$Broker = new Broker();
if($nsePriceCollection = $Broker->someNseDailyPriceInfo()) {
    $counter = 1;
    foreach ($nsePriceCollection as $aNse) {
        $href = Functions::getDocUrl(true).$aNse[TblNseDailyPrice::FILE];
        $tr .= "

            <tr>
                <td>".($counter++)."</td>
                <td>
                    ASI: ".number_format($aNse[TblNseDailyPrice::ASI], 2)." <br />
                    Deals: ".number_format($aNse[TblNseDailyPrice::DEALS])."<br />
                    Market Volume: ".number_format($aNse[TblNseDailyPrice::MARKET_VOLUME], 2)."<br />
                    Market Value: ".number_format($aNse[TblNseDailyPrice::MARKET_VALUE], 2)."<br />
                    Market Cap: ".number_format($aNse[TblNseDailyPrice::MARKET_CAP], 2)."<br />
                </td>
                <td>{$aNse[TblNseDailyPrice::DATE]}</td>
                <td>
                    <a href='$href' class='text-white btn btn-primary' data-toggle='tooltip' data-original-title='View'>
                        <i class='ti-download' aria-hidden='true'></i>
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
                                <input type='hidden' name='id' value='{$aNse[TblNseDailyPrice::ID]}'>
                                <p>You are about to delete '{$aNse[TblNseDailyPrice::DATE]}' pricelist? This action is not reversible</p>
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
