<?php
namespace app\controller;

class homeController extends Controller
{
	public function index($var = null){
		if(isset($var) and !empty($var) and $var != null){
			exit(json_encode($var));
		}
	}
	
}