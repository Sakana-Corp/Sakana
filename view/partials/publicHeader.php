<?php $publicPage = $publicPage ?? ''; ?>
<header class="public-header">
    <a class="public-brand" href="<?= app_url('index.php') ?>" aria-label="Sakana - página inicial">
        <img src="<?= app_url('view/images/logo.png') ?>" alt="" class="public-brand-logo">
        <span>Sakana</span>
    </a>
    <nav class="public-nav" aria-label="Navegação principal">
        <a href="<?= app_url('index.php') ?>"<?= $publicPage === 'home' ? ' aria-current="page"' : '' ?>>Home</a>
        <a href="<?= app_url('index.php?action=login') ?>"<?= $publicPage === 'login' ? ' aria-current="page"' : '' ?>>Entrar</a>
        <a href="<?= app_url('index.php?action=cadastro') ?>"<?= $publicPage === 'cadastro' ? ' aria-current="page"' : '' ?>>Cadastrar</a>
    </nav>
</header>
