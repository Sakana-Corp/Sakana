<form class="perfil-form" method="post" action="/Sakana/index.php?action=editarPerfil" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

    <div class="form-grid">
        <label class="form-field">
            <span>Nome completo</span>
            <input type="text" name="nome" class="form-input" value="<?= $nomeUsuario ?? "" ?>" required>
        </label>

        <label class="form-field">
            <span>E-mail</span>
            <input type="email" name="email" class="form-input" value="<?= $emailUsuario ?? "" ?>" required>
        </label>
    </div>

    <div class="form-grid">
        <label class="form-field">
            <span>Foto de perfil</span>
            <input type="file" name="fotoPerfil" class="form-input" accept="image/png, image/jpeg, image/webp, image/jpg">
            <small class="perfil-upload-help">Formatos aceitos: JPG, JPEG, PNG ou WEBP. Tamanho máximo: 2MB.</small>
        </label>
    </div>

        <div class="perfil-actions">
            <a href="/Sakana/index.php?action=logadoGerencia" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Salvar alterações</button>
        </div>
    </div>
</form>