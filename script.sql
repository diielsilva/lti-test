CREATE TABLE users (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
email VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
created_at DATETIME NOT NULL,
updated_at DATETIME
);

CREATE TABLE categories (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED NOT NULL,
name VARCHAR(100) NOT NULL,
created_at DATETIME NOT NULL,
updated_at DATETIME,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE spents (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED NOT NULL,
category_id INT UNSIGNED NOT NULL,
value DOUBLE(10,2) NOT NULL,
description VARCHAR(100) NOT NULL,
created_at DATETIME NOT NULL,
updated_at DATETIME,
FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);