<?php
    class AccountModel{
        public function cadastrarUser($nome, $email, $senha){
            require_once __DIR__ . "/../config/conexao.php";

            try{
                $conexao = Conexao::getConn();

                $sql = "INSERT INTO LoginUser (nomeUser, email, senha) VALUES (:nome, :email, :senha)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":senha", $senha);
                $stmt->execute();
                return true;
            } catch(PDOException $e){
                return false;
            }
        }
    }
?>
