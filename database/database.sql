CREATE DATABASE IF NOT EXISTS sdc310_store
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sdc310_store;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;


CREATE TABLE users (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    is_admin TINYINT(1) NOT NULL DEFAULT 0,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP

);


CREATE TABLE products (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,

    description TEXT NOT NULL,

    price DECIMAL(10,2) NOT NULL,

    stock INT UNSIGNED NOT NULL DEFAULT 0,

    active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP

);


CREATE TABLE orders (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL,

    total DECIMAL(10,2) NOT NULL,

    shipping_address TEXT NOT NULL,

    status VARCHAR(30)
        NOT NULL DEFAULT 'Pending',

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)

);


CREATE TABLE order_items (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    order_id INT UNSIGNED NOT NULL,

    product_id INT UNSIGNED NOT NULL,

    quantity INT UNSIGNED NOT NULL,

    price DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_items_order
        FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)

);


INSERT INTO users
(
    name,
    email,
    password,
    is_admin
)
VALUES
(
    'Store Administrator',
    'admin@example.com',

    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC0h1cZrNQwQ1X6aVY5W',

    1
);


INSERT INTO products
(
    name,
    description,
    price,
    stock,
    active
)
VALUES

(
    'Classic Notebook',
    'A simple lined notebook for school and project notes.',
    8.99,
    25,
    1
),

(
    'Developer Mug',
    'A ceramic mug for long coding sessions.',
    14.99,
    20,
    1
),

(
    'USB-C Cable',
    'A durable USB-C charging and data cable.',
    11.49,
    30,
    1
),

(
    'Desk Organizer',
    'A compact organizer for a clean development workspace.',
    19.99,
    12,
    1
),

(
    'Laptop Stand',
    'An adjustable stand for a comfortable workstation.',
    29.99,
    10,
    1
),

(
    'Wireless Mouse',
    'A simple wireless mouse for everyday computer use.',
    24.99,
    15,
    1
);
