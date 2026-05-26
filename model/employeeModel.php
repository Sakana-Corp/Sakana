<?php
    class EmployeeModel {
        public function cadastrarFunc($nome, $cpf, $endereco, $cargo) {
            require_once __DIR__ . "/../config/conexao.php";

            try {
                $conexao = Conexao::getConn();

                // verificação cpf existente
                $sqlCheck = "SELECT COUNT(*) as count FROM Funcionario WHERE cpf = :cpf";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->bindParam(":cpf", $cpf);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($result["count"] > 0) {
                    return ["ok" => false, "error" => "cpf_exists"];
                }

                $sql = "INSERT INTO Funcionario (nomeFunc, cpf, endereco, cargo) VALUES (:nome, :cpf, :endereco, :cargo)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":cpf", $cpf);
                $stmt->bindParam(":endereco", $endereco);
                $stmt->bindParam(":cargo", $cargo);
                $stmt->execute();

                return ["ok" => true];
            } catch(PDOException $e){
                if ($e->getCode() == 23000) {
                    return ["ok" => false, "error" => "cpf_exists"];
                }
                error_log("Erro PDO ao cadastrar funcionário: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar funcionário: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }
    }
?>
