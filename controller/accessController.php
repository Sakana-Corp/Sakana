<?php 
require_once __DIR__ . "/baseController.php";
require_once __DIR__ . "/../model/accountRepository.php";
require_once __DIR__ . "/../service/accountService.php";
class AccessController extends BaseController {

    private function getService(): AccountService {
        return new AccountService(new AccountRepository());
    }

    function logado(): void {
        $this->requirePost("login");
        $this->startSession();
        $this->validateCsrfOrRedirect("login");


        $resultado = $this->getService()->logar($_POST);

        if (!empty($resultado["ok"])) {
            session_regenerate_id(true);
            $_SESSION["idUser"] = $resultado["user"]["idUser"];
            $_SESSION["nomeUser"] = $resultado ["user"]["nomeUser"];
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

            $this->redirectToAction("painelAcesso");
        }

        $this->flashAndRedirect(
            $resultado["flashType"],
            $resultado["flashMessage"],
            $resultado["nextAction"]
        );
    }

    public function loginForm(): void {
        $this->startSession();
        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/loginPage.php";
    }

    public function painelAcesso(): void {
        $this->requireAuth("login");
        require_once __DIR__ . "/../view/accessPage.php";
    }

    public function logout() {
        $this->startSession();
        SessionHelper::encerrar();

        $this->redirectToAction("home");
    }
}
?>