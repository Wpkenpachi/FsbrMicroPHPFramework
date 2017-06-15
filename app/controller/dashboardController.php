<?php 

namespace app\controller;
use core\Controller;

use app\models\User;



class dashboardController extends Controller
{
	
	public function index(){
		$users = User::all()->first();
		$this->toRender('admin/dashboard.php', ['users' => $users]);
	}


	public function show(){
		//$nome = $this->getParam('nome');
		//$sobrenome = $this->getParam('sobrenome');
		//$idade = $this->getBodyAttr('idade');
		$body = $this->getBody();
		print_r($body);
		die();
		//print_r($this->getParams());
		echo "
		Meu completo nome é : {$nome} {$sobrenome},
		E eu tenho {$idade} anos de idade.
		";

	}




}
