CREATE DATABASE IF NOT EXISTS shopdb;
USE shopdb;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@shop.com', '0192023a7bbd73250516f069df18b500', 'admin'),
('John Doe', 'user@shop.com', '0192023a7bbd73250516f069df18b500', 'user');

-- Sample products
INSERT INTO products (name, description, price, stock, category, image_url) VALUES
('Laptop Pro X1', 'High-performance laptop with 16GB RAM and 512GB SSD', 12999000, 25, 'Electronics', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400'),
('Wireless Mouse', 'Ergonomic wireless mouse with 2.4GHz connection', 349000, 100, 'Accessories', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400'),
('Mechanical Keyboard', 'RGB backlit mechanical keyboard with blue switches', 899000, 50, 'Accessories', 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400'),
('Monitor 27"', '4K IPS monitor with 144Hz refresh rate', 5499000, 15, 'Electronics', 'https://images.unsplash.com/photo-1527443224154-c4a573d5f5d0?w=400'),
('USB-C Hub', '7-in-1 USB-C hub with HDMI and card reader', 449000, 75, 'Accessories', 'https://images.unsplash.com/photo-1625895197185-efcec01cffe0?w=400'),
('Headphones', 'Noise-cancelling over-ear headphones', 2199000, 30, 'Audio', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400');
