<?php
require_once __DIR__ . "/baseController.php";
require_once __DIR__ . "/../model/accountModel.php";
require_once __DIR__ . "/../service/accountService.php";
class AccountController extends BaseController {

    public function getService(): AccountService {
        return new AccountService(new AccountModel());
    }

    public function cadastro(): void{
        $this->startSession();
        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/registerPage.php";
    }

    public function cadastrar(): void{
        $this->requirePost("cadastro");
        $this->startSession();
        $this->validateCsrfOrRedirect("cadastro");

        $resultado = $this->getService()->cadastrar($_POST);

        if (!empty($resultado["ok"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        $this->flashAndRedirect(
            $resultado["flashType"],
            $resultado["flashMessage"],
            $resultado["nextAction"]
        );
    }
}
?>
