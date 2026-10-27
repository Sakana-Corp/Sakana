<?php
require_once __DIR__ . "/baseController.php";
class AccessController extends BaseController
{
    function logado(): void
    {
        $this->requirePost("login");
        $this->startSession();
        $this->validateCsrfOrRedirect("login");

        $email = $_POST["txtEmail"] ?? "";
        $senha = $_POST["txtSenha"] ?? "";

        if ($email === "" || $senha === "") {
            $this->flashAndRedirect("warning", "Preencha email e senha para continuar.", "login");
        }

        $chaveLogin = strtolower(trim($email));
        if (SessionHelper::loginBloqueado($chaveLogin)) {
            $this->flashAndRedirect("warning", "Login bloqueado por 15 minutos após tentativas inválidas.", "login");
        }

        require_once __DIR__ . "/../model/accountModel.php";
        $accountModel = new AccountModel();

        $resultado = $accountModel->logarUser($email, $senha);

        if ($resultado["ok"]) {
            SessionHelper::limparFalhasLogin($chaveLogin);
            session_regenerate_id(true);

            $_SESSION["idUserPrincipal"] = $resultado["user"]["idUser"];
            $_SESSION["nomeUserPrincipal"] = $resultado["user"]["nomeUser"];
            $_SESSION["emailUserPrincipal"] = $resultado["user"]["email"];

            $_SESSION["idUser"] = $resultado["user"]["idUser"];
            $_SESSION["nomeUser"] = $resultado["user"]["nomeUser"];
            $_SESSION["emailUser"] = $resultado["user"]["email"];
            $_SESSION["fotoPerfil"] = $resultado["user"]["fotoPerfil"] ?? "";

            unset($_SESSION["setorAtual"]);

            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $_SESSION["ultimaAtividade"] = time();

            $this->redirectToAction("painelAcesso");
        }
        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "invalid_credentials") {
            $bloqueado = SessionHelper::registrarFalhaLogin($chaveLogin);
            $msg = $bloqueado
                ? "Login bloqueado por 15 minutos após 5 tentativas inválidas."
                : "Email ou senha incorretos. Tente novamente.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao processar login. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "login");
    }

    public function loginForm(): void
    {
        $this->startSession();
        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/loginPage.php";
    }

    public function painelAcesso(): void
    {
        $this->requireAuth("login");

        unset($_SESSION["setorAtual"]);
        unset($_SESSION["idFuncionario"]);
        unset($_SESSION["cargo"]);

        require_once __DIR__ . "/../view/accessPage.php";
    }

    public function logout()
    {
        $this->startSession();
        SessionHelper::encerrar();

        $this->redirectToAction("home");
    }


    public function loginSetorForm(string $setor): void
    {
        $this->requireAuth("login");

        $setoresPermitidos = ["gerencia", "atendimento", "cozinha"];

        if (!in_array($setor, $setoresPermitidos, true)) {
            $this->redirectToAction("painelAcesso");
        }

        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/pages/usersLogin/sectorLogin.php";
    }

    public function entrarSetor(): void
    {
        $this->requirePost("painelAcesso");
        $this->startSession();
        $this->validateCsrfOrRedirect("painelAcesso");

        $setor = $_POST["setor"] ?? "";
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? "";

        $setoresPermitidos = ["gerencia", "atendimento", "cozinha"];

        if (
            !in_array($setor, $setoresPermitidos, true) || $email === "" || $senha === ""
        ) {
            $this->flashAndRedirect(
                "warning",
                "Preencha os dados corretamente.",
                "painelAcesso"
            );
        }

        $chaveLogin = strtolower($email);
        if (SessionHelper::loginBloqueado($chaveLogin)) {
            $this->flashAndRedirect(
                "warning",
                "Login bloqueado por 15 minutos após tentativas inválidas.",
                "loginSetor&setor=" . urlencode($setor)
            );
        }

        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !in_array($setor, $setoresPermitidos, true) ||
            $senha === ""
        ) {
            $this->flashAndRedirect(
                "warning",
                "Informe um email válido e uma senha.",
                "loginSetor&setor=" . urlencode($setor)
            );
        }

        require_once __DIR__ . "/../model/accountModel.php";

        $accountModel = new AccountModel();
        $resultado = $accountModel->logarUser($email, $senha);

        if (($resultado["error"] ?? "") === "database_error") {
            $this->flashAndRedirect(
                "error",
                "Banco de dados indisponível. Tente mais tarde.",
                "loginSetor&setor=" . urlencode($setor)
            );
        }

        if (!$resultado["ok"]) {
            $bloqueado = SessionHelper::registrarFalhaLogin($chaveLogin);
            $this->flashAndRedirect(
                $bloqueado ? "warning" : "error",
                $bloqueado
                    ? "Login bloqueado por 15 minutos após 5 tentativas inválidas."
                    : "Email ou senha incorretos.",
                "loginSetor&setor=" . urlencode($setor)
            );
        }

        $usuario = $resultado["user"];

        if ($setor === "gerencia") {
            $idPrincipal = $_SESSION["idUserPrincipal"] ?? null;

            if ((int) $usuario["idUser"] !== (int) $idPrincipal) {
                $this->flashAndRedirect(
                    "error",
                    "A gerência deve usar o cadastro registrado ao iniciar o simulador.",
                    "loginSetor&setor=gerencia"
                );
            }
        }
        if (
            $setor === "atendimento" &&
            $usuario["nivelAcesso"] !== "garcom"
        ) {
            $this->flashAndRedirect(
                "error",
                "Este usuário não possui acesso ao atendimento.",
                "loginSetor&setor=atendimento"
            );
        }

        if (
            $setor === "cozinha" &&
            $usuario["nivelAcesso"] !== "cozinha"
        ) {
            $this->flashAndRedirect(
                "error",
                "Este usuário não possui acesso à cozinha.",
                "loginSetor&setor=cozinha"
            );
        }

        session_regenerate_id(true);
        SessionHelper::limparFalhasLogin($chaveLogin);

        $_SESSION["idUser"] = $usuario["idUser"];
        $_SESSION["nomeUser"] = $usuario["nomeUser"];
        $_SESSION["emailUser"] = $usuario["email"];
        $_SESSION["fotoPerfil"] = $usuario["fotoPerfil"] ?? "";
        $_SESSION["idFuncionario"] = $usuario["idFuncionario"] ?? null;
        $_SESSION["cargo"] = $usuario["nomeCargo"] ?? null;

        $_SESSION["setorAtual"] = $setor;
        $_SESSION["ultimaAtividade"] = time();

        if ($setor === "gerencia") {
            $this->redirectToAction("logadoGerencia");
        }

        if ($setor === "atendimento") {
            $this->redirectToAction("logadoGerencia&setor=atendimento");
        }

        $this->redirectToAction("logadoGerencia&setor=cozinha");
    }
}
