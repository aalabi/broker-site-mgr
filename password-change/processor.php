<?php
require_once "../config.php";

if (
    isset($_POST[Functions::getCsrfTokenSessionName()])
    && Functions::checkCSRFToken($_POST[Functions::getCsrfTokenSessionName()])
) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) === false ? "" : filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $token = htmlspecialchars($_POST['token']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $settings = (new Settings(SETTING_FILE))->getDetails();
    $errors = [];
    if ($password !== $confirmPassword) {
        $errors[] = "password and confirm password differs";
    }
    try {
        $Authentication = new Authentication($email);
    } catch (\Throwable $th) {
        $errors[] = "invalid email";
    }
    if (!$Authentication->isResetCodeValid($token, true)) {
        $errors[] = "invalid token";
    }
    try {
        $Authentication->changePassword($password);
    } catch (\Throwable $th) {
        $errors[] = $th->getMessage();
    }
    
    if (!$errors) {
        $loggerInfo = (new TblLogger())->select([TblLogger::ID], [TblLogger::EMAIL=>['=', $email, 'isValue']]);
        $User = new User(User::profileIdFrmLoginId($loggerInfo[0][TblLogger::ID]));
        $userInfo = $User->getInfo();
        $body = "
            <p style='margin-bottom:10px; margin-top:10px;'>Good Day {$userInfo['profile'][TblProfile::NAME]}</p>
            <p style='margin-bottom:10px;'>
                We will like to notify you that you have changed your password on our system.
            </p>
            <p style='margin-bottom:60px;'>
                If you did not make this change please contact us immediately.
            </p>
        ";
        $Notification = new Notification();
        $Notification->sendMail(['to'=>[$userInfo['profile'][TblProfile::NAME]=>$email]], 'Password Change', $body);
        $title = "";
        $messages = ["Your password has been successfully changed. <u><a href='{$settings->machine->url}'>Login</a></u>"];
        $redirect = $settings->machine->url."password-change/?changed=true";
        $responseStatus = WebPage::RESPONSE_POSITIVE;
    } else {
        $title = "";
        $messages = [implode(", ", $errors)];
        $redirect = $settings->machine->url."password-change";
        $responseStatus = WebPage::RESPONSE_NEGATIVE;
    }
    WebPage::setResponse($title, $messages, $responseStatus);
    header("Location: ".$redirect);
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
