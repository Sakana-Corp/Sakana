<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta funcionário</title>
    <link rel="stylesheet" href="/Sakana/view/css/style.css">   
</head>
<body>

    <h2>Consulta funcionário</h2>

        <table>
            <thead>
             <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Endereço</th>
                <th>Cargo</th>
             </tr>  
            </thead>  


            <tbody>
                <?php if (isset($listaFuncionarios) && count($listaFuncionarios) > 0): ?>
                <?php foreach($listaFuncionarios as $f): ?>
                <tr>
                    <td>#<?= $f['idFuncionario'] ?></td>
                    <td><?= htmlspecialchars($f['nomeFunc']) ?></td>
                    <td><?= htmlspecialchars($f['cpf']) ?></td>
                    <td><?= htmlspecialchars($f['endereco']) ?></td>
                    <td><?= htmlspecialchars($f['cargo']) ?></td>
                </tr>
                
                    <?php endforeach;?>
                <?php else: ?>
                    <tr><td colspan="7" class="msg-vazia">Nenhum registro para exibir.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>


</body>
</html>