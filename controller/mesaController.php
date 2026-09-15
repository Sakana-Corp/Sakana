<?php

require_once __DIR__ . '/../model/mesaModel.php';

class MesaController
{
    private $mesaModel;

    public function __construct()
    {
        $this->mesaModel = new Mesa();
    }

    public function abrirCadastro(){
    require_once __DIR__ . "/usersController.php";
    $controller = new UsersController();
    $controller->logadoGerencia("cadastroMesa", []);
    }

    public function salvarMesa(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $numeroMesa = trim($_POST['numeroMesa'] ?? '');
        $numeroLugares = filter_input(
            INPUT_POST,
            'numeroLugares',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($numeroMesa === '' || $numeroLugares === false || $numeroLugares === null) {
            echo "Informe o número da mesa e a quantidade de lugares.";
            return;
        }

        $resultado = $this->mesaModel->cadastrarMesa($numeroMesa, $numeroLugares);

        if ($resultado) {

            header(
                "Location: /Sakana/index.php?action=logadoGerencia&page=mesas"
            );

            exit;
        }

        echo "Erro ao cadastrar mesa.";
    }

    public function editarMesa(){
        $idMesa = $_GET['id'] ?? '';

        if (empty($idMesa)) {
            echo "Mesa não encontrada.";
            return;
        }

        $mesa = $this->mesaModel->buscarMesa($idMesa);

        if (!$mesa) {
            echo "Mesa não encontrada.";
            return;
        }

        require_once __DIR__ . '/usersController.php';

        $controller = new UsersController();

        $controller->logadoGerencia(
            'editarMesa',
            [
                'mesa' => $mesa
            ]
        );
    }

    public function atualizarMesa(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $idMesa = $_POST['idMesa'] ?? '';
        $numeroMesa = trim($_POST['numeroMesa'] ?? '');
        $numeroLugares = filter_input(
            INPUT_POST,
            'numeroLugares',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if (empty($idMesa) || $numeroMesa === '' || $numeroLugares === false || $numeroLugares === null) {
            echo "Dados inválidos.";
            return;
        }

        $resultado = $this->mesaModel->editarMesa(
            $idMesa,
            $numeroMesa,
            $numeroLugares
        );

        if ($resultado) {

            header(
                "Location: /Sakana/index.php?action=logadoGerencia&page=mesas"
            );

            exit;
        }

        echo "Erro ao atualizar mesa.";
    }

    public function excluirMesa(){
        $idMesa = $_GET['id'] ?? '';

        if (empty($idMesa)) {
            echo "Mesa não encontrada.";
            return;
        }

        $resultado = $this->mesaModel->excluirMesa($idMesa);

        if ($resultado) {

            header(
                "Location: /Sakana/index.php?action=logadoGerencia&page=mesas"
            );

            exit;
        }

        echo "Erro ao excluir mesa.";
    }

    public function abrirMesa(){
        $idMesa = $_GET['id'] ?? '';

        if (empty($idMesa)) {
            echo "Mesa não encontrada.";
            return;
        }

        $resultado = $this->mesaModel->abrirMesa($idMesa);

        if ($resultado) {

            header(
                "Location: /Sakana/index.php?action=logadoGerencia&page=mesas"
            );

            exit;
        }

        echo "Erro ao abrir mesa.";
    }

    public function fecharMesa(){
        $idMesa = $_GET['id'] ?? '';

        if (empty($idMesa)) {
            echo "Mesa não encontrada.";
            return;
        }

        $resultado = $this->mesaModel->fecharMesa($idMesa);

        if ($resultado) {

            header(
                "Location: /Sakana/index.php?action=logadoGerencia&page=mesas"
            );

            exit;
        }

        echo "Erro ao fechar mesa.";
    }
}