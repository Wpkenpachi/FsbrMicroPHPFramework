# FSBR Render Engine
O fsbr engine aceita os seguintes tipos de estruras:

- Delimitadores
    - Tags PHP
- Variáveis
    - Interpolação
- CONDICIONAIS
    - If
    - Eelseif
    - Else
- REPETIÇÃO
    - Foreach

## Delimitador `:>` e `<:`
```php
<?php
$nome = 'Wesley';
?>
<body>
    // Utilizando as tags pra dar include em arquivos
    :> include 'config.php'; <:
    // Utilizando as tags pra uso de funções com o render.
    <h1> :> echo strtoupper($nome); <: </h1>
</body>
```

## Interpolação de Variáveis
```php
<?php
$nome = 'Wesley';
$jogos = ['Guerra' => ['Medalha de Honra'], 'Estrategia' => ['Xadrez']];
?>
<body>
// Interpolando a variável $nome
    <h1> {{ nome }} </h1>
// Interpolando o array de variaveis $jogos
    <ul>
        <li>{{ jogos['Guerra'][0] }}</li>
        <li>{{ jogos['Estrategia'][0] }}</li>
    </ul>
</body>
```

## Condicionais
```php
<?php
$idade = 20;
?>
<body>
    @if($idade < 18 ):
    <p> É menor de Idade, não pode votar. </p>
    @elseif($idade < 18 && $idade >= 16):
    <p> É menor de Idade, mas pode votar. </p>
    @else
    <p> É Maior de idade e pode votar. </p>
    @endif
</body>
```
**Nota:** Nas estruturas condicionais, as variáveis são escritas em sua forma normal, com o '$' atencendodo o nome da variável.

## Repetição
```php
<?php
$produtos = [
    ['nome' => 'Sapato','valor' => 150],
    ['nome' => 'Chapéu','valor' => 80]
    ];
?>
<body>
    <ul>
    @foreach($produtos as $produto):
        <ul>
            <li>{{ produto['nome'] }}</li>
            <li>{{ produto['valor'] }}</li>
        <ul>
    @endfor
    </ul>
</body>
```