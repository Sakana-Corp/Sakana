<script src="/Sakana/view/js/searchProducts.js" defer></script>

<link rel="stylesheet" href="/Sakana/view/css/cardapio.css">
<div class="cardapio-container">

    <h2 class="titulo-pagina">Cardápio</h2>

    <div class="acoes-cardapio">
        <div class="card-mod">

            <a href="/Sakana/index.php?action=logadoGerencia&page=cadastroProduto" class="card-opcao">
                <div class="card-icon">🍣</div>
                <h3>Cadastrar Produtos</h3>
            </a>

            <a href="/Sakana/index.php?action=logadoGerencia&page=cadastroCategoria" class="card-opcao">
                <div class="card-icon">🍱</div>
                <h3>Cadastrar categorias</h3>
            </a>
        </div>
        <div class="card-visualizar">
            <a href="/Sakana/index.php?action=logadoGerencia&page=consultarCardapio" class="card-opcao">
                <div class="card-icon">📋</div>
                <h3>Visualizar cardápio</h3>
            </a>
        </div>
    </div>

    <div class="cardapio-container">
        <div class="cardapio-header">
            <div class="cardapio-categoria">
                <?php if (isset($listaCategorias) && count($listaCategorias) > 0): ?>
                    <button class="aba-categoria aba-ativa" data-categoria="todos" onclick="filtrarCategoria(this.dataset.categoria)">
                        <p class="categoria-nome">Todas as categorias</p>
                    </button>
                    <?php foreach($listaCategorias as $c): ?>
                        <button class="aba-categoria" data-categoria="<?php echo htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?>" onclick="filtrarCategoria(this.dataset.categoria)">
                            <img class="imagem-categoria" src="<?php echo htmlspecialchars($c['imgCategoria'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?>" class="categoria-icon">
                            <p class="categoria-nome"><?php echo htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </button>
                    <?php endforeach; ?>
            </div>
                <?php else: ?>
                    <div class="tabela-vazia">Nenhuma categoria registrada.</div>
                <?php endif; ?>
            <div class="cardapio-pesquisa">
                <input type="text" id="pesquisa-produtos" placeholder="Pesquisar produtos..." onkeyup="buscar()">
            </div>
        </div>
        <div class="cardapio-body">
            <?php if (isset($listaProdutos) && count($listaProdutos) > 0): ?>
                <?php foreach($listaProdutos as $p): ?>
                    <div class="produto-frame" data-categoria="<?php echo htmlspecialchars($p['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?>">
                        <img src="<?php echo htmlspecialchars($p['imgProduto'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p['nomeProduto'], ENT_QUOTES, 'UTF-8'); ?>" class="imagem-produto">
                        <div class="produto-info">
                            <h3 class="produto-nome"><?php echo htmlspecialchars($p['nomeProduto'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="produto-descricao"><?php echo htmlspecialchars($p['descProduto'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="produto-valor">R$ <?php echo number_format($p['valorProduto'], 2, ',', '.'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="tabela-vazia">Nenhuma categoria registrada.</div>
            <?php endif; ?>
        </div>
    </div>


</div>

    
    
