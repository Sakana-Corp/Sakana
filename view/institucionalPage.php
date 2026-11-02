<?php $voltarUrl = app_previous_url(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o projeto | Sakana</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body class="page-institucional">
    <main class="institucional-container">
        <section class="institucional-card" aria-labelledby="institucional-titulo">
            <img src="view/images/logo.png" alt="Logo Sakana" class="institucional-logo">
            <h1 id="institucional-titulo">Sobre o projeto</h1>

            <section aria-labelledby="origem-titulo">
                <h2 id="origem-titulo">Origem</h2>
                <p>O Sakana é um projeto acadêmico desenvolvido por estudantes do 3º ano do curso Técnico em Informática da ETEC de Franco da Rocha.</p>
                <p>O simulador foi criado como parte da formação do grupo para aplicar conhecimentos de programação, banco de dados e desenvolvimento web em rotinas de gerenciamento de restaurante.</p>
            </section>

            <section aria-labelledby="autoria-titulo">
                <h2 id="autoria-titulo">Equipe do projeto</h2>
                <p>Petrick Preite, Sojiro Takemura, Maria Bononi, Giovanna Ferreira e Matheus Marangoni.</p>
            </section>

            <section aria-labelledby="contato-titulo">
                <h2 id="contato-titulo">Contato</h2>
                <p>Para conhecer o projeto ou entrar em contato com a equipe, acesse o repositório oficial:</p>
                <a class="institucional-contact" href="https://github.com/Sakana-Corp/Sakana" target="_blank" rel="noopener noreferrer">
                    github.com/Sakana-Corp/Sakana
                </a>
            </section>

            <nav class="institucional-actions" aria-label="Navegação">
                <a class="btn-secondary" href="<?= htmlspecialchars($voltarUrl, ENT_QUOTES, 'UTF-8') ?>">Voltar</a>
            </nav>
            <a class="home-privacy-link" href="index.php?action=politicaPrivacidade">Política de Privacidade</a>
        </section>
    </main>
</body>
</html>
