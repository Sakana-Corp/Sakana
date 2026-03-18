<!DOCTYPE html>
<html lang="pt-BR">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Gerência | Sakana</title>

<link rel="stylesheet" href="/Sakana/view/css/style.css">
<link rel="stylesheet" href="/Sakana/view/css/gerencia.css">

</head>

<body class="page-gerencia">

<!-- TOPO -->
<header class="topbar">
<div class="logo-area">
<img src="/Sakana/view/images/logo.png" class="logo">
<span class="logo-text">SAKANA</span>
</div>

<div class="titulo-sistema">
SIMULADOR DE RESTAURANTE
</div>

<div class="user-area">
<span>User : N/A</span>
<img src="/Sakana/view/images/user.png" class="user-icon">
</div>

</header>


<!-- LAYOUT -->
<div class="layout">

<!-- MENU LATERAL -->
<aside class="sidebar">

<a href="funcionarios.html" target="frame" class="menu-btn">Funcionários</a>
<a href="pedidos.html" target="frame" class="menu-btn">Pedidos</a>
<a href="cardapio.html" target="frame" class="menu-btn">Cardápio</a>
<a href="mesas.html" target="frame" class="menu-btn">Mesas</a>
<a href="/Sakana/index.php?action=logado" class="btn-setor">Trocar de setor</a>

</aside>


<!-- ÁREA PRINCIPAL -->
<main class="conteudo">
<iframe name="frame" src="funcionarios.html"></iframe>
</main>

</div>

</body>
</html>
