<?php
    require_once __DIR__ . "/../config/conexao.php";
    class ProdutoModel {
        public function cadastrarProduto($idDono, $nome, $descricao, $foto, $categoriaId, $valor) {

            try {
                $conexao = Conexao::getConn();

                // verificação nome existente
                $sqlCheck = "SELECT COUNT(*) AS count FROM produto WHERE idDono = :idDono AND nomeProduto = :nome";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->execute([":idDono" => $idDono, ":nome" => $nome]);
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (($result["count"] ?? 0) > 0) {
                    return ["ok" => false, "error" => "name_exists"];
                }

                $categoriaStmt = $conexao->prepare(
                    "SELECT COUNT(*) FROM categoria WHERE idCategoria = :idCategoria AND idDono = :idDono"
                );
                $categoriaStmt->execute([":idCategoria" => $categoriaId, ":idDono" => $idDono]);
                if ((int) $categoriaStmt->fetchColumn() !== 1) {
                    return ["ok" => false, "error" => "category_not_found"];
                }

                $sql = "INSERT INTO produto (idDono, idCategoria, nomeProduto, descProduto, imgProduto, valorProduto) VALUES (:idDono, :categoriaId, :nome, :descricao, :foto, :valor)";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([":idDono" => $idDono, ":nome" => $nome, ":descricao" => $descricao, ":foto" => $foto, ":categoriaId" => $categoriaId, ":valor" => $valor]);



                return ["ok" => true];
            } catch(PDOException $e){
                error_log("Erro PDO ao cadastrar produto: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar produto: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }

        public function listarProdutos($idDono) {
            $sql = "SELECT p.idProduto, p.nomeProduto, p.descProduto, p.imgProduto, p.valorProduto, c.nomeCategoria
                FROM produto p
                INNER JOIN categoria c ON p.idCategoria = c.idCategoria AND c.idDono = p.idDono
                WHERE p.idDono = :idDono
                ORDER BY p.nomeProduto ASC;";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute([":idDono" => $idDono]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function excluirProduto($idDono, $id){
            try {
            $conexao = Conexao::getConn();

            $sql = "DELETE FROM produto WHERE idDono = :idDono AND idProduto = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([":idDono" => $idDono, ":id" => $id]);

            return ["ok" => true];
        } catch(PDOException $e) {
            error_log("Erro PDO ao excluir categoria: " . $e->getMessage());
            return ["ok" => false, "error" => "database_error"];
        }
        }
    }
?>
