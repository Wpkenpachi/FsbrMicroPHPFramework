<?php
namespace core;

use core\View;
use app\controller\Request;

class init
{
	protected $Routes;

	private $Url;
	private $Controller;
	private $Action;
	private $Data;
	private $Verb;

	//==============================================================//
	//																//
	//																//
	//					HTTP VERBS  FUNCTIONS 	 					//
	//																//
	//==============================================================//
	public function get($url , $controller){
		$this->Verb = "get";
		$this->Data = $url;//$this->resolve_request_vars($url);

		$this->Url = $url;
		$this->verifyCtrl($controller);
	}

	/* FUNCTION TO RESOLVE REQUEST VARS
	private function resolve_request_vars($url){
		$regex1 = "/\{[a-z0-9\_]+\}/i";
		$return_vars = null;
		if(preg_match_all($regex1, $url, $return_vars))
			return $return_vars[0];
	}
	*/

	public function post($url , $controller, $var = null){
		$this->Verb = "post";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	public function patch($url , $controller, $var = null){
		$this->Verb = "patch";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	public function put($url , $controller, $var = null){
		$this->Verb = "put";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	public function delete($url , $controller, $var = null){
		$this->Verb = "delete";
		$this->Data = $var;

		$this->Url = $url;
		$this->verifyCtrl($controller);	
	}

	// VERIFY IF THE ROUTE DEAL WITH CONTROLLER OR ANONYMOUS FUNCTION 
	private function verifyCtrl($ctrl){

		if(is_object($ctrl)){
			$this->Controller = $ctrl;
		}else{
			// So this is a string...
			$ctrl_act = explode('@', $ctrl);
			$this->Controller = $ctrl_act[0];
			$this->Action = $ctrl_act[1];
		}

		$this->buildRoute();

	}

	//  BUILD THE ROUTE WITH YOUR FIELDS
	private function buildRoute(){

		$this->Routes[] = [
			'verb' => $this->Verb,
			'url' => $this->Url,
			'controller' => $this->Controller,
			'action' => $this->Action,
			'data' => $this->Data
			];

	}

	public function getRoutes(){

		echo '<pre>';print_r($this->Routes);echo '<pre>';

	}

	//==============================================================//
	//																//
	//																//
	//					FUNCTIONS OF VIEW CLASS 					//
	//																//
	//==============================================================//

	public function toRender($path = null , array $dataBind = null, $template = null){
		extract($dataBind);
		if($template){
			include $this->render_template($path);
		}else{
			include $this->render_view($path);
		}
		
	}

	public function render_template($path){
		return __DIR__."/../app/views/templates/".$path;
	}

	private function render_view($path){
		return __DIR__."/../app/views/".$path;
	}


	protected function helper($dado){
		echo "<pre>";
		print_r($dado);
		echo "</pre>";
	}
}