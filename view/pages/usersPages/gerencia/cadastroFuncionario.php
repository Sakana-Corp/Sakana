<link rel="stylesheet" href="view/css/cardapio.css?v=4">
<script src="<?= app_url('view/js/inputMasks.js') ?>" defer></script>
<script src="<?= app_url('view/js/cepLookup.js') ?>" defer></script>

<h2 class="titulo-form-func">Cadastrar funcionários na equipe</h2>

<form action="<?= app_url('index.php?action=cadastrarFunc') ?>" method="POST" class="form-grupo">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <div class="cardapio-field cardapio-field-wide">
        <label class="form-label" for="nomeFunc">Nome completo do funcionário</label>
        <input type="text" id="nomeFunc" name="nomeFunc" class="form-input" required
               autocomplete="name" placeholder="Ex: Ana Paula Silva">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" class="form-input" required
               inputmode="numeric" autocomplete="off"
               placeholder="000.000.000-00" minlength="14" maxlength="14"
               pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
               title="Informe o CPF no formato 000.000.000-00">
        <small id="cpfStatus" class="form-hint" aria-live="polite"></small>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cargo">Cargo</label>
        <select id="cargo" name="cargo" class="form-input" required>
            <option value="">Selecione o cargo</option>
            <option value="Garçom">Garçom</option>
            <option value="Cozinha">Cozinha</option>
        </select>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="email">Email do funcionário</label>
        <input type="email" id="email" name="email" class="form-input" required
               autocomplete="off" placeholder="Ex: ana.silva@sakana.com">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="senha">Senha do funcionário</label>
        <input type="password" id="senha" name="senha" class="form-input" required
               minlength="8" autocomplete="new-password" placeholder="Mínimo de 8 caracteres">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cep">CEP</label>
        <input type="text" id="cep" name="cep" class="form-input" required
               inputmode="numeric" autocomplete="postal-code"
               placeholder="00000-000" minlength="9" maxlength="9"
               pattern="\d{5}-\d{3}"
               title="Informe o CEP no formato 00000-000">
        <small id="cepStatus" class="form-hint" aria-live="polite"></small>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="numero">Número</label>
        <input type="text" id="numero" name="numero" class="form-input" required
               maxlength="10" autocomplete="off" placeholder="Ex: 100 ou S/N">
    </div>

    <div class="cardapio-field cardapio-field-wide">
        <label class="form-label" for="logradouro">Logradouro</label>
        <input type="text" id="logradouro" name="logradouro" class="form-input" required
               maxlength="150" autocomplete="address-line1" placeholder="Preenchido pelo CEP">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="complemento">Complemento (opcional)</label>
        <input type="text" id="complemento" name="complemento" class="form-input"
               maxlength="60" autocomplete="address-line2" placeholder="Ex: Apto 12, Bloco B">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="bairro">Bairro</label>
        <input type="text" id="bairro" name="bairro" class="form-input" required
               maxlength="80" autocomplete="off" placeholder="Preenchido pelo CEP">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cidade">Cidade</label>
        <input type="text" id="cidade" name="cidade" class="form-input" required
               maxlength="80" autocomplete="address-level2" placeholder="Preenchido pelo CEP">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="uf">UF</label>
        <select id="uf" name="uf" class="form-input" required>
            <option value="">Selecione</option>
            <?php foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf): ?>
                <option value="<?= $uf ?>"><?= $uf ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn-primary">Cadastrar colaborador</button>
</form>
