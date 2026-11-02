<?php

require_once __DIR__ . '/../config/conexao.php';

class PedidoModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConn();
    }

    public function criarPedido(int $idMesa, array $itens, ?int $idGarcom = null): bool
    {
        try {
            $this->pdo->beginTransaction();
            $pedido = $this->pdo->prepare(
                "INSERT INTO pedido (idMesa, dataPedido, status, idGarcom)
                 VALUES (:idMesa, NOW(), 'aberto', :idGarcom)"
            );
            $pedido->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $pedido->bindValue(':idGarcom', $idGarcom, $idGarcom === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $pedido->execute();
            $idPedido = (int) $this->pdo->lastInsertId();

            $item = $this->pdo->prepare(
                "INSERT INTO itemPedido (idPedido, idProduto, quantidade, valor, status)
                 VALUES (:idPedido, :idProduto, :quantidade, :valor, 'pendente')"
            );
            foreach ($itens as $produto) {
                $item->execute([
                    ':idPedido' => $idPedido,
                    ':idProduto' => $produto['idProduto'],
                    ':quantidade' => $produto['quantidade'],
                    ':valor' => $produto['valor']
                ]);
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Erro ao criar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function listarPedidosPorMesa($idMesa)
    {
        $sql = "SELECT pe.idPedido,
                       pe.dataPedido,
                       pe.status AS statusPedido,
                       pe.idGarcom,
                       ip.idItemPedido,
                       ip.idProduto,
                       p.nomeProduto,
                       p.imgProduto,
                       ip.quantidade AS Quantidade,
                       ip.valor AS Valor,
                       ip.status AS statusItem
                FROM pedido pe
                INNER JOIN itemPedido ip ON ip.idPedido = pe.idPedido
                INNER JOIN produto p ON p.idProduto = ip.idProduto
                WHERE pe.idMesa = :idMesa
                ORDER BY pe.idPedido DESC, ip.idItemPedido ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarItensParaCozinha(): array
    {
        $stmt = $this->pdo->query(
            "SELECT ip.idItemPedido, ip.quantidade, ip.valor, ip.status AS statusItem,
                    pe.idPedido, pe.dataPedido, pe.status AS statusPedido,
                    m.numeromesa, p.nomeProduto
             FROM itemPedido ip
             INNER JOIN pedido pe ON pe.idPedido = ip.idPedido
             INNER JOIN mesa m ON m.idmesa = pe.idMesa
             INNER JOIN produto p ON p.idProduto = ip.idProduto
             WHERE ip.status = 'pendente' AND pe.status <> 'cancelado'
             ORDER BY pe.dataPedido ASC, ip.idItemPedido ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarItemPreparado(int $idItemPedido): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE itemPedido SET status = 'preparado'
             WHERE idItemPedido = :idItemPedido AND status = 'pendente'"
        );
        $stmt->execute([':idItemPedido' => $idItemPedido]);
        if ($stmt->rowCount() !== 1) {
            return false;
        }

        $pedidoStmt = $this->pdo->prepare(
            "UPDATE pedido pe
             SET pe.status = CASE
                 WHEN NOT EXISTS (
                     SELECT 1 FROM itemPedido ip
                     WHERE ip.idPedido = pe.idPedido AND ip.status = 'pendente'
                 ) THEN 'concluido'
                 ELSE 'em_preparo'
             END
             WHERE pe.idPedido = (
                 SELECT idPedido FROM itemPedido WHERE idItemPedido = :idItemPedido
             )"
        );
        $pedidoStmt->execute([':idItemPedido' => $idItemPedido]);
        return true;
    }

    public function excluirPedidosPorMesa($idMesa)
    {
        try {
            $sql = "DELETE ip, pe
                    FROM pedido pe
                    LEFT JOIN itemPedido ip ON ip.idPedido = pe.idPedido
                    WHERE pe.idMesa = :idMesa";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }
}
