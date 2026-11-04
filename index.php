<?php



if (getenv("SAKANA_APP_DEBUG") === "1") {
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", "0");
    ini_set("display_startup_errors", "0");
}




    require_once "config/config.php";

    session_set_cookie_params($SECURITY_CONFIG["session"]);
    SessionHelper::garanteSessaoIniciada();

    require_once "controller/router.php";
?>
