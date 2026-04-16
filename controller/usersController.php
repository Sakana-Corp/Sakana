<?php
require_once __DIR__ . "/baseController.php";
class UsersController extends BaseController {
    private function renderGerencia(string $pagina = "home"): void {
        $mapaPaginas = [
            "home" => null,
            "funcionarios" => __DIR__ . "/../view/pages/usersPages/gerencia/funcionarios.php",
            "pedidos" => __DIR__ . "/../view/pages/usersPages/gerencia/pedidos.php",
            "cardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php",
            "mesas" => __DIR__ . "/../view/pages/usersPages/gerencia/mesas.php"
        ];

        if (!array_key_exists($pagina, $mapaPaginas)) {
            $pagina = "home";
        }

        $paginaAtiva = $pagina;
        $arquivoConteudo = $mapaPaginas[$pagina];


        require_once __DIR__ . "/../view/pages/usersPages/gerencia/ManagementPanel.php";
    }

    function logarGerencia(): void{
        $this->requireAuth("login");
        $this->renderGerencia("home");
    }
    function logadoGerencia(string $pagina = "home"): void{
        $this->requireAuth("login");
        $this->renderGerencia($pagina);
    }
}
?>
