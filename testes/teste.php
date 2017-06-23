<?php

class Route {

    // Variável que vai receber as rotas salvas no sistema
    protected $Routes;

    // Função construtora, é executada assim que instanciamos um objeto
    // da classe Route
    function __construct($routes){
        $this->Routes = $routes;
    }

    // Guarda o tipo de método da requisição [ GET POST PUT PATCH DELETE {...} ]
    protected $Request_Method;

    // Guarda a Url que está sendo chamada no Browser
    protected $Url_Current;

    // Expressão regular que identifica as variaveis
    // de Url {id}
    protected $Find_Var_Regex = "/\{[a-z0-9\_]+\}/i";

    

    // É atribuída com a Url de rota salva compatível
    protected $Route_Compatible = null;

    // Função que inicia o processo de comparação de url de rotas
    public function run(){

        // Obtendo URL NO BROWSER
        $this->Url_Current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // ELIMINANDO BARRA inicial '/' da url no browser
        $this->Url_Current = substr($this->Url_Current, 1); 

        // Obtendo e tratando método da requisição.
		$this->Request_Method = strtolower($_SERVER['REQUEST_METHOD']);
 
        // Chamando função que trata os espaços em branco que podem ocorrer
        // na url em caso de por exemplo: '/home/' ...
        // Nesse caso um explode nas barras resultaria em [ 0 => , 1 => 'home' , 2 => ]
        // Essa função trata essas barras desnecessárias
        $this->Url_Current_Tree = $this->treat_url_slashes($this->Url_Current);

        // Chamando primeiro algoritmo de comparação
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

    // Primeiro ALgoritmo de comparação de Urls
    private function route_string_comparison(){
        // Percorre todas as Urls de Rotas reconhecidas pelo sistema
        // Caso haja compatibilidade a variável $this->Route_Compatible
        // é preenchida com a url salva, que for compatível e para o processo de
        // laço de percorrimento de urls
        foreach($this->Routes as $route){
            if($route['url'] == $this->Url_Current){
                $this->Route_Compatible = $route['url'];
                break;
            }
        }

        // Depois de ter percorrido as urls ou ter encontrado uma compatível e saído do laço
        // Fazemos uma verificação pra saber se existe $this->Route_Compatible ou se ela ainda
        // é nula. 
        if($this->Route_Compatible){
            $this->url_found();
        }
        // Se for nula significa que não foi encontrada rota compatível
        // e então partimos pro segundo algoritmo de comparação 
        else{
            $this->route_tree_keys_comparison();
        }
    }


    // Segundo ALgoritmo de comparação de Urls
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
                // Segunda subcomparação: Verifica igualdade branch à branch
                if( count($difereces) == count($how_much_vars[0]) ){
                    // Chama função que verifica se isso é uma variável de url {id}
                    // se form a compatibilidade dessa branch é aceita como true
                    // e Route_Compatible recebe a url salva atual que está sendo
                    // comparada.
                    if($this->verify_regex($how_much_vars[0])){
                        $this->Route_Compatible = $route['url'];
                        break;
                    }
                }
                // Caso a primeira subcomparação seja falsa Route_Compatible ganha null
                // e o laço então damos continue, pra que possamos testar o a proxima url salva
                else{
                    $this->Route_Compatible = null;
                    continue;
                }
            }
            // Caso a primeira subcomparação seja falsa Route_Compatible ganha null
            // e o laço então damos continue, pra que possamos testar o a proxima url salva
            else{
                $this->Route_Compatible = null;
                continue;
            }
        endforeach;

        // Depois de ter percorrido as urls ou ter encontrado uma compatível e saído do laço
        // Fazemos uma verificação pra saber se existe $this->Route_Compatible ou se ela ainda
        // é nula. 
        if($this->Route_Compatible){
            $this->url_found();
        }
        // Se for nula significa que não foi encontrada rota compatível
        // e então concluímos que a rota que o usuario tentou acessar na existe no sistema
        // então chamamos a função que retorna código 404 e encerra a execução do script
        else{
            $this->url_not_found();
        }
    }

    // Função Helper (Ajudante) que verifica se uma branch é uma variável
    // O algoritmo faz isso atravez da regex que definimos lá em cima Find_Var_Regex
    // Retorna true, se for variável, e false caso não seja.
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

    // Função que é chamada caso nenhum dos algoritmos de comparação
    // encontre uma url salva compatível com a url do browser
    // Retorna um erro 404 e encerra o script.
    public function url_not_found(){
        exit(http_response_code(404));
    }

    // Só a nível de retorno, essa função é chamada, quando é encontrada
    // uma url salva compatível com a que está no browser
    public function url_found(){
        echo $this->Route_Compatible."&nbsp<= ROTA COMPATIVEL";
    }
        
}

// Aqui vamos criamos algumas rotas teste, 'cadastradas no sistema'
$Routes = 
[ 
    0 => ['url' => 'home'],
    1 => ['url' => 'home/settings'],
    2 => ['url' => 'home/{empresa}'],
    3 => ['url' => 'home/{empresa}/profile/{id}']
];

// Aqui instanciamos um objeto da classe Route
$new = new Route($Routes);
// Iniciamos o processo de comparação
$new->run();