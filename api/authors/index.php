<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    // Handle CORS preflight
    if($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

    // Handle the API requests based on the HTTP method
    switch($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                require 'read_single.php';
            }
            else {
                require 'read.php';
            }
            break;

        case 'POST':
            require 'create.php';
            break;

        case 'PUT':
            require 'update.php';
            break;

        case 'DELETE':
            require 'delete.php';
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
