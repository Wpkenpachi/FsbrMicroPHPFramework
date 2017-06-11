<?php
namespace app\controller;

use app\models\User;

class homeController extends Controller
{

	/*
	API Action(From API routes api/url) , will return data by this way... exit(json_encode())..
	'api/' -> Or whatever you want to define.
	*/
	public function api($var = null){
		if(isset($var) and !empty($var) and $var != null){
			exit(json_encode($var));
		}
	}


	//WEB Action (from web routes web/url), will return data by redering in views or right there...
	// web/ or whatever you want to define...
	public function web($var = null){
		$users = User::all();
		echo '<ul>';
		foreach ($users as $user) {
			echo '<li>Nome: '.$user->nome.'<ul><li>';
			echo 'Email: '.$user->email.'</li></ul></li>';

		}
		echo '</ul>';
	}
	
}