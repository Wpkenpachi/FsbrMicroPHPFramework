<?php


class Route {

    protected $Routes = 
    [ 
        0 => ['url' => 'home'],
        1 => ['url' => 'home/home'],
        2 => ['url' => 'large/{id}/l/{i}'],
        3 => ['url' => 'large/{id}'],
        5 => ['url' => 'large/{id}/prof/{pd}'],
        6 => ['url' => 'large/prof/{id}/{pd}']

    ];
    protected $Request_Method;
    protected $Url_Current;

    protected $Find_Var_Regex = "/\{[a-z0-9\_]+\}/i";

    protected $Requested_Url = null;
    protected $Route_Compatible = null;

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
                $this->Route_Compatible = $route['url'];
                break;
            }
        }

        if($this->Route_Compatible){
            $this->url_found();
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
                echo 'Primeira subcomparação <br>';
                // Segunda subcomparação: Verifica igualdade branch à branch
                if( count($difereces) == count($how_much_vars[0]) ){
                    echo 'Segunda subcomparação <br>';
                    // Verifica se a branch atual é variável
                    if($this->verify_regex($how_much_vars[0])){
                        echo 'branch atual é variável <br>';
                        $this->Route_Compatible = $route['url'];
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
            $this->url_found();
        }else{
            $this->url_not_found();
        }
    }

    public function verify_regex($branchs){
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

    public function url_not_found(){
        echo 'nada encontrado';
        exit(http_response_code(404));
    }

    public function url_found(){
        echo $this->Route_Compatible.'  <- compatible';
    }
        
}

$new = new Route;
$new->run();