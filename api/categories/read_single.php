<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog category object
    $category = new Category($db);

    // Get ID
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get category
    $category->read_single();

    // Create array
    if($category->read_single()) {
        echo json_encode([
            'id' => $category->id,
            'category' => $category->category
        ]);
    } else {
        echo json_encode(['message' => 'category_id Not Found']);
    }