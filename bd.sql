CREATE DATABASE usuarios;
USE usuarios;


CREATE TABLE usuarios (
id_usuario INT PRIMARY KEY AUTO_INCREMENT,
nome VARCHAR(255) NOT NULL,
cpf VARCHAR(14) UNIQUE NOT NULL,
senha VARCHAR(255) NOT NULL,
email VARCHAR(255) UNIQUE NOT NULL
);


CREATE TABLE contas (
id_conta INT PRIMARY KEY AUTO_INCREMENT,
id_usuario INT NOT NULL,
nome_conta VARCHAR(100) NOT NULL,
tipo_conta VARCHAR(50) NOT NULL,
saldo DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);


CREATE TABLE categorias (
id_categoria INT PRIMARY KEY AUTO_INCREMENT,
nome_categoria VARCHAR(100) UNIQUE NOT NULL,
tipo_categoria ENUM('despesa', 'receita') NOT NULL
);


CREATE TABLE transacoes (
id_transacao INT PRIMARY KEY AUTO_INCREMENT,
id_usuario INT NOT NULL,
id_conta INT NOT NULL,
id_categoria INT NOT NULL,
tipo_transacao ENUM('despesa', 'receita') NOT NULL,
data_transacao DATE NOT NULL,
descricao VARCHAR(255),
valor DECIMAL(10, 2) NOT NULL,
data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
FOREIGN KEY (id_conta) REFERENCES contas(id_conta),
FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);