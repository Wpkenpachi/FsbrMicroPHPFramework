<?php
/*
:> include $this->render_template('header.php'); <:
<div>
    <header>
        <h1> Olá usuário {{ user['id'] }}, {{ user['nome'] }} {{ user['sobrenome'] }}!</h1>
    </header>
    <div>
        <p> Informações de usuário: <br>
            Nome: {{ user['nome'] }} {{ user['sobrenome']}} <br>
            Email: {{ user['email'] }}<br>
            Idade: {{ user['idade'] }}
            @if( $user['idade'] >= 18 ):
                <span> (Maior de Idade) </span>
            @else
                <span> (Menor de Idade) </span>
            @endif
        </p>

        <ul>
            @foreach($user['jogos'] as $jogo):
            <li> {{ jogo }} </li>
            @endforeach
        </ul>

    </div>
</div>
:> include $this->render_template('footer.php') <:
*/
?>