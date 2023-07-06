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

$pageTitle = "News";
$subPageTitle = "News";
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
$editModal = $deleteModal = "";
$Broker = new Broker();
if($newsCollection = $Broker->someNewsInfo()) {
    $counter = 1;
    foreach ($newsCollection as $aNews) {
        $tr .= "
            <tr>
                <td>".($counter++)."</td>
                <td>{$aNews[TblNews::TITLE]}</td>
                <td>
                    {$aNews[TblNews::BODY]}<br/>
                    Source: <a href='{$aNews[TblNews::SOURCE]}' target='_blank'><u>{$aNews[TblNews::SOURCE]}</u></a>
                </td>
                <td class='col-2'>
                    <a href='javascript:void(0)' class='text-white btn btn-warning' data-toggle='modal' data-target='#modal-edit-center-$counter'>
                        <i class='ti-pencil' data-toggle='tooltip' data-original-title='Edit' aria-hidden='true'></i>
                    </a>
                    <a class='text-white btn btn-danger' data-toggle='modal' data-target='#modal-delete-center-$counter'>
                        <i class='ti-trash' data-toggle='tooltip' data-original-title='Delete' aria-hidden='true'></i>
                    </a>
                </td>
            </tr>
        ";
        
        $editModal .= "
            <!-- Modal -->
            <div class='modal center-modal fade' id='modal-edit-center-$counter' tabindex='-1'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title'>Edit News</strong></h5>
                            <button type='button' class='close' data-dismiss='modal'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <form action='processor.php' method='post' id='modal-edit-form-$counter'>
                                ".WebPage::getCSRFTokenInputTag()."
                                <input type='hidden' name='id' value='{$aNews[TblNews::ID]}'>
                                <input type='hidden' name='action' value='edit'>
                                <div class='form-group'>
                                    <label>Title *</label>
                                    <input required name='title' type='text' class='form-control' value='{$aNews[TblNews::TITLE]}'>
                                </div>
                                <div class='form-group'>
                                    <label>Body *</label>
                                    <textarea required name='body' rows='5' class='form-control'>{$aNews[TblNews::BODY]}</textarea>
                                </div>
                                <div class='form-group'>
                                    <label>Source *</label>
                                    <input required name='source' type='url' class='form-control' value='{$aNews[TblNews::SOURCE]}' placeholder='https://'>
                                </div>
                                <!-- /.box-body -->
                            </form>
                        </div>
                        <div class='modal-footer modal-footer-uniform'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                Close
                            </button>
                            <button type='submit' class='btn btn-primary float-right' form='modal-edit-form-$counter'>
                                Change
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
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
                            <form action='processor.php' method='post' id='modal-delete-form-$counter'>
                                ".WebPage::getCSRFTokenInputTag()."
                                <input type='hidden' name='id' value='{$aNews[TblNews::ID]}'>
                                <input type='hidden' name='action' value='delete'>
                                <p>You are about to delete '{$aNews[TblNews::TITLE]}'! This action is not reversible</p>
                            </form>
                        </div>
                        <div class='modal-footer modal-footer-uniform'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>
                                Close
                            </button>
                            <button type='submit' form='modal-delete-form-$counter' class='btn btn-danger float-right'>
                                Delete
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        ";
    }
}
