<?php  include $this->render_template('header.php');  ?>
<div>
    <header>
        <h1> Olá usuário <?=$user['id'];?>, <?=$user['nome'];?> <?=$user['sobrenome'];?>!</h1>
    </header>
    <div>
        <p> Informações de usuário: <br>
            Nome: <?=$user['nome'];?> <?=$user['sobrenome'];?> <br>
            Email: <?=$user['email'];?><br>
            Idade: <?=$user['idade'];?>
            <?php if( $user['idade'] >= 18 ): ?>
                <span> (Maior de Idade) </span>
            <?php else: ?>
                <span> (Menor de Idade) </span>
            <?php endif; ?>
        </p>

        <ul>
            <?php foreach($user['jogos'] as $jogo): ?>
            <li> <?=$jogo;?> </li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>
<?php  include $this->render_template('footer.php')  ?>