<?php
namespace core;

use core\View;
use core\phprender;
use app\controller\Request;

class init
{
	protected $Routes;

	private $Url;
	private $Controller;
	private $Action;
	private $Data;
	private $Verb;

	private $wprender;

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
			$data = json_encode($this->Routes);
			$routes = fopen(__DIR__.'/../Generator/types/route.json', "w");
			fwrite($routes, $data);
			fclose($routes);

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

	public function toRender($path = null , array $dataBind = null, $render_engine = null){
		extract($dataBind);
		if($render_engine == null){
			include $this->render_view($path);
		}else{
			$html = file_get_contents($this->render_view($path));
			$this->wprender = new phprender($html);
			$fp = fopen(__DIR__."/../app/views/cache/render_engine_temp.php", "w");
			fwrite($fp, $this->wprender->getHtml());
			fclose($fp);
			include(__DIR__."/../app/views/cache/render_engine_temp.php");
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