CREATE DATABASE IF NOT EXISTS bdSakana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bdSakana;

CREATE TABLE IF NOT EXISTS LoginUser (
    idUser INT AUTO_INCREMENT PRIMARY KEY,
    nomeUser VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    fotoPerfil VARCHAR(255) NULL,
    nivelAcesso ENUM('gerente', 'garcom', 'cozinha') NOT NULL DEFAULT 'gerente',
    politicaAceita TINYINT(1) NOT NULL DEFAULT 0,
    politicaAceitaEm DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS imagem (
    nomeImg VARCHAR(100) NOT NULL,
    endpasta VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cargo (
    idCargo INT AUTO_INCREMENT PRIMARY KEY,
    nomeCargo VARCHAR(20) NOT NULL,
    salario DECIMAL(7,2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Funcionario (
    idFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    nomeFunc VARCHAR(100) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    cep CHAR(8) NOT NULL,
    logradouro VARCHAR(150) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(60) NULL,
    bairro VARCHAR(80) NOT NULL,
    cidade VARCHAR(80) NOT NULL,
    uf CHAR(2) NOT NULL,
    telefone CHAR(11) UNIQUE NULL,
    dataCadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idUser INT NOT NULL UNIQUE,
    idCargo INT NOT NULL,
    idDepartamento INT NULL,
    CONSTRAINT fkFunc_user FOREIGN KEY (idUser) REFERENCES LoginUser(idUser),
    CONSTRAINT fkFunc_cargo FOREIGN KEY (idCargo) REFERENCES cargo(idCargo)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categoria (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    nomeCategoria VARCHAR(40) NOT NULL UNIQUE,
    descCategoria VARCHAR(255) NOT NULL UNIQUE,
    imgCategoria VARCHAR(255) UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produto (
    idProduto INT AUTO_INCREMENT PRIMARY KEY,
    idCategoria INT NOT NULL,
    nomeProduto VARCHAR(40) NOT NULL UNIQUE,
    descProduto VARCHAR(255) NOT NULL UNIQUE,
    imgProduto VARCHAR(255) NOT NULL UNIQUE,
    valorProduto DECIMAL(7,2) NOT NULL,
    CONSTRAINT fkProd_Cat FOREIGN KEY (idCategoria) REFERENCES categoria(idCategoria)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mesa (
    idmesa INT AUTO_INCREMENT PRIMARY KEY,
    numeromesa INT NOT NULL UNIQUE,
    lugares INT NOT NULL,
    status ENUM('Disponivel', 'Indisponivel') NOT NULL DEFAULT 'Disponivel'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pedido (
    idPedido INT AUTO_INCREMENT PRIMARY KEY,
    idMesa INT NOT NULL,
    dataPedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('aberto', 'em_preparo', 'concluido', 'cancelado') NOT NULL DEFAULT 'aberto',
    idGarcom INT NULL,
    CONSTRAINT fkPed_Mesa FOREIGN KEY (idMesa) REFERENCES mesa(idmesa),
    CONSTRAINT fkPed_Garcom FOREIGN KEY (idGarcom) REFERENCES Funcionario(idFuncionario)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS itemPedido (
    idItemPedido INT AUTO_INCREMENT PRIMARY KEY,
    idPedido INT NOT NULL,
    idProduto INT NOT NULL,
    quantidade INT NOT NULL,
    valor DECIMAL(7,2) NOT NULL,
    status ENUM('pendente', 'preparado') NOT NULL DEFAULT 'pendente',
    CONSTRAINT fkItem_Pedido FOREIGN KEY (idPedido) REFERENCES pedido(idPedido) ON DELETE CASCADE,
    CONSTRAINT fkItem_Produto FOREIGN KEY (idProduto) REFERENCES produto(idProduto)
) ENGINE=InnoDB;

INSERT INTO cargo (idCargo, nomeCargo, salario) VALUES
    (1, 'Gerente', 0.00),
    (2, 'Garçom', 0.00),
    (3, 'Cozinha', 0.00)
ON DUPLICATE KEY UPDATE nomeCargo = VALUES(nomeCargo);

INSERT INTO LoginUser
    (idUser, nomeUser, email, senha, nivelAcesso, politicaAceita, politicaAceitaEm)
VALUES
    (1, 'Administrador Sakana', 'admin@sakana.tech',
     '$2y$10$z9K1J8Z1kI1laQn.LCpe.O.dU941jBkTK5fCbpUE4AKLJZuwKUU2W',
     'gerente', 1, CURRENT_TIMESTAMP),
    (2, 'Usuario Sakana', 'usuario@sakana.tech',
     '$2y$10$InAst5MwJyuH3q9HocVJFOJ8mABN/H/ZO8lFh6HFGG5akn40R5cFG',
     'garcom', 1, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
    nomeUser = VALUES(nomeUser),
    senha = VALUES(senha),
    nivelAcesso = VALUES(nivelAcesso),
    politicaAceita = VALUES(politicaAceita),
    politicaAceitaEm = VALUES(politicaAceitaEm);

INSERT INTO Funcionario
    (idFuncionario, nomeFunc, cpf, cep, logradouro, numero, bairro, cidade, uf, idUser, idCargo)
VALUES
    (1, 'Administrador Sakana', '52998224725', '07700000', 'Rua Principal', '1', 'Centro', 'Franco da Rocha', 'SP', 1, 1),
    (2, 'Usuario Sakana', '11144477735', '07700000', 'Rua Principal', '2', 'Centro', 'Franco da Rocha', 'SP', 2, 2)
ON DUPLICATE KEY UPDATE
    nomeFunc = VALUES(nomeFunc),
    idCargo = VALUES(idCargo);
