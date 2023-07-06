<?php
require_once "config.php";

if (
    isset($_POST[Functions::getCsrfTokenSessionName()])
    && Functions::checkCSRFToken($_POST[Functions::getCsrfTokenSessionName()])
) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) === false ? "" : filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($_POST['password']);

    try {
        $Authentication = new Authentication($email);
        $authenticationInfo = $Authentication->login($password);
    } catch (\Throwable $th) {
        $authenticationInfo['error'] = $authenticationInfo['error'] ? $authenticationInfo['error'] : "Login failed @". __LINE__;
    }

    if (!$authenticationInfo['error']) {
        $Settings = new Settings(SETTING_FILE);
        header("Location: ".$Settings->getDetails()->url."dashboard");
        exit();
    } else {
        WebPage::setResponse(
            'Login Failed',
            ['Your login details are not correct. Please try again'],
            WebPage::RESPONSE_NEGATIVE
        );
        header("Location: .");
        exit();
    }
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
