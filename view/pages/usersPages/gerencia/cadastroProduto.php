<link rel="stylesheet" href="/Sakana/view/css/cardapio.css">
<h2 class="titulo-form">Cadastrar Itens do Cardápio</h2>

<form action="/Sakana/index.php?action=cadastrarProduto" method="POST" enctype="multipart/form-data" class="form-grupo">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label class="form-label">Nome do Produto:</label>
    <input type="text" name="nomeProduto" class="form-input" required placeholder="Ex: Temaki">

    <label class="form-label">Descrição do produto:</label>
    <input type="text" name="descProduto" class="form-input" required placeholder="Ex: Temaki de salmão com cream cheese">
    
    <div class="form-separador">
        <label class="form-label">URL da foto do produto (obrigatório):</label>
        <div class="file-upload-group">
            <input type="file" id="fotoProduto" name="fotoProduto" class="form-inputFile" accept="image/png, image/jpeg, image/webp, image/jpg">
            <label for="fotoProduto" class="custom-file-upload">Selecionar arquivo</label>
            <span class="file-name">Nenhum arquivo selecionado</span>
        </div>
        <small class="perfil-upload-help">Formatos aceitos: JPG, JPEG, PNG ou WEBP. Tamanho máximo: 2MB.</small>
    </div>

    <label class="form-label">Categoria:</label>
    <select name="idCategoria" class="form-input" required>
        <option value="">Selecione...</option>
        <?php foreach($listaCategorias as $c): ?>
            <option value="<?=$c['idCategoria']?>">
                <?= $c['nomeCategoria']?></option>
        <?php endforeach; ?>
    </select>
    
    <label class="form-label">Valor:</label>
    <input type="number" step="0.01" name="valorProduto" class="form-input" required placeholder="Ex: 25,90">

    
    <button type="submit" class="btn-primary">Cadastrar Produto</button>

</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('fotoProduto');
    var fileName = document.querySelector('.file-name');

    if (input && fileName) {
      input.addEventListener('change', function() {
        fileName.textContent = input.files.length
          ? input.files[0].name
          : 'Nenhum arquivo selecionado';
      });
    }
  });
</script>