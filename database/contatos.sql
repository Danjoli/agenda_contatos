CREATE DATABASE agenda_contatos
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE agenda_contatos;

CREATE TABLE pais (
    id_pais INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE contatos(
    email VARCHAR(50) PRIMARY KEY NOT NULL,
    nome VARCHAR(50) NOT NULL,
    sexo ENUM('M', 'F', 'O') DEFAULT 'O',
    nascimento DATE,
    telefone VARCHAR(20),
    id_pais INT,
    imagem VARCHAR(255),
    FULLTEXT KEY buscador (email, nome),

    CONSTRAINT fk_pais FOREIGN KEY (id_pais)
        REFERENCES pais(id_pais)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pais (id_pais, nome) VALUES 
    (1, "México"),
    (2, "Brasil"),
    (3, "Argentina"),
    (4, "Estados Unidos"),
    (5, "Canadá"),
    (6, "Alemanha"),
    (7, "França"),
    (8, "Itália"),
    (9, "Reino Unido"),
    (10, "Espanha"),
    (11, "Portugal"),
    (12, "Japão"),
    (13, "China"),
    (14, "Coreia do Sul"),
    (15, "Índia"),
    (16, "Rússia"),
    (17, "Austrália"),
    (18, "Nova Zelândia"),
    (19, "África do Sul"),
    (20, "Egito"),
    (21, "Marrocos"),
    (22, "Nigéria"),
    (23, "Angola"),
    (24, "Moçambique"),
    (25, "Chile"),
    (26, "Peru"),
    (27, "Colômbia"),
    (28, "Venezuela"),
    (29, "Paraguai"),
    (30, "Uruguai"),
    (31, "Bolívia"),
    (32, "Cuba"),
    (33, "República Dominicana"),
    (34, "Ucrânia"),
    (35, "Turquia");

