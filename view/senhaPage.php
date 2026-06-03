<?php
    require_once __DIR__ . "/../config/config.php";
    $flash = SessionHelper::getFlash();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha | Sakana</title>
    <link rel="stylesheet" href="/Sakana/view/css/style.css">
</head>
<body class="page">
    <?php if ($flash): ?>
        <div class="alert-toast alert-<?php echo $flash['type']; ?>">
            <span><?php echo htmlspecialchars($flash['message']); ?></span>
            <button class="alert-close">&times;</button>
        </div>
    <?php endif; ?>

    <div class="container" style="flex-direction: column;">
        <div class="card">
            <h2 style="color: var(--dark-blue);">RECUPERAR SENHA</h2>

            <form class="input-group" action="/Sakana/index.php?action=atualizarSenha" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>" required>
                
                <input type="email" name="email" placeholder="Digite seu email" maxlength="50" required>
                <input type="password" name="novaSenha" placeholder="Digite a nova senha" minlength="8" maxlength="128" autocomplete="new-password" required>

                <button type="submit" class="btn-primary">Alterar senha</button>
            </form>
            
            <a href="/Sakana/index.php?action=login" class="btn-link" style="margin-top: 10px; font-size: 0.9em;">
                Voltar para login
            </a>
        </div>
    </div>

    <script src="/Sakana/view/js/alerts.js" defer></script>
</body>
</html>