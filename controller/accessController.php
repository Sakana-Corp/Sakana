<?php 
    class AccessController {
        function logado(){
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                SessionHelper::garanteSessaoIniciada();

                // Bloqueia envio de formulário forjado (CSRF).
                if (!SessionHelper::validarToken()) {
                    echo "<script>
                            alert('Tentativa de requisição inválida!');
                            window.location='/Sakana/index.php?action=login';
                          </script>";
                    return;
                }

                $email = $_POST["txtEmail"];
                $senha = $_POST["txtSenha"];

                if ($email === "" || $senha === "") {
                    echo "<script>alert('Por favor, preencha todos os campos!');</script>";
                    require_once __DIR__ . "/../view/loginPage.php";
                    return;
                }

                require_once __DIR__ . "/../model/accountModel.php";
                $accontModel = new AccountModel();

                $usuario = $accontModel->logarUser($email, $senha);

                if ($usuario) {
                    // Troca o ID da sessão após autenticar para evitar fixation.
                    session_regenerate_id(true);

                    $_SESSION["idUser"] = $usuario["idUser"];
                    $_SESSION["nomeUser"] = $usuario["nomeUser"];

                    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

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
                exit;
            }
        }

        public function loginForm() {
            SessionHelper::garanteSessaoIniciada();
            SessionHelper::gerarToken();

            require_once __DIR__ . "/../view/loginPage.php";
        }

        public function painelAcesso() {
            SessionHelper::garanteSessaoIniciada();

            if (empty($_SESSION["idUser"])) {
                header("Location: /Sakana/index.php?action=login");
                exit;
            }

            require_once __DIR__ . "/../view/accessPage.php";
        }

        public function logout() {
           SessionHelper::garanteSessaoIniciada();
           SessionHelper::encerrar();

           header("Location: /Sakana/index.php?action=home");
           exit();
        }
    }
?>
