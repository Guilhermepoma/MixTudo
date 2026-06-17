-- Banco de dados do MixTudo Variedades
-- criado por: eu mesmo

drop database if exists projetoloja;
create database projetoloja;
use projetoloja;

-- Tabela de categorias
CREATE TABLE categoria (
  id_categoria   int          primary key auto_increment,
  nome           varchar(100) not null,
  classificacao  varchar(100) not null
);

-- Tabela de produtos (referencia categoria)
create table produtos (
  id           int           primary key auto_increment,
  nome         varchar(100)  not null,
  preco        decimal(10,2) not null,
  marca        varchar(100)  not null,
  id_categoria int,
  foreign key (id_categoria) references categoria(id_categoria)
);

-- Estoque separado pq cada produto tem um estoque
create table estoque (
  id_estoque  int primary key auto_increment,
  quantidade  int not null,
  id_produto  int,
  foreign key (id_produto) references produtos(id)
);

-- Tabela de clientes
CREATE TABLE clientes (
  id_clientes     int          primary key auto_increment,
  nome            varchar(100) not null,
  telefone        varchar(20)  not null,
  endereco        varchar(200) not null,
  forma_pagamento varchar(100) not null,
  usuario         varchar(50)  unique,
  senha           varchar(255)
);

-- Tabela de vendas (fk clientes)
create table vendas (
  id_venda    int           primary key auto_increment,
  data_venda  date          not null,
  total       decimal(10,2) not null,
  id_clientes int,
  foreign key (id_clientes) references clientes(id_clientes)
);

-- Itens da venda (fk venda e fk produto)
create table itens_venda (
  id             int           primary key auto_increment,
  quantidade     int           not null,
  preco_unitario decimal(10,2) not null,
  id_venda       int,
  id_produto     int,
  foreign key (id_venda)   references vendas(id_venda),
  foreign key (id_produto) references produtos(id)
);

-- Caso já tenha o banco criado, rode o ALTER abaixo:
-- ALTER TABLE clientes ADD COLUMN usuario VARCHAR(50) UNIQUE, ADD COLUMN senha VARCHAR(255);

-- View pra facilitar os relatorios
create view vw_relatorios as
select
  (select count(*) from vendas)        as quant_vendas,
  (select count(*) from clientes)      as quant_clientes,
  (select count(*) from produtos)      as quant_produtos,
  (select sum(quantidade) from estoque) as quant_estoque;
