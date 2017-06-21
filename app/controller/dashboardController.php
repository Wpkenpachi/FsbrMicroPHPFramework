<?php 

namespace app\controller;
use core\Controller;

use app\models\User;



class dashboardController extends Controller
{
	
	public function index(){
		//$users = User::all()->first();
		$this->toRender('admin/dashboard.php', ['users' => $users]);
	}


	public function show(){
		//$nome = $this->getParam('nome');
		//$sobrenome = $this->getParam('sobrenome');
		//$idade = $this->getBodyAttr('idade');
		//$body = $this->getBody();

		$id = $this->getParam('id');

		$db = new \PDO('mysql:host=localhost;dbname=mvc', 'root', 'mysql');
		$query = $db->prepare("SELECT * FROM users WHERE id = $id");
		$query->execute();
		$data = $query->fetch();

		$this->toRender('index.php', ['dados' => $data]);

	}

	public function teste(){
		$this->toRender('index2.php',['nome' => 'wesley', 'sobrenome' => 'Paulo','_idade' => 21, 'esportes' => ['futebol', 'voley']]);
	}

}
