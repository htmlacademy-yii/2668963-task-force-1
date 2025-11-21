CREATE DATABASE taskforce 
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE specializations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128) NOT NULL UNIQUE,
    code VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128) NOT NULL UNIQUE,
    code VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(128) NOT NULL,
    birthday DATETIME,
    name VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    location VARCHAR(128) NOT NULL,
    about VARCHAR(128),
    avatar VARCHAR(255),
    phone VARCHAR(128),
    telegram VARCHAR(128),
    specialization_id INT,
    FOREIGN KEY (specialization_id) REFERENCES specializations(id)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    title VARCHAR(128) NOT NULL,
    description VARCHAR(128) NOT NULL,
    category_id INT NOT NULL,
    location VARCHAR(128) NOT NULL,
    budget INT NOT NULL,
    deadline DATETIME NOT NULL,
    status VARCHAR(128),
    customer_id INT NOT NULL,
    performer_id INT,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (performer_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX task_title (title)
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT,
    file VARCHAR(128) NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    text VARCHAR(128) NOT NULL,
    score INT NOT NULL,
    task_id INT NOT NULL,
    customer_id INT NOT NULL,
    performer_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (performer_id) REFERENCES users(id)
);

CREATE TABLE offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    performer_id INT NOT NULL,
    price INT NOT NULL,
    task_id INT NOT NULL,
    status VARCHAR(128) NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (performer_id) REFERENCES users(id)
);