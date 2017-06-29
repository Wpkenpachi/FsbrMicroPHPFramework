# Fsbr Database
A estrutura nativa do Fsbr, para lidar com o banco de dados é concentrada em um único
Model, o `FSBRDatabase`, que pode ser chamado diretamente dentro de qualquer controller.
Segue o exemplo da sintaxe de uma consulta ao banco:
```php
<?php
namespace app\controller;
use core\Controller;


class MeuController extends Controller
{
	
	public function index(){
            $data = $this->Database
                ->select('tableName', 'nome')
                ->where('id', 2)
                ->all();
	}
}
```

## CRUD

As funcionalidades mais corriqueiras no uso do banco de dados
são as do CRUD _(Create , Read, Update, Delete)_ e com o FSBRDatabase
essas operações podem ser feitas de forma bem simples. Segue a sintaxe:

```php
$this->Database->create('tableName', ['nome' => 'Name', 'age' => 20]);
$this->Database->select('tableName', ' * ');
$this->Database->update('tableName', ['nome' => 'NameUpdated', 'age' => 22]);
$this->Database->delete('tableName');
```
## CRUD Parâmetros

Cada query do CRUD tem seus próprios parâmetros, vamos conhecê-los.

### Create
O create() recebe 2 parâmetros:
> Primeiro: Uma string, com o nome da tabela que será afetada.

> Segundo: Um array com os dados a serem insetidos.
```php
create('tableName', ['nome' => 'Name', 'age' => 20]);
// INSERT INTO tableName (nome, age) VALUES ('Name', '20')
```
### Select
O select() recebe 2 parâmetros:
> Primeiro: Uma string, com o nome da tabela que será afetada.

> (Opcional) Segundo: Uma string, com os dados do registro que você quer retornar, caso você não os especifique, o valor padrão é " * ".

```php
select('tableName', 'nome,email');
// SELECT nome,email FROM tableName
```
### Update
O update() recebe 2 parâmetros:
> Primeiro: Uma string, com o nome da tabela que será afetada.

> Segundo: Um array com os dados de atualização.

```php
update('tableName', ['nome' => 'Wesley']);
// UPDATE tableName SET (nome) VALUES ('Wesley')
```
### Delete
O delete() recebe 1 parametro:
> Primeiro: Uma string, com o nome da tabela que será afetada.

```php
delete('tableName');
```
## Condição ( WHERE )

Para a maioria das querys que você utilizar no banco, certamente
você vai querer inpor uma condição pra que esta atenda sua instruções e responda de acordo. Pra isso a linguagem SQL dispõe das _Conditions_. E pra utilizá-las você as encadeia com a função
da query que pretende utilizar, segue o exemplo:

O metodo where(), adiciona a palavra chave WHERE e a condicional na string da query, caso esta seja passada.

```php
$this->Database->select('users')->where();
// SELECT * FROM users WHERE
```

```php
$this->Database->select('users')->where('id', 1);
// SELECT * FROM users WHERE id = 1
```

```php
$this->Database->select('users')->where('id', '>' , 1);
// SELECT * FROM users WHERE id > 1
```
Nota: Usar apenas 2 argumentos é equivalente a dizer que o  `'argumento1 = argumento2'`. 3 argumentos, lhe da a liberdade de especificar o operador assim como é feito no exemplo acima.

PS: O `where()` é utilizado dessa mesma forma também com as outras querys

## Condicionais dentro do WHERE

Existem ainda outros tipos de metodos que se pode  usar para criar condições mais completas e complexas.

### Operador **and ( )**
O operador and, adiciona a palavra chave AND e os argumentos na string da query, caso estes sejam passados.
```php

$this->Database->select('users')->where()->and();
// SELECT * FROM users WHERE id = 1 AND

$this->Database->select('users')->where('id', 1)->and('id', 2);
// SELECT * FROM users WHERE id = 1 AND id = 2

$this->Database->select('users')->where('id', 1)->and('id','<', 5);
// SELECT * FROM users WHERE id = 1 AND id < 5
```


### Operador **or ( )**
O operador and, adiciona a palavra chave OR e os argumentos na string da query, caso estes sejam passados.
```php

$this->Database->select('users')->where('id', 1)->or();
// SELECT * FROM users WHERE id = 1 OR

$this->Database->select('users')->where('id', 1)->or('id', 2);
// SELECT * FROM users WHERE id = 1 OR id = 2

$this->Database->select('users')->where('id', 1)->or('id','<', 5);
// SELECT * FROM users WHERE id = 1 OR id < 5
```

