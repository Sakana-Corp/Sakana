<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerência | Sakana</title>

    <link rel="stylesheet" href="/Sakana/view/css/style.css">
    <link rel="stylesheet" href="/Sakana/view/css/gerencia.css?v=1">
    <link rel="stylesheet" href="/Sakana/view/css/perfil.css?v=1">
    <link rel="stylesheet" href="/Sakana/view/css/alerts.css">
</head>

<body class="page-gerencia">

    <header class="topbar">
        <div class="logo-area">
            <img src="/Sakana/view/images/logo.png" alt="Logo Sakana" class="logo">
            <span class="logo-text">SAKANA</span>
        </div>

        <div class="titulo-sistema">
            SIMULADOR DE RESTAURANTE
        </div>

        <div class="user-area">
            <span><?php if (isset($_SESSION['nomeUser'])): ?>
                <?= htmlspecialchars($_SESSION['nomeUser'], ENT_QUOTES, 'UTF-8') ?>
            <?php else: ?>
                Usuário não logado
            <?php endif; ?>
        </span>
        <a href="/Sakana/index.php?action=editarPerfil" class="user-profile-link">
            <img src="<?= htmlspecialchars($_SESSION['fotoPerfil'] ?? '/Sakana/view/images/user.png', ENT_QUOTES, 'UTF-8') ?>" alt="Usuário" class="user-icon">
        </a>
            
        </div>
    </header>

    <div class="layout">

        <aside class="sidebar">
            <a href="/Sakana/index.php?action=logadoGerencia&page=funcionarios" class="menu-btn">Funcionários</a>
            <a href="/Sakana/index.php?action=logadoGerencia&page=pedidos" class="menu-btn">Pedidos</a>
            <a href="/Sakana/index.php?action=logadoGerencia&page=cardapio" class="menu-btn">Cardápio</a>
            <a href="/Sakana/index.php?action=logadoGerencia&page=mesas" class="menu-btn">Mesas</a>

            <a href="/Sakana/index.php?action=painelAcesso" class="btn-setor">Trocar de setor</a>
        </aside>

        <main class="conteudo">
            <?php $flash = SessionHelper::getFlash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-toast alert-<?php echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?>" role="alert" aria-live="polite">
                    <span class="alert-text"><?php echo htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
                </div>
            <?php endif; ?>
            <?php if (!empty($arquivoConteudo) && file_exists($arquivoConteudo)): ?>
                <?php require $arquivoConteudo; ?>
            <?php else: ?>
                <div class="home-gerencia"></div>
            <?php endif; ?>
        </main>

    </div>

</body>
</html>