<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sakana</title>
    <link rel="stylesheet" href="/Sakana/view/css/style.css">
</head>
<body class="page">

    <div class="container">
        <h2 style="color: var(--dark-blue); margin-bottom: 20px; letter-spacing: 2px;">LOGAR NO SISTEMA</h2>
        
    <form class="card" action="/Sakana/index.php?action=logadoGerencia" method="POST">

        <div class="input-group">
            <input type="email" placeholder="E-mail">
            <input type="password" placeholder="Senha">
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; margin-bottom: 15px;">
            ENTRAR
        </button>

        <a href="/Sakana/index.php?action=logado" style="text-decoration: none; width: 100%;">
            <button type="button" class="btn-primary" style="width: 100%; background-color: var(--dark-blue);">
                VOLTAR
            </button>
        </a>

    </form>

    </div>

</body>
</html>
