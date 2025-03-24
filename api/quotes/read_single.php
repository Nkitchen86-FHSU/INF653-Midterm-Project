<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog quote object
    $quote = new Quote($db);

    // Get ID
    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get quote
    $quote->read_single();

    // Create array
    if ($quote->read_single()) {
        echo json_encode([
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author_name,
            'category' => $quote->category_name
        ]);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }