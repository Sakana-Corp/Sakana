create database bdSakana;
use bdSakana;

create table LoginUser(
	idUser int (11) auto_increment primary key,
    nomeUser varchar (30) not null,
    email varchar (50) unique not null,
    senha varchar(16) unique not null,
    contaImg varchar (255) unique
);

create table cargo (
	idCargo int(11) auto_increment primary key,
    nomeCargo varchar(20) not null,
    salario decimal (7,2) not null
);

create table funcionarios (
	idFuncionario int (11) auto_increment primary key,
    nome varchar(30) not null,
    cpf char(11) unique not null,
    telefone char(11) unique not null,
    idUser int(11) not null unique,
		constraint fkFunc_user foreign key (idUser) references LoginUser(idUser),
    idCargo int(11) not null,
		constraint fkFunc_cargo foreign key (idCargo) references cargo(idCargo),
    idDepartamento int(11) not null
);
    
create table categoria(
idCategoria int (11) auto_increment primary key,
nomeCategoria varchar(40) not null
);


create table produto (
idProduto int (11) auto_increment primary key,
idCategoria int (11) not null,
nomeProduto varchar(40) not null unique,
Preco decimal (7,2) not null,
imgProduto varchar (255) unique,
DescProduto varchar (255) unique,
	constraint fkProd_Cat foreign key (idCategoria) references categoria(idCategoria)
);

create table mesa (
idMesa int (11) auto_increment primary key,
horarioIni datetime not null,
horarioFim datetime not null,
ValorTotal decimal (7,2) not null
);
	

create table pedido (
idPedido  int (11) auto_increment primary key,
idProduto int (11) not null,
	constraint fkPed_Prod foreign key (idProduto) references produto(idProduto),
idMesa int (11) not null,
	constraint fkPed_Mesa foreign key (idMesa) references mesa(idMesa),
Quantidade int(5) not null,
Valor decimal (7,2) not null
);

