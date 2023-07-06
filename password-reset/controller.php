<?php
require_once '../config.php';
$settings = (new Settings(SETTING_FILE))->getDetails();

$pageTitle = "Password Reset";
$HomePage = new WebPage($settings->sitename.":: Password Reset");
$headTag = $HomePage->head();
$footerScriptTag = $HomePage->footerFiles([], true);

$responseTag = "";
if ($formResponse = WebPage::getResponse()) {
    $responseTag = WebPage::responseTag($formResponse['title'], $formResponse['messages'][0], $formResponse['status']);
}
