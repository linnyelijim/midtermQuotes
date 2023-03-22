<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Quotes.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quotes = new Quotes($db);

//Declares variables to store categories and count rows
$result = $quotes->read_quotes();
$num = $result->rowCount();

//Message for response
$no_quotes = array('message' => 'No Quotes Found');

//Creates array of quotes, ouputs array else no quote
if ($num > 0) {
    $quote_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quote_item = array(
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
            'category' => $category
        );

        array_push($quote_arr, $quote_item);
    }

    echo json_encode($quote_arr);
} else {
    echo json_encode($no_quotes);
}

?>