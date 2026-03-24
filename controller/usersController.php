<?php
    class UsersController {
        private function requireAuth(){
            if (session_status() !== PHP_SESSION_ACTIVE){
                session_start();
            }
            if (empty($_SESSION["idUser"])){
                header("Location: /Sakana/index.php?action=login");
                exit();
            }
        }

        function logarGerencia(){
            $this->requireAuth();
            require_once __DIR__ . "/../view/pages/usersLogin/ManagementLogin.php";
        }
        function logadoGerencia(){
            $this->requireAuth();
            require_once __DIR__ . "/../view/pages/usersPages/gerencia/ManagementPanel.php";
        }
    }
?>
