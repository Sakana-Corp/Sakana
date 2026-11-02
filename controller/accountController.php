<?php
require_once __DIR__ . "/baseController.php";
class AccountController extends BaseController {

    public function cadastro(){
        $this->startSession();
        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/registerPage.php";
    }

    public function cadastrar(){
        $this->requirePost("cadastro");
        $this->startSession();
        $this->validateCsrfOrRedirect("cadastro");

        $nome = $_POST["txtNome"] ?? "";
        $email = $_POST["txtEmail"] ?? "";
        $senha = $_POST["txtSenha"] ?? "";
        $confirmaSenha = $_POST["txtConfirmaSenha"] ?? "";

        if (($_POST["aceitaPolitica"] ?? "") !== "1") {
            $this->flashAndRedirect(
                "warning",
                "Leia e aceite a Política de Privacidade para continuar.",
                "cadastro"
            );
        }

        require_once __DIR__ . "/../service/inputValidator.php";
        $validator = new InputValidator();
        $validator->name("nome", $nome, 100)
                  ->email("email", $email)
                  ->strongPassword("senha", $senha)
                  ->match("confirmaSenha", $senha, $confirmaSenha, "As senhas");

        if (!$validator->isValid()) {
            $this->flashAndRedirect("warning", $validator->getFirstError(), "cadastro");
        }

        require_once __DIR__ . "/../model/accountModel.php";
        $accountModel = new AccountModel();
        $resultado = $accountModel->cadastrarUser($nome, $email, $senha);

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Cadastro realizado com sucesso!", "login");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "email_exists") {
            $msg = "Este email já está cadastrado. Use outro ou faça login.";
        }
        elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        }
        else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "cadastro");
    }
}
?>
