<?php

require_once __DIR__ . '/../config/conexao.php';

class Mesa{
    private $pdo;

    public function __construct(){
        $this->pdo = Conexao::getConn();
    }

        public function cadastrarMesa($numeroMesa, $numeroLugares){
        try {

            $sql = "INSERT INTO mesa 
                (numeromesa, lugares, status)
                    VALUES 
                (:numeroMesa, :numeroLugares, 'Disponivel')";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_INT);
            $stmt->bindValue(':numeroLugares', $numeroLugares, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }

    public function listarMesas(){
        $sql = "SELECT *
                FROM mesa
                ORDER BY numeromesa ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarMesa($idMesa){
        $sql = "SELECT *
                FROM mesa
                WHERE idMesa = :idMesa";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editarMesa($idMesa, $numeroMesa, $numeroLugares){
        try {

            $sql = "UPDATE mesa
                    SET numeromesa = :numeroMesa,
                        lugares = :numeroLugares
                    WHERE idMesa = :idMesa";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmt->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_INT);
            $stmt->bindValue(':numeroLugares', $numeroLugares, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }


    public function excluirMesa($idMesa){
        try {

            $sql = "DELETE FROM mesa
                    WHERE idMesa = :idMesa";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }

    public function abrirMesa($idMesa){
        $sql = "UPDATE mesa
                SET status = 'Indisponivel'
                WHERE idMesa = :idMesa";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function fecharMesa($idMesa){
        $sql = "UPDATE mesa
                SET status = 'Disponivel'
                WHERE idMesa = :idMesa";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        return $stmt->execute();
    }
}