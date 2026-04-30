<?php 
require_once __DIR__ . "/baseController.php";
class AccessController extends BaseController {
    function logado(): void {
        $this->requirePost("login");
        $this->startSession();
        $this->validateCsrfOrRedirect("login");

        $email = $_POST["txtEmail"] ?? "";
        $senha = $_POST["txtSenha"] ?? "";

        if ($email === "" || $senha === "") {
            $this->flashAndRedirect("warning", "Preencha email e senha para continuar.", "login");
        }

        require_once __DIR__ . "/../model/accountModel.php";
        $accontModel = new AccountModel();

        $resultado = $accontModel->logarUser($email, $senha);

        if ($resultado["ok"]) {
            session_regenerate_id(true);

            $_SESSION["idUser"] = $resultado["user"]["idUser"];
            $_SESSION["nomeUser"] = $resultado["user"]["nomeUser"];
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

            $this->redirectToAction("painelAcesso");
        }
        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "invalid_credentials") {
            $msg = "Email ou senha incorretos. Tente novamente.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao processar login. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "login");
    }

    public function loginForm(): void {
        $this->startSession();
        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/loginPage.php";
    }

    public function painelAcesso(): void {
        $this->requireAuth("login");
        require_once __DIR__ . "/../view/accessPage.php";
    }

    public function logout() {
        $this->startSession();
        SessionHelper::encerrar();

        $this->redirectToAction("home");
    }
}
?>