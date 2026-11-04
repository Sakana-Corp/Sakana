<?php

require_once __DIR__ . '/../config/conexao.php';

class Mesa{
    private $pdo;

    public function __construct(){
        $this->pdo = Conexao::getConn();
    }

        public function cadastrarMesa($idDono, $numeroMesa, $numeroLugares){
        try {

            $sql = "INSERT INTO mesa 
                (idDono, numeromesa, lugares, status)
                    VALUES 
                (:idDono, :numeroMesa, :numeroLugares, 'Disponivel')";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idDono', $idDono, PDO::PARAM_INT);
            $stmt->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_INT);
            $stmt->bindValue(':numeroLugares', $numeroLugares, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }

    public function listarMesas($idDono){
        $sql = "SELECT *
                FROM mesa WHERE idDono = :idDono
                ORDER BY numeromesa ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idDono' => $idDono]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarMesa($idDono, $idMesa){
        $sql = "SELECT *
                FROM mesa
                WHERE idDono = :idDono AND idMesa = :idMesa";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idDono', $idDono, PDO::PARAM_INT);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editarMesa($idDono, $idMesa, $numeroMesa, $numeroLugares){
        try {

            $sql = "UPDATE mesa
                    SET numeromesa = :numeroMesa,
                        lugares = :numeroLugares
                    WHERE idDono = :idDono AND idMesa = :idMesa";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idDono', $idDono, PDO::PARAM_INT);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmt->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_INT);
            $stmt->bindValue(':numeroLugares', $numeroLugares, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }


    public function excluirMesa($idDono, $idMesa){
        try {

            $this->pdo->beginTransaction();

            // Remove primeiro os pedidos vinculados à mesa, já que a
            // tabela pedido tem uma FK para mesa (fkPed_Mesa).
            $sqlPedidos = "DELETE FROM pedido WHERE idMesa = :idMesa";
            $stmtPedidos = $this->pdo->prepare($sqlPedidos);
            $stmtPedidos->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmtPedidos->execute();

            $sql = "DELETE FROM mesa
                    WHERE idDono = :idDono AND idMesa = :idMesa";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idDono', $idDono, PDO::PARAM_INT);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return false;
        }
    }

    public function abrirMesa($idDono, $idMesa){
        $sql = "UPDATE mesa
                SET status = 'Indisponivel'
                WHERE idDono = :idDono AND idMesa = :idMesa";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idDono', $idDono, PDO::PARAM_INT);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function fecharMesa($idDono, $idMesa){
        $sql = "UPDATE mesa
                SET status = 'Disponivel'
                WHERE idDono = :idDono AND idMesa = :idMesa";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idDono', $idDono, PDO::PARAM_INT);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        return $stmt->execute();
    }
}