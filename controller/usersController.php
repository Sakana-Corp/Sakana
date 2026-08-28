<?php

require_once __DIR__ . "/baseController.php";

class UsersController extends BaseController{
    private function renderPainel(string $pagina = "home", array $dados = []): void{
        SessionHelper::gerarToken();

        $mapaPaginas = [
            "home" => null,

            "editarPerfil" => __DIR__ . "/../view/pages/usersPages/edtPerfil/edtPerfil.php",

            "funcionarios" => __DIR__ . "/../view/pages/usersPages/gerencia/funcionarios.php",

            "cadastroFuncionario" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroFuncionario.php",

            "consultaFuncionario" => __DIR__ . "/../view/pages/usersPages/gerencia/consultaFuncionario.php",

            "pedidos" => __DIR__ . "/../view/pages/usersPages/gerencia/pedidos.php",

            "cardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php",

            "cadastroProduto" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroProduto.php",

            "cadastroCategoria" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroCategoria.php",

            "consultaCardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/consultaCardapio.php",

            "mesas" => __DIR__ . "/../view/pages/usersPages/gerencia/mesas.php",

            "cadastroMesa" => __DIR__ . "/../view/pages/usersPages/gerencia/mesasForm.php",

            "editarMesa" => __DIR__ . "/../view/pages/usersPages/gerencia/mesasForm.php"
        ];

        if (!array_key_exists($pagina, $mapaPaginas)) {
            $pagina = "home";
        }

        $paginaAtiva = $pagina;
        $arquivoConteudo = $mapaPaginas[$pagina];

        extract($dados, EXTR_SKIP);

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/ManagementPanel.php";
    }