### Operador **not ( )**
O operador and, adiciona a palavra chave NOT e os argumentos na string da query, caso estes sejam passados.
```php

$this->Database->select('users')->where()->not();
// SELECT * FROM users WHERE NOT

$this->Database->select('users')->where('id', 1)->not('id', 2);
// SELECT * FROM users WHERE id = 1 NOT id = 2

$this->Database->select('users')->where('id', 1)->not('id','<', 5);
// SELECT * FROM users WHERE id = 1 NOT id < 5
```

### Operador **andWhere ( )**
O metodo andWhere além de adicionar o operador AND na string da query, ainda coloca os argumentos entre parenteses.
```php

$this->Database->select('users')->where()->andWhere(['nome', 'wesley'] , ['id', '>', 1]);
// SELECT * FROM users WHERE (nome = 'wesley' AND id > 1)
```
PS: **Todos os argumentos passados devem ser arrays**

### Operador **orWhere ( )**
O metodo orWhere além de adicionar o operador OR na string da query, ainda coloca os argumentos entre parenteses.

```php
$this->Database->select('users')->where()->orWhere(['nome', 'wesley'] , ['id', '>', 1]);
// SELECT * FROM users WHERE (nome = 'wesley' OR id > 1)
```

### Operador **customWhere ( )**
O metodo customWhere permite a customização manual de uma condição, por esse motivo, é necessário que ou você adicione algum método ou operador antes da utilização dele.

```php
$this->Database->select('users')->customWhere();
// SELECT * FROM users
```

```php
$this->Database->select('users')->where('id', 2)->customWhere("AND (nome = 'wesley' OR pass = 213)");
// SELECT * FROM users WHERE id = 2 AND (nome = 'wesley' OR pass = 213)
```

## Delimitador

É possível delimitar uma quantidade de registros a serem afetados por uma query.

```php
$this->Database->delete('users')->where('id', '>' ,2)->limit(10);
// DELETE FROM users WHERE id > 2 LIMIT 10
```

## Ordenador

Caso você queira receber registro de uma consulta, de forma ordenada, também é possível.

```php
$this->Database->select('users')->orderBy('ASC');
// SELECT * FROM users ORDER BY ASC
```

```php
$this->Database->select('users')->orderBy('nome');
// SELECT * FROM users ORDER BY nome
```
```php
$this->Database->select('users')->orderBy('nome ASC, email DESC');
// SELECT * FROM users ORDER BY nome ASC, email DESC
```
## Obtendo o Resultado das Querys

### Metodo **all ()**
Retorna um `Array` com todos os resultados obtidos na query. Mais utilizado quando se espera mais de um retornos de registro.
```php
$this->Database->select('users')->orderBy('nome ASC, email DESC')->all();
/*
Array (
    [0] => [
        ['nome'] => 'Amelia',
        ['idade'] => 20
    ],
    [1] => [
        ['nome'] => 'Bernardo',
        ['idade'] =>39
    ]
)
*/
```

### Metodo **single ()**
Retorna o primeiro registro obtido como resultado da query. Mais utilizado quando se espera apenas um retornos de registro.
```php
$this->Database->select('users')->where('id', 1)->single();
/*
Array (
    [0] => [
        ['nome'] => 'Amelia',
        ['idade'] => 20
    ]
)
*/
```

### Metodo **confirm ()**
Retorna o número de registros afetados pela query. Pode ser utilizado pra confirmação de que a operação no banco foi realizada com sucesso. Caso ocorra algum erro retorna 0.
```php
$this->Database->delete('users')->where('id', 2)->confirm();
// 1
```
```php
$this->Database->update('users', ['pass', '4321'])->where('id', 2)->confirm();
// 1
```
### Metodo **lastInsertId ()**
Retorna o id do último registro inserido no banco. É uma boa pra confirmar a inserção de um dado no banco, e ainda conseguir o id desse registro.
```php
$this->Database->create('users', ['nome' => 'John', 'idade' => 20])->lastInsertId();
// 17
```

## Debug

Se você quiser testar como está ficando a sintaxe da sua query você pode utilizar o método returnWhere().

```php
$this->Database->select('users')->where()->andWhere(['nome', 'wesley'] , ['id', '>', 1])->returnWhere();
// mostra na tela -> SELECT * FROM users WHERE (nome = 'wesley' AND id > 1)
```