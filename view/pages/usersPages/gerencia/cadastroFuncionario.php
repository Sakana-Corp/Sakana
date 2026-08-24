<link rel="stylesheet" href="/Sakana/view/css/cardapio.css">

<form action="/Sakana/index.php?action=cadastrarFunc" method="POST" class="form-grupo">
    <h2 class="titulo-form">Cadastrar funcionários na equipe</h2>

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

    <div class="form-field">
        <label class="form-label" for="nomeFunc">Nome completo do funcionário</label>
        <input type="text" id="nomeFunc" name="nomeFunc" class="form-input" required placeholder="Ex: Ana Paula Silva">
    </div>

    <div class="form-grid">
        <div class="form-field">
            <label class="form-label" for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" class="form-input" required
                   placeholder="000.000.000-00" minlength="11" maxlength="14"
                   pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}">
        </div>

        <div class="form-field">
            <label class="form-label" for="cargo">Cargo</label>
            <input type="text" id="cargo" name="cargo" class="form-input" required placeholder="Ex: Garçonete">
        </div>
    </div>

    <div class="form-field">
        <label class="form-label" for="endereco">Endereço</label>
        <input type="text" id="endereco" name="endereco" class="form-input" required
               placeholder="Ex: Avenida Brasil, nº 100 - CEP: 00000-000">
    </div>

    <button type="submit" class="btn-primary" style="margin-top: 8px;">Cadastrar colaborador</button>
</form>