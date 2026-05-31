<?php
    require_once __DIR__ . "/baseController.php";
    class EmployeeController extends BaseController {

        public function cadastrar() {
            $this->requirePost("cadastrarFuncForm");
            $this->startSession();
            $this->validateCsrfOrRedirect("cadastrarFuncForm");

            $nome = $_POST["nomeFunc"] ?? "";
            $cpf = preg_replace("/[^0-9]/", "", $_POST["cpf"] ?? "");
            $endereco = $_POST["endereco"] ?? "";
            $cargo = $_POST["cargo"] ?? "";

            // validações
            if ($nome === "" || $cpf === "" || $endereco === "" || $cargo === "") {
                $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "cadastrarFuncForm");
            }

            require_once __DIR__ . "/../service/inputValidator.php";
            $validator = new InputValidator();
            $validator->notEmpty("cpf", $cpf)->cpf("cpf", $cpf);

            if (!$validator->isValid()) {
                $this->flashAndRedirect("warning", $validator->getFirstError(), "logadoGerencia&page=cadastroFuncionario");
            }

            require_once __DIR__ . "/../model/employeeModel.php";
            $employeeModel = new EmployeeModel();
            $resultado = $employeeModel->cadastrarFunc($nome, $cpf, $endereco, $cargo);

            if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Cadastro realizado com sucesso!", "logadoGerencia&page=cadastroFuncionario");
            }

            $error = $resultado["error"] ?? "unknown_error";

            if ($error === "cpf_exists") {
                $msg = "Este CPF já está cadastrado.";
            }
            elseif ($error === "database_error") {
                $msg = "Banco de dados indisponível. Tente mais tarde.";
            }
            else {
                $msg = "Erro ao Cadastrar. Tente novamente.";
            }

            $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroFuncionario");

        }
    }
?>