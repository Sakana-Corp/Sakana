-- Faça backup antes de executar. Ajuste @id_dono_legado para o usuário dono dos
-- registros já existentes (os dados antigos não têm como revelar o proprietário).
SET @id_dono_legado = 1;

ALTER TABLE Funcionario ADD COLUMN idDono INT NULL AFTER idUser;
ALTER TABLE categoria ADD COLUMN idDono INT NULL AFTER idCategoria;
ALTER TABLE produto ADD COLUMN idDono INT NULL AFTER idProduto;
ALTER TABLE mesa ADD COLUMN idDono INT NULL AFTER idmesa;

UPDATE Funcionario SET idDono = @id_dono_legado WHERE idDono IS NULL;
UPDATE categoria SET idDono = @id_dono_legado WHERE idDono IS NULL;
UPDATE produto SET idDono = @id_dono_legado WHERE idDono IS NULL;
UPDATE mesa SET idDono = @id_dono_legado WHERE idDono IS NULL;

ALTER TABLE Funcionario MODIFY idDono INT NOT NULL;
ALTER TABLE categoria MODIFY idDono INT NOT NULL;
ALTER TABLE produto MODIFY idDono INT NOT NULL;
ALTER TABLE mesa MODIFY idDono INT NOT NULL;

ALTER TABLE Funcionario ADD CONSTRAINT fkFunc_dono FOREIGN KEY (idDono) REFERENCES LoginUser(idUser);
ALTER TABLE categoria DROP INDEX nomeCategoria, DROP INDEX descCategoria, DROP INDEX imgCategoria,
    ADD UNIQUE KEY uk_categoria_dono_nome (idDono, nomeCategoria),
    ADD UNIQUE KEY uk_categoria_dono_desc (idDono, descCategoria),
    ADD CONSTRAINT fkCategoria_dono FOREIGN KEY (idDono) REFERENCES LoginUser(idUser);
ALTER TABLE produto DROP INDEX nomeProduto, DROP INDEX descProduto, DROP INDEX imgProduto,
    ADD UNIQUE KEY uk_produto_dono_nome (idDono, nomeProduto),
    ADD UNIQUE KEY uk_produto_dono_desc (idDono, descProduto),
    ADD UNIQUE KEY uk_produto_dono_img (idDono, imgProduto),
    ADD CONSTRAINT fkProduto_dono FOREIGN KEY (idDono) REFERENCES LoginUser(idUser);
ALTER TABLE mesa DROP INDEX numeromesa,
    ADD UNIQUE KEY uk_mesa_dono_numero (idDono, numeromesa),
    ADD CONSTRAINT fkMesa_dono FOREIGN KEY (idDono) REFERENCES LoginUser(idUser);
