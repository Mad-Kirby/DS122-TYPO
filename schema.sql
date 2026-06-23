CREATE DATABASE IF NOT EXISTS ds122_typo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE ds122_typo;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(16) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE partidas (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    pontos INT NOT NULL DEFAULT 0,
    jogada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_partidas_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

CREATE TABLE ligas (
    id_liga INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(32) NOT NULL UNIQUE,
    palavra_chave_hash VARCHAR(255) NOT NULL,
    id_criador INT NOT NULL,
    criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_ligas_criador
        FOREIGN KEY (id_criador)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

CREATE TABLE usuarios_ligas (
    id_usuario INT NOT NULL,
    id_liga INT NOT NULL,
    entrou_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_usuario, id_liga),

    CONSTRAINT fk_usuarios_ligas_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,

    CONSTRAINT fk_usuarios_ligas_liga
        FOREIGN KEY (id_liga)
        REFERENCES ligas(id_liga)
        ON DELETE CASCADE
);