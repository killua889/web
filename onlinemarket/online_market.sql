-- Create the database
CREATE DATABASE IF NOT EXISTS online_market;
USE online_market;

-- Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Insert default admin user
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('user', 'user123', 'user');

-- Create the 'products' table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255), -- Path to the product image
    num INT NOT NULL DEFAULT 0 -- Quantity available
);

-- Insert example products
INSERT INTO products (name, description, price, image, num) VALUES
('Luffy Poster', 'Epic Monkey D. Luffy One Piece poster.', 12.50, 'wallpaperflare.com_wallpaper(1).jpg', 15),
('Sukuna Poster', 'Jujutsu Kaisen Ryomen Sukuna poster.', 11.75, 'wallpaperflare.com_wallpaper(3).jpg', 10);


-- Create the 'carts' table
CREATE TABLE IF NOT EXISTS carts (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert example cart items
INSERT INTO carts (user_id, product_id, quantity) VALUES
(1, 1, 2), 
(1, 2, 1);