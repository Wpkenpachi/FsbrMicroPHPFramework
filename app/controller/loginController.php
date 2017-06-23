<?php 

namespace app\controller;
use core\Controller;
use app\models\Usuario;

class loginController extends Controller
{
	
	public function login(){
        $this->toRender('login.php', [], true);
	}

    public function rota1(){
        echo 'Rota 1';
    }

    public function rota2(){
        echo 'Rota 2';
    }

    public function validate(){
        // obtendo informações do body da request
        $user = $this->getBody();
        // conexão e consulta no banco de dados
        $usuario = new Usuario($user);
        $data = $usuario->getResult();
        if(!empty($data)){
            $this->toRender('dashboard.php', ['user' => $data], true);
        }else{
            $this->toRender('error.php', [], true);
        }
    }

}