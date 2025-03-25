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

    // Check for filters
    $quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
    $quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    // Blog quote query
    $result = $quote->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any quotes
    if($num > 0) {
        // Quote array
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => html_entity_decode($quote),
                'author' => $author_name,
                'category' => $category_name
            );

            // Push to "data"
            array_push($quotes_arr, $quote_item);
        }

        // Turn to JSON & output
        echo json_encode($quotes_arr);

    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }