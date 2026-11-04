<?php
    require_once __DIR__ . "/../config/conexao.php";
    class CategoriaModel {
        public function cadastrarCategoria($idDono, $nome, $descricao, $foto) {

            try {
                $conexao = Conexao::getConn();

                // verificação nome existente
                $sqlCheck = "SELECT COUNT(*) AS count FROM categoria WHERE idDono = :idDono AND nomeCategoria = :nome";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->execute([":idDono" => $idDono, ":nome" => $nome]);
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (($result["count"] ?? 0) > 0) {
                    return ["ok" => false, "error" => "name_exists"];
                }

                $sql = "INSERT INTO categoria (idDono, nomeCategoria, descCategoria, imgCategoria) VALUES (:idDono, :nome, :descricao, :foto)";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([":idDono" => $idDono, ":nome" => $nome, ":descricao" => $descricao, ":foto" => $foto]);

    
                return ["ok" => true, "id" => $conexao->lastInsertId()];
            } catch(PDOException $e){
                error_log("Erro PDO ao cadastrar categoria: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar categoria: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }

        public function listarCategorias($idDono) {
            $sql = "SELECT * FROM categoria WHERE idDono = :idDono ORDER BY idCategoria ASC";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute([":idDono" => $idDono]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarCategoriasComProdutos($idDono) {
            $sql = "SELECT c.*
                FROM categoria c
                WHERE EXISTS (
                    SELECT 1
                    FROM produto p
                    WHERE p.idCategoria = c.idCategoria AND p.idDono = :idDonoProduto
                )
                AND c.idDono = :idDonoCategoria
                ORDER BY c.idCategoria ASC";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute([":idDonoProduto" => $idDono, ":idDonoCategoria" => $idDono]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    

    public function excluirCategoria($idDono, $id) {
        try {
            $conexao = Conexao::getConn();

            $sqlCheck = "SELECT COUNT(*) AS count FROM produto WHERE idDono = :idDono AND idCategoria = :id";
            $stmtCheck = $conexao->prepare($sqlCheck);
            $stmtCheck->execute([":idDono" => $idDono, ":id" => $id]);
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (($result["count"] ?? 0) > 0) {
                return ["ok" => false, "error" => "has_products"];
            }

            $sql = "DELETE FROM categoria WHERE idDono = :idDono AND idCategoria = :id";
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
