<?php
namespace core;

class init
{
	protected $Routes;

	private $Url;
	private $Controller;
	private $Action;
	private $Data;
	private $Verb;

	// FIRST FUNCTION FOR ADDING ROUTES
	public function get($url , $controller, $var){
		$this->Verb = "get";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);
	}

	public function post($url , $controller, $var){
		$this->Verb = "post";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	public function patch($url , $controller, $var){
		$this->Verb = "patch";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	public function put($url , $controller, $var){
		$this->Verb = "put";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	public function delete($url , $controller, $var){
		$this->Verb = "delete";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	private function verifyCtrl($ctrl){
		if(is_object($ctrl)){
			$this->Controller = $ctrl();
		}else{
			// So this is a string...
			$ctrl_act = explode('@', $ctrl);
			$this->Controller = $ctrl_act[0];
			$this->Action = $ctrl_act[1];
		}

		$this->buildRoute();
	}

	private function buildRoute(){
		$this->Routes[] = [
			'verb' => $this->Verb,
			'url' => $this->Url,
			'controller' => $this->Controller,
			'action' => $this->Action,
			'data' => $this->Data
			];
	}
}