# Fox Online Shop 🛒 | Anime Poster Store

Welcome to Fox Online Shop – a simple and functional online marketplace for anime posters where users can browse products, manage carts, and update profiles. Admins can efficiently manage products via the admin dashboard.

## Features ✨

- **User Roles:**
    - **Admin:** Manage products (add, update, and remove).
    - **Normal Users:** Browse products, add/remove items to/from their cart, and update profile information.
- **Authentication:** Secure login/logout system.
- **Cart Management:** Add and view cart items.
- **Responsive UI:** Simple and clean design for ease of use.

## Tech Stack 💻

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP (Procedural style)
- **Database:** MySQL

## Setup Instructions ⚙️

Follow these steps to get the project running on your local machine:
### 1. Clone the Repository
``` bash
    git clone https://github.com/killua889/web.git
    cd web/onlinemarket
```
### 2. Database Setup
- Option 1: Import the Provided SQL File (Recommended)
```
    Open your MySQL tool (e.g., phpMyAdmin).
    Create a new database named online_market.
    Import the online_market.sql file located in the project directory.
```
- Option 2: Manually Build the Database

    - If you want to create the tables manually, execute the following SQL **queries:**
``` sql
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
```

### 3. Configure the Backend
Open the connect.php file and update your MySQL database credentials:
``` php
define("HOSTNAME","yourservername");
define("USERNAME","DBusername");
define("PASSWORD","DBpassword");
define("DATABASE","online market");
```
### 4. Run the Project

- Place the project folder into your local server directory (e.g., htdocs for XAMPP).
- Start Apache and MySQL services in your local server tool (e.g., XAMPP, WAMP).
- Open your browser and navigate to:

- http://localhost/onlinemarket


## For any questions or issues, feel free to reach out:

    GitHub: killua889
    Email: yassentaalab91@gmail.com