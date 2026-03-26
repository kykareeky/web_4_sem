-- создание базы данных
CREATE DATABASE IF NOT EXISTS friends CHARACTER SET utf8 COLLATE utf8_general_ci;
USE friends;

-- создание таблицы contacts
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    gender ENUM('м', 'ж') DEFAULT 'м',
    birth_date DATE,
    phone VARCHAR(50),
    address VARCHAR(255),
    email VARCHAR(100),
    comment TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- пример тестовой записи
INSERT INTO contacts (last_name, first_name, middle_name, gender, birth_date, phone, address, email, comment) 
VALUES ('Иванов', 'Иван', 'Иванович', 'м', '1990-05-15', '+7-999-123-45-67', 'г. Москва, ул. Ленина, д.1', 'ivan@example.com', 'тестовый контакт');