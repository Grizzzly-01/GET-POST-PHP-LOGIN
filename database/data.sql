CREATE DATABASE login_system;

USE login_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255) DEFAULT NULL
);

INSERT INTO users (username, password) VALUES
('Afdhal', MD5('anjay1')),
('Ersyad', MD5('ketik1')),
('Viola', MD5('Afdhal')),
('Revah', MD5('cihuy')),
('Deo', MD5('kue')),
('Surya', MD5('rokok'));
