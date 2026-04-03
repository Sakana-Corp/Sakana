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
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->loginForm();
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
        break;

    // 👇 COLOQUE AQUI
    case 'funcionarios':
        require_once "view/pages/usersPages/gerencia/funcionarios.php";
        break;

    case 'pedidos':
        require_once "view/pages/usersPages/gerencia/pedidos.php";
        break;

    case 'cardapio':
        require_once "view/pages/usersPages/gerencia/cardapio.php";
        break;

    case 'mesas':
        require_once "view/pages/usersPages/gerencia/mesas.php";
        break;

    case "painelAcesso":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->painelAcesso();
        break;

    case "logout":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->logout();
        break;

    default:
        require_once "homeController.php";
        $controller = new HomeController();
        $controller->index();
        break;
}
?>