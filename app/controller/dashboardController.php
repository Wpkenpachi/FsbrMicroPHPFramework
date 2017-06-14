<?php 

namespace app\controller;

use app\models\User;

class dashboardController extends Controller
{
	
	public function index(){
		$data = new User;
		$users = $data::all()->first();
		echo "Está sendo executado";
		return $this->toRender('index.php', ['user' => $users]);
	}

	protected function helper($dado){
		echo "<pre>";
		print_r($dado);
		echo "</pre>";
	}

	public function update(){
		// Obtendo valores das variáveis da url
		$id = func_get_arg(0)['data']['id'];
		$nome = func_get_arg(0)['data']['nome'];
		
		// Criando instância do model que referencia a tabela 'users'
		// Atualizando um dado específico da tabela users
		$data = new User;
		$users = $data::find($id);
		$users->update(['nome' => $nome]);


		// renderizar a view, e passar as variáveis
		// que essa view, terá acesso.
		$this->toRender('index.php', ['user' => $users]);
		
	}


}
