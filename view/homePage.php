<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKANA | Simulador de restaurante</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body class="page-home">
    <?php $publicPage = 'home'; include __DIR__ . '/partials/publicHeader.php'; ?>

    <main class="home-container" id="home-container">
        <section class="home-brand" aria-label="Sakana">
            <img src="view/images/logo.png" alt="Logo Sakana" id="logo">
            <h1 class="titulo">SAKANA</h1>
            <h2 class="subtitulo">SIMULADOR DE ROTINA DE RESTAURANTE</h2>
            <p class="home-intro">Simulador acadêmico para conhecer e exercitar rotinas de gerência, atendimento e cozinha.</p>

            <div class="home-actions">
                <a class="btn-primary" href="index.php?action=login">
                    Iniciar Simulador
                </a>
                <button class="btn-secondary" type="button" id="about-toggle" aria-expanded="false" aria-controls="about-panel">
                    Sobre e como usar
                </button>
            </div>
        </section>

        <section class="about-panel" id="about-panel" aria-labelledby="about-title" hidden>
            <h2 id="about-title">Conheça o Sakana</h2>
            <p><strong>O que é:</strong> um simulador acadêmico de rotinas de restaurante. Os pedidos são registrados para mesas e podem ser consultados por mesa.</p>
            <p><strong>Para quem:</strong> estudantes e pessoas que querem conhecer as tarefas disponíveis nos setores de gerência, atendimento e cozinha.</p>

            <h3>Como usar</h3>
            <ol class="home-steps">
                <li>Cadastre a conta inicial da gerência ou entre com uma conta já cadastrada.</li>
                <li>Na gerência, cadastre colaboradores, mesas e itens do cardápio. Os colaboradores cadastrados podem entrar nos setores de atendimento ou cozinha com suas próprias credenciais.</li>
                <li>No atendimento, selecione uma mesa disponível e registre os produtos. Na cozinha, consulte os pedidos e o resumo por mesa.</li>
                <li>No resumo, confira os produtos e o total; ao fechar a mesa, ela fica marcada como indisponível.</li>
            </ol>
            <p class="home-project-note">O Sakana é um projeto acadêmico desenvolvido por estudantes do curso Técnico em Informática da ETEC de Franco da Rocha.</p>
            <a class="home-privacy-link" href="index.php?action=politicaPrivacidade">Política de Privacidade</a>
            <br><br>
            <a class="btn-link" href="index.php?action=institucional">Origem do projeto e contato</a>
        </section>
    </main>

    <?php include __DIR__ . '/partials/publicFooter.php'; ?>

    <script>
        const aboutToggle = document.getElementById('about-toggle');
        const aboutPanel = document.getElementById('about-panel');
        const homeContainer = document.getElementById('home-container');
        let closeTimer;
        let closeTransitionHandler;

        aboutToggle.addEventListener('click', () => {
            const isOpen = aboutToggle.getAttribute('aria-expanded') === 'true';

            clearTimeout(closeTimer);
            if (closeTransitionHandler) {
                aboutPanel.removeEventListener('transitionend', closeTransitionHandler);
                closeTransitionHandler = null;
            }

            aboutToggle.setAttribute('aria-expanded', String(!isOpen));
            aboutToggle.textContent = isOpen ? 'Sobre e como usar' : 'Fechar';

            const finishClosing = () => {
                if (aboutToggle.getAttribute('aria-expanded') === 'false') {
                    aboutPanel.hidden = true;
                    aboutPanel.style.maxHeight = '';
                }
            };

            closeTransitionHandler = (event) => {
                if (event.target !== aboutPanel || event.propertyName !== 'max-height') {
                    return;
                }

                if (aboutToggle.getAttribute('aria-expanded') === 'true') {
                    aboutPanel.style.maxHeight = 'none';
                } else {
                    finishClosing();
                }
            };
            aboutPanel.addEventListener('transitionend', closeTransitionHandler);

            if (isOpen) {
                aboutPanel.style.maxHeight = `${aboutPanel.getBoundingClientRect().height}px`;
                aboutPanel.getBoundingClientRect();
                homeContainer.classList.remove('has-about');
                aboutPanel.style.maxHeight = '0px';

                const closeDuration = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 400 : 650;
                closeTimer = window.setTimeout(finishClosing, closeDuration);
                return;
            }

            aboutPanel.hidden = false;
            aboutPanel.style.maxHeight = '0px';
            aboutPanel.getBoundingClientRect();
            homeContainer.classList.add('has-about');
            aboutPanel.style.maxHeight = `${aboutPanel.scrollHeight}px`;
        });
    </script>
</body>
</html>
