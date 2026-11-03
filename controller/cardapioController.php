<?php
require_once __DIR__ . "/baseController.php";
require_once __DIR__ . "/../model/categoriaModel.php";
require_once __DIR__ . "/../model/produtoModel.php";
class CardapioController extends BaseController
{
    private function validarEGuardarImagem(string $campo, string $diretorio, string $prefixo): ?string
    {
        if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $arquivo = $_FILES[$campo];
        if ($arquivo['size'] <= 0 || $arquivo['size'] > 2 * 1024 * 1024) {
            return null;
        }
        if (!is_uploaded_file($arquivo['tmp_name'])) {
            return null;
        }

        $tiposPermitidos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png'
        ];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipoReal = $finfo->file($arquivo['tmp_name']);
        if (!isset($tiposPermitidos[$tipoReal])) {
            return null;
        }

        $nomeFoto = $prefixo . '_' . bin2hex(random_bytes(12)) . '.' . $tiposPermitidos[$tipoReal];
        $arquivoDestino = rtrim($diretorio, '/\\') . DIRECTORY_SEPARATOR . $nomeFoto;
        if (!move_uploaded_file($arquivo['tmp_name'], $arquivoDestino)) {
            return null;
        }

        return $nomeFoto;
    }

    public function cadastrarCategoria()
    {
        $this->requirePost("logadoGerencia&page=cadastroCategoria");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroCategoria");
        $this->requireSetor("gerencia");

        $nomeCategoria = trim($_POST["nomeCategoria"] ?? "");
        $descCategoria = trim($_POST["descCategoria"] ?? "");
        if (strlen($nomeCategoria) > 40 || strlen($descCategoria) > 255) {
            $this->flashAndRedirect("warning", "Confira os limites dos campos informados.", "logadoGerencia&page=cadastroCategoria");
        }
        if ($nomeCategoria === "" || $descCategoria === "") {
            $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "logadoGerencia&page=cadastroCategoria");
        }

        $diretorioUpload = __DIR__ . '/../view/images/categorias';
        $nomeFoto = $this->validarEGuardarImagem('fotoCategoria', $diretorioUpload, 'categoria');
        if ($nomeFoto === null) {
            $this->flashAndRedirect("warning", "Envie uma imagem JPG ou PNG válida, com no máximo 2 MB.", "logadoGerencia&page=cadastroCategoria");
        }
        $caminhoWeb = app_url('view/images/categorias/' . $nomeFoto);
        $fotoCategoria = $caminhoWeb;

        if ($nomeCategoria === "" || $descCategoria === "" || $fotoCategoria === null) {
            $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "logadoGerencia&page=cadastroCategoria");
        }

        require_once __DIR__ . "/../model/categoriaModel.php";
        $categoriaModel = new CategoriaModel();
        $resultado = $categoriaModel->cadastrarCategoria($nomeCategoria, $descCategoria, $fotoCategoria);

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Categoria cadastrada com sucesso!", "logadoGerencia&page=cadastroCategoria");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "name_exists") {
            $msg = "Já existe uma categoria com esse nome.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroCategoria");
    }

    public function editarCategoria(): void
    {
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) $this->flashAndRedirect("error", "Categoria inválida.", "logadoGerencia&page=cardapio");
        $categoria = (new CategoriaModel())->buscarCategoria((int) $id);
        if (!$categoria) $this->flashAndRedirect("error", "Categoria não encontrada.", "logadoGerencia&page=cardapio");
        require_once __DIR__ . '/usersController.php';
        (new UsersController())->logadoGerencia('editarCategoria', ['categoria' => $categoria]);
    }

    public function atualizarCategoria(): void
    {
        $voltar = "logadoGerencia&page=cardapio";
        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireSetor("gerencia", $voltar);

        $id = filter_input(INPUT_POST, 'idCategoria', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nomeCategoria'] ?? '');
        $descricao = trim($_POST['descCategoria'] ?? '');
        if (!$id || $nome === '' || $descricao === '' || strlen($nome) > 40 || strlen($descricao) > 255) {
            $this->flashAndRedirect("warning", "Informe dados válidos dentro dos limites do cadastro.", $voltar);
        }

        $foto = null;
        if (isset($_FILES['fotoCategoria']) && $_FILES['fotoCategoria']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fotoNome = $this->validarEGuardarImagem('fotoCategoria', __DIR__ . '/../view/images/categorias', 'categoria');
            if ($fotoNome === null) $this->flashAndRedirect("warning", "Envie uma imagem JPG ou PNG válida, com no máximo 2 MB.", $voltar);
            $foto = app_url('view/images/categorias/' . $fotoNome);
        }

        $resultado = (new CategoriaModel())->atualizarCategoria((int) $id, $nome, $descricao, $foto);
        if (!$resultado['ok']) {
            $msg = $resultado['error'] === 'name_exists' ? 'Já existe uma categoria com esse nome.' : 'Não foi possível atualizar a categoria.';
            $this->flashAndRedirect("error", $msg, $voltar);
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Categoria atualizada com sucesso!", $voltar);
    }

    public function editarProduto(): void
    {
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) $this->flashAndRedirect("error", "Produto inválido.", "logadoGerencia&page=cardapio");
        $produto = (new ProdutoModel())->buscarProduto((int) $id);
        if (!$produto) $this->flashAndRedirect("error", "Produto não encontrado.", "logadoGerencia&page=cardapio");
        require_once __DIR__ . '/usersController.php';
        (new UsersController())->logadoGerencia('editarProduto', ['produto' => $produto]);
    }

    public function atualizarProduto(): void
    {
        $voltar = "logadoGerencia&page=cardapio";
        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireSetor("gerencia", $voltar);

        $id = filter_input(INPUT_POST, 'idProduto', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nomeProduto'] ?? '');
        $descricao = trim($_POST['descProduto'] ?? '');
        $categoria = filter_var($_POST['idCategoria'] ?? null, FILTER_VALIDATE_INT);
        $textoValor = str_replace(',', '.', str_replace('.', '', trim($_POST['valorProduto'] ?? '')));
        $valor = filter_var($textoValor, FILTER_VALIDATE_FLOAT);
        if (!$id || $nome === '' || $descricao === '' || !$categoria || $valor === false || $valor <= 0 || strlen($nome) > 40 || strlen($descricao) > 255) {
            $this->flashAndRedirect("warning", "Informe nome, descrição, categoria e um preço válido maior que zero.", $voltar);
        }

        $foto = null;
        if (isset($_FILES['fotoProduto']) && $_FILES['fotoProduto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fotoNome = $this->validarEGuardarImagem('fotoProduto', __DIR__ . '/../view/images/produtos', 'produto');
            if ($fotoNome === null) $this->flashAndRedirect("warning", "Envie uma imagem JPG ou PNG válida, com no máximo 2 MB.", $voltar);
            $foto = app_url('view/images/produtos/' . $fotoNome);
        }

        $resultado = (new ProdutoModel())->atualizarProduto((int) $id, $nome, $descricao, (int) $categoria, (float) $valor, $foto);
        if (!$resultado['ok']) {
            $msg = $resultado['error'] === 'name_exists' ? 'Já existe um produto com esse nome.' : 'Não foi possível atualizar o produto.';
            $this->flashAndRedirect("error", $msg, $voltar);
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Produto atualizado com sucesso!", $voltar);
    }

    public function listarCategorias()
    {
        $this->requireAnySetor([
            "gerencia",
            "atendimento"
        ]);
        $categoriaModel = new CategoriaModel();
        $listaCategorias = $categoriaModel->listarCategorias();

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php";
    }

    public function cadastrarProduto()
    {
        $this->requirePost("logadoGerencia&page=cadastroProduto");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroProduto");
        $this->requireSetor("gerencia");

        $nomeProduto = trim($_POST["nomeProduto"] ?? "");
        $descProduto = trim($_POST["descProduto"] ?? "");
        $categoriaId = filter_var($_POST["idCategoria"] ?? null, FILTER_VALIDATE_INT);
        $valorProdutoTexto = trim($_POST["valorProduto"] ?? "");
        $valorProdutoTexto = str_replace('.', '', $valorProdutoTexto);
        $valorProdutoTexto = str_replace(',', '.', $valorProdutoTexto);
        $valorProduto = filter_var($valorProdutoTexto, FILTER_VALIDATE_FLOAT);

        if ($nomeProduto === "" || $descProduto === "" || !$categoriaId || $valorProduto === false || $valorProduto <= 0) {
            $this->flashAndRedirect("warning", "Informe nome, descrição, categoria e um preço maior que zero.", "logadoGerencia&page=cadastroProduto");
        }
        if (strlen($nomeProduto) > 40 || strlen($descProduto) > 255) {
            $this->flashAndRedirect("warning", "Confira os limites dos campos informados.", "logadoGerencia&page=cadastroProduto");
        }

        $diretorioUpload = __DIR__ . '/../view/images/produtos';
        $nomeFoto = $this->validarEGuardarImagem('fotoProduto', $diretorioUpload, 'produto');
        if ($nomeFoto === null) {
            $this->flashAndRedirect("warning", "Envie uma imagem JPG ou PNG válida, com no máximo 2 MB.", "logadoGerencia&page=cadastroProduto");
        }
        $fotoProduto = app_url('view/images/produtos/' . $nomeFoto);

        require_once __DIR__ . "/../model/produtoModel.php";
        $produtoModel = new ProdutoModel();
        $resultado = $produtoModel->cadastrarProduto($nomeProduto, $descProduto, $fotoProduto, $categoriaId, $valorProduto);

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Produto cadastrado com sucesso!", "logadoGerencia&page=cadastroProduto");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "name_exists") {
            $msg = "Já existe um produto com esse nome.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroProduto");
    }

    public function listarProdutos()
    {
        $this->requireAnySetor([
            "gerencia",
            "atendimento"
        ]);
        $produtoModel = new ProdutoModel();
        $listaProdutos = $produtoModel->listarProdutos();

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php";
    }

    public function seedCardapio()
    {
        $this->requirePost("logadoGerencia&page=cadastroCategoria");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroCategoria");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        require_once __DIR__ . "/../model/categoriaModel.php";
        require_once __DIR__ . "/../model/produtoModel.php";

        $categoriaModel = new CategoriaModel();
        $produtoModel = new ProdutoModel();

        $categoriasExemplo = [
            ["Bebidas", "Sucos, refrigerantes e drinks", app_url("view/images/seed/categorias/bebidas.jpg")],
            ["Sushis", "Combinados e peças avulsas", app_url("view/images/seed/categorias/sushis.jpg")],
            ["Temakis", "Enrolado em forma de cone recheado", app_url("view/images/seed/categorias/temakis.jpg")],
        ];

        $idsCategorias = [];
        foreach ($categoriasExemplo as [$nome, $desc, $foto]) {
            $resultado = $categoriaModel->cadastrarCategoria($nome, $desc, $foto);
            if ($resultado["ok"]) {
                $idsCategorias[$nome] = $resultado["id"];
            }
        }

        $produtosExemplo = [
            ["Coca-Cola 350ml", "Lata gelada de Coca-Cola", app_url("view/images/seed/produtos/cocacola.jpg"), "Bebidas", 6.00],
            ["Guaraná Antarctica 350ml", "Lata gelada de Guarana", app_url("view/images/seed/produtos/guarana.jpg"), "Bebidas", 6.00],
            ["Combinado 20 peças", "Sushi e sashimi variados", app_url("view/images/seed/produtos/combinado20.jpg"), "Sushis", 45.00],
            ["Combinado 10 peças", "Sushis variados", app_url("view/images/seed/produtos/combinado10.jpg"), "Sushis", 25.00],
            ["Temaki Salmão Cru", "Temaki cru", app_url("view/images/seed/produtos/temakiCru.jpg"), "Temakis", 15.00],
            ["Temaki Salmão Grelhado", "Temaki grelhado", app_url("view/images/seed/produtos/temakiGrelhado.jpg"), "Temakis", 15.00],
        ];

        foreach ($produtosExemplo as [$nome, $desc, $foto, $nomeCategoria, $valor]) {
            $categoriaId = $idsCategorias[$nomeCategoria] ?? null;
            if ($categoriaId) {
                $produtoModel->cadastrarProduto($nome, $desc, $foto, $categoriaId, $valor);
            }
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Cardápio de exemplo cadastrado!", "logadoGerencia&page=cardapio");
    }

    public function excluirExemplosCardapio(): void
    {
        $this->requirePost("logadoGerencia&page=cardapio");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cardapio");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        $nomesProdutos = [
            "Coca-Cola 350ml",
            "Guaraná Antarctica 350ml",
            "Combinado 20 peças",
            "Combinado 10 peças",
            "Temaki Salmão Cru",
            "Temaki Salmão Grelhado"
        ];
        $nomesCategorias = ["Bebidas", "Sushis", "Temakis"];

        try {
            $conexao = Conexao::getConn();
            $conexao->beginTransaction();

            $placeholdersProdutos = implode(", ", array_fill(0, count($nomesProdutos), "?"));
            $stmtProdutos = $conexao->prepare(
                "DELETE FROM produto WHERE nomeProduto IN ($placeholdersProdutos)"
            );
            $stmtProdutos->execute($nomesProdutos);

            $stmtCategoria = $conexao->prepare(
                "SELECT idCategoria FROM categoria WHERE nomeCategoria = ?"
            );
            $stmtProdutosCategoria = $conexao->prepare(
                "SELECT COUNT(*) FROM produto WHERE idCategoria = ?"
            );
            $stmtExcluirCategoria = $conexao->prepare(
                "DELETE FROM categoria WHERE idCategoria = ?"
            );

            foreach ($nomesCategorias as $nomeCategoria) {
                $stmtCategoria->execute([$nomeCategoria]);
                $idCategoria = $stmtCategoria->fetchColumn();

                if ($idCategoria === false) {
                    continue;
                }

                $stmtProdutosCategoria->execute([$idCategoria]);
                if ((int) $stmtProdutosCategoria->fetchColumn() === 0) {
                    $stmtExcluirCategoria->execute([$idCategoria]);
                }
            }

            $conexao->commit();
            $this->flashAndRedirect("success", "Exemplos do cardápio excluídos!", "logadoGerencia&page=cardapio");
        } catch (PDOException $e) {
            if (isset($conexao) && $conexao->inTransaction()) {
                $conexao->rollBack();
            }

            $this->flashAndRedirect("error", "Não foi possível excluir os exemplos.", "logadoGerencia&page=cardapio");
        }
    }

    public function excluirCategoria()
    {
        $this->requirePost("logadoGerencia&page=cardapio");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cardapio");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        $idCategoria = $_POST["idCategoria"] ?? "";

        if ($idCategoria === "") {
            $this->flashAndRedirect("warning", "Categoria inválida.", "logadoGerencia&page=cardapio");
        }

        require_once __DIR__ . "/../model/categoriaModel.php";
        $categoriaModel = new CategoriaModel();
        $resultado = $categoriaModel->excluirCategoria($idCategoria);

        if ($resultado["ok"]) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Categoria excluída com sucesso!", "logadoGerencia&page=cardapio");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "has_products") {
            $msg = "Não é possível excluir: existem produtos cadastrados nessa categoria.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao excluir categoria.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cardapio");
    }

    public function excluirProduto()
    {
        $this->requirePost("logadoGerencia&page=cardapio");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cardapio");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        $idProduto = $_POST["idProduto"] ?? "";

        if ($idProduto === "") {
            $this->flashAndRedirect("warning", "Produto inválido.", "logadoGerencia&page=cardapio");
        }

        require_once __DIR__ . "/../model/produtoModel.php";
        $produtoModel = new ProdutoModel();
        $resultado = $produtoModel->excluirProduto($idProduto);

        if ($resultado["ok"]) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Produto excluído com sucesso!", "logadoGerencia&page=cardapio");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao excluir produto.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cardapio");
    }
}
