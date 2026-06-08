<?php
    require_once __DIR__ . "/baseController.php";
    require_once __DIR__ . "/../model/categoriaModel.php";
    class CardapioController extends BaseController {
        public function cadastrarCategoria() {
            $this->requirePost("logadoGerencia&page=cadastroCategoria");
            $this->startSession();
            $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroCategoria");

            if (!isset($_FILES['fotoCategoria']) || $_FILES['fotoCategoria']['error'] !== 0) {
            echo "<script>alert('Nenhum arquivo foi enviado.'); window.history.back();</script>";
            exit;
            }

            $extensao = strtolower(pathinfo($_FILES['fotoCategoria']['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

            if (!in_array($extensao, $extensoesPermitidas, true)) {
                echo "<script>alert('Formato inválido. Envie apenas JPG, JPEG ou PNG.'); window.history.back();</script>";
                exit;
            }

            $nomeFoto = uniqid('', true) . '.' . $extensao;
            $diretorioUpload = __DIR__ . '/../view/images/categorias';
            $arquivoDestino = $diretorioUpload . '/' . $nomeFoto;

            if (!is_dir($diretorioUpload)) {
                echo "<script>alert('A pasta view/images/categorias não existe.'); window.history.back();</script>";
                exit;
            }

            if (!move_uploaded_file($_FILES['fotoCategoria']['tmp_name'], $arquivoDestino)) {
                echo "<script>alert('Erro ao mover o arquivo para o servidor.'); window.history.back();</script>";
                exit;
            }

            $caminhoWeb = '/Sakana/view/images/categorias/' . $nomeFoto;

            $nomeCategoria = $_POST["nomeCategoria"] ?? "";
            $descCategoria = $_POST["descCategoria"] ?? "";
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
            }
            elseif ($error === "database_error") {
                $msg = "Banco de dados indisponível. Tente mais tarde.";
            }
            else {
                $msg = "Erro ao Cadastrar. Tente novamente.";
            }

            $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroCategoria");
        
    }

    public function listarCategorias() {
        $categoriaModel = new CategoriaModel();
        $listaCategorias = $categoriaModel->listarCategorias();

        require_once __DIR__ . "/../view/pages/pages/gerencia/cardapio.php";
        }
    
    public function cadastrarProduto() {
        $this->requirePost("logadoGerencia&page=cadastroProduto");
            $this->startSession();
            $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroProduto");

            if (!isset($_FILES['fotoProduto']) || $_FILES['fotoProduto']['error'] !== 0) {
            echo "<script>alert('Nenhum arquivo foi enviado.'); window.history.back();</script>";
            exit;
            }

            $extensao = strtolower(pathinfo($_FILES['fotoProduto']['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

            if (!in_array($extensao, $extensoesPermitidas, true)) {
                echo "<script>alert('Formato inválido. Envie apenas JPG, JPEG ou PNG.'); window.history.back();</script>";
                exit;
            }

            $nomeFoto = uniqid('', true) . '.' . $extensao;
            $diretorioUpload = __DIR__ . '/../view/images/produtos';
            $arquivoDestino = $diretorioUpload . '/' . $nomeFoto;

            if (!is_dir($diretorioUpload)) {
                echo "<script>alert('A pasta view/images/produtos não existe.'); window.history.back();</script>";
                exit;
            }

            if (!move_uploaded_file($_FILES['fotoProduto']['tmp_name'], $arquivoDestino)) {
                echo "<script>alert('Erro ao mover o arquivo para o servidor.'); window.history.back();</script>";
                exit;
            }

            $caminhoWeb = '/Sakana/view/images/produtos/' . $nomeFoto;

            $nomeProduto = $_POST["nomeProduto"] ?? "";
            $descProduto = $_POST["descProduto"] ?? "";
            $categoriaId = $_POST["idCategoria"] ?? "";
            $valorProduto = $_POST["valorProduto"] ?? "";
            $fotoProduto = $caminhoWeb;

            if ($nomeProduto === "" || $descProduto === "" || $fotoProduto === null || $categoriaId === "" || $valorProduto === "") {
                $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "logadoGerencia&page=cadastroProduto");
            }

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
            }
            elseif ($error === "database_error") {
                $msg = "Banco de dados indisponível. Tente mais tarde.";
            }
            else {
                $msg = "Erro ao Cadastrar. Tente novamente.";
            }

            $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroProduto");
    }

     public function listarProdutos() {
        $produtoModel = new ProdutoModel();
        $listaProdutos = $produtoModel->listarProdutos();

        require_once __DIR__ . "/../view/pages/pages/gerencia/cardapio.php";
        }
    }
?>
