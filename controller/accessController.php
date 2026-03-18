<?php 
    class AccessController {
        function logado(){
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $email = $_POST["txtEmail"];
                $senha = $_POST["txtSenha"];

                require_once __DIR__ . "/../model/accountModel.php";
                $accontModel = new AccountModel();

                $usuario = $accontModel->logarUser($email, $senha);

                if ($usuario) {
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                    $_SESSION["idUser"] = $usuario["idUser"];
                    $_SESSION["nomeUser"] = $usuario["nomeUser"];

                    require_once __DIR__ . "/../view/accessPage.php";
                }
                else {
                    echo "<script>
                            alert('Email ou senha incorretos!');
                            window.location='/Sakana/index.php?action=login';
                          </script>";
                }
            }
            else {
                header("Location: /Sakana/index.php?action=login");
            }
        }

    }
?>
