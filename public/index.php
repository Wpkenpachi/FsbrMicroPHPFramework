<?php 
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection($dbconfig);
// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();
// Setup the Eloquent ORM
$capsule->bootEloquent();

$app = new core\mvc;

// This will return a object.
$app->get('', function(){
	$array = ['a' => b];

	return (Object)$array;
});


$app->get('web/home/{id}', 'homeController@web');
$app->get('web/home/profile/{id}/option/{option}', 'homeController@web');
$app->get('categorias/{id}/produto/{id_produto}', 'homeController@web');
$app->post('categorias/{id}/produto/{id_produto}', 'homeController@web');
$app->patch('categorias/{id}/produto/{id_produto}', 'homeController@web');

/*
$app->post('web/home', 'homeController@web');
$app->patch('web/home', 'homeController@web', ['patch']);
$app->put('web/home', 'homeController@web', ['put']);
$app->delete('web/home', 'homeController@web', ['delete']);
*/

// This will return a string
// API Routes
$app->post('api/home', 'homeController@api', ['post']);
$app->get('api/home', 'homeController@api', ['get']);
$app->patch('api/home', 'homeController@api', ['patch']);
$app->put('api/home', 'homeController@api', ['put']);
$app->delete('api/home', 'homeController@api', ['delete']);



$app->run();