<?php
// Using Eloquent
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

/*
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection($dbconfig);
// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();
// Setup the Eloquent ORM
$capsule->bootEloquent();
*/