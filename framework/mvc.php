<?php 
namespace core;
class mvc extends init
{

	public function run(){
		// Will catch current URL
		$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$currentUrl = substr($currentUrl, 1);
		if(substr($currentUrl, -1) == '/'){
			$currentUrl = substr($currentUrl, 0, -1);
		}

		// Expressão regular pra identificar a ocorrência de {id}..{id_user} e etc
		$regex1 = "/\{[a-z0-9\_]+\}/i";
		// Expressão regular que identifica numeros ou letras que estejam entre parenteses, especificamente nesse formato -> /2/ ou /a/ .
		// claro que podendo ser qualquer numero de 0-9, e qualquer letra de a-z.
		$regex2 = "/(\/){1}[a-z0-9]+(\/){1}/";

		// Quebrando url do browser em sub_urls
		$SUB_URLS_BROWSER = explode('/', $currentUrl);

		// Obtendo e tratando método da requisição.
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$foundRoute = 0;
		foreach ($this->Routes as $route) {

		$SUB_URLS_ROTA = explode('/', $route['url']);

		if(count($SUB_URLS_ROTA) == count($SUB_URLS_BROWSER)){ // Verifica se a url no broser tem o mesmo tanto de sub urls, que a url da rota verificada no momento.
			$variaveis_de_rota = $this->compatibilidade_total($SUB_URLS_ROTA,$SUB_URLS_BROWSER,count($SUB_URLS_ROTA),$regex1,$regex2);
			if($variaveis_de_rota != false){
				if($request_method == 'options'){
					$foundRoute++;
					break;
				}else if($request_method == $route['verb'] && is_array($variaveis_de_rota)){
					$this->exec($route['controller'] , $route['action'], $variaveis_de_rota, $route['verb']);
					$foundRoute++;
				}	
			}

		}
		}
		if($foundRoute == 0){
			exit(http_response_code(404));
		}
	}

	private function compatibilidade_total(Array $SUB_URLS_ROTA, Array $SUB_URLS_BROWSER, $count, $p, $p2){
		$array_vars = [];
		for ($i=0; $i < $count; $i++) { // Percorre as sub-urls(correntes, passadas pelo foreach lá de cima)
			// Verfica se essa suburl(corrente) da rota(corrente) trata-se de uam variavel e nao uma {var}
			if(preg_match($p, $SUB_URLS_ROTA[$i])){
			// Verifica se a suburl(corrente) do browser, NÃO bate cm a expressão regular.
				if(!preg_match($p2, $SUB_URLS_BROWSER[$i]) == false){
					// Caso essa condição seja verdade imediatamente retorna false
					// indicando que existe uma incompatibilidade na url
					return false;
				}else{
					preg_match("/(\{){0}[a-z0-9\_]+(\}){0}/i", $SUB_URLS_ROTA[$i], $SUB_URLS_ROTA[$i]);
					$array_vars[$SUB_URLS_ROTA[$i][0]] = $SUB_URLS_BROWSER[$i];
				}
			}
			// Caso a suburl da rota não seja uma variavel,NEM seja igual a suburl(corrent) que ta no browser.
			else if($SUB_URLS_ROTA[$i] != $SUB_URLS_BROWSER[$i]){ 
				// Caso essa condição seja verdade imediatamente retorna false
				// indicando que existe uma incompatibilidade na url
				return false;
			}
		}
		return $array_vars;// Caso não haja nenhuma incompatibilidade a função retorna true.
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
						$return = $vars;
					break;
				case 'post':
					$data = json_decode(file_get_contents('php://input'), true);
					if(isset($data) && !empty($data)){
						$return['data'] = $data;
					}
					if(isset($vars) && !empty($vars)){
						$return['gets'] = $vars;
					}
					break;
				case 'put':
					$data = json_decode(file_get_contents('php://input'), true);	
					if(isset($data) && !empty($data)){
						$return['data'] = $data;
					}
					if(isset($vars) && !empty($vars)){
						$return['gets'] = $vars;
					}
					break;
				case 'patch':
					$data = json_decode(file_get_contents('php://input'), true);
					if(isset($data) && !empty($data)){
						$return['data'] = $data;
					}
					if(isset($vars) && !empty($vars)){
						$return['gets'] = $vars;
					}
					break;

				case 'delete':
					$data = json_decode(file_get_contents('php://input'), true);
					if(isset($data) && !empty($data)){
						$return['data'] = $data;
					}
					if(isset($vars) && !empty($vars)){
						$return['gets'] = $vars;
					}
					break;
			}
			return $return;
	}
	
}