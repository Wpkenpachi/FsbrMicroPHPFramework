<?php

namespace app\controller;
use core\Controller;

class homeController extends Controller{

    public function home(){
        $profile = [
            'nome' => 'Wesley',
            'sobrenome' => 'Paulo',
            'idade' => 18,
            'email' => 'wesley@email.com',
            'jogos' => ['Dragon Ball Z Budokai Tenkaichi 3', 'League of Legends', 'Battlefield 4'],
            'id' => $this->getParam('id')
        ];

        //$this->toRender('teste.php', ['user' => $profile]);
        $this->toRender('teste2.php', ['user' => $profile], true);
    }

}