<?php 
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

$app = new core\mvc;

/*
	EasyMVC allow you to create WEB pages and API's.But in routes
	this haven't any difference, you will do the same for both kind of applications.

	Easy and simple example of a route that haven't a controller,
	this route just execute something directly on the route function.
	This will return a object.

	---------------------------
	$app->get('', function(){
		$array = ['a' => b];
		return (Object)$array;
	});
	---------------------------

	Easy and simple example of a route that have a controller.
	This will execute the function web() on homeController

	---------------------------
	$app->get('web/home/{id}', 'homeController@web');
	---------------------------

	You also have anothers function to define what kind of a route is this...
	$app->post($url, $controller); Most used to add data.
	$app->put($url, $controller);	Most used to update all fields from a especific data.
	$app->delete($url, $controller); Most used to delete a especific data.

_______Tchau
______sE cuida

_____boA noite
____dorMe bem 
_fica cOm deus
*/

$app->get('web/home/{id}/{idd}', 'homeController@web');
$app->post('web/home/{id}/{idd}', 'homeController@web');
$app->patch('web/home/{id}/{idd}', 'homeController@web');

$app->get('', function(){
		$array = ['a' => 'vazio'];
		return $array;
});

$app->get('ha/{id}/p/{idd}', function(){
		$array = ['a' => 'h'];
		print_r(func_get_args());
		//return (Object)$array;
});


$app->run();