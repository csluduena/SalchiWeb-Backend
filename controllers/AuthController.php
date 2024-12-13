<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/JwtHandler.php';

class AuthController {
    private $db;
    private $user;
    private $jwtHandler;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->jwtHandler = new JwtHandler();
    }

    public function login($data) {
        if (!isset($data['email']) || !isset($data['password'])) {
            return ['success' => false, 'message' => 'Email and password are required'];
        }

        $user = $this->user->login($data['email'], $data['password']);

        if ($user) {
            $token = $this->jwtHandler->generateToken([
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);

            return [
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
    }

    public function register($data) {
        if (!isset($data['firstName']) || !isset($data['lastName']) || !isset($data['email']) || !isset($data['password']) || !isset($data['metamaskAddress'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        try {
            $userId = $this->user->register($data['firstName'], $data['lastName'], $data['email'], $data['password'], $data['metamaskAddress']);

            if ($userId) {
                return ['success' => true, 'message' => 'Registration successful', 'userId' => $userId];
            } else {
                return ['success' => false, 'message' => 'Registration failed. Email may already be in use.'];
            }
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed: Database error'];
        }
    }
}

