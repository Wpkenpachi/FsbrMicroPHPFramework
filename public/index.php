<?php 
require __DIR__ . '/../vendor/autoload.php';
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, PATCH');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

$app = new core\mvc;

// This will return a object.
$app->get('url', function(){
	$array = ['a' => b];

	return (Object)$array;
});

// 'user/{id}/session/{id}'
// 'user/2/session/3'



// This will return a string
$app->post('home', 'homeController@index', ['post']);
$app->get('home', 'homeController@index', ['get']);
$app->patch('home', 'homeController@index', ['patch']);
$app->put('home', 'homeController@index', ['put']);
$app->delete('home', 'homeController@index', ['delete']);



$app->run();