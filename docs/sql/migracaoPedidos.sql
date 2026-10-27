USE bdSakana;

-- Migração única da tabela antiga pedido (itens soltos) para pedido + itemPedido.
-- Execute somente em uma instalação que ainda usa a estrutura antiga.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS itemPedido;
DROP TABLE IF EXISTS pedido_novo;
DROP TABLE IF EXISTS pedido_legado;

RENAME TABLE pedido TO pedido_legado;

CREATE TABLE pedido (
    idPedido INT(11) AUTO_INCREMENT PRIMARY KEY,
    idMesa INT(11) NOT NULL,
    dataPedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('aberto', 'em_preparo', 'concluido', 'cancelado') NOT NULL DEFAULT 'aberto',
    idGarcom INT(11) NULL,
    CONSTRAINT fkPedido_Mesa FOREIGN KEY (idMesa) REFERENCES mesa(idmesa),
    CONSTRAINT fkPedido_Garcom FOREIGN KEY (idGarcom) REFERENCES Funcionario(idFuncionario)
);

CREATE TABLE itemPedido (
    idItemPedido INT(11) AUTO_INCREMENT PRIMARY KEY,
    idPedido INT(11) NOT NULL,
    idProduto INT(11) NOT NULL,
    quantidade INT(5) NOT NULL,
    valor DECIMAL(7,2) NOT NULL,
    status ENUM('pendente', 'preparado') NOT NULL DEFAULT 'pendente',
    CONSTRAINT fkItemPedido_Pedido FOREIGN KEY (idPedido) REFERENCES pedido(idPedido) ON DELETE CASCADE,
    CONSTRAINT fkItemPedido_Produto FOREIGN KEY (idProduto) REFERENCES produto(idProduto)
);

INSERT INTO pedido (idMesa, dataPedido, status)
SELECT idMesa, CURRENT_TIMESTAMP, 'concluido'
FROM pedido_legado
GROUP BY idMesa;

INSERT INTO itemPedido (idPedido, idProduto, quantidade, valor, status)
SELECT pe.idPedido, legado.idProduto, legado.Quantidade, legado.Valor, 'preparado'
FROM pedido_legado legado
INNER JOIN pedido pe ON pe.idMesa = legado.idMesa;

DROP TABLE pedido_legado;
SET FOREIGN_KEY_CHECKS = 1;
