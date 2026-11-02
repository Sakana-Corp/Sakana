<link rel="stylesheet" href="<?= app_url('view/css/cardapio.css?v=4') ?>">
<script src="<?= app_url('view/js/cepLookup.js') ?>" defer></script>

<h2 class="titulo-form-func">Editar funcionário</h2>

<form action="<?= app_url('index.php?action=atualizarFuncionario') ?>" method="POST" class="form-grupo">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="idFuncionario" value="<?= (int) $funcionario['idFuncionario'] ?>">

    <div class="cardapio-field cardapio-field-wide">
        <label class="form-label" for="nomeFunc">Nome completo</label>
        <input type="text" id="nomeFunc" name="nomeFunc" class="form-input" required maxlength="100"
               value="<?= htmlspecialchars($funcionario['nomeFunc']) ?>">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cpf">CPF</label>
        <input type="text" id="cpf" class="form-input" readonly
               value="<?= htmlspecialchars($funcionario['cpf']) ?>">
        <small class="form-hint">O CPF não pode ser alterado.</small>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cargo">Cargo</label>
        <select id="cargo" name="cargo" class="form-input" required>
            <option value="Garçom" <?= $funcionario['cargo'] === 'Garçom' ? 'selected' : '' ?>>Garçom</option>
            <option value="Cozinha" <?= $funcionario['cargo'] === 'Cozinha' ? 'selected' : '' ?>>Cozinha</option>
        </select>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-input" required maxlength="50"
               value="<?= htmlspecialchars($funcionario['email']) ?>">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="senha">Nova senha (opcional)</label>
        <input type="password" id="senha" name="senha" class="form-input" minlength="8"
               autocomplete="new-password" placeholder="Deixe vazio para manter a atual">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cep">CEP</label>
        <input type="text" id="cep" name="cep" class="form-input" required
               value="<?= htmlspecialchars($funcionario['cep']) ?>">
        <small id="cepStatus" class="form-hint" aria-live="polite"></small>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="numero">Número</label>
        <input type="text" id="numero" name="numero" class="form-input" required maxlength="10"
               value="<?= htmlspecialchars($funcionario['numero']) ?>">
    </div>

    <div class="cardapio-field cardapio-field-wide">
        <label class="form-label" for="logradouro">Logradouro</label>
        <input type="text" id="logradouro" name="logradouro" class="form-input" required maxlength="150"
               value="<?= htmlspecialchars($funcionario['logradouro']) ?>">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="complemento">Complemento (opcional)</label>
        <input type="text" id="complemento" name="complemento" class="form-input" maxlength="60"
               value="<?= htmlspecialchars($funcionario['complemento'] ?? '') ?>">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="bairro">Bairro</label>
        <input type="text" id="bairro" name="bairro" class="form-input" required maxlength="80"
               value="<?= htmlspecialchars($funcionario['bairro']) ?>">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cidade">Cidade</label>
        <input type="text" id="cidade" name="cidade" class="form-input" required maxlength="80"
               value="<?= htmlspecialchars($funcionario['cidade']) ?>">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="uf">UF</label>
        <select id="uf" name="uf" class="form-input" required>
            <?php foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf): ?>
                <option value="<?= $uf ?>" <?= $funcionario['uf'] === $uf ? 'selected' : '' ?>><?= $uf ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn-primary">Salvar alterações</button>
    <a href="<?= app_url('index.php?action=logadoGerencia&page=consultaFuncionario') ?>" class="btn-secondary">Cancelar</a>
</form>
