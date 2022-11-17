CREATE DATABASE taskforce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

/* Города */
CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    latitude DECIMAL(9, 7) NOT NULL,
    longtitude DECIMAL(9, 7) NOT NULL
);

/* Пользователи */
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    birthday DATETIME,
    phone VARCHAR(100),
    telegram VARCHAR(100),
    information CHAR,
    specializations VARCHAR(255),
    avatar VARCHAR(255),
    register_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    role ENUM ('customer', 'executor') NOT NULL,
    succesful_tasks INT,
    failed_tasks INT,
    city_id INT,
    FOREIGN KEY (city_id) REFERENCES cities(id)
);

/* Категории заданий */
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    alias VARCHAR(100) NOT NULL
);

/* Задания */
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description CHAR,
    category_id INT NOT NULL,
    city_id INT,
    latitude DECIMAL(9, 7),
    longtitude DECIMAL(9, 7),
    budget VARCHAR(100),
    deadline DATETIME,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'new' NOT NULL,
    executor_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (executor_id) REFERENCES users(id),
    FOREIGN KEY (city_id) REFERENCES cities(id)
);

/* Файлы */
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(255) NOT NULL UNIQUE,
    task_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

/* Отклики */
CREATE TABLE responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    executor_id INT NOT NULL,
    task_id INT NOT NULL,
    comment CHAR,
    price INT,
    FOREIGN KEY (executor_id) REFERENCES users(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

/* Отзывы */
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    executor_id INT NOT NULL,
    task_id INT NOT NULL,
    comment CHAR,
    grade INT,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (executor_id) REFERENCES users(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

CREATE FULLTEXT INDEX task_title_search ON tasks(title);
CREATE FULLTEXT INDEX task_description_search ON tasks(description);
