<?php 
    class AccessController {
        function logado(){
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                SessionHelper::garanteSessaoIniciada();

                // Bloqueia envio de formulário forjado (CSRF).
                if (!SessionHelper::validarToken()) {
                    SessionHelper::setFlash("error", "Tentativa de requisição inválida.");
                    header("Location: /Sakana/index.php?action=login");
                    exit;
                }

                $email = $_POST["txtEmail"] ?? "";
                $senha = $_POST["txtSenha"] ?? "";

                if ($email === "" || $senha === "") {
                    SessionHelper::setFlash("warning", "Preencha email e senha para continuar.");
                    header("Location: /Sakana/index.php?action=login");
                    exit;
                }

                require_once __DIR__ . "/../model/accountModel.php";
                $accontModel = new AccountModel();

                $resultado = $accontModel->logarUser($email, $senha);

                if ($resultado["ok"]) {
                    // Troca o ID da sessão após autenticar para evitar fixation.
                    session_regenerate_id(true);

                    $_SESSION["idUser"] = $resultado["user"]["idUser"];
                    $_SESSION["nomeUser"] = $resultado["user"]["nomeUser"];

                    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

                    SessionHelper::setFlash("success", "Login realizado com sucesso! Bem-vindo.");
                    header("Location: /Sakana/index.php?action=painelAcesso");
                    exit;
                }
                else {
                    $error = $resultado["error"] ?? "unknown_error";

                    if ($error === "invalid_credentials") {
                        $msg = "Email ou senha incorretos. Tente novamente.";
                    }
                    elseif ($error === "database_error") {
                        $msg = "Banco de dados indisponível. Tente mais tarde.";
                    }
                    else {
                        $msg = "Erro ao processar login. Tente novamente.";
                    }

                    SessionHelper::setFlash("error", $msg);
                    header("Location: /Sakana/index.php?action=login");
                    exit;
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
                SessionHelper::setFlash("info", "Sessão expirada. Faça login novamente.");
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
