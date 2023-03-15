<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quotes.php';

$database = new Database();
$db = $database->connect();

$quotes = new Quotes($db);

if (isset($_GET['id'])) {
    $quotes->id = isset($_GET['id']) ? $_GET['id'] : die();
    $quotes->read_single();

    $quotes_arr = array(
        'id' => $quotes->id,
        'quote' => $quotes->quote,
        'author' => $quotes->author,
        'category' => $quotes->category
    );

    if ($quotes->quote !== null) {
        //Change to JSON data
        echo json_encode($quotes_arr);
    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
}



if ($categories->category != null) {
    $category_arr = array(
        'id' => $categories->id,
        'category' => $categories->category
    );

    echo json_encode($category_arr);
} else {
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}



if (isset($_GET['author_id']) !== null) {
    $quotes->id = isset($_GET['author_id']) ? $_GET['author_id'] : die();
    $quotes_arr = $quotes->read_single();

    echo json_encode($quotes_arr);
} else {
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
}

if (isset($_GET['category_id']) !== null) {
    $quotes->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die();
    $quotes_arr = $quotes->read_single();

    echo json_encode($quotes_arr);
} else {
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}

if (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    $quotes->id = isset($_GET['author_id']) ? $_GET['author_id'] : die();
    $quotes_arr->read_single();

    echo json_encode($quotes_arr);
}

?>