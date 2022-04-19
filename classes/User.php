<?php

require_once("Database.php");

class User extends Database
{
    public function signUp($firstName, $lastName, $email, $password, $passwordConfirmation, $gender)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if ($password != $passwordConfirmation) {
            throw new Exception("Password and confirm password are not equal.");
        }

        if (!in_array($gender, ["Male", "Female"])) {
            throw new Exception("Invalid gender values. Valid values are: Male, Female");
        }

        $password = password_hash($password, PASSWORD_BCRYPT);

        $statement = $this->connection->prepare("INSERT INTO users 
                                                (first_name, last_name, gender, email, password) 
                                                VALUES
                                                (?, ?, ?, ?, ?)");
        $statement->bind_param("sssss", $firstName, $lastName, $gender, $email, $password);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $user_id = $statement->insert_id;
        $statement->close();

        // $url_address = "$firstName.$lastName.$user_id";
        // $url_address_update = $this->connection->prepare("UPDATE users SET url_address=? WHERE user_id=?");
        // $url_address_update->bind_param("si", $url_address, $user_id);
        // $url_address_update->execute();
        // $url_address_update->close();

        return true;
    }

    public function login($email, $password)
    {
        $statement = $this->connection->prepare("SELECT * FROM users where email=? LIMIT 1");
        $statement->bind_param("s", $email);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            throw new Exception("Invalid credentials.");
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user["password"])) {
            throw new Exception("Invalid password.");
        }

        $_SESSION["fundus_userid"] = $user["user_id"];

        $statement->close();

        return true;
    }

    public function getUserById($userId)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE user_id=? LIMIT 1");
        $statement->bind_param("i", $userId);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            return false;
        }

        $user = $result->fetch_assoc();

        $statement->close();

        return $user;
    }
}
