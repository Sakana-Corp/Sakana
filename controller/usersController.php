<?php
require_once __DIR__ . "/baseController.php";

class UsersController extends BaseController {

    private function renderGerencia(string $pagina = "home", array $dados = []): void {
        $mapaPaginas = [
            "home" => null,
            "editarPerfil" => __DIR__ . "/../view/pages/usersPages/edtPerfil/edtPerfil.php",

            "funcionarios" => __DIR__ . "/../view/pages/usersPages/gerencia/funcionarios.php",
            "cadastroFuncionario" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroFuncionario.php",
            "consultaFuncionario" => __DIR__ . "/../view/pages/usersPages/gerencia/consultaFuncionario.php",

            "pedidos" => __DIR__ . "/../view/pages/usersPages/gerencia/pedidos.php",

            "cardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php",
            "cadastroCardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroCardapio.php",
            "consultaCardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/consultaCardapio.php",

            "mesas" => __DIR__ . "/../view/pages/usersPages/gerencia/mesas.php"
        ];

        if (!array_key_exists($pagina, $mapaPaginas)) {
            $pagina = "home";
        }

        $paginaAtiva = $pagina;
        $arquivoConteudo = $mapaPaginas[$pagina];

        extract($dados, EXTR_SKIP);

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/ManagementPanel.php";
    }

    public function editarPerfil(): void {
        $this->requireAuth("login");
        $this->startSession();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            SessionHelper::garanteSessaoIniciada();

            if (!SessionHelper::validarToken()) {
                SessionHelper::setFlash("error", "Tentativa de requisição inválida.");
                $this->renderGerencia("editarPerfil", []);
                return;
            }

            $nome = trim($_POST["nome"] ?? "");
            $email = trim($_POST["email"] ?? "");

            if ($nome === "" || $email === "") {
                SessionHelper::setFlash("warning", "Preencha nome e email para continuar.");
                $this->renderGerencia("editarPerfil", []);
                return;
            }

            require_once __DIR__ . "/../model/accountRepository.php";

            try {
                $accountRepository = new AccountRepository();
                $emailAtual = $_SESSION["emailUser"] ?? "";
                $usuarioAtual = $emailAtual !== "" ? $accountRepository->findByEmail($emailAtual) : null;

                if ($usuarioAtual === null) {
                    SessionHelper::setFlash("error", "Não foi possível localizar o usuário atual.");
                    $this->renderGerencia("editarPerfil", []);
                    return;
                }

                if ($email !== $usuarioAtual["email"] && $accountRepository->emailExists($email)) {
                    SessionHelper::setFlash("error", "Este email já está sendo usado por outro usuário.");
                    $this->renderGerencia("editarPerfil", []);
                    return;
                }

                $fotoPerfil = $usuarioAtual["fotoPerfil"] ?? null;

                if (!empty($_FILES["fotoPerfil"]["tmp_name"]) && is_uploaded_file($_FILES["fotoPerfil"]["tmp_name"])) {
                    $extensao = strtolower(pathinfo($_FILES["fotoPerfil"]["name"], PATHINFO_EXTENSION));
                    $permitidas = ["jpg", "jpeg", "png", "webp"];

                    if (!in_array($extensao, $permitidas, true)) {
                        SessionHelper::setFlash("warning", "A imagem deve ser JPG, JPEG, PNG ou WEBP.");
                        $this->renderGerencia("editarPerfil", []);
                        return;
                    }

                    if ($_FILES["fotoPerfil"]["size"] > 2 * 1024 * 1024) {
                        SessionHelper::setFlash("warning", "A imagem deve ter no máximo 2MB.");
                        $this->renderGerencia("editarPerfil", []);
                        return;
                    }

                    $dirDestino = __DIR__ . "/../view/images/perfis";
                    if (!is_dir($dirDestino) && !mkdir($dirDestino, 0755, true) && !is_dir($dirDestino)) {
                        SessionHelper::setFlash("error", "Não foi possível preparar a pasta de imagens.");
                        $this->renderGerencia("editarPerfil", []);
                        return;
                    }

                    $nomeArquivo = "perfil_" . (int) $usuarioAtual["idUser"] . "_" . bin2hex(random_bytes(8)) . "." . $extensao;
                    $caminhoDestino = $dirDestino . "/" . $nomeArquivo;

                    if (!move_uploaded_file($_FILES["fotoPerfil"]["tmp_name"], $caminhoDestino)) {
                        SessionHelper::setFlash("error", "Não foi possível salvar a imagem de perfil.");
                        $this->renderGerencia("editarPerfil", []);
                        return;
                    }

                    $fotoPerfil = "/Sakana/view/images/perfis/" . $nomeArquivo;
                }

                $ok = $accountRepository->updateProfile((int) $usuarioAtual["idUser"], $nome, $email, $fotoPerfil);

                if ($ok) {
                    $_SESSION["nomeUser"] = $nome;
                    $_SESSION["emailUser"] = $email;
                    $_SESSION["fotoPerfil"] = $fotoPerfil ?? "";
                    SessionHelper::setFlash("success", "Perfil atualizado com sucesso.");
                } else {
                    SessionHelper::setFlash("error", "Não foi possível atualizar o perfil.");
                }
            } catch (RuntimeException $e) {
                SessionHelper::setFlash("error", "Erro ao atualizar o perfil. Tente novamente.");
            }

            $this->renderGerencia("editarPerfil", [] );
            return;
        }

        SessionHelper::gerarToken();
        $this->renderGerencia("editarPerfil", []);
    }

    public function logarGerencia(): void {
        $this->requireAuth("login");
        $this->renderGerencia("home", []);
    }

    public function logadoGerencia(string $pagina = "home"): void {
        $this->requireAuth("login");
        $dados = [];
        if ($pagina === "consultaFuncionario") {
            require_once __DIR__ . "/../model/employeeModel.php";
            $employeeModel = new EmployeeModel();
            $dados["listaFuncionarios"] = $employeeModel->listarTodosFuncionario();
            return;
        }
        $this->renderGerencia($pagina, $dados ?? []);
    }
}
?>