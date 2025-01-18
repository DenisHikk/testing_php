CREATE DATABASE IF NOT EXISTS rootdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rootdb;
CREATE TABLE IF NOT EXISTS products (
    id INT,
    name VARCHAR(255) NOT NULL,
    name_trans VARCHAR(255) NOT NULL,
    price INT NOT NULL,
    small_text VARCHAR(30),
    big_text TEXT NOT NULL,
    user_id INT NOT NULL,
    primary key(id, user_id)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
set names utf8mb4;