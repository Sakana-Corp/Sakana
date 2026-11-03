<link rel="stylesheet" href="<?= app_url('view/css/cardapio.css?v=5') ?>">
<h2 class="titulo-form">Editar produto</h2>
<form action="<?= app_url('index.php?action=atualizarProduto') ?>" method="POST" enctype="multipart/form-data" class="form-grupo cardapio-form">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <input type="hidden" name="idProduto" value="<?= (int) $produto['idProduto'] ?>">
  <div class="cardapio-field"><label class="form-label" for="nomeProduto">Nome do produto</label><input type="text" id="nomeProduto" name="nomeProduto" class="form-input" maxlength="40" required value="<?= htmlspecialchars($produto['nomeProduto'], ENT_QUOTES, 'UTF-8') ?>"></div>
  <div class="cardapio-field cardapio-field-wide"><label class="form-label" for="descProduto">Descrição</label><input type="text" id="descProduto" name="descProduto" class="form-input" maxlength="255" required value="<?= htmlspecialchars($produto['descProduto'], ENT_QUOTES, 'UTF-8') ?>"></div>
  <div class="cardapio-field"><label class="form-label" for="idCategoria">Categoria</label><select id="idCategoria" name="idCategoria" class="form-input" required><?php foreach ($listaCategorias as $categoria): ?><option value="<?= (int) $categoria['idCategoria'] ?>" <?= (int) $categoria['idCategoria'] === (int) $produto['idCategoria'] ? 'selected' : '' ?>><?= htmlspecialchars($categoria['nomeCategoria'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></div>
  <div class="cardapio-field"><label class="form-label" for="valorProduto">Valor</label><input type="text" id="valorProduto" name="valorProduto" class="form-input" inputmode="decimal" required value="<?= htmlspecialchars(number_format((float) $produto['valorProduto'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?>"></div>
  <div class="cardapio-field cardapio-field-wide"><label class="form-label" for="fotoProduto">Nova imagem (opcional)</label><input type="file" id="fotoProduto" name="fotoProduto" accept="image/jpeg,image/png" class="form-input"></div>
  <button type="submit" class="btn-primary">Salvar alterações</button>
</form>
