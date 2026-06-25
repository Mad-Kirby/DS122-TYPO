-- Cria o banco de dados do projeto, caso ele ainda não exista.
-- O charset utf8mb4 permite armazenar acentos e caracteres especiais corretamente.
CREATE DATABASE IF NOT EXISTS ds122_typo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE ds122_typo;

-- Tabela responsável por armazenar os usuários cadastrados no sistema.
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(16) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabela responsável por armazenar cada partida jogada.
CREATE TABLE partidas (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    pontos INT NOT NULL DEFAULT 0,
    jogada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- Relaciona a partida ao usuário que jogou.
    -- Caso o usuário seja removido, suas partidas também são removidas.
    CONSTRAINT fk_partidas_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- Tabela responsável por armazenar as ligas criadas pelos usuários.
CREATE TABLE ligas (
    id_liga INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(32) NOT NULL UNIQUE,
    palavra_chave_hash VARCHAR(255) NOT NULL,
    id_criador INT NOT NULL,
    criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- Relaciona a liga ao usuário que criou essa liga.
    CONSTRAINT fk_ligas_criador
        FOREIGN KEY (id_criador)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- Tabela associativa entre usuários e ligas.
-- Ela permite que vários usuários participem de várias ligas.
CREATE TABLE usuarios_ligas (
    id_usuario INT NOT NULL,
    id_liga INT NOT NULL,
    entrou_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_usuario, id_liga),

    -- Relaciona o vínculo ao usuário.
    CONSTRAINT fk_usuarios_ligas_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,

    -- Relaciona o vínculo à liga.
    CONSTRAINT fk_usuarios_ligas_liga
        FOREIGN KEY (id_liga)
        REFERENCES ligas(id_liga)
        ON DELETE CASCADE
);