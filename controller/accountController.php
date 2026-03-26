<?php
    class AccountController {

        public function cadastro(){
            SessionHelper::garanteSessaoIniciada();
            SessionHelper::gerarToken();

            require_once __DIR__ . "/../view/registerPage.php";
        }

        public function cadastrar(){
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                header("Location: /Sakana/index.php?action=cadastro");
                exit;
                return;
            }

            SessionHelper::garanteSessaoIniciada();

            // Exige token CSRF válido antes de processar o cadastro.
            if (!SessionHelper::validarToken()) {
                SessionHelper::setFlash("error", "Tentativa de requisição inválida.");
                header("Location: /Sakana/index.php?action=cadastro");
                exit;
            }

            $nome = $_POST["txtNome"] ?? "";
            $email = $_POST["txtEmail"] ?? "";
            $senha = $_POST["txtSenha"] ?? "";
            $confirmaSenha = $_POST["txtConfirmaSenha"] ?? "";


            // validações
            if ($nome === "" || $email === "" || $senha === "" || $confirmaSenha === "") {
                SessionHelper::setFlash("warning", "Preencha todos os campos para continuar.");
                header("Location: /Sakana/index.php?action=cadastro");
                exit;
            }

            if($senha !== $confirmaSenha){
                SessionHelper::setFlash("warning", "As senhas não conferem. Digite novamente.");
                header("Location: /Sakana/index.php?action=cadastro");
                exit;
            }

            require_once __DIR__ . "/../model/accountModel.php";
            $accountModel = new AccountModel();
            $resultado = $accountModel->cadastrarUser($nome, $email, $senha);

            if ($resultado["ok"]) {
                // Renova token após sucesso para reduzir reutilização.
                $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

                SessionHelper::setFlash("success", "Cadastro realizado com sucesso!");
                header("Location: /Sakana/index.php?action=login");
                exit;
            }
            else {
                $error = $resultado["error"];

                if ($error === "email_exists") {
                    $msg = "Este email já está cadastrado. Use outro ou faça login.";
                }
                elseif ($error === "database_error") {
                    $msg = "Banco de dados indisponível. Tente mais tarde.";
                }
                else {
                    $msg = "Erro ao Cadastrar. Tente novamente.";
                }

                SessionHelper::setFlash("error", $msg);
                header("Location: /Sakana/index.php?action=cadastro");
                exit;
            }   
        }
    }
?>
