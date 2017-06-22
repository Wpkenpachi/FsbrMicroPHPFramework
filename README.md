## ROTAS 
* get
* post 
* patch
* put 
* delete

###### Route type 01
``` php
// arquivo> public/index.php
$app->get('profile/{id}', function(){
    // echo 'Hello World';
    return 'Olá Mundo!';
});
```

###### Route type 02
Essa rota aqui, diferentemente da outra acima, especifica um 
controller(Class) e uma action (Function) pra lidar com os dados,
exibílos de lá mesmo ou chamar 

```php
$app->get('profile/{id}', 'MeuController@minha_Action');
```

## CONTROLLERS 
###### Criando Controller
**ps**: Nome do Controller, tem que ser o mesmo do arquivo, especificamente

```php
<?php 
namespace app\controller;// Definindo o namespace do controller
use core\Controller;// Declarando o Controller principal, pra poder extender

class MeuController extends Controller{
    public function minha_Action(){

    }
}
```

###### Obtendo dados enviados via url e via body, da rota criada.
```php
<?php //arquivo> public/index.php
{..}
$app = new core\mvc;
// -1 rota, queremos pegar esse dado enviado via url {id}
$app->get('produto/{id}', 'MeuController@mostra');
// -2 rota, queremos adicionar um novo produto, e vamos pegar os dados
// desse produto via body da requisição post
$app->post('produto', 'MeuController@add');
$app->run();// função que roda o mvc
```
```php
<?php //arquivo> app/controller/MeuController.php
namespace app\controller;
use core\Controller;

class MeuController extends Controller{
    
    // Action da nossa rota -1
    public function mostra(){
    // digamos que o usuário tenha acessado produto/2
    // getParam( mesmo nome que colocamos la na rota )

    $id_do_produto = $this->getParam('id'); //id_do_produto = 2
    
    // Se quisessemos pegar mais de um parametro, no caso de uma rota
    // que tivesse url categoria/{id_cat}/produto/{id_prod}
    $params = $this->getParams(); // ele retorna um array com os parametros e seus valore

    }

    // Action da nossa rota -2
    public function add(){
    // digamos que a requisição tenha enviado body com os seguintes dados
    // { nome: 'Sapato', valor: 300 }

    // Pegando valores separadamente
    $produto_preco = getBodyAttr('valor');
    
    // Se quisermos todo o corpo
    $produto = getBody();
        
    }

}
```

## RENDER 

###### Como chamar um html pra ser renderizado, e passar variáveis pra ele
```php
// rota:
$app->get('produto/{id}', 'MeuController@mostra');
```
```php
//arquivo> app/controller/MeuController.php
<?php
namespace app\controller;
use core\Controller;

class MeuController extends Controller{
    
    public function mostra(){
        $produto_id = getParam('id');

        // Buscar no banco dados do produto o qual temos o ID já.
        $db = new \PDO('mysql:host=localhost;dbname=mvc', 'root', 'mysql');
		$query = $db->prepare("SELECT * FROM users WHERE id = $produto_id");
		$query->execute();
		$data = $query->fetch();

        /*
         Se o html tiver diretamente dentro da pasta views vc pode colocar direto
         Segundo parametro tem que ser um array dessa forma ['produto' =>  $dados]
         No caso tu vai acessar os dados do produto por $produto no html
        */
		$this->toRender('teste.php', ['dados' => $data]);
        // caso estivesse dentro de views/produtos/teste.php ficaria
        // $this->toRender('protudos/teste.php', ['produto' => $data]);
    }

}
```
```html
<!--- arquivo php/view> app/views/teste.php  -->
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <ul>
        <li>Nome do Produto: <?= $produto['nome'] ?> <li>
        <li>Preço do Produto: <?= $produto['valor'] ?> <li>
    </ul>
</body>
</html>
```

## RENDER ENGINE
```php
/*
Existe ainda uma outra forma de você desenvolver suas páginas html
e de forma mais 'rápida' e 'agradável'. Que seria atravez da render engine 
nativa do mvc.
*/

// rota:
$app->get('produto/{id}', 'MeuController@mostra');

//arquivo> app/controller/MeuController.php
<?php
namespace app\controller;
use core\Controller;

class MeuController extends Controller{
    
    public function mostra(){
        $produto_id = getParam('id');

        // Buscar no banco dados do produto o qual temos o ID já.
        $db = new \PDO('mysql:host=localhost;dbname=mvc', 'root', 'mysql');
		$query = $db->prepare("SELECT * FROM users WHERE id = $produto_id");
		$query->execute();
		$data = $query->fetch();

        /*
         Aqui você precisa passar o terceiro parametro como true.
         Dizendo que você quer utilizar o render_engine
        */
		$this->toRender('teste.php', ['produto' => $data], true);
    }

}
```
```html
<!-- arquivo php/view -->
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    @if(!empty($produto)):
        @foreach($produto as $p):
        <ul>
            <li>Nome do Produto: {{ $p['nome'] }}<li>
            <li>Preço do Produto: {{ $p['valor'] }} <li>
        </ul>
        @endforeach
    @endif;
</body>
</html>
```