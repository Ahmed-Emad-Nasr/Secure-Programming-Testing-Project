-- SQL script to create the users table for the login system
-- Run this in phpMyAdmin or via MySQL command line

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a sample user for testing
-- Password is 'password' (hashed with MD5 for demo - in production use proper hashing like bcrypt)
INSERT INTO users (username, password, email, first_name, last_name) VALUES
('admin', 'password', 'admin@example.com', 'Admin', 'User'),
('testuser', 'testpass', 'test@example.com', 'Test', 'User');