    public function editarPerfil(): void
    {
        $this->requireAuth("login");
        $this->startSession();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            SessionHelper::garanteSessaoIniciada();

            if (!SessionHelper::validarToken()) {

                SessionHelper::setFlash(
                    "error",
                    "Tentativa de requisição inválida."
                );

                $this->renderPainel("editarPerfil", []);

                return;
            }

            $nome = trim($_POST["nome"] ?? "");
            $email = trim($_POST["email"] ?? "");

            if ($nome === "" || $email === "") {

                SessionHelper::setFlash(
                    "warning",
                    "Preencha nome e email para continuar."
                );

                $this->renderPainel("editarPerfil", []);

                return;
            }

            require_once __DIR__ . "/../model/accountRepository.php";

            try {

                $accountRepository = new AccountRepository();

                $emailAtual = $_SESSION["emailUser"] ?? "";

                $usuarioAtual =
                    $emailAtual !== ""
                    ? $accountRepository->findByEmail($emailAtual)
                    : null;

                if ($usuarioAtual === null) {

                    SessionHelper::setFlash(
                        "error",
                        "Não foi possível localizar o usuário atual."
                    );

                    $this->renderPainel("editarPerfil", []);

                    return;
                }

                if (
                    $email !== $usuarioAtual["email"] &&
                    $accountRepository->emailExists($email)
                ) {

                    SessionHelper::setFlash(
                        "error",
                        "Este email já está sendo usado por outro usuário."
                    );

                    $this->renderPainel("editarPerfil", []);

                    return;
                }

                $fotoPerfil = $usuarioAtual["fotoPerfil"] ?? null;

                if (
                    !empty($_FILES["fotoPerfil"]["tmp_name"]) &&
                    is_uploaded_file($_FILES["fotoPerfil"]["tmp_name"])
                ) {

                    $extensao = strtolower(
                        pathinfo(
                            $_FILES["fotoPerfil"]["name"],
                            PATHINFO_EXTENSION
                        )
                    );

                    $permitidas = [
                        "jpg",
                        "jpeg",
                        "png",
                        "webp"
                    ];

                    if (!in_array($extensao, $permitidas, true)) {

                        SessionHelper::setFlash(
                            "warning",
                            "A imagem deve ser JPG, JPEG, PNG ou WEBP."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    if ($_FILES["fotoPerfil"]["size"] > 2 * 1024 * 1024) {

                        SessionHelper::setFlash(
                            "warning",
                            "A imagem deve ter no máximo 2MB."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    $dirDestino =
                        __DIR__ . "/../view/images/perfis";

                    if (
                        !is_dir($dirDestino) &&
                        !mkdir($dirDestino, 0755, true) &&
                        !is_dir($dirDestino)
                    ) {

                        SessionHelper::setFlash(
                            "error",
                            "Não foi possível preparar a pasta de imagens."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    $nomeArquivo =
                        "perfil_" .
                        (int) $usuarioAtual["idUser"] .
                        "_" .
                        bin2hex(random_bytes(8)) .
                        "." .
                        $extensao;

                    $caminhoDestino =
                        $dirDestino . "/" . $nomeArquivo;

                    if (
                        !move_uploaded_file(
                            $_FILES["fotoPerfil"]["tmp_name"],
                            $caminhoDestino
                        )
                    ) {

                        SessionHelper::setFlash(
                            "error",
                            "Não foi possível salvar a imagem de perfil."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    $fotoPerfil =
                        "/Sakana/view/images/perfis/" . $nomeArquivo;
                }

                $ok = $accountRepository->updateProfile(
                    (int) $usuarioAtual["idUser"],
                    $nome,
                    $email,
                    $fotoPerfil
                );

                if ($ok) {

                    $_SESSION["nomeUser"] = $nome;
                    $_SESSION["emailUser"] = $email;
                    $_SESSION["fotoPerfil"] = $fotoPerfil ?? "";

                    SessionHelper::setFlash(
                        "success",
                        "Perfil atualizado com sucesso."
                    );

                } else {

                    SessionHelper::setFlash(
                        "error",
                        "Não foi possível atualizar o perfil."
                    );
                }

            } catch (RuntimeException $e) {

                SessionHelper::setFlash(
                    "error",
                    "Erro ao atualizar o perfil. Tente novamente."
                );
            }

            $this->renderPainel("editarPerfil", []);

            return;
        }

        SessionHelper::gerarToken();

        $this->renderPainel("editarPerfil", []);
    }


    public function logarGerencia(): void{
        $this->requireSetor("gerencia");

        $this->renderPainel("home", []);
    }

    // ACESSO ÀS PÁGINAS DE GERÊNCIA / ATENDIMENTO
    public function logadoGerencia(string $pagina = "home", array $dados = []): void
{
    $this->requireAnySetor([
        "gerencia",
        "atendimento",
        "cozinha"
    ]);

    // Permissões
    if ($pagina === "funcionarios") {
        $this->requireSetor("gerencia");
    }

    if (
        $pagina === "cadastroMesa" ||
        $pagina === "editarMesa"
    ) {
        $this->requireSetor("gerencia");
    }

    if ($pagina === "pedidos") {
        $this->requireAnySetor([
            "gerencia",
            "atendimento",
            "cozinha"
        ]);
    }

    if (
        $pagina === "cardapio" ||
        $pagina === "consultaCardapio" ||
        $pagina === "mesas"
    ) {
        $this->requireAnySetor([
            "gerencia",
            "atendimento"
        ]);
    }

    if (
        $pagina === "cadastroProduto" ||
        $pagina === "cadastroCategoria"
    ) {
        $this->requireSetor("gerencia");
    }

    // Funcionários
    if ($pagina === "consultaFuncionario") {

        require_once __DIR__ . "/../model/employeeModel.php";

        $employeeModel = new EmployeeModel();

        $dados["listaFuncionarios"] =
            $employeeModel->listarTodosFuncionario();
    }

    // Cardápio
    if ($pagina === "cardapio") {

        require_once __DIR__ . "/../model/categoriaModel.php";

        $categoriaModel = new CategoriaModel();

        $dados["listaCategorias"] =
            $categoriaModel->listarCategorias();

        require_once __DIR__ . "/../model/produtoModel.php";

        $produtoModel = new ProdutoModel();

        $dados["listaProdutos"] =
            $produtoModel->listarProdutos();
    }

    // Cadastro de produto
    if ($pagina === "cadastroProduto") {

        require_once __DIR__ . "/../model/categoriaModel.php";

        $categoriaModel = new CategoriaModel();

        $dados["listaCategorias"] =
            $categoriaModel->listarCategorias();
    }

    // Mesas
    if ($pagina === "mesas") {

        require_once __DIR__ . "/../model/mesaModel.php";

        $mesaModel = new Mesa();

        $dados["listaMesas"] =
            $mesaModel->listarMesas();
    }

    // Finalmente renderiza a página
    $this->renderPainel($pagina, $dados);
}
}