DROP DATABASE IF EXISTS bdSakana;
CREATE DATABASE bdSakana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bdSakana;

CREATE TABLE LoginUser (
  idUser INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nomeUser VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  fotoPerfil VARCHAR(255) NULL,
  nivelAcesso ENUM('gerente','garcom','cozinha') NOT NULL DEFAULT 'gerente'
);

CREATE TABLE cargo (
  idCargo INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nomeCargo VARCHAR(20) NOT NULL UNIQUE,
  salario DECIMAL(7,2) NOT NULL
);

CREATE TABLE Funcionario (
  idFuncionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nomeFunc VARCHAR(100) NOT NULL,
  cpf CHAR(11) NOT NULL UNIQUE,
  cep CHAR(8) NOT NULL,
  logradouro VARCHAR(150) NOT NULL,
  numero VARCHAR(10) NOT NULL,
  complemento VARCHAR(60) NULL,
  bairro VARCHAR(80) NOT NULL,
  cidade VARCHAR(80) NOT NULL,
  uf CHAR(2) NOT NULL,
  telefone CHAR(11) NULL UNIQUE,
  dataCadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  idUser INT NOT NULL UNIQUE,
  idCargo INT NOT NULL,
  CONSTRAINT fkFunc_user FOREIGN KEY (idUser) REFERENCES LoginUser(idUser),
  CONSTRAINT fkFunc_cargo FOREIGN KEY (idCargo) REFERENCES cargo(idCargo)
);

CREATE TABLE categoria (
  idCategoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nomeCategoria VARCHAR(40) NOT NULL UNIQUE,
  descCategoria VARCHAR(255) NOT NULL UNIQUE,
  imgCategoria VARCHAR(255) NULL UNIQUE
);

CREATE TABLE produto (
  idProduto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  idCategoria INT NOT NULL,
  nomeProduto VARCHAR(40) NOT NULL UNIQUE,
  descProduto VARCHAR(255) NOT NULL UNIQUE,
  imgProduto VARCHAR(255) NOT NULL UNIQUE,
  valorProduto DECIMAL(7,2) NOT NULL,
  CONSTRAINT fkProd_Cat FOREIGN KEY (idCategoria) REFERENCES categoria(idCategoria)
);

CREATE TABLE mesa (
  idmesa INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  numeromesa INT NOT NULL UNIQUE,
  lugares INT NOT NULL,
  status ENUM('Disponivel','Indisponivel') NOT NULL DEFAULT 'Disponivel'
);

CREATE TABLE pedido (
  idPedido INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  idMesa INT NOT NULL,
  dataPedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('aberto','em_preparo','concluido','cancelado') NOT NULL DEFAULT 'aberto',
  idGarcom INT NULL,
  CONSTRAINT fkPed_Mesa FOREIGN KEY (idMesa) REFERENCES mesa(idmesa),
  CONSTRAINT fkPed_Garcom FOREIGN KEY (idGarcom) REFERENCES Funcionario(idFuncionario)
);

CREATE TABLE itemPedido (
  idItemPedido INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  idPedido INT NOT NULL,
  idProduto INT NOT NULL,
  quantidade INT NOT NULL,
  valor DECIMAL(7,2) NOT NULL,
  status ENUM('pendente','preparado') NOT NULL DEFAULT 'pendente',
  CONSTRAINT fkItem_Pedido FOREIGN KEY (idPedido) REFERENCES pedido(idPedido) ON DELETE CASCADE,
  CONSTRAINT fkItem_Produto FOREIGN KEY (idProduto) REFERENCES produto(idProduto)
);

INSERT INTO cargo (nomeCargo, salario) VALUES ('Gerente',0), ('Garçom',0), ('Cozinha',0);

INSERT INTO LoginUser (nomeUser,email,senha,nivelAcesso) VALUES
('Gerente Teste','gerente@sakana.com','$2y$10$Ufqn4xpGEgBtGbH/wWpUDOTwINa/Y1LDY8aUjsUIh5bDhC2bOmaqa','gerente'),
('Garçom Teste','garcom@sakana.com','$2y$10$ZlSPJC9rT3l7ulOGSO1YuOnEX4gYilHF.Q75kCc0SQa9nSfMLFEXe','garcom'),
('Cozinha Teste','cozinha@sakana.com','$2y$10$EvTLfNtAvt7q6bS0EdGyD.iqHHFbkqAzGahib2RBmQOA0dAu8foIS','cozinha');

INSERT INTO Funcionario (nomeFunc,cpf,cep,logradouro,numero,bairro,cidade,uf,idUser,idCargo) VALUES
('Gerente Teste','00000000001','80010000','Rua do Teste','1','Centro','Curitiba','PR',1,1),
('Garçom Teste','00000000002','80010000','Rua do Teste','2','Centro','Curitiba','PR',2,2),
('Cozinha Teste','00000000003','80010000','Rua do Teste','3','Centro','Curitiba','PR',3,3);

INSERT INTO categoria (nomeCategoria,descCategoria,imgCategoria) VALUES
('Entradas','Pratos para começar','view/images/categorias/entrada fria.jpg'),
('Bebidas','Sucos, refrigerantes e drinks','view/images/seed/categorias/bebidas.jpg');

INSERT INTO produto (idCategoria,nomeProduto,descProduto,imgProduto,valorProduto) VALUES
(1,'Entrada Fria','Seleção fria preparada pela cozinha','view/images/produtos/entrada fria.jpg',29.90),
(2,'Refrigerante','Refrigerante gelado 350 ml','view/images/produtos/6a91db1a3b1de4.90866077.jpg',7.50);

INSERT INTO mesa (numeromesa,lugares,status) VALUES
(1,2,'Disponivel'),(2,4,'Disponivel'),(3,4,'Disponivel');
