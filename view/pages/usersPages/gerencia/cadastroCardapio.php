<link rel="stylesheet" href="/Sakana/view/css/cardapio.css">
<h2 class="titulo-form">Cadastrar Itens do Cardápio</h2>

<form action="/Sakana/index.php?action=salvarItem" method="POST" class="form-grupo">

    <label class="form-label">Nome do item:</label>
    <input type="text" name="nomeItem" class="form-input" required placeholder="Ex: Temaki">

    <label class="form-label">Descrição do item:</label>
    <input type="text" name="dscItem" class="form-input" required placeholder="Ex: Temaki de salmão com cream cheese">

    <label class="form-label">Valor:</label>
    <input type="number" step="0.01" name="valorItem" class="form-input" required placeholder="Ex: 25,90">

    <button type="submit" class="btn-primary">Cadastrar item</button>

</form>