# Fsbr Framework
O Fsbr Framework, é uma framework MVC Pattern feita em php, e visa tanto acelerar o desenvolvimento de Sistemas Web, Sites, e até Web Services (API's).

## Instalação

### Requisitos:
Ter o PHP, e o Composer instalados, são requisitos pra utilização do Fsbr Framework.
### Instalando:
Clone este repositório em algum diretório local. Abra um terminal neste mesmo diretório e digite o comando:

    $ composer install

## ROTAS 
De forma simples, as Rotas são a abstração organizada, das páginas que o sistemas pode ter. Contendo nelas informações como: Url , Controller e Action.

As rotas podem ser de 5 tipos. Especificamente separadas pelo tipo de requisição necessária pra o fim desejado. Então temos

* get
    - Comumente utilizado para obter html das páginas, e passar parâmetros via url.
* post 
    - Utilizado para submeter informações de formulário, entre outras coisas.
* patch
    - Mais utilizado para requisitar atualização de 1 ou mais campos de um registro. Utilizado principalmente em Api's
* put 
    - Mais utilizado para requisitar atualização de todos os campos de um registro. Utilizado principalmente em Api's
* delete
    - Mais utilizado para remoção/deleção de registros. Utilizado principalmente em Api's.

### Construíndo uma Rota
Existem duas formas de se construir uma rota.
A primeira e mais utilizada a fim de fazer testes, e aprender como se da a utilização da framework é:

### Rota com Controller imbutido
Aqui você informa a url da rota, e já cria o que seria o seu controller. Já podendo obter uma saída no browser, tanto utilizando o return, quanto um `echo()`,`print_r()`,`var_dump()`.
``` php
//  arquivo> routes.php
$app->get('profile/{id}', function(){
    // echo 'Hello World';
    return 'Olá Mundo!';
});
```


### Rota completa
Essa rota aqui, diferentemente da outra acima, especifica um 
controller(Class) e uma action (Function) pra lidar com os dados,
exibílos de lá mesmo ou chamar 

```php
$app->get('profile/{id}', 'MeuController@minha_Action');
```

## CONTROLLERS 
Os Controllers são as estruturas que vão cuidar tanto do acesso aos models(Fazendo _requisições_ e operações no banco), quanto de fazer o _data-bind_ para com uma view. Ou seja, trocando em palavras simples, o controller pode manusear informação do banco, e renderizar um html, podendo até, passar dados(variáveis) para essa view renderizada (html).
### Utilização dos Controllers
**ps**: Nome do Controller(da _Classe_), tem que ser o mesmo do arquivo, especificamente.

```php
<?php 
namespace app\controller;// Definindo o namespace do controller
use core\Controller;// Declarando o Controller principal, pra poder extender
class MeuController extends Controller{
    
}
```
### Actions, o que são?
As actions nada mais são que os métodos de um controller.
E são dentro deles que serão feitas as operações que citei na explicação do que
um controller faz.
### Criando uma action
```php
namespace app\controller;// Definindo o namespace do controller
use core\Controller;// Declarando o Controller principal, pra poder extender
    class MeuController extends Controller{
    public function minha_Action(){

    }
}
```
### Obtendo dados enviados via url e via body.

**ps2:** Lembrando que ele tem que estar entre a instância do `core\mvc`, e a chamada do método 
`run()`.

Logo abaixo, temos algumas rotas criadas, pro exemplo:
```php
<?php //arquivo> routes.php
// -1 rota, queremos pegar esse dado enviado via url {id}
$app->get('produto/{id}', 'MeuController@mostra');
// -2 rota, queremos adicionar um novo produto, e vamos pegar os dados
// desse produto via body da requisição post
$app->post('produto', 'MeuController@add');
$app->run();// função que roda o mvc
```
E aqui temos o Controller das rotas acima.
Note que no controller temos as actions especificadas nas rotas.
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
## MODELS
Os Models dentro de uma MVC Pattern, são as estruturas que cuidam do gerenciamento da base de dados do sistema.
Aqui você pode utilizar-se dessa estrutura de duas formas. Utilizando um ORM, o qual você pode instalar ( [Eloquent](https://laravel.com/docs/5.4/eloquent ) é altamente recomendado). [Aqui](Eloquent.md) se encontra a documentação para utilização do Eloquent junto ao Fsbr Framework.

A outra forma seria a tulização do sistema de Model nativo do framework. [Documentação](FsbrDatabase.md) do Fsbr Database


## RENDERIZANDO VIEW
Como citado na introdução do Controller, é possível chamar uma view através do controller.
### Chamando uma view, e passando parametros pra ela
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
<!--- arquivo view> app/views/teste.php  -->
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
Uma outra forma de você desenvolver suas páginas html
e de forma mais 'rápida' e 'agradável'. Seria através da render engine nativa do mvc. Segue abaixo um exemplo de uso, e escrita:
```php
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
### Exemplo de escrita, da Render Engine Nativa do MVC.
```html
<!-- arquivo view app/views/teste.php -->
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    @if(!empty($produto)):
        @foreach($produto as $p):
        <ul>
            <li>Nome do Produto: {{ p['nome'] }}<li>
            <li>Preço do Produto: {{ p['valor'] }} <li>
        </ul>
        @endforeach
    @endif;
</body>
</html>
```
**Nota:** [Documentação](RenderEngine.md) da Render Engine Nativa

## Generator 
Você certamente deve estar se perguntando se vai ter que criar todas essas estruturas apresentadas na documentação, na mão... Pois então, isso não vai ser necessário. O Mvc dispõe de um programa em linha de comando para criação de estruturas como Controllers, Dbmaps e Models (Caso esteja utilizando o ELoquent ou algum outro ORM), e até view (Com a possibilidade de você criar um modelo/template pra elas). Segue a documentação do [Generator](Generator.md).

