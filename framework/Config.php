<?php

include __DIR__ . '/../config/config.php';

// CORS HEADERS CONFIGURATION
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, PATCH');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');


// Using Native Orm WPDaabase
define('DRIVER', $dbconfig['driver']);
define('HOST', $dbconfig['host']);
define('DBNAME', $dbconfig['database']);
define('USER', $dbconfig['username']);
define('PASS', $dbconfig['password']);