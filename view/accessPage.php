<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso | Sakana</title>
    <link rel="stylesheet" href="/Sakana/view/css/style.css">
</head>
<body class="page" role="application" aria-label="Painel de Acesso">

    <div class="container">
        <h2 class="title-card">
            ACESSO - Bem-vindo, <?= htmlspecialchars($_SESSION["nomeUser"] ?? "", ENT_QUOTES, "UTF-8") ?>
        </h2>
        
        <div class="card">
            <div class="input-group" aria-label="Menu de setores">
                <a href="/Sakana/index.php?action=logadoGerencia"
                   class="btn-link"
                   style="margin-bottom: 15px;"
                   aria-label="Acessar setror de Gerência">
                    GERÊNCIA
                </a>

                <button class="btn-primary"
                    disabled
                    style="margin-bottom: 15px;"
                    aria-label="Setor de Atendimento indisponível no momento">
                    ATENDIMENTO
                </button>
                
                
                <button class="btn-primary"
                    disabled
                    style="margin-bottom: 15px;"
                    aria-label="Setor de Cozinha indisponível no momento">
                    COZINHA
                </button>

                <a href="/Sakana/index.php?action=logout"
                   class="link-voltar link-logout"
                   aria-label="Fazer logout do sistema">
                   Sair
                </a>
                
            </div>
        </div>
       
        
    </div>

</body>
</html>
