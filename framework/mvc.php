<?php 
namespace core;

// To use a native the simple native ORM 'FSBRDatabase'
use core\FSBRDatabase;


class mvc extends init
{

	protected static $Request;
	protected $Database;

	protected $Request_Method;
    protected $Url_Current;
	protected $Url_Vars;

    protected $Find_Var_Regex = "/\{[a-z0-9\_]+\}/i";

    protected $Requested_Url = null;
    protected $Route_Compatible = null;

	function __construct(){
		$this->Database = new FSBRDatabase;
	}

    public function run(){
        $this->Url_Current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);// Pegando URL NO BROWSER
        $this->Url_Current = substr($this->Url_Current, 1); // ELIMINANDO BARRA inicial '/'

        // Obtendo e tratando método da requisição.
		$this->Request_Method = strtolower($_SERVER['REQUEST_METHOD']);
 
        // Quebra url atual
        $this->Url_Current = $this->treat_url_slashes($this->Url_Current);
        //echo $this->Url_Current;
        $this->route_string_comparison();
        
    }

    // Quebra Url, e trata possíveis espaços(keys/chaves) em branco gerados pelo explode
    private function treat_url_slashes(&$url_tree_array){
        $array = explode('/', $url_tree_array);
        $branchs = [];
        array_walk($array,function($branch) use (&$branchs){
            if(!empty($branch) && isset($branch)){
                $branchs[] = $branch;
            }
        });

        return implode('/', $branchs);
    }

    private function route_string_comparison(){
        // Verify if the url plane string is recognized
        // Simple Comparison
        foreach($this->Routes as $route){
            if($route['url'] == $this->Url_Current){
                $this->Route_Compatible = $route;
                break;
            }
        }

        if($this->Route_Compatible){
            $this->url_found($this->Route_Compatible);
        }else{
            $this->route_tree_keys_comparison();
        }
    }

    public function route_tree_keys_comparison(){
    // Convert the url plane string in an tree keys array
    // Detailed comparison
    $url_tree_keys = explode('/',$this->Url_Current); // broken the current url

        foreach($this->Routes as $route):
            $route_url_keys = explode('/', $route['url']);// broken route
            preg_match_all($this->Find_Var_Regex, $route['url'], $how_much_vars);// quantidade de variáveis
            $difereces = array_diff_assoc($url_tree_keys, $route_url_keys); // diferença entre route_url e current_url
            
            // Primeira subcomparação: Verifica igualdade na quantidade de branches
            if(count($url_tree_keys) == count($route_url_keys)){
                //echo 'Primeira subcomparação <br>';
                // Segunda subcomparação: Verifica igualdade branch à branch
                if( count($difereces) == count($how_much_vars[0]) ){
                    //echo 'Segunda subcomparação <br>';
                    // Verifica se a branch atual é variável
                    if($this->verify_regex($how_much_vars[0])){
                        //echo 'branch atual é variável <br>';
						$this->get_url_vars($how_much_vars[0], $difereces);
                        $this->Route_Compatible = $route;
                        break;
                    }else{
                        //echo 'Debug: Não é uma variável <br>';
                        $this->Route_Compatible = null;
                        continue;
                    }
                }else{
                    //echo 'Debug: Erro na Segunda subcomparação <br>';
                    $this->Route_Compatible = null;
                    continue;
                }
            }else{
                //echo 'Debug: Erro na Primeira subcomparação <br>';
                $this->Route_Compatible = null;
                continue;
            }
        endforeach;

        if($this->Route_Compatible){
            $this->url_found($this->Route_Compatible);
        }else{
            $this->url_not_found();
        }
    }

    private function verify_regex($branchs){
        $return = null;
        foreach($branchs as $branch){
            if(preg_match($this->Find_Var_Regex, $branch)){
                $return = true;
            }else{
                $return = false;
            }
        }
        return $return;
    }

	private function get_url_vars($keys, $values){
		$keys = preg_replace('/[\{\}]+/', '', $keys);
		$url_vars = array_combine($keys, $values);
		$this->Url_Vars = $url_vars;
	}

    private function url_not_found(){
        //echo 'nada encontrado';
        exit(http_response_code(404));
    }

    private function url_found($route){
		$this->exec($route['controller'], $route['action'], $this->Url_Vars, $route['verb']);
    }


	private function exec($ctrl, $action, $vars, $verb){

		$this->setParams($this->verbVerify($vars, $verb));

		if(is_string($ctrl)){
			$class = "app\\controller\\".$ctrl;
			$controller = new $class;
			$controller->$action();
		}else{
			echo '<pre>';print_r($ctrl($vars));echo '</pre>';
		}
	}

	
	// Verifica o tipo de requisição, pra obter o conteúdo da forma correta;
	// O retorno disso, é passado como argumento, pra função do controller;
	private function verbVerify($vars , $verb){
			switch ($verb) {
				case 'get':
						$return = $this->return_get($vars);
					break;
				case 'post':
						$return = $this->return_post($vars);
					break;
				case 'put':
						$return = $this->return_put($vars);
					break;
				case 'patch':
						$return = $this->return_patch($vars);
					break;
				case 'delete':
						$return = $this->return_delete($vars);
					break;
			}
			return $return;
	}

	// Retorno da requisição GET
	private function return_get($vars){
		return $return['gets'] = $vars;
	}
	// Retorno da requisição POST
	private function return_post($vars){

		$data = $this->validate_content_type();
		
		if(isset($data) && !empty($data)){
			$return['data'] = $data;
		}
		if(isset($vars) && !empty($vars)){
			$return['gets'] = $vars;
		}
		
		return $return;
	}

	// Retorno da requisição PATCH
	private function return_patch(){
		$data = $this->validate_content_type();
		
		if(isset($data) && !empty($data)){
			$return['data'] = $data;
		}
		if(isset($vars) && !empty($vars)){
			$return['gets'] = $vars;
		}
		
		return $return;
	}
	// Retorno da requisição PUT
	private function return_put(){
		$data = $this->validate_content_type();
		
		if(isset($data) && !empty($data)){
			$return['data'] = $data;
		}
		if(isset($vars) && !empty($vars)){
			$return['gets'] = $vars;
		}
		
		return $return;
	}
	// Retorno da requisição DELETE
	private function return_delete(){
		$data = $this->validate_content_type();
		
		if(isset($data) && !empty($data)){
			$return['data'] = $data;
		}
		if(isset($vars) && !empty($vars)){
			$return['gets'] = $vars;
		}
		
		return $return;
	}


	// validate content type and return the content as array
	private function validate_content_type(){
		
		$headers = getallheaders(); // Pegando todos os headers da requisição

		switch($headers['Content-Type']){
			case 'application/x-www-form-urlencoded':
				$return_content = $this->resolve_url_data(file_get_contents('php://input'));
			break;

			case 'application/json':
				$return_content = json_decode(file_get_contents('php://input'), true);
			break;
		}
		return $return_content;
	}

	// Resolve url data of x-www-form-urlencoded
	function resolve_url_data($url_vars){
		
		$params = urldecode($url_vars);
		$params = explode('&', $params);
		$vars = [];

		foreach($params as $param){
			$helper = explode('=' , $param);
			$vars[$helper[0]] = $helper[1];
		}

		return $vars;
	}

	// Parametro que seta o Atributo Request (init), com os parametros
	// atuais passados a rota.
	public function setParams($params){
		self::$Request = $params;
	}
	
}
