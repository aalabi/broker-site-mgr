<?php
require_once 'config.php';
$settings = (new Settings(SETTING_FILE))->getDetails();

$HomePage = new WebPage($settings->sitename.":: Log In");
$headTag = $HomePage->head();
$footerScriptTag = $HomePage->footerFiles([], true);

$responseTag = "";
if ($formResponse = WebPage::getResponse()) {
    $responseTag = WebPage::responseTag($formResponse['title'], $formResponse['messages'][0], $formResponse['status']);
}
