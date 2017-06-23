
# Explicando Algoritmo de Url amigável
_O script dessa classe se encontra [aqui](RouteClass.md)_
#### Url de Rotas criadas pelo usuário
> home

> home/settings

> home/{empresa}

> home/{empresa}/profile/{id}

##### Url atual no browser
> home/coca-cola/profile/2

# Primeiro Algoritmo de Comparação
A primeira comparação feita é em "route_string_comparison()"
onde vai ser comparada as string literais uma por uma:
> home == home/coca-cola/profile/2 `?` - [ `false` ] 

> home/settings `==` home/coca-cola/profile/2 `?` - [ `false` ]

> home/{empresa} `==` home/coca-cola/profile/2 `?` - [ `false` ]

> home/{empresa}/profile/{id} `==` home/coca-cola/profile/2 `?` - [ `false` ]

# Segundo Algoritmo de Comparação
A Não tendo obtido sucesso nessas comparações é chamada outra função pra comparar
de forma mais detalhada. A `route_tree_keys_comparison()`:

Aqui vamos passar novamente por todas as rotas salvas, pra comparar com  a rota 
que está no browser, só que dessa vez vamos quebrar tanto a url salva que está sendo testada, quanto a do browser, **ambas
em um array**, pra fazermos a comparação branch a branch (galho a galho), ficaria então:

PS1: A quebra em branches e feita pela '/', pela função `explode()`<br>

PS2: Tenha como branch, cada item do array.<br>

    Subcomparações:
-  _Existem ainda duas subcomparações aqui nessa fase_:<br>

-  _Essas comparações são em cascata, se uma for verdadeira, o algoritmo verifica a proxima_
    

    1. Subcomparação de quantidade de branches
    2. Subcomparação branch à branch (Pra saber se rota_salva[0] == url_browser[0])
        Dentro da comparação 2 verifica-se ainda se:

        - Verifica se a branch da rota salva é uma variável de url (Na prática {id} se está entre chaves),
          ou se ela é apenas uma string , se for string a comparação é feita normalmente 

          `rota_salva[0] == url_browser[0]`

            caso seja uma variável, o algotirmo entende que a a compatibilidade
          é verdadeira, exemplos:
            > `[0] => id == [0] => 1` , isso não é aceito.

            > `[0] => {id} == [0] => 1` , isso é aceito.


# Seguem as comparações
## **Rota 'home'**
    No caso dessa rota, ela nao se encaixa logo na primeira
    subcomparação
```php
Array 
(
    [0] => home
)

Array 
(
    [0] => home (true)
    [1] => coca-cola (false)
    [2] => profile (false)
    [3] => 2 (false)
)
```
Url de Rota Salva | Url Atual no Browser | Comparação
----------------- | -------------------- | ----------
home | home | true
`empty` | coca-cola | false
`empty` | profile | false
`empty` | 2 | false

## **Rota 'home/settings'**
    Perde ainda na primeira subcomparação, pois tem menos branches
    que a url que esta no browser
```php

Array 
(
    [0] => home
    [1] => settings
)

Array 
(
    [0] => home (true)
    [1] => coca-cola (false)
    [2] => profile (false)
    [3] => 2 (false)
)
```
Url de Rota Salva | Url Atual no Browser | Comparação
----------------- | -------------------- | ----------
home | home | true
settings | coca-cola | false
`empty` | profile | false
`empty` | 2 | false
## **Rota 'home/{empresa}'**
    No caso dessa rota, ela nao se encaixa logo na primeira
    subcomparação
```php
Array 
(
    [0] => home
    [1] => {empresa}
)

Array 
(
    [0] => home (true)
    [1] => coca-cola (true)
    [2] => profile (false)
    [3] => 2 (false)
)
```
Url de Rota Salva | Url Atual no Browser | Comparação
----------------- | -------------------- | ----------
home | home | true
{empresas} | coca-cola | true
`empty` | profile | false
`empty` | 2 | false
## **Rota 'home/{empresa}/profile/{id}'**
    Url Correta encontrada com mesmo numero de branches,
    e ainda com comparação branch à branch totalmente compatível
```php
Array 
(
    [0] => home
    [1] => {empresa}
    [2] => profile
    [3] => {id}
)

Array 
(
    [0] => home (true)
    [1] => coca-cola (true)
    [2] => profile (true)
    [3] => 2 (true)
)
```
Url de Rota Salva | Url Atual no Browser | Comparação
----------------- | -------------------- | ----------
home | home | true
{empresa} | coca-cola | true
profile | profile | true
{id} | 2 | true