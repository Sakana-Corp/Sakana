<?php
$setor = $_GET["setor"] ?? "gerencia";

$titulos = [
    "gerencia" => "Login da Gerência",
    "atendimento" => "Login do Atendimento",
    "cozinha" => "Login da Cozinha"
];

$titulo = $titulos[$setor] ?? "Login do setor";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?> | Sakana</title>
    <link rel="stylesheet" href="<?= app_url('view/css/style.css') ?>">
</head>
<body class="page">
    <?php $publicPage = 'login'; include dirname(__DIR__, 2) . '/partials/publicHeader.php'; ?>
    <?php
        SessionHelper::garanteSessaoIniciada();
        $flash = SessionHelper::getFlash();

        if ($flash && !in_array($flash["type"], ["error", "warning", "info", "success"])) {
            $flash = null;
        }
    ?>

    <?php if ($flash): ?>
        <div class="alert alert-toast alert-<?= htmlspecialchars($flash["type"], ENT_QUOTES, "UTF-8") ?>" role="alert" aria-live="polite">
            <span class="alert-text"><?= htmlspecialchars($flash["message"], ENT_QUOTES, "UTF-8") ?></span>
            <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
        </div>
    <?php endif; ?>

    <div class="container" style="flex-direction:row; gap:120px;">
        <div class="card">
            <h2><?= htmlspecialchars($titulo, ENT_QUOTES, "UTF-8") ?></h2>

            <form action="<?= app_url('index.php?action=entrarSetor') ?>" method="post" class="input-group">
                <input type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

                <input type="hidden"
                    name="setor"
                    value="<?= htmlspecialchars($setor, ENT_QUOTES, "UTF-8") ?>">

                <input type="email" name="email" placeholder="Email" required>

                <input type="password" name="senha" placeholder="Senha" required>

                <button type="submit" class="btn-primary">
                    Entrar
                </button>
            </form>

            <a href="<?= app_url('index.php?action=painelAcesso') ?>" class="btn-link">
                Voltar aos setores
            </a>
        </div>
    </div>
    <?php include dirname(__DIR__, 2) . '/partials/publicFooter.php'; ?>
</body>
<script src="<?= app_url('view/js/alerts.js') ?>" defer></script>
</html>