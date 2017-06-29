# DBmaps
Os Dbmaps, são facilitadores para criação rápida de tabelas simples.
Segue um exemplo de sintaxe simples, da modelagem de uma dbmap.

#### Primeramente você precisa criar essa dbmap
Você pode fazer isso manualmente, ou pelo Generator:

    php fsbr make dbmap produtos

Vá no diretório padrão pra onde vão os dbmaps `app/dbmap/`, e abra esse arquivo, por padrão ele vai vir assim:

```php
    <?php
    use core\Dbmap\DBlueprint;
    use core\Dbmap\DataTable;

    DataTable::run()->createTable('cliente', function(DBlueprint $table) {
            $table->int('id', 10)->increments()->primaryKey();
            
            return $table;
    });

```
Por padrão, o dbmod vem somente com uma chave primária setada. Porém você pode modelar a tabela da forma que quiser, dentro das possibilidades
do DBmaps. Essas possibilidades são:

## Tipos de campos

#### char
    table->char('nomeDoCampo', 10)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres)

#### varchar
    table->varchar('nomeDoCampo', 10)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres)

#### text
    table->text('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### blob
    table->blob('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### longText
    table->longText('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### longBlob
    table->longBlob('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### enum
    table->enum('nomeDoCampo', ['item1', 'item2', 3])
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $enums (Array com os parametros enum)

#### tinyInt
    table->tinyInt('nomeDoCampo', 10)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres)

#### smallInt
    table->smallInt('nomeDoCampo', 10)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres)

#### int
    table->int('nomeDoCampo', 10)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres)

#### bigInt
    table->bigInt('nomeDoCampo', 10)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres)

#### float
    table->float('nomeDoCampo', 3, 2)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres antes da virgula)

> $d (quantidade de caracteres depois da virgula)

#### double
    table->double('nomeDoCampo', 3, 2)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres antes da virgula)

> $d (quantidade de caracteres depois da virgula)

#### decimal
    table->decimal('nomeDoCampo', 3, 2)
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

> $size (quantidade de caracteres antes da virgula)

> $d (quantidade de caracteres depois da virgula)

#### dataType
    table->dataType('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### datatimeType
    table->datatimeType('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### timestampType
    table->timestampType('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### timeType
    table->timeType('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

#### yearType
    table->yearType('nomeDoCampo')
Equivalente ao tipo do SQL, e aceita os parametros:

> $name (nome do campo)

## Modificadores

#### unique()
Equivalente ao modificador do SQL.

    $table->int('numeDoCampo', 2)->unique();

#### notNull()
Equivalente ao modificador do SQL.

    $table->int('numeDoCampo', 2)->notNull();

#### increments()
Equivalente ao modificador AUTO_INCREMENT do SQL

    $table->int('numeDoCampo', 2)->increments();

#### primaryKey()
Equivalente ao modificador PRIMARY KEY do SQL

    $table->int('numeDoCampo', 2)->primaryKey();

#### onUpdate()
Equivalente ao modificador ON UPDATE do SQL

Aceita os parametros do SQL `cascade`, `restrict` e etc.

    $table->int('numeDoCampo', 2)->onUpdate();

#### onDelete()
Equivalente ao modificador ON DELETE do SQL

Aceita os parametros do SQL `cascade`, `restrict` e etc.

    $table->int('numeDoCampo', 2)->onDelete();

#### foreign()
Equivalente ao modificador FOREIGN KEY do SQL

E apesar de estar na seção de modificadores ele é uma nova atribuição criada:

Aceita os parametros

> $fk_name ( nome da chave primaria )

> $tb_field ( campo da tabela que vai ser chave local )

> $target_table ( tabela da chave estrangeira )

> $target_field ( campo da chave estrangeira )

    $table->foreign('fk_name', 'nomeDoCampo', 'nomeDaTabelaAlvo', 2)->onDelete('cascade')->onUpdate('cascade');

## Helper

####
Você pode debugar cada campo da query gerada pelo Generator, utilizando a função return All. 

    $table->int('id', 11)->increments()->primaryKey;
    $table->varchar('nome', 20);
    $table->varchar('email', 20)->unique();
    
    var_dump($table->returnAll());
    // return $table;

Para visualizar o resultado utilize o comando up_tables e especifique o nome desse dbmap

    php fsbr up_tables nomeDaTable

**Note:** Lembre-se de comentar a linha return $table;
