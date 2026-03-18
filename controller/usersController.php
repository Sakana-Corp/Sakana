<?php
    class UsersController {
        function logarGerencia(){
            require_once __DIR__ . "/../view/pages/usersLogin/ManagementLogin.php";
        }
        function logadoGerencia(){
            require_once __DIR__ . "/../view/pages/usersPages/gerencia/ManagementPanel.php";
        }
    }
?>
