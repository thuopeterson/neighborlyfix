CREATE DATABASE IF NOT EXISTS neighborlyfix;
USE neighborlyfix;

CREATE TABLE IF NOT EXISTS issues (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category VARCHAR(50),
  location VARCHAR(255),
  description TEXT,
  photo VARCHAR(255),
  status VARCHAR(50) DEFAULT 'Pending'
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100),
  last_name VARCHAR(100),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  is_admin BOOLEAN DEFAULT 0
);
