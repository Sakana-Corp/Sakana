<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Sakana/view/css/style.css">
    <title>Cadastro | Sakana</title>
</head>
<body class="page">
    <div class="container" style="flex-direction:row; gap:120px;">
        <!-- DIV CADASTRO -->
        <div class="card">
            <h2 style="color: var(--dark-blue);">CADASTRAR</h2>

            <form class="input-group" action="/Sakana/index.php?action=cadastrar" method="POST">
                <input type="text" name="txtNome" placeholder="Nome" minlength="8" maxlength="30" required>
                <input type="email" name="txtEmail" placeholder="Email" minlength="8" maxlength="50" required>
                <input type="password" name="txtSenha" placeholder="Senha" minlength="8" maxlength="16" autocomplete="new-password" required>
                <input type="password" name="txtConfirmaSenha" placeholder="Confirmar senha" minlength="8" maxlength="16" autocomplete="new-password" required>

                <button type="submit" name="btnEnviar" class="btn-primary">CADASTRAR</button>
            </form>
        </div>
</div>

</body>
</html>
