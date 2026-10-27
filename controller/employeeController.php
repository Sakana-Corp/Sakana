<?php
require_once __DIR__ . "/baseController.php";
require_once __DIR__ . "/../model/employeeModel.php";
class EmployeeController extends BaseController
{

    public function cadastrar()
    {
        $voltar = "logadoGerencia&page=cadastroFuncionario";

        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireSetor("gerencia", "logadoGerencia");

        $nome = $_POST["nomeFunc"] ?? "";
        $cpf = preg_replace("/[^0-9]/", "", $_POST["cpf"] ?? "");
        $endereco = [
            "cep" => preg_replace("/[^0-9]/", "", $_POST["cep"] ?? ""),
            "logradouro" => trim($_POST["logradouro"] ?? ""),
            "numero" => trim($_POST["numero"] ?? ""),
            "complemento" => trim($_POST["complemento"] ?? ""),
            "bairro" => trim($_POST["bairro"] ?? ""),
            "cidade" => trim($_POST["cidade"] ?? ""),
            "uf" => strtoupper(trim($_POST["uf"] ?? ""))
        ];
        $cargo = $_POST["cargo"] ?? "";
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? "";

        // validações (complemento é opcional)
        if (
            $nome === "" ||
            $cpf === "" ||
            $endereco["cep"] === "" ||
            $endereco["logradouro"] === "" ||
            $endereco["numero"] === "" ||
            $endereco["bairro"] === "" ||
            $endereco["cidade"] === "" ||
            $endereco["uf"] === "" ||
            $cargo === "" ||
            $email === "" ||
            $senha === ""
        ) {
            $this->flashAndRedirect(
                "warning",
                "Preencha todos os campos para continuar.",
                $voltar
            );
        }

        require_once __DIR__ . "/../service/inputValidator.php";
        $validator = new InputValidator();
        $validator->notEmpty("cpf", $cpf)->cpf("cpf", $cpf);
        $validator->cep("cep", $endereco["cep"])->uf("uf", $endereco["uf"]);
        $validator->email("email", $email);

        if (strlen($senha) < 8) {
            $this->flashAndRedirect(
                "warning",
                "A senha deve ter no mínimo 8 caracteres.",
                $voltar
            );
        }

        if (!$validator->isValid()) {
            $this->flashAndRedirect("warning", $validator->getFirstError(), "logadoGerencia&page=cadastroFuncionario");
        }

        require_once __DIR__ . "/../model/employeeModel.php";
        $employeeModel = new EmployeeModel();
        $resultado = $employeeModel->cadastrarFunc(
            $nome,
            $cpf,
            $endereco,
            $cargo,
            $email,
            $senha
        );

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Cadastro realizado com sucesso!", "logadoGerencia&page=cadastroFuncionario");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "cpf_exists") {
            $msg = "Este CPF já está cadastrado.";
        } elseif ($error === "email_exists") {
            $msg = "Este email já está em uso.";
        } elseif ($error === "invalid_cargo") {
            $msg = "Selecione um cargo válido.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroFuncionario");
    }

    public function listarFuncionarios()
    {
        $this->requireSetor("gerencia");
        $employeeModel = new EmployeeModel();
        $listaFuncionarios = $employeeModel->listarTodosFuncionario();

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/consultaFuncionario.php";
    }

    public function atualizar(): void
    {
        $voltar = "logadoGerencia&page=consultaFuncionario";
        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireSetor("gerencia", "logadoGerencia");

        $idFuncionario = filter_input(INPUT_POST, "idFuncionario", FILTER_VALIDATE_INT);
        $nome = trim($_POST["nomeFunc"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $cargo = $_POST["cargo"] ?? "";
        $senha = $_POST["senha"] ?? "";
        $endereco = [
            "cep" => preg_replace("/[^0-9]/", "", $_POST["cep"] ?? ""),
            "logradouro" => trim($_POST["logradouro"] ?? ""),
            "numero" => trim($_POST["numero"] ?? ""),
            "complemento" => trim($_POST["complemento"] ?? ""),
            "bairro" => trim($_POST["bairro"] ?? ""),
            "cidade" => trim($_POST["cidade"] ?? ""),
            "uf" => strtoupper(trim($_POST["uf"] ?? ""))
        ];
        if (!$idFuncionario || $nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || $cargo === "" || $endereco["cep"] === "" || $endereco["logradouro"] === ""
            || $endereco["numero"] === "" || $endereco["bairro"] === ""
            || $endereco["cidade"] === "" || $endereco["uf"] === "") {
            $this->flashAndRedirect("warning", "Preencha os dados corretamente.", "editarFuncionario&id=" . (int) $idFuncionario);
        }
        if ($senha !== "" && strlen($senha) < 8) {
            $this->flashAndRedirect("warning", "A senha deve ter no mínimo 8 caracteres.", "editarFuncionario&id=" . (int) $idFuncionario);
        }

        $employeeModel = new EmployeeModel();
        $resultado = $employeeModel->atualizar((int) $idFuncionario, $nome, $email, $endereco, $cargo, $senha);
        if ($resultado !== "ok") {
            $mensagens = [
                "email_exists" => "Este email já está em uso.",
                "invalid_cargo" => "Selecione um cargo válido.",
                "not_found" => "Funcionário não encontrado.",
                "database_error" => "Não foi possível atualizar o funcionário."
            ];
            $this->flashAndRedirect("error", $mensagens[$resultado] ?? "Erro ao atualizar funcionário.", "editarFuncionario&id=" . (int) $idFuncionario);
        }
        $this->flashAndRedirect("success", "Funcionário atualizado com sucesso.", $voltar);
    }

    public function excluir(): void
    {
        $voltar = "logadoGerencia&page=consultaFuncionario";
        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireSetor("gerencia", "logadoGerencia");
        $idFuncionario = filter_input(INPUT_POST, "idFuncionario", FILTER_VALIDATE_INT);
        if (!$idFuncionario) {
            $this->flashAndRedirect("warning", "Funcionário inválido.", $voltar);
        }
        $resultado = (new EmployeeModel())->excluir((int) $idFuncionario);
        if ($resultado !== "ok") {
            $this->flashAndRedirect("error", "Não foi possível excluir o funcionário.", $voltar);
        }
        $this->flashAndRedirect("success", "Funcionário excluído com sucesso.", $voltar);
    }
}
