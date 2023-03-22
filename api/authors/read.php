<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate authors object
$authors = new Authors($db);

//Declares variables to store categories and count rows
$result = $authors->read_authors();
$num = $result->rowCount();

//Message for response
$no_author = array('message' => 'author_id Not Found');

//Creates array of categories, ouputs array else no category
if ($num > 0) {
    $author_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $author_item = array(
            'id' => $id,
            'author' => $author
        );
        array_push($author_arr, $author_item);
    }
    echo json_encode($author_arr);
} else {
    echo json_encode($no_author);
}
?>