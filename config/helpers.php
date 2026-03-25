<?php
    class SessionHelper {
        public static function garanteSessaoIniciada() {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
        }

        public static function gerarToken() {
            // Gera token CSRF uma vez por sessão/formulário.
            if (empty($_SESSION["csrf_token"])) {
                $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            }
        }

        public static function validarToken() {
            $tokenPost = $_POST["csrf_token"] ?? "";
            $tokenSessao = $_SESSION["csrf_token"] ?? "";

            if (!is_string($tokenPost) || !is_string($tokenSessao)) {
                return false;
            }

            if ($tokenPost === "" || $tokenSessao === "") {
                return false;
            }

            if (strlen($tokenPost) !== 64 || strlen($tokenSessao) !== 64) {
                return false;
            }

            // hash_equals evita comparação com timing previsível.
            return hash_equals($tokenPost, $tokenSessao);
        }

        public static function encerrar() {
            $_SESSION = [];

           if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
           }
           session_destroy();
        }
    }
?>