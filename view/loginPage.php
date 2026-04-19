<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | Sakana</title>

<link rel="stylesheet" href="/Sakana/view/css/style.css">
</head>

<body class="page-login" role="application" aria-label="Página de Login">

    <?php
        SessionHelper::garanteSessaoIniciada();
        $flash = SessionHelper::getFlash();

        if ($flash && !in_array($flash["type"], ["error", "warning", "info"])) {
            $flash = null;
        }
    ?>

    <?php if ($flash): ?>
        <div class="alert alert-toast alert-<?= htmlspecialchars($flash["type"], ENT_QUOTES, "UTF-8") ?>" role="alert" aria-live="polite">
            <span class="alert-text"><?= htmlspecialchars($flash["message"], ENT_QUOTES, "UTF-8") ?></span>
            <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
        </div>
    <?php endif; ?>

    <main class="container container-row">
        <section class="card" aria-labelledby="login-title">
            <h1 id="login-title" class="title-primary">LOGAR</h1>

            <form class="input-group"
                  action="/Sakana/index.php?action=logado"
                  method="POST"
                  aria-label="Formulário de login">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

                <div class="form-group">
                    <label for="email-input">Email</label>
                    <input id="email-input"
                           type="email"
                           name="txtEmail"
                           placeholder="seu@email.com"
                           maxlength="50"
                           required
                           aria-required="true"
                           aria-label="Email para login">
                </div>
                <div class="form-group">
                    <label for="password-input">Senha</label>
                    <input type="password"
                           name="txtSenha"
                           placeholder="Sua senha"
                           minlength="8"
                           maxlength="16"
                           autocomplete="current-password"
                           required
                           aria-required="true"
                           aria-label="Senha para login">
                </div>


                <button type="submit" class="btn-primary" aria-label="Fazer login no sistema">
                    LOGAR
                </button>
            </form>

            <a href="/Sakana/index.php?action=cadastro"
               class="btn-primary btn-secondary btn-link"
               aria-label="Ir para página de cadastro de novo usuário">
                CADASTRAR
            </a>
        </section>
    </main>

    <script src="/Sakana/view/js/alerts.js" defer></script>
</body>
</html>