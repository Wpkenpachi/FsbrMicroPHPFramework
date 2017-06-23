<?php
namespace app\models;
class Usuario {

    private $Conn;
    private $Query;
    private $Data;

    function __construct($dados){
        $this->Conn = new \PDO('mysql:host=localhost;dbname=mvc;', 'root', 'mysql');
        $this->Data = $dados;

        $this->preparing();
    }

    private function preparing(){
        $this->Query = $this->Conn->prepare("SELECT * FROM users WHERE email = :email AND pass = :pass");

        $this->binding();
    }

    private function binding(){
        $this->Query->bindValue(':email', $this->Data['login']);
        $this->Query->bindValue(':pass', $this->Data['pass']);

        $this->executing();
    }

    private function executing(){
        $this->Query->execute();
    }
    
    public function getResultAll(){
        return $this->Query->fetchAll();
    }

    public function getResult(){
        return $this->Query->fetch();
    }

}