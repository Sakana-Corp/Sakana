<?php
    $secure = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off");
    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "domain" => "",
        "secure" => $secure,
        "httponly" => true,
        "samesite" => "Lax"
    ]);

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    require_once "controller/router.php";
?>
