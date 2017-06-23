<?php include $this->render_template('header.php'); ?>
<div>
    <header>
        <h1> Olá, <?= $user['nome'].' '.$user['sobrenome'] ?>!</h1>
    </header>
    <div>
        <p> Informações de usuário: <br>
            Nome: <?= $user['nome'].' '.$user['sobrenome'] ?><br>
            Email: <?= $user['email'] ?><br>
        </p>
    </div>
</div>
<?php include $this->render_template('footer.php') ?>