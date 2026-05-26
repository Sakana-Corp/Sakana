create database bdSakana;
use bdSakana;

create table LoginUser(
	idUser int (11) auto_increment primary key,
    nomeUser varchar (30) not null,
    email varchar (50) unique not null,
    senha varchar(255) not null
);
create table imagem(
	nomeImg varchar (100) not null,
    endpasta varchar (255) not null
);
/*
create table garcom (
	idGarcom int(11) auto_increment primary key,
    loginGarcom varchar(20) not null unique,
    senhaGarcom varchar(20)
);

create table cozinha (
	idCozinha int(11) auto_increment primary key,
    loginCozinha varchar(20) not null unique,
    senhaCozinha varchar(20)
);

create table atendimento (
	idAtendimento int(11) auto_increment primary key,
    loginAtendimento varchar(20) not null unique,
    senhaAtendimento varchar(20)
);

create table funcionarios (
	
);
*/
select * from imagem;
select * from LoginUser;
