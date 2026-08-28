<?php 
$listaMesas = $listaMesas ?? [];
$setorAtual = $_SESSION["setorAtual"] ?? null;
?>

<div class="mesas-container">

    <div class="mesas-header">

        <div>
            <h1>Mesas</h1>
            <p>Gerenciamento das mesas do restaurante</p>
        </div>

        <?php if ($setorAtual === "gerencia"): ?>

            <a href="/Sakana/index.php?action=cadastrarMesa"
               class="btn-primary">
                Cadastrar mesa
            </a>

        <?php endif; ?>

    </div>


    <div class="mesas-grid">

        <?php if (empty($listaMesas)): ?>

            <div class="mesas-vazio">
                <p>Nenhuma mesa cadastrada.</p>
            </div>

        <?php else: ?>

            <?php foreach ($listaMesas as $mesa): ?>

                <div class="mesa-card">

                    <img
                        src="/Sakana/view/images/mesa.png"
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

                        <?php if ($setorAtual === "atendimento"): ?>

                            <?php if ($mesa['status'] === 'Disponivel'): ?>

                                <a href="/Sakana/index.php?action=abrirMesa&id=<?= $mesa['idmesa'] ?>"
                                   class="btn-abrir">
                                    Abrir mesa
                                </a>

                            <?php else: ?>

                                <a href="/Sakana/index.php?action=fecharMesa&id=<?= $mesa['idmesa'] ?>"
                                   class="btn-fechar">
                                    Fechar mesa
                                </a>

                            <?php endif; ?>

                        <?php endif; ?>


                        <?php if ($setorAtual === "gerencia"): ?>

                            <a href="/Sakana/index.php?action=editarMesa&id=<?= $mesa['idmesa'] ?>"
                               class="btn-editar">
                                Editar
                            </a>

                            <a href="/Sakana/index.php?action=excluirMesa&id=<?= $mesa['idmesa'] ?>"
                               class="btn-excluir"
                               onclick="return confirm('Deseja realmente excluir esta mesa?');">
                                Excluir
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>