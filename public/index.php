<?php 
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

$app = new core\mvc;

use core\Controller;


include_once(__DIR__ . "/../routes.php");
// Inicio da aplicação
$app->run();