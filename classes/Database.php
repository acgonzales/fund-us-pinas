<?php

class Database
{
    private $HOST = "127.0.0.1"; // localhost
    private $USER = "funduspinas";
    private $PASSWORD = "password";
    private $DB = "fundraise_db";

    public mysqli $connection;

    public function __construct()
    {
        $this->connect();
        // $this->initializeTables();
    }

    private function connect()
    {
        $this->connection = new mysqli($this->HOST, $this->USER, $this->PASSWORD, $this->DB);

        if ($this->connection->connect_error) {
            die('Database connection failed.');
        }

        return $this->connection;
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    private function initializeTables()
    {

        $tables = [
            "CREATE TABLE IF NOT EXISTS `admin_users` (
                `admin_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `password` VARCHAR(60)
            );",
            "CREATE TABLE IF NOT EXISTS `users` (
                `user_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                `first_name` VARCHAR(255) NOT NULL,
                `middle_name` VARCHAR(255),
                `last_name` VARCHAR(255) NOT NULL,
                `gender` ENUM('Male', 'Female') NOT NULL,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `password` VARCHAR(60) NOT NULL,
                `url_address` VARCHAR(255) UNIQUE,
                `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );",
            "CREATE TABLE IF NOT EXISTS `stories` (
                `story_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                `user_id` INT UNSIGNED NOT NULL,
                `content` TEXT NOT NULL,
                `likes` INT UNSIGNED DEFAULT 0,
                `image` TEXT,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
            );",
            "CREATE TABLE IF NOT EXISTS `fundraisers` (
                `fundraiser_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                `user_id` INT UNSIGNED NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `image` TEXT,
                `description` TEXT,
                `goal_amount` DECIMAL NOT NULL,
                `expiration_date` DATE NOT NULL,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
            );",
            // "CREATE TABLE IF NOT EXISTS `fundraiser_donations` (
            //     `fundraiser_donation_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            //     `fundraiser_id` INT UNSIGNED NOT NULL,
            //     `user_id` INT UNSIGNED NOT NULL,
            //     `is_anonymous` INT UNSIGNED DEFAULT 1,
            //     `amount` DECIMAL NOT NULL,
            //     `donated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            //     FOREIGN KEY (`fundraiser_id`) REFERENCES `fundraisers` (`fundraiser_id`),
            //     FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
            // );",
            // "CREATE TABLE IF NOT EXISTS `foundation_donations` (
            //     `foundation_donation_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            //     `user_id` INT UNSIGNED NOT NULL,
            //     `is_anonymous` INT UNSIGNED DEFAULT 1,
            //     `amount` DECIMAL NOT NULL,
            //     `donated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            //     FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
            // );",
            "CREATE TABLE IF NOT EXISTS `shops` (
                `shop_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                `user_id` INT UNSIGNED NOT NULL,
                `is_confirmed` INT(1) DEFAULT 0,
                `name` VARCHAR(255) NOT NULL,
                `image` TEXT,
                `description` TEXT NOT NULL,
                `fb_link` TEXT,
                `ig_link` TEXT,
                `shopee_link` TEXT,
                `lazada_link` TEXT,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
            );",
            // "CREATE TABLE IF NOT EXISTS `shop_donations` (
            //     `shop_donation_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            //     `shop_id` INT UNSIGNED NOT NULL,
            //     `amount` DECIMAL NOT NULL,
            //     `donated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            //     FOREIGN KEY (`shop_id`) REFERENCES `shops` (`shop_id`)
            // );",
            "CREATE TABLE IF NOT EXISTS `donations` (
                `donation_id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                `donatable_id` INT UNSIGNED,
                `donatable_type` ENUM('Foundation', 'Fundraiser', 'Shop'),
                `fundraiser_id` INT UNSIGNED,
                `xendit_id` VARCHAR(255) UNIQUE,
                `amount` DECIMAL NOT NULL,
                `is_paid` INT(1) DEFAULT 0,
                `is_anonymous` INT(1) DEFAULT 1,
                `donated_at` TIMESTAMP,
                FOREIGN KEY (`fundraiser_id`) REFERENCES `fundraisers` (`fundraiser_id`) ON DELETE SET NULL
            );"
        ];
        try {
            $this->connection->begin_transaction();

            foreach ($tables as $table) {
                $this->connection->query($table);
            }

            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollback();
            die($e);
        }
    }
}
