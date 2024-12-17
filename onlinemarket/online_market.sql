-- Create the database
CREATE DATABASE IF NOT EXISTS online_market;
USE online_market;

-- Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Insert default admin user
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@example.com', 'admin123', 'admin');
INSERT INTO users (name, email, password, role) VALUES
('user', 'user@example.com', 'user123', 'user');

-- Create the 'products' table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    img VARCHAR(255), -- Path to the product image
    num INT NOT NULL DEFAULT 0 -- Quantity available
);

-- Insert example products
INSERT INTO products (name, description, price, img, num) VALUES
('Luffy Poster', 'Epic Monkey D. Luffy One Piece poster.', 12.50, 'imgs/wallpaperflare.com_wallpaper(1).jpg', 15),
('Sukuna Poster', 'Jujutsu Kaisen Ryomen Sukuna poster.', 11.75, 'imgs/wallpaperflare.com_wallpaper(3).jpg', 10);

-- Create the 'carts' table
CREATE TABLE IF NOT EXISTS carts (
    userid INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (userid, product_id),
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert example cart items
INSERT INTO carts (userid, product_id, quantity) VALUES
(1, 1, 2), 
(1, 2, 1); 
