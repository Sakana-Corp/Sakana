<?php
class AccountService {
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository) {
        $this->accountRepository = $accountRepository;
    }

    private function hashPassword(string $senha): string {
        return password_hash($senha, PASSWORD_DEFAULT);
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

        try {
            if ($this->accountRepository->emailExists($email)) {
                return [
                    "ok" => false,
                    "flashType" => "warning",
                    "flashMessage" => "Este email já está cadastrado. use outro ou faça login.",
                    "nextAction" => "cadastro"
                ];
            }

            $senhaHash = $this->hashPassword($senha);

            $this->accountRepository->create($nome, $email, $senhaHash);

            return [
                "ok" => true,
                "flashType" => "success",
                "flashMessage" => "Cadastro realizado com sucesso!",
                "nextAction" => "login"
            ];
        } catch (RuntimeException $e) {
            $error = $e->getMessage();

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

        try {
            $usuario = $this->accountRepository->findByEmail($email);

            if (!$usuario || !password_verify($senha, $usuario["senha"])) {
                return [
                    "ok" => false,
                    "flashType" => "error",
                    "flashMessage" => "Email ou senha incorretos. Tente novamente.",
                    "nextAction" => "login"
                ];
            }

            unset($usuario["senha"]);

            return [
                "ok" => true,
                "user" => $usuario
            ];
        } catch (RuntimeException $e) {
            return [
                "ok" => false,
                "flashType" => "error",
                "flashMessage" => "Banco de dados indisponível. Tente mais tarde.",
                "nextAction" => "login"
            ];
        }
    }
}
?>