CREATE DATABASE pokemon_database
    DEFAULT CHARACTER SET utf8
    COLLATE utf8_unicode_ci;

USE pokemon_database;

CREATE TABLE pokemon (
    id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    weight DECIMAL(5,2) NOT NULL,
    height DECIMAL(4,2) NOT NULL,
    type ENUM('Fire', 'Water', 'Grass', 'Electric', 'Poison') NOT NULL,
    num_evolution INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE USER pokemon_user@localhost
    IDENTIFIED BY 'pokemon_password';

GRANT ALL
    ON pokemon_database.*
    TO pokemon_user@localhost;

FLUSH PRIVILEGES;