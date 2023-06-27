//sudo mysql -u root
sudo mariadb

CREATE DATABASE personelDB;
USE personelDB;

CREATE TABLE users(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	pass VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL);

INSERT INTO users(name, pass, email)
VALUES
('name1', '12345678', 'name1@gmail.com');

CREATE TABLE personels(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    citizenNo VARCHAR(11) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    phone VARCHAR(25) NOT NULL,
    email VARCHAR(100) NOT NULL,
    birthDate DATE NOT NULL,
    employmentDate DATE NOT NULL,
    dismissalDate DATE,
    sgkType VARCHAR(50) NOT NULL,
    department VARCHAR(100) NOT NULL,
	duty TEXT NOT NULL,
	iban VARCHAR(50) NOT NULL);

INSERT INTO personels(name, surname, citizenNo, gender, phone, email, birthDate, employmentDate,
sgkType, department, duty, iban)
VALUES
('name1', 'surname1', '11111111111', 'Erkek', '01111111111', 'name1@gmail.com', '1968-05-04', '2023-01-01',
'1', 'ofis', 'developer', 'TR11 1111 1111 1111 1111 1111 11');
