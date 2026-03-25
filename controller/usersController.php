<?php
    class UsersController {
        private function requireAuth(){
            SessionHelper::garanteSessaoIniciada();
            
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
