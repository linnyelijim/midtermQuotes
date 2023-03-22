<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Quotes.php';
include_once '../../functions/isValid.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quotes = new Quotes($db);

//Message responses
$no_author = array('message' => 'author_id Not Found');
$no_category = array('message' => 'category_id Not Found');
$no_quotes = array('message' => 'No Quotes Found');

//Get id, call to read quote with specified id, validates quote exists, creates array for output
if (isset($_GET['id'])) {
    $quotes->id = isset($_GET['id']) ? $_GET['id'] : die();
    $quotes->read_single();

    if ($quotes->quote !== null) {
        $quotes_arr = array(
            'id' => $quotes->id,
            'quote' => $quotes->quote,
            'author' => $quotes->author,
            'category' => $quotes->category
        );
        //Output array else no quote
        echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);
    } else {
        echo json_encode($no_quotes);
    }

    //Get author_id and category_id
} else if (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    $quotes->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : die();
    $quotes->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die();

    //Validates quote exists
    if (!isValid($quotes->category_id, $quotes)) {
        echo json_encode($no_category);
        exit();
    }
    if (!isValid($quotes->author_id, $quotes)) {
        echo json_encode($no_author);
        exit();
    }

    //Call to read quote with specified ids  
    $quotes_arr = $quotes->read_single();

    //Creates array for output
    echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);

    //Get author_id 
} else if (isset($_GET['author_id'])) {
    $quotes->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : die();

    //Validates quote exists
    if (!isValid($quotes->author_id, $quotes)) {
        echo json_encode($no_author);
        exit();
    }

    //Call to read quote with specified id
    $quotes_arr = $quotes->read_single();

    //Creates array for output
    echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);

    //Get category_id
} else if (isset($_GET['category_id'])) {
    $quotes->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die();

    //Validates quote exists
    if (!isValid($quotes->category_id, $quotes)) {
        echo json_encode($no_category);
        exit();
    }

    //Call to read quote with specified id
    $quotes_arr = $quotes->read_single();

    //Creates array for output
    echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);
}



?>