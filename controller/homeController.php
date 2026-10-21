<?php
    class HomeController {
        public function index() {
            require_once __DIR__ . "/../view/homePage.php";
        }

        public function institucional() {
            require_once __DIR__ . "/../view/institucionalPage.php";
        }

        public function politicaPrivacidade() {
            require_once __DIR__ . "/../view/privacyPolicyPage.php";
        }
    }
?>
