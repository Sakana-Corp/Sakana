<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerência | Sakana</title>

    <link rel="stylesheet" href="/Sakana/view/css/style.css">
    <link rel="stylesheet" href="/Sakana/view/css/gerencia.css?v=1">
    <link rel="stylesheet" href="/Sakana/view/css/cardapio.css">
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
            <span>User : N/A</span>
            <img src="/Sakana/view/images/user.png" alt="Usuário" class="user-icon">
        </div>
    </header>

    <div class="layout">

        <aside class="sidebar">
            <a href="/Sakana/index.php?action=logadoGerencia&page=funcionarios" class="menu-btn">Funcionários</a>
            <a href="/Sakana/index.php?action=logadoGerencia&page=pedidos" class="menu-btn">Pedidos</a>
            <a href="/Sakana/index.php?action=logadoGerencia&page=cardapio" class="menu-btn">Cardápio</a>
            <a href="/Sakana/index.php?action=logadoGerencia&page=mesas" class="menu-btn">Mesas</a>

            <a href="/Sakana/index.php?action=loginGerencia" class="btn-setor">Trocar de setor</a>
        </aside>

        <main class="conteudo">
            <?php
                $pagina = $_GET['page'] ?? 'home';

                switch ($pagina) {
                    case 'funcionarios':
                        include __DIR__ . '/funcionarios.php';
                        break;

                    case 'pedidos':
                        include __DIR__ . '/pedidos.php';
                        break;

                    case 'cardapio':
                        include __DIR__ . '/cardapio.php';
                        break;

                    case 'mesas':
                        include __DIR__ . '/mesas.php';
                        break;

                    default:
                        echo '<div class="home-gerencia"></div>';
                        break;
                }
            ?>
        </main>

    </div>

</body>
</html>