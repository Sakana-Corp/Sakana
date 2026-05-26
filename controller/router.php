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
    
    case "cadastrarFunc":
        require_once "employeeController.php";
        $controller = new EmployeeController();
        $controller->cadastrar();
        break;
    
    case "cadastrarFuncForm":
        require_once "employeeController.php";
        $controller = new EmployeeController();
        $controller->cadastrarFuncForm();
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

    case "logadoGerencia":
        require_once "usersController.php";
        $controller = new UsersController();
        $controller->logadoGerencia($_GET["page"] ?? "home");
        break;

    case 'mesas':
        require_once "usersController.php";
        $controller = new UsersController();
        $controller->logadoGerencia($action);
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


