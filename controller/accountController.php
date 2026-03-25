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
                exit();
                return;
            }

            SessionHelper::garanteSessaoIniciada();

            // Exige token CSRF válido antes de processar o cadastro.
            if (!SessionHelper::validarToken()) {
                echo "<script>
                        alert('Tentativa de requisição inválida!');
                         window.location='/Sakana/index.php?action=cadastro';
                      </script>";
                require_once __DIR__ . "/../view/registerPage.php";
                return;
            }

            $nome = $_POST["txtNome"] ?? "";
            $email = $_POST["txtEmail"] ?? "";
            $senha = $_POST["txtSenha"] ?? "";
            $confirmaSenha = $_POST["txtConfirmaSenha"] ?? "";


            // validações
            if ($nome === "" || $email === "" || $senha === "" || $confirmaSenha === "") {
                echo "<script>alert('Por favor, preencha todos os campos!');</script>";
                require_once __DIR__ . "/../view/registerPage.php";
                return;
            }

            if($senha !== $confirmaSenha){
                echo "<script>alert('As senhas não conferem!');</script>";
                require_once __DIR__ . "/../view/registerPage.php";
                return;
            }

            require_once __DIR__ . "/../model/accountModel.php";
            $accountModel = new AccountModel();
            $cadastrou = $accountModel->cadastrarUser($nome, $email, $senha);

            if ($cadastrou) {
                // Renova token após sucesso para reduzir reutilização.
                $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

                echo "<script>alert('Cadastro realizado com sucesso!');</script>";
                require_once __DIR__ . "/../view/loginPage.php";
                return;
            }
            else {
                echo "<script>alert('Erro ao realizar cadastro!');</script>";
                require_once __DIR__ . "/../view/registerPage.php";
            }   
        }
    }
?>
