<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/AuthController.php';

class Router {
    private $authController;

    public function __construct() {
        $this->authController = new AuthController();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($method === 'OPTIONS') {
            header('Access-Control-Allow-Origin: http://localhost:5173');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Max-Age: 86400');
            exit(0);
        }

        switch ($path) {
            case '/register':
                if ($method === 'POST') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo json_encode($this->authController->register($data));
                } else {
                    $this->methodNotAllowed();
                }
                break;
            case '/login':
                if ($method === 'POST') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo json_encode($this->authController->login($data));
                } else {
                    $this->methodNotAllowed();
                }
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function notFound() {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Not Found']);
    }

    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    }
}