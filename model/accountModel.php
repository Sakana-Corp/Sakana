<?php
    class AccountModel{
        public function cadastrarUser($nome, $email, $senha){
            require_once __DIR__ . "/../config/conexao.php";

            try{
                $conexao = Conexao::getConn();
                
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = "INSERT INTO LoginUser (nomeUser, email, senha) VALUES (:nome, :email, :senha)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":senha", $senhaHash);
                $stmt->execute();
                return true;
            } catch(PDOException $e){
                return false;
            }
        }

        public function logarUser($email, $senha){
            require_once __DIR__ . "/../config/conexao.php";

            try{
                $conexao = Conexao::getConn();

                $sql = "SELECT * FROM LoginUser WHERE email = :email LIMIT 1";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":email", $email);
                $stmt->execute();

                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario && password_verify($senha, $usuario["senha"])){
                    return $usuario;
                }
                return false;

            } catch(PDOException $e){
                return false;
            }
        }
    }
?>
