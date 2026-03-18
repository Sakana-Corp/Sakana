<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | Sakana</title>

<link rel="stylesheet" href="/Sakana/view/css/style.css">

</head>

<body class="page">

<div class="container" style="flex-direction:row; gap:120px;">

    <!-- DIV LOGIN -->
    <div class="card">

        <h2 style="color: var(--dark-blue);">LOGAR</h2>

        <form class="input-group" action="/Sakana/index.php?action=logado" method="POST">
            <input type="email" name="txtEmail" placeholder="Email" required>
            <input type="password" name="txtSenha" placeholder="Senha" required>

            <button type="submit" class="btn-primary">LOGAR</button>
        </form>

        <a href="/Sakana/index.php?action=cadastro" class="btn-primary" style="width: 100%; background-color: var(--dark-blue);">
            CADASTRAR
        </a>
    </div>

</div>

</body>
</html>
