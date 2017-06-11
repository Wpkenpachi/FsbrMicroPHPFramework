<?php
// CORS HEADERS CONFIGURATION
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, PATCH');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

$dbconfig = [
	'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'mvc',
    'username'  => 'root',
    'password'  => 'mysql',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];