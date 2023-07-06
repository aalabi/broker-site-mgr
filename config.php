<?php
session_start();
date_default_timezone_set('Africa/Lagos');
define("SETTING_FILE", "C:/wamp64/www/site/site-mgr/settings.json");

spl_autoload_register(function ($class) {
    $lastSplash = strrpos($class, "\\");
    $classname = substr($class, $lastSplash);
    $folder = "";
    if (substr($classname, 0, 3) == "Tbl") {
        $folder = "table/";
    }
    require_once "class/$folder" . ucfirst($classname) .".php";
});
