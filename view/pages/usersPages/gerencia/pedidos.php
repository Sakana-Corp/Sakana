<?php
$listaMesas = $listaMesas ?? [];
$itensCozinha = $itensCozinha ?? [];
?>

<div class="mesas-container">

    <div class="mesas-header">

        <div>
            <h1>Pedidos</h1>
            <p>Escolha uma mesa para lançar um novo pedido ou ver o resumo dos pedidos já feitos.</p>
        </div>

    </div>

    <?php if (($_SESSION['setorAtual'] ?? '') === 'cozinha'): ?>
        <div class="consulta-tabela-wrapper">
            <h2>Itens pendentes de preparo</h2>
            <table class="consulta-tabela">
                <thead>
                    <tr><th>Pedido</th><th>Mesa</th><th>Produto</th><th>Quantidade</th><th>Data</th><th>Ação</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($itensCozinha)): ?>
                        <tr class="tabela-vazia"><td colspan="6">Nenhum item pendente.</td></tr>
                    <?php else: ?>
                        <?php foreach ($itensCozinha as $item): ?>
                            <tr class="tabela-linha">
                                <td>#<?= (int) $item['idPedido'] ?></td>
                                <td><?= htmlspecialchars($item['numeromesa']) ?></td>
                                <td><?= htmlspecialchars($item['nomeProduto']) ?></td>
                                <td><?= (int) $item['quantidade'] ?></td>
                                <td><?= htmlspecialchars($item['dataPedido']) ?></td>
                                <td>
                                    <form method="post" action="<?= app_url('index.php?action=marcarItemPreparado') ?>">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="idItemPedido" value="<?= (int) $item['idItemPedido'] ?>">
                                        <button type="submit" class="btn-primary">Marcar preparado</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
    <div class="mesas-grid">

        <?php if (empty($listaMesas)): ?>

            <div class="mesas-vazio">
                <p>Nenhuma mesa cadastrada.</p>
            </div>

        <?php else: ?>

            <?php foreach ($listaMesas as $mesa): ?>

                <div class="mesa-card">

                    <img
                        src="<?= app_url('view/images/mesa.png') ?>"
                        alt="Mesa <?= htmlspecialchars($mesa['numeromesa']) ?>"
                        class="mesa-imagem"
                    >

                    <h3>
                        Mesa <?= htmlspecialchars($mesa['numeromesa']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($mesa['lugares'] ?? '0') ?> lugares
                    </p>

                    <?php if ($mesa['status'] === 'Disponivel'): ?>

                        <span class="mesa-status disponivel">
                            Disponível
                        </span>

                    <?php else: ?>

                        <span class="mesa-status indisponivel">
                            Indisponível
                        </span>

                    <?php endif; ?>


                    <div class="mesa-acoes">

                        <?php if (
                            $mesa['status'] === 'Disponivel' &&
                            in_array($_SESSION['setorAtual'] ?? '', ['gerencia', 'atendimento'], true)
                        ): ?>

                            <a href="<?= app_url('index.php?action=novoPedido&id=' . $mesa['idmesa']) ?>"
                               class="btn-abrir">
                                Pedido
                            </a>

                        <?php endif; ?>

                        <a href="<?= app_url('index.php?action=verResumoPedido&id=' . $mesa['idmesa']) ?>"
                           class="btn-resumo">
                            Ver resumo
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
    <?php endif; ?>

</div>
