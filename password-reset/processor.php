<?php
require_once "../config.php";

if (
    isset($_POST[Functions::getCsrfTokenSessionName()])
    && Functions::checkCSRFToken($_POST[Functions::getCsrfTokenSessionName()])
) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) === false ? "" : filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    try {
        if (!$loggerInfo = (new TblLogger())->select([TblLogger::ID], [TblLogger::EMAIL=>['=', $email, 'isValue']])) {
            throw new Exception("Unknow email");
        }
        $User = new User(User::profileIdFrmLoginId($loggerInfo[0][TblLogger::ID]));
        $Authentication = new Authentication($email);
        $Authentication->setResetCode();
        $userInfo = $User->getInfo();
        $resetCode = $userInfo['logger'][TblLogger::RESET_TOKEN];
        $settings = (new Settings(SETTING_FILE))->getDetails();
        $url = $settings->machine->url."password-change/?reset-code=".urlencode($resetCode)."&email=".urlencode($email);
        $body = "
            <p style='margin-bottom:10px; margin-top:10px;'>Good Day {$userInfo['profile'][TblProfile::NAME]}</p>
            <p style='margin-bottom:10px;'>
                Trouble signing in?
            </p>
            <p style='margin-bottom:10px;'>
                Resetting your password is easy.
            </p>
            <p style='margin-bottom:10px;'>
                Just press the button below and follow the instructions, your reset token is '<strong>$resetCode</strong>'. 
                We'll have you up and running in no time. This code is valid until {$userInfo['user'][TblLogger::RESET_TIME]}<br/>
                <a style='
                        border-radius:0.7em;
                        display:inline-block; 
                        width:5em; 
                        padding:0.5em; 
                        background-color:#fff; 
                        color:#020202;
                        text-decoration: none;
                        text-align: center;
                        margin-top: 1em;'
                    href='$url'>
                    CLICK
                </a>                
            </p>
            <p style='margin-bottom:60px;'>
                If you did not make this request then please ignore this email.
            </p>
        ";
        $Notification = new Notification();
        $Notification->sendMail(['to'=>[$userInfo['profile'][TblProfile::NAME]=>$email]], 'Password Reset Token', $body);
        $title = "";
        $messages = ["Please check your email '$email' for password reset instructions"];
        $responseStatus = WebPage::RESPONSE_POSITIVE;
    } catch (\Throwable $th) {
        $title = "";
        $messages = ["The Please email '$email' provided is not on our system"];
        $responseStatus = WebPage::RESPONSE_NEGATIVE;
    }
    WebPage::setResponse($title, $messages, $responseStatus);
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
