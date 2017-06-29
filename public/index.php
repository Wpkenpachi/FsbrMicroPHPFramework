<?php 
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

$app = new core\mvc;

use core\Controller;

// Rota padrão '/'

$app->get('', function(){
	$style = 'text-align: center;';
	echo "<h1 style=\"{$style}\"> Bem Vindo ao FSBR MVC PHP </h1>";
});

$app->get('home/{id}', 'homeController@index');







$app->run();