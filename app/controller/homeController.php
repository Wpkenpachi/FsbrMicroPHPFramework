<?php
namespace app\controller;

use app\models\User;

class homeController extends Controller
{

	/*
	API Action(From API routes api/url) , will return data by this way... exit(json_encode())..
	'api/' -> Or whatever you want to define.
	*/
	public function api($vars = null){
		if(isset($vars) and !empty($vars) and $vars != null){
			exit(json_encode($vars));
		}
	}


	//WEB Action (from web routes web/url), will return data by redering in views or right there...
	// web/ or whatever you want to define...
	public function web($vars = null){

		//echo '<pre>';print_r($vars);echo '</pre>';
		$this->toRender('index', ['vars' => $vars]);

		die();
		
		$users = User::all();
		echo '<ul>';
		foreach ($users as $user) {
			echo '<li>Nome: '.$user->nome.'<ul><li>';
			echo 'Email: '.$user->email.'</li></ul></li>';

		}
		echo '</ul>';
	}
	
}