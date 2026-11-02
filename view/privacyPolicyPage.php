<?php $voltarUrl = app_previous_url(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade | Sakana</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body class="page-institucional">
    <main class="institucional-container">
        <article class="institucional-card" aria-labelledby="politica-titulo">
            <img src="view/images/logo.png" alt="Logo Sakana" class="institucional-logo">
            <h1 id="politica-titulo">Política de Privacidade</h1>
            <p class="privacy-updated">Última atualização: 21 de outubro de 2026</p>
            <p>Esta política descreve como o simulador acadêmico Sakana trata dados pessoais, em conformidade com os princípios da Lei Geral de Proteção de Dados (Lei nº 13.709/2018 – LGPD).</p>

            <section aria-labelledby="dados-coletados">
                <h2 id="dados-coletados">Dados tratados</h2>
                <ul>
                    <li>Dados de conta: nome, e-mail e senha armazenada como hash criptográfico; foto de perfil, se enviada.</li>
                    <li>Dados de colaboradores cadastrados pela gerência: nome, CPF, e-mail, cargo e endereço (CEP, logradouro, número, complemento, bairro, cidade e UF), além da credencial de acesso protegida por hash.</li>
                    <li>Dados operacionais do simulador: mesas, produtos, categorias, imagens associadas, itens de pedidos, quantidades, valores e situação da mesa.</li>
                    <li>Dados técnicos necessários à sessão: identificador de sessão em cookie, setor autenticado e token de segurança contra requisições indevidas. O servidor que hospeda o site também pode registrar dados técnicos de acesso, conforme sua configuração.</li>
                </ul>
            </section>

            <section aria-labelledby="finalidade-dados">
                <h2 id="finalidade-dados">Finalidade do uso</h2>
                <p>Os dados são usados para criar e autenticar contas, controlar o acesso por setor, administrar colaboradores e informações do cardápio, registrar pedidos por mesa, exibir resumos e proteger as operações do sistema.</p>
                <p>Ao preencher um CEP no cadastro de colaborador, o navegador consulta ViaCEP e, se necessário, BrasilAPI para sugerir o endereço. Essas consultas enviam o CEP informado ao serviço correspondente; os demais campos pessoais do cadastro não são enviados por essa consulta.</p>
            </section>

            <section aria-labelledby="guarda-dados">
                <h2 id="guarda-dados">Armazenamento e período de guarda</h2>
                <p>Os registros da aplicação são armazenados no banco de dados e, no caso das imagens, nos arquivos do sistema. As senhas não são guardadas em texto puro. O cookie de sessão é usado para manter a autenticação e é configurado para expirar ao encerrar a sessão do navegador.</p>
                <p>O sistema não possui prazo automático de expurgo ou rotina de exclusão de contas. Os dados permanecem armazenados enquanto forem necessários para o funcionamento do simulador. Para solicitar acesso, correção ou exclusão, use o canal de contato abaixo. A solicitação será analisada e poderá haver retenção quando necessária para cumprir obrigação legal ou preservar direitos.</p>
            </section>

            <section aria-labelledby="compartilhamento-dados">
                <h2 id="compartilhamento-dados">Compartilhamento</h2>
                <p>O Sakana não vende nem compartilha dados pessoais para publicidade. O CEP é consultado com ViaCEP e, como alternativa, BrasilAPI, conforme explicado acima. O provedor de hospedagem e infraestrutura pode processar dados técnicos necessários para disponibilizar o site. Dados também poderão ser fornecidos quando houver obrigação legal ou ordem válida de autoridade competente.</p>
            </section>

            <section aria-labelledby="direitos-titular">
                <h2 id="direitos-titular">Seus direitos</h2>
                <p>Nos termos da LGPD, o titular pode solicitar confirmação do tratamento e acesso aos dados, correção de informações incompletas ou desatualizadas, anonimização, bloqueio ou eliminação de dados desnecessários ou tratados em desconformidade, portabilidade quando aplicável, informação sobre compartilhamentos e eliminação dos dados tratados com consentimento, observadas as hipóteses legais de conservação. Também pode revogar consentimento quando essa for a base do tratamento.</p>
                <p>Para exercer esses direitos ou tirar dúvidas, informe no contato qual solicitação deseja fazer e a conta ou cadastro a que ela se refere. Poderemos pedir informações estritamente necessárias para confirmar a identidade do solicitante e proteger os dados de terceiros.</p>
            </section>

            <section aria-labelledby="contato-privacidade">
                <h2 id="contato-privacidade">Contato</h2>
                <p>O canal público informado pela equipe para dúvidas e solicitações de privacidade é o repositório oficial do projeto:</p>
                <a class="institucional-contact" href="https://github.com/Sakana-Corp/Sakana" target="_blank" rel="noopener noreferrer">
                    github.com/Sakana-Corp/Sakana
                </a>
                <p>Este canal é um repositório GitHub, não um endereço de e-mail dedicado à privacidade.</p>
            </section>

            <nav class="institucional-actions" aria-label="Navegação">
                <a class="btn-secondary" href="<?= htmlspecialchars($voltarUrl, ENT_QUOTES, 'UTF-8') ?>">Voltar</a>
                <a class="btn-secondary" href="index.php?action=institucional">Sobre o projeto</a>
            </nav>
        </article>
    </main>
</body>
</html>
