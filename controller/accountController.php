<?php
    class AccountController {

        public function cadastro(){
            require_once __DIR__ . "/../view/registerPage.php";
        }
        public function login(){
            require_once __DIR__ . "/../view/loginPage.php";
        }

        public function cadastrar(){
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                require_once __DIR__ .  "/../view/registerPage.php";
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
