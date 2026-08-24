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
            $_SESSION["emailUser"] = $resultado["user"]["email"];
            $_SESSION["fotoPerfil"] = $resultado["user"]["fotoPerfil"] ?? "";
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

    public function recuperarSenhaForm(): void {
        $this->startSession();
        SessionHelper::gerarToken();
        require_once __DIR__ . "/../view/senhaPage.php";
    }

    public function atualizarSenha(): void {
        $this->requirePost("recuperarSenha");
        $this->startSession();
        $this->validateCsrfOrRedirect("recuperarSenha");

        $email = trim($_POST["email"] ?? "");
        $novaSenha = trim($_POST["novaSenha"] ?? "");

        if ($email === "" || $novaSenha === "") {
            $this->flashAndRedirect("warning", "Preencha email e nova senha para continuar.", "recuperarSenha");
        }

        if (strlen($novaSenha) < 8) {
            $this->flashAndRedirect("warning", "A nova senha deve ter pelo menos 8 caracteres.", "recuperarSenha");
        }

        require_once __DIR__ . "/../model/accountRepository.php";
        $accountRepository = new AccountRepository();

        try {
            // Verificar se email existe
            if (!$accountRepository->emailExists($email)) {
                $this->flashAndRedirect("error", "Email não encontrado no sistema.", "recuperarSenha");
            }

            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            if ($accountRepository->updatePassword($email, $senhaHash)) {
                $this->flashAndRedirect("success", "Senha alterada com sucesso! Faça login com a nova senha.", "login");
            } else {
                $this->flashAndRedirect("error", "Erro ao alterar a senha. Tente novamente.", "recuperarSenha");
            }
        } catch (RuntimeException $e) {
            $this->flashAndRedirect("error", "Erro ao processar a alteração. Tente novamente mais tarde.", "recuperarSenha");
        }
    }
}
?>