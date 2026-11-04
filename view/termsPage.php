<?php $voltarUrl = app_previous_url(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Termos de Uso do simulador de restaurante Sakana.">
    <link rel="icon" type="image/png" href="view/images/logo.png">
    <title>Termos de Uso | Sakana</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body class="page-institucional">
    <?php $publicPage = 'termos'; include __DIR__ . '/partials/publicHeader.php'; ?>
    <main class="institucional-container">
        <article class="institucional-card" aria-labelledby="termos-titulo">
            <img src="view/images/logo.png" alt="Logo Sakana" class="institucional-logo">
            <h1 id="termos-titulo">Termos de Uso</h1>
            <p>O Sakana é um simulador acadêmico destinado ao aprendizado de rotinas de gerência,
                atendimento e cozinha de restaurante.</p>

            <h2>Uso permitido</h2>
            <p>Use o sistema somente para fins legais e educacionais. Informe dados verdadeiros,
                não compartilhe credenciais e não tente acessar funções ou dados de outras contas.</p>

            <h2>Conteúdo publicado</h2>
            <p>O usuário autorizado é responsável pelos dados, imagens e descrições que cadastrar.
                Não publique conteúdo ilegal, ofensivo, discriminatório, enganoso ou que viole direitos
                de terceiros. A gerência pode remover conteúdo incompatível com estes termos.</p>

            <h2>Disponibilidade e segurança</h2>
            <p>O projeto é acadêmico e pode sofrer manutenção ou indisponibilidade. Vulnerabilidades
                ou uso indevido devem ser comunicados à equipe pelo repositório oficial.</p>

            <h2>Privacidade</h2>
            <p>O tratamento de dados segue a
                <a href="<?= app_url('index.php?action=politicaPrivacidade') ?>">Política de Privacidade e LGPD</a>.</p>

            <nav class="institucional-actions" aria-label="Navegação">
                <a class="btn-secondary" href="<?= htmlspecialchars($voltarUrl, ENT_QUOTES, 'UTF-8') ?>">Voltar</a>
            </nav>
        </article>
    </main>
    <?php include __DIR__ . '/partials/publicFooter.php'; ?>
</body>
</html>
