<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Validate JSON
    if (isset($data->quote) && !empty($data->quote) && isset($data->author_id) && !empty($data->author_id) && isset($data->category_id) && !empty($data->category_id)){
        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        // Update post
        if($quote->update()) {
            echo json_encode(
                array('id' => $quote->id, 'quote' => $quote->quote, 'author_id' => $quote->author_id, 'category_id' => $quote->category_id)
            );
        } else {
            echo json_encode(
                array('message' => 'Quote Not Updated')
            );
        }
    } else {
        echo json_encode(['message' => 'Missing Required Parameters']);
    }