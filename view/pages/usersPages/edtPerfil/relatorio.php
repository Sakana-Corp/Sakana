<?php
$diretorioImagens = __DIR__ . "/../../../images";
$imagens = [];
foreach (glob($diretorioImagens . "/*/*.{jpg,jpeg,png,webp}", GLOB_BRACE) ?: [] as $arquivo) {
    $imagens[] = [
        "nome" => basename($arquivo),
        "endpasta" => str_replace(__DIR__ . "/../../..", "", $arquivo)
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Relatório de Imagens</h2>
    <a href="imagemForm.php" class="btn-voltar">Voltar para Upload</a>
    <table>
        <thead>
            <th>Nome</th>
            <th>Endereço da pasta</th>
            <th>Imagem pasta</th>
        </thead>
        <tbody>
            <?php foreach ($imagens as $img): ?>
            <tr>
                <td><?= htmlspecialchars($img["nome"]) ?></td>
                <td><?= htmlspecialchars($img["endpasta"]) ?></td>
                <td>
                    <?php $urlImagem = str_replace("\\", "/", "/Sakana/view/images" . $img["endpasta"]); ?>
                    <img src="<?= htmlspecialchars($urlImagem) ?>" width="120" alt="Foto">
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</body>
</html>
    