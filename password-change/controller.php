<?php
require_once '../config.php';
$settings = (new Settings(SETTING_FILE))->getDetails();

$pageTitle = "Change Password";
$HomePage = new WebPage($settings->sitename.":: $pageTitle");
$headTag = $HomePage->head();
$footerScriptTag = $HomePage->footerFiles([], true);

$responseTag = "";
if ($formResponse = WebPage::getResponse()) {
    $responseTag = WebPage::responseTag($formResponse['title'], $formResponse['messages'][0], $formResponse['status']);
}

$resetToken = "";
$readOnlyToken = $readOnlyPassword = "readonly";
$emailTag = "";
if (isset($_GET['reset-code']) && isset($_GET['email'])) {
    $resetToken = urldecode($_GET['reset-code']);
    $email = urldecode($_GET['email']);
    $emailTag = "<input type='hidden' value='$email' name='email'>";
    try {
        $Authentication = new Authentication($email);
        if (!$Authentication->isResetCodeValid($resetToken, true)) {
            throw new Exception();
        }
        $readOnlyPassword = "";
    } catch (\Throwable $th) {
        $responseTag = WebPage::responseTag('', 'Invalid reset token', WebPage::RESPONSE_NEGATIVE);
    }
}

if (!isset($_GET['reset-code']) && !isset($_GET['email'])) {
    $readOnlyToken = $readOnlyPassword = "";
    $emailTag = "
        <div class='form-group'>
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text bg-transparent'><i class='ti-email'></i></span>
                </div>
                <input type='email' required name='email' class='form-control pl-15 bg-transparent' placeholder='Enter the email associated with your account'>
            </div>
        </div>
    ";
}

if (isset($_GET['changed']) && $_GET['changed'] == "true") {
    $readOnlyToken = $readOnlyPassword = "readonly";
    $emailTag = "";
    if ($formResponse = WebPage::getResponse()) {
        $responseTag = WebPage::responseTag($formResponse['title'], $formResponse['messages'][0], $formResponse['status']);
    }
}
