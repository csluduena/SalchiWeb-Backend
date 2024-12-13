<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'u931942264_salchiDB';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                options: array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4")
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}

