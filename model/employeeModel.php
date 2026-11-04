<?php
    require_once __DIR__ . "/../config/conexao.php";
    class EmployeeModel {
        public function cadastrarFunc(
            $nome,
            $cpf,
            $endereco,
            $cargo,
            $email,
            $senha,
            $idDono
        ) {
            $conexao = null;

        try {
            $conexao = Conexao::getConn();

            $nivelAcesso = match ($cargo) {
                "Garçom" => "garcom",
                "Cozinha" => "cozinha",
                default => null
            };

            if ($nivelAcesso === null) {
                return [
                    "ok" => false,
                    "error" => "invalid_cargo"
                ];
            }

            $sqlCpf = "SELECT COUNT(*)
                    FROM Funcionario
                    WHERE cpf = :cpf";

            $stmtCpf = $conexao->prepare($sqlCpf);
            $stmtCpf->execute([
                ":cpf" => $cpf
            ]);

            if ((int) $stmtCpf->fetchColumn() > 0) {
                return [
                    "ok" => false,
                    "error" => "cpf_exists"
                ];
            }

            $sqlEmail = "SELECT COUNT(*)
                        FROM LoginUser
                        WHERE email = :email";

            $stmtEmail = $conexao->prepare($sqlEmail);
            $stmtEmail->execute([
                ":email" => $email
            ]);

            if ((int) $stmtEmail->fetchColumn() > 0) {
                return [
                    "ok" => false,
                    "error" => "email_exists"
                ];
            }

            $sqlCargo = "SELECT idCargo
                        FROM cargo
                        WHERE nomeCargo = :cargo
                        LIMIT 1";

            $stmtCargo = $conexao->prepare($sqlCargo);
            $stmtCargo->execute([
                ":cargo" => $cargo
            ]);

            $idCargo = $stmtCargo->fetchColumn();

            if (!$idCargo) {
                return [
                    "ok" => false,
                    "error" => "cargo_not_found"
                ];
            }

            $conexao->beginTransaction();

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sqlUser = "INSERT INTO LoginUser
                        (nomeUser, email, senha, nivelAcesso)
                        VALUES (:nome, :email, :senha, :nivelAcesso)";

            $stmtUser = $conexao->prepare($sqlUser);
            $stmtUser->execute([
                ":nome" => $nome,
                ":email" => $email,
                ":senha" => $senhaHash,
                ":nivelAcesso" => $nivelAcesso
            ]);

            $idUser = $conexao->lastInsertId();

            $sqlFuncionario = "INSERT INTO Funcionario
                            (nomeFunc, cpf, cep, logradouro, numero, complemento,
                             bairro, cidade, uf, idUser, idDono, idCargo)
                            VALUES (:nome, :cpf, :cep, :logradouro, :numero, :complemento,
                                    :bairro, :cidade, :uf, :idUser, :idDono, :idCargo)";

            $stmtFuncionario = $conexao->prepare($sqlFuncionario);
            $stmtFuncionario->execute([
                ":nome" => $nome,
                ":cpf" => $cpf,
                ":cep" => $endereco["cep"],
                ":logradouro" => $endereco["logradouro"],
                ":numero" => $endereco["numero"],
                ":complemento" => $endereco["complemento"] !== "" ? $endereco["complemento"] : null,
                ":bairro" => $endereco["bairro"],
                ":cidade" => $endereco["cidade"],
                ":uf" => $endereco["uf"],
                ":idUser" => $idUser,
                ":idDono" => $idDono,
                ":idCargo" => $idCargo
            ]);

            $conexao->commit();

            return [
                "ok" => true
            ];
        } catch (PDOException $e) {
            if ($conexao !== null && $conexao->inTransaction()) {
                $conexao->rollBack();
            }

            if ($e->getCode() === "23000") {
                return [
                    "ok" => false,
                    "error" => "duplicate_data"
                ];
            }

            error_log(
                "Erro PDO ao cadastrar funcionário: " . $e->getMessage()
            );

            return [
                "ok" => false,
                "error" => "database_error"
            ];
        } catch (Throwable $e) {
            if ($conexao !== null && $conexao->inTransaction()) {
                $conexao->rollBack();
            }

            error_log(
                "Erro inesperado ao cadastrar funcionário: " . $e->getMessage()
            );

            return [
                "ok" => false,
                "error" => "database_error"
            ];
        }
    }

        public function listarTodosFuncionario(int $idDono) {
            $sql = "SELECT f.idFuncionario,
                   f.idUser,
                   f.nomeFunc,
                   f.cpf,
                   f.cep,
                   f.logradouro,
                   f.numero,
                   f.complemento,
                   f.bairro,
                   f.cidade,
                   f.uf,
                   c.nomeCargo AS cargo
            FROM Funcionario f
            LEFT JOIN cargo c ON f.idCargo = c.idCargo
            INNER JOIN LoginUser u ON u.idUser = f.idUser
            WHERE f.idDono = :idDono
            ORDER BY f.idFuncionario ASC";

            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute([":idDono" => $idDono]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function buscarPorId(int $idFuncionario, ?int $idDono = null): ?array {
            $sql = "SELECT f.idFuncionario, f.idUser, f.nomeFunc, f.cpf, f.cep,
                        f.logradouro, f.numero, f.complemento, f.bairro,
                        f.cidade, f.uf, c.nomeCargo AS cargo, u.email
                 FROM Funcionario f
                 INNER JOIN LoginUser u ON u.idUser = f.idUser
                 LEFT JOIN cargo c ON c.idCargo = f.idCargo
                 WHERE f.idFuncionario = :idFuncionario AND f.idDono = :idDono
                 LIMIT 1";
            $sqlParams = [":idFuncionario" => $idFuncionario];
            $donoSql = "";
            if ($idDono !== null) {
                $donoSql = " AND f.idDono = :idDono";
                $sqlParams[":idDono"] = $idDono;
            }
            $stmt = Conexao::getConn()->prepare(str_replace(" AND f.idDono = :idDono", $donoSql, $sql));
            $stmt->execute($sqlParams);
            $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $funcionario ?: null;
        }

        public function atualizar(int $idFuncionario, string $nome, string $email, array $endereco, string $cargo, ?string $senha = null, ?int $idDono = null): string {
            $conexao = Conexao::getConn();
            $funcionario = $this->buscarPorId($idFuncionario, $idDono);
            if ($funcionario === null) {
                return "not_found";
            }

            $idCargoStmt = $conexao->prepare("SELECT idCargo FROM cargo WHERE nomeCargo = :cargo LIMIT 1");
            $idCargoStmt->execute([":cargo" => $cargo]);
            $idCargo = $idCargoStmt->fetchColumn();
            if (!$idCargo) {
                return "invalid_cargo";
            }
            $nivelAcesso = match ($cargo) {
                "Garçom" => "garcom",
                "Cozinha" => "cozinha",
                default => null
            };
            if ($nivelAcesso === null) {
                return "invalid_cargo";
            }

            $emailStmt = $conexao->prepare(
                "SELECT COUNT(*) FROM LoginUser WHERE email = :email AND idUser <> :idUser"
            );
            $emailStmt->execute([":email" => $email, ":idUser" => $funcionario["idUser"]]);
            if ((int) $emailStmt->fetchColumn() > 0) {
                return "email_exists";
            }

            try {
                $conexao->beginTransaction();
                $userSql = "UPDATE LoginUser SET nomeUser = :nome, email = :email, nivelAcesso = :nivelAcesso";
                $userParams = [
                    ":nome" => $nome,
                    ":email" => $email,
                    ":nivelAcesso" => $nivelAcesso,
                    ":idUser" => $funcionario["idUser"]
                ];
                if ($senha !== null && $senha !== "") {
                    $userSql .= ", senha = :senha";
                    $userParams[":senha"] = password_hash($senha, PASSWORD_DEFAULT);
                }
                $userSql .= " WHERE idUser = :idUser";
                $conexao->prepare($userSql)->execute($userParams);

                $conexao->prepare(
                    "UPDATE Funcionario
                     SET nomeFunc = :nome, cep = :cep, logradouro = :logradouro,
                         numero = :numero, complemento = :complemento, bairro = :bairro,
                         cidade = :cidade, uf = :uf, idCargo = :idCargo
                     WHERE idFuncionario = :idFuncionario"
                )->execute([
                    ":nome" => $nome,
                    ":cep" => $endereco["cep"],
                    ":logradouro" => $endereco["logradouro"],
                    ":numero" => $endereco["numero"],
                    ":complemento" => $endereco["complemento"] !== "" ? $endereco["complemento"] : null,
                    ":bairro" => $endereco["bairro"],
                    ":cidade" => $endereco["cidade"],
                    ":uf" => $endereco["uf"],
                    ":idCargo" => $idCargo,
                    ":idFuncionario" => $idFuncionario
                ]);
                $conexao->commit();
                return "ok";
            } catch (Throwable $e) {
                if ($conexao->inTransaction()) {
                    $conexao->rollBack();
                }
                error_log("Erro ao atualizar funcionário: " . $e->getMessage());
                return "database_error";
            }
        }

        public function excluir(int $idFuncionario, ?int $idDono = null): string {
            $conexao = Conexao::getConn();
            $funcionario = $this->buscarPorId($idFuncionario, $idDono);
            if ($funcionario === null) {
                return "not_found";
            }

            try {
                $conexao->beginTransaction();
                $conexao->prepare("DELETE FROM Funcionario WHERE idFuncionario = :idFuncionario")
                    ->execute([":idFuncionario" => $idFuncionario]);
                $conexao->prepare("DELETE FROM LoginUser WHERE idUser = :idUser")
                    ->execute([":idUser" => $funcionario["idUser"]]);
                $conexao->commit();
                return "ok";
            } catch (Throwable $e) {
                if ($conexao->inTransaction()) {
                    $conexao->rollBack();
                }
                error_log("Erro ao excluir funcionário: " . $e->getMessage());
                return "database_error";
            }
        }
    }
?>
