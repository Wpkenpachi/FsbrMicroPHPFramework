<?php 

namespace app\controller;
use core\Controller;

class loginController extends Controller
{
	
	public function login(){
         $this->toRender('login.php', [], true);
	}

    public function validate(){
        // obtendo informações do body da request
        //$this->helper($this->getBody());
        $user = $this->getBody();
        // conexão e consulta no banco de dados
        $db = new \PDO('mysql:host=localhost;dbname=mvc;', 'root', 'mysql');
        $query = $db->prepare("SELECT * FROM users WHERE email = :email AND pass = :pass");
        $query->bindValue(':email', $user['login']);
        $query->bindValue(':pass', $user['pass']);
        $query->execute();
        $data = $query->fetch();
        if(!empty($data)){
            $this->toRender('dash.php', ['user' => $data], true);
        }else{
            $this->toRender('error.php', [], true);
        }
    }

}