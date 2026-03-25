<?php
    require_once "config/config.php";

    session_set_cookie_params($SECURITY_CONFIG["session"]);
    SessionHelper::garanteSessaoIniciada();

    require_once "controller/router.php";
?>
