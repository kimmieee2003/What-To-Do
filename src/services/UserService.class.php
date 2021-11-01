<?php
require_once "..//vendor/autoload.php";
require_once "./index.php";
require_once 'rb.php';
require_once "controllers/UserController.class.php";
class UserService
{
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function validateLoggedIn()
    {
        session_start();
        if (isset($_SESSION['token'])) {
            return;
        } elseif (!isset($_SESSION['token'])) {
            header("Location: ../../user/login");
        }
    }
}
?>