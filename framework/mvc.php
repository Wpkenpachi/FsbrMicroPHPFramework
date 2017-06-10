<?php 
namespace core;

class mvc extends init
{

	public function run(){
		// Will catch current URL
		$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$currentUrl = substr($currentUrl, 1);
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$foundRoute = 0;
		foreach ($this->Routes as $route) {
			if( $currentUrl == $route['url'] ) {
				if($request_method == 'options'){
					$foundRoute++;
				break;
				}else if($request_method == $route['verb']){
					$this->exec($route['controller'] , $route['action'], $route['data'], $route['verb']);
					$foundRoute++;
				}
				
			}
		}
		if($foundRoute == 0){
			exit(http_response_code(404));
		}
	}

	private function exec($ctrl, $action, $vars, $verb){
		if(is_string($ctrl)){
			$class = "app\\controller\\".$ctrl;
			$controller = new $class;
			$controller->$action($this->verbVerify($vars, $verb));

		}else{
			echo '<pre>';print_r($ctrl);echo '</pre>';
		}
	}

	// Verifica o tipo de requisição, pra obter o conteúdo da forma correta;
	// O retorno disso, é passado como argumento, pra função do controller;
	private function verbVerify($vars , $verb){
			switch ($verb) {
				case 'get':
					for($i=0; $i < count($vars);$i++){
						$return[$vars[$i]] = $_GET[$vars[$i]];
					}
					break;
				case 'post':
					$data = json_decode(file_get_contents('php://input'), true);
					for($i =0; $i < count($vars);$i++){
						$return[$vars[$i]] = $data[$vars[$i]];
					}
					break;
				case 'put':
					$data = json_decode(file_get_contents('php://input'), true);	
					for($i =0; $i < count($vars);$i++){
						$return[$vars[$i]] = $data[$vars[$i]];
					}
					break;
				case 'patch':
					$data = json_decode(file_get_contents('php://input'), true);
					for($i =0; $i < count($vars);$i++){
						$return[$vars[$i]] = $data[$vars[$i]];
					}
					break;

				case 'delete':
					$data = json_decode(file_get_contents('php://input'), true);
					for($i =0; $i < count($vars);$i++){
						$return[$vars[$i]] = $data[$vars[$i]];
					}
					break;
			}
			return $return;
	}
	
}