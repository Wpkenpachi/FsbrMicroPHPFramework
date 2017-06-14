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
	public function index($vars = null){
		$this->toRender('admin/login.php');
	}

	public function dashboard($vars = null){
		$users = User::all()->first();
		$this->toRender('admin/dashboard.php', ['users' => $users]);
	}
	
}