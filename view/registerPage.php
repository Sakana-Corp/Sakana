<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Sakana/view/css/style.css">
    <title>Cadastro | Sakana</title>
</head>
<body class="page" role="application" aria-label="Página de Cadastro">

    <?php
        SessionHelper::garanteSessaoIniciada();
        $flash = SessionHelper::getFlash();
    ?>

    <?php if ($flash): ?>
        <div class="alert alert-toast alert-<?= htmlspecialchars($flash["type"], ENT_QUOTES, "UTF-8") ?>"
        role="alert"
        aria-live="polite"
        aria-atomic="true">
            <span class="alert-text"><?= htmlspecialchars($flash["message"], ENT_QUOTES, "UTF-8") ?></span>
            <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
        </div>
    <?php endif; ?>

    <main class="container container-row">
        <!-- DIV CADASTRO -->
        <section class="card" aria-labelledby="register-title">

            <h1 id="register-title" class="title-primary">CADASTRAR</h1>

            <form class="input-group"
                  action="/Sakana/index.php?action=cadastrar"
                  method="POST"
                  aria-label="Formulário de cadastro de novo usuário">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

                <div class="form-group">
                    <label for="nome-input">Nome</label>
                    <input id="nome-input"
                           type="text" 
                           name="txtNome" 
                           placeholder="Seu nome completo" 
                           minlength="2" 
                           maxlength="30" 
                           required
                           aria-required="true"
                           aria-label="Nome completo">
                </div>

                <div class="form-group">
                    <label for="email-register-input">Email</label>
                    <input id="email-register-input"
                           type="email" 
                           name="txtEmail" 
                           placeholder="seu@email.com" 
                           maxlength="50" 
                           required
                           aria-required="true"
                           aria-label="Email para cadastro">
                </div>

                <div class="form-group">
                    <label for="password-register-input">Senha</label>
                    <input id="password-register-input"
                           type="password" 
                           name="txtSenha" 
                           placeholder="Mínimo 8 caracteres" 
                           minlength="8" 
                           maxlength="16" 
                           autocomplete="new-password" 
                           required
                           aria-required="true"
                           aria-label="Senha (mínimo 8 caracteres)">
                </div>

                <div class="form-group">
                    <label for="confirm-password-input">Confirmar Senha</label>
                    <input id="confirm-password-input"
                           type="password" 
                           name="txtConfirmaSenha" 
                           placeholder="Confirme sua senha" 
                           minlength="8" 
                           maxlength="16" 
                           autocomplete="new-password" 
                           required
                           aria-required="true"
                           aria-label="Confirmação de senha">
                </div>

                <button type="submit"
                        name="btnEnviar" 
                        class="btn-primary"
                        aria-label="Enviar formulário de cadastro">
                    CADASTRAR
                </button>
            </form>
        </section>
    </main>


    <script src="/Sakana/view/js/alerts.js" defer></script>
</body>
</html>
