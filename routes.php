<?php
// Rota Padrão(root) ' / ';
$app->get('', function(){
	$style = 'text-align: center;';
	echo "<h1 style=\"{$style}\"> Bem Vindo ao FSBR MVC PHP </h1>";
});