<script src="/Sakana/view/js/searchEmployee.js" defer></script>

<div class="consulta-container">
    <div class="consulta-header">
        <h1 class="consulta-titulo">Funcionários</h1>
        
        <div class="consulta-filtros">
            <div class="abas-cargo">
                <button class="aba-cargo aba-ativa" onclick="filtrarCategoria('todos')">Todos</button>
                <button class="aba-cargo" onclick="filtrarCategoria('garcom')">Garçom</button>
                <button class="aba-cargo" onclick="filtrarCategoria('cozinha')">Cozinha</button>
                <button class="aba-cargo" onclick="filtrarCategoria('atendente')">Atendente</button>
            </div>

            <div class="barra-pesquisa-wrapper">
                <input type="text" class="barra-pesquisa" id="pesquisaFuncionario" placeholder="Nome ou CPF do Funcionario" onkeyup="buscar()">
            </div>
        </div>
    </div>

    <div class="consulta-tabela-wrapper">
        <table class="consulta-tabela">
            <thead>
                <tr>
                    <th>idFuncionario</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Cargo</th>
                </tr>
            </thead>

            <tbody>
                <?php if (isset($listaFuncionarios) && count($listaFuncionarios) > 0): ?>
                    <?php foreach($listaFuncionarios as $f): ?>
                    <tr class="tabela-linha" data-cargo="<?= strtolower(htmlspecialchars($f['cargo'])) ?>">
                        <td><?= htmlspecialchars($f['idFuncionario']) ?></td>
                        <td class="celula-nome">
                            <span><?= htmlspecialchars($f['nomeFunc']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($f['cpf']) ?></td>
                        <td><?= htmlspecialchars($f['cargo']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="tabela-vazia">
                        <td colspan="4">Nenhum registro para exibir.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>