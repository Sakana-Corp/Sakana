<?php
    require_once __DIR__ . "/../config/conexao.php";
    class ProdutoModel {
        public function cadastrarProduto($nome, $descricao, $foto, $categoriaId, $valor) {

            try {
                $conexao = Conexao::getConn();

                // verificação nome existente
                $sqlCheck = "SELECT COUNT(*) AS count FROM produto WHERE nomeProduto = :nome";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->bindParam(":nome", $nome);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (($result["count"] ?? 0) > 0) {
                    return ["ok" => false, "error" => "name_exists"];
                }

                $sql = "INSERT INTO produto (idCategoria, nomeProduto, descProduto, imgProduto, valorProduto) VALUES (:categoriaId, :nome, :descricao, :foto, :valor)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":descricao", $descricao);
                $stmt->bindParam(":foto", $foto);
                $stmt->bindParam(":categoriaId", $categoriaId);
                $stmt->bindParam(":valor", $valor);
                $stmt->execute();



                return ["ok" => true];
            } catch(PDOException $e){
                error_log("Erro PDO ao cadastrar produto: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar produto: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }

        public function buscarProduto(int $id): ?array {
            $stmt = Conexao::getConn()->prepare("SELECT * FROM produto WHERE idProduto = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $produto ?: null;
        }

        public function atualizarProduto(int $id, string $nome, string $descricao, int $categoriaId, float $valor, ?string $foto = null): array {
            try {
                $conexao = Conexao::getConn();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM produto WHERE nomeProduto = :nome AND idProduto <> :id");
                $stmt->execute([':nome' => $nome, ':id' => $id]);
                if ((int) $stmt->fetchColumn() > 0) return ['ok' => false, 'error' => 'name_exists'];

                if ($foto === null) {
                    $sql = "UPDATE produto SET nomeProduto = :nome, descProduto = :descricao, idCategoria = :categoria, valorProduto = :valor WHERE idProduto = :id";
                    $params = [':nome' => $nome, ':descricao' => $descricao, ':categoria' => $categoriaId, ':valor' => $valor, ':id' => $id];
                } else {
                    $sql = "UPDATE produto SET nomeProduto = :nome, descProduto = :descricao, idCategoria = :categoria, valorProduto = :valor, imgProduto = :foto WHERE idProduto = :id";
                    $params = [':nome' => $nome, ':descricao' => $descricao, ':categoria' => $categoriaId, ':valor' => $valor, ':foto' => $foto, ':id' => $id];
                }
                $conexao->prepare($sql)->execute($params);
                return ['ok' => true];
            } catch (Throwable $e) {
                error_log('Erro ao atualizar produto: ' . $e->getMessage());
                return ['ok' => false, 'error' => 'database_error'];
            }
        }

        public function listarProdutos() {
            $sql = "SELECT p.idProduto, p.nomeProduto, p.descProduto, p.imgProduto, p.valorProduto, c.nomeCategoria
                FROM produto p
                INNER JOIN categoria c ON p.idCategoria = c.idCategoria
                ORDER BY p.nomeProduto ASC;";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function excluirProduto($id){
            try {
            $conexao = Conexao::getConn();

            $sql = "DELETE FROM produto WHERE idProduto = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return ["ok" => true];
        } catch(PDOException $e) {
            error_log("Erro PDO ao excluir categoria: " . $e->getMessage());
            return ["ok" => false, "error" => "database_error"];
        }
        }
    }
?>
