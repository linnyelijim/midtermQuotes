<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Gets HTTP request value
$method = $_SERVER['REQUEST_METHOD'];

//Getting URL that is being passed
$uri = $_SERVER['REQUEST_URI'];

//Declares permitted methods and access
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

//Assigns methods to appropriate files
if ($method === 'GET') {
    //checking URL if it has a query statement like id=1
    if (parse_url($uri, PHP_URL_QUERY)) {
        require('read_single.php');
    } else {
        require('read.php');
    }
} else if ($method === 'POST') {
    require('create.php');
} else if ($method === 'PUT') {
    require('update.php');
} else if ($method === 'DELETE') {
    require('delete.php');
}
?>