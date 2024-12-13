<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT id, first_name, last_name, email, password, role FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }

        return false;
    }

    public function register($firstName, $lastName, $email, $password, $metamaskAddress) {
        // Check if email already exists
        if ($this->emailExists($email)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (first_name, last_name, email, password, metamask_address, role) 
                  VALUES (?, ?, ?, ?, ?, 'user')";
        
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(1, $firstName);
        $stmt->bindParam(2, $lastName);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $hashedPassword);
        $stmt->bindParam(5, $metamaskAddress);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}

