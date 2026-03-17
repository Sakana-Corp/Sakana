<?php
    $action = $_GET["action"] ?? "home";

    switch($action) {
        case "home":
        case "index":
            require_once "homeController.php";
            $controller = new HomeController();
            $controller->index();
            break;
        case "login":
            require_once "accountController.php";
            $controller = new AccountController();
            $controller->login();
            break;
        case "cadastrar":
            require_once "accountController.php";
            $controller = new AccountController();
            $controller->cadastrar();
            break;
        case "cadastro":
            require_once "accountController.php";
            $controller = new AccountController();
            $controller->cadastro();
            break;
        case "logado":
            require_once "accessController.php";
            $controller = new AccessController();
            $controller->logado();
            break;
        case "loginGerencia":
            require_once "usersController.php";
            $controller = new UsersController();
            $controller->logarGerencia();
            break;
        case "logadoGerencia":
            require_once "usersController.php";
            $controller = new UsersController();
            $controller->logadoGerencia();
    }
?>
