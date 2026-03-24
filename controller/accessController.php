<?php 
    class AccessController {
        function logado(){
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $email = $_POST["txtEmail"];
                $senha = $_POST["txtSenha"];

                require_once __DIR__ . "/../model/accountModel.php";
                $accontModel = new AccountModel();

                $usuario = $accontModel->logarUser($email, $senha);

                if ($usuario) {
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    session_regenerate_id(true);

                    $_SESSION["idUser"] = $usuario["idUser"];
                    $_SESSION["nomeUser"] = $usuario["nomeUser"];

                    header("Location: /Sakana/index.php?action=painelAcesso");
                    exit;
                }
                else {
                    echo "<script>
                            alert('Email ou senha incorretos!');
                            window.location='/Sakana/index.php?action=login';
                          </script>";
                }
            }
            else {
                header("Location: /Sakana/index.php?action=login");
            }
        }

        public function painelAcesso() {
            if (session_status() !== PHP_SESSION_ACTIVE){
                session_start();
            }
            if (empty($_SESSION["idUser"])) {
                header("Location: /Sakana/index.php?action=login");
                exit;
            }

            require_once __DIR__ . "/../view/accessPage.php";
        }

        public function logout() {
           if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
           }

           $_SESSION = [];

           if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
           }

           session_destroy();
           header("Location: /Sakana/index.php?action=home");
           exit();
        }
    }
?>
