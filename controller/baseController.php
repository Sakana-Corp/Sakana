<?php
    class BaseController {
        protected function startSession(): void {
            SessionHelper::garanteSessaoIniciada();
        }

        protected function redirectToAction(string $action): void {
            header("Location: /Sakana/index.php?action={$action}");
            exit;
        }

        protected function flashAndRedirect(string $type, string $message, string $action): void {
            SessionHelper::setFlash($type, $message);
            $this->redirectToAction($action);
        }

        protected function requirePost(string $fallbackAction): void {
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                $this->redirectToAction($fallbackAction);
            }
        }

        protected function validateCsrfOrRedirect(string $fallbackAction): void {
            if (!SessionHelper::validarToken()) {
                $this->flashAndRedirect("error", "Tentativa de requisição inválida.", $fallbackAction);
            }
        }

        protected function requireAuth(string $fallbackAction = "login"): void {
            $this->startSession();

            if (empty($_SESSION["idUser"])) {
                $this->flashAndRedirect("info", "Sessão expirada. Faça login novamente.", $fallbackAction);
            }
        }
    }
?>