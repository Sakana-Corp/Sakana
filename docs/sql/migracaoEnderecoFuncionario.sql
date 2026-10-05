use bdSakana;

alter table Funcionario
    add column cep char(8) not null default '' after cpf,
    add column logradouro varchar(150) not null default '' after cep,
    add column numero varchar(10) not null default '' after logradouro,
    add column complemento varchar(60) null after numero,
    add column bairro varchar(80) not null default '' after complemento,
    add column cidade varchar(80) not null default '' after bairro,
    add column uf char(2) not null default '' after cidade;

-- Preserva o endereço antigo (texto livre) no logradouro dos registros existentes.
update Funcionario set logradouro = left(endereco, 150) where logradouro = '';

alter table Funcionario drop column endereco;
