<?php 

namespace app\controller;
use core\Controller;

class MeuController extends Controller
{
	
	public function mostra(){
        $dados = ['nome' => 'sapato', 'valor' => 300];
		$this->toRender('index2.php', ['produto' => $dados], true);
	}

}