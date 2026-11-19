-- Music Analyzer and Notifier database
-- Run once in phpMyAdmin (SQL tab) or: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS maan
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE maan;

CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(254) NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email)
);
