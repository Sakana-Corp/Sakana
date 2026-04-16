<?php
class AccountService {
    private $accountModel;

    public function __construct(AccountModel $accountModel) {
        $this->accountModel = $accountModel;
    }

    public function cadastrar(array $input): array {
        $nome = trim((string)($input["txtNome"]?? ""));
        $email = trim((string)($input["txtEmail"] ?? ""));
        $senha = (string)($input["txtSenha"] ?? "");
        $confirmaSenha = (string)($input["txtConfirmaSenha"] ?? "");

        if ($nome === "" || $email === "" || $senha === "" || $confirmaSenha === "") {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => "Preencha todos os campos para continuar.",
                "nextAction" => "cadastro"
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => "Email inválido. Verifique e tente novamente.",
                "nextAction" => "cadastro"
            ];
        }

        if ($senha !== $confirmaSenha) {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => "As senhas não conferem. Digite novamente.",
                "nextAction" => "cadastro"
            ];
        }

        $resultado = $this->accountModel->cadastrarUser($nome, $email, $senha);

        if (!empty($resultado["ok"])) {
            return [ 
                "ok" => true,
                "flashType" => "success",
                "flashMessage" => "Cadastro realizado com sucesso!",
                "nextAction" => "login"
            ];
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "email_exists") {
            $msg = "Este email já está cadastrado. Use outro ou faça login."; 
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao cadastrar. Tente novamente.";
        }

        return [
            "ok" => false,
            "flashType" => "error",
            "flashMessage" => $msg,
            "nextAction" => "cadastro"
        ];
    }

    public function logar(array $input): array {
        $email = trim((string)($input["txtEmail"] ?? ""));
        $senha = (string)($input["txtSenha"] ?? "");

        if ($email === "" || $senha === "") {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => "Preencha email e senha para continuar.",
                "nextAction" => "login"
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => "Email inválido. Verifique e tente novamente.",
                "nextAction" => "login"
            ];
        }

        $resultado = $this->accountModel->logarUser($email, $senha);

        if (!empty($resultado["ok"])) {
            return [
                "ok" => true,
                "user" => $resultado["user"]
            ];
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "invalid_credentials") {
            $msg = "Email ou senha incorretos. Tente novamente.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao processar login. Tente novamente.";
        }

        return [ 
            "ok" => false,
            "flashType" => "error",
            "flashMessage" => $msg,
            "nextAction" => "login"
        ];
    }
}
?>