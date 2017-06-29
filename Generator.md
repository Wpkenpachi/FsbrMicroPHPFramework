# FSBR Generator
Com todas as estruturas que formam a mvc pattern, pode ser que fique cansativo
a criação manual desses arquivos, e é pra isso que o Fsbr dispõe de um gerador
de arquivos. Ele facilita tanto a criação de arquivos e estruturas, quanto também
serve pra um controle das mesmas, sendo possível ainda a listagem destas.

Segue a arvore de comandos e opções

Generator (fsbr)

- make
    - controller
        - {controllerName}
    - model
        - {modelName}
    - view
        - {viewName}
    - dbmap
        - {tableName}

- list
    - view
    - model
    - dbmap
    - route

- up_tables
    - {tableName}
    - all

- refresh_tables
    - {tableName}
    - all

- delete_tables
    - {tableName}        
    - all

## Comandos
Os comandos são os primeiros argumentos passados
ao gerador, seguido das opções e seus parametros.

### COMANDO MAKE
O make, é o comando criador de arquivos.
Sua sintaxe é simples:

    php fsbr make controller MeuController

    {Comando}  {TipoDeEstrutura} {nomeDoArquivo}

Possibilidades aceitas pelo make:
> controller

> model

> view

> dbmap


### COMANDO LISTS
O lists é o comando que lista estruturas e arquivos.
Sua sintaxe:
    
    php fsbr lists controller

    {Comando} {TipoDeEstrutura}

Possibilidades aceitas pelo make:

> controller. Lista todos seus Controllers.

> model. Lista todos seus Models.

> dbmap. Lista seus Dbmaps.

> route. Lista as suas rotas.

### UP TABLES
O uptables constrói as tabelas dos dbmaps.

    php fsbr up_tables all

    {Comando} {parametro}

Possibilidades:
Você pode subir a tabela de um dbmap específico, colocando o `nome` dele a frente do argumento up_tables, ou pode subir todos as tabelas de uma só vez, usando o `all`

### REFREH TABLES
O refresh_tables deleta e constrói novamente as tabelas dos dbmaps.

**Note:** Tabelas que tenham chaves estrangeiras podem dar erro no mysql ao tentar refazê-las ao usar este comando. A menos que se use a configuração `onDelete('cascade')` na tabela em questão, você terá que deleta-las manualmente, e só depois dar o refresh.

    php fsbr refresh_tables all

    {Comando} {parametro}

Possibilidades:
Você pode dar refresh em um dbmap específico, colocando o `nome` dele a frente do argumento refresh_tables, ou pode dar refresh em todos os seus dbmaps de uma só vez, usando o `all`

### DELETE TABLES
O delete_tables deleta as tabelas dos dbmaps.

**Note:** Tabelas que tenham chaves estrangeiras podem dar erro no mysql ao tentar deleta-las ao usar este comando. A menos que se use a configuração `onDelete('cascade')` na tabela em questão, você terá que deleta-las manualmente.

    php fsbr refresh_tables all

    {Comando} {parametro}

Possibilidades:
Você pode deletar um dbmap específico, colocando o `nome` dele a frente do argumento delete_tables, ou pode deletar todas as tabelas de uma só vez, usando o `all`.

### Criando novos diretórios para listagem
Com o Generator, você pode também criar novos argumentos
para o comando `lists`, pra que ele liste arquivos php de qualquer pasta que você queira. Para isso siga os passos:

> Abra o arquivo `path_map.json` que se encontra na raiz do projeto.

> Adicione uma nova linha e coloque o nome do novo comando.

> Adicione logo a frente o diretório alvo da listagem.
    
    {
    "controller" : "/../app//controller//",
    "template": "/../app//view//templates//",
    "model" : "/../app//model//",
    "view" : "/../app//view//",
    "dbmap" : "/../app//dbmap//",
    "mods": "/mods//",
    "typesList": "/types//",

    "novoComando": "/../newFolder//"
    }
    
> Adicione um arquivo php, e digite `php fsbr lists novoComando` no terminal.

    

## Observação :
*PS* : Se você ficou perdido quando se falou sobre dbmaps, aqui temos a [documentação ](DBmaps.md) desse utilitário dp Fsbr.

*PS2* : Os comandos refresh_tables e delete_tables, não deletam as dbmaps, mas sim as tabelas construídas por estes arquivos. A remoção de uma dbmap deve ser feita manualmente no diretório em que estes arquivos se encontram.