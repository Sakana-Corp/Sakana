<link rel="stylesheet" href="<?= app_url('view/css/cardapio.css?v=5') ?>">
<h2 class="titulo-form">Editar categoria</h2>
<form action="<?= app_url('index.php?action=atualizarCategoria') ?>" method="POST" enctype="multipart/form-data" class="form-grupo cardapio-form">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <input type="hidden" name="idCategoria" value="<?= (int) $categoria['idCategoria'] ?>">
  <div class="cardapio-field"><label class="form-label" for="nomeCategoria">Nome da categoria</label><input type="text" id="nomeCategoria" name="nomeCategoria" class="form-input" maxlength="40" required value="<?= htmlspecialchars($categoria['nomeCategoria'], ENT_QUOTES, 'UTF-8') ?>"></div>
  <div class="cardapio-field cardapio-field-wide"><label class="form-label" for="descCategoria">Descrição</label><input type="text" id="descCategoria" name="descCategoria" class="form-input" maxlength="255" required value="<?= htmlspecialchars($categoria['descCategoria'], ENT_QUOTES, 'UTF-8') ?>"></div>
  <div class="cardapio-field cardapio-field-wide"><label class="form-label" for="fotoCategoria">Nova imagem (opcional)</label><input type="file" id="fotoCategoria" name="fotoCategoria" accept="image/jpeg,image/png" class="form-input"></div>
  <button type="submit" class="btn-primary">Salvar alterações</button>
</form>
