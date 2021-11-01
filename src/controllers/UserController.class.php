<?php
require_once "..//vendor/autoload.php";
require_once "./index.php";
require_once 'rb.php';
class UserController
{
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function login()
    {
        $var = $this->twig->load('userLogin.php');
        echo $var->render();
    }

    public function userDELETE()
    {
        session_start();
        $sessionIds = R::getAll("SELECT id FROM sessions");
        foreach ($sessionIds as $sessionId) {
            $id = $sessionId["id"];
        }
        var_dump($id);
        $deletesession = R::exec("DELETE FROM sessions WHERE id = $id");
        session_destroy();
        header("Location: ../../todo/todoIndex");
    }

    public function userPOST()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $users = R::getAll("SELECT * FROM users");
        foreach ($users as $user) {
            $id = $user["id"];
            $naam = $user["username"];
            $woord = $user["wachtwoord"];
            $usergegevens = R::getAll("SELECT * FROM users WHERE id = $id");
            if ($username == $naam) {
                if ($password == $woord) {
                    $token = bin2hex(random_bytes(100));
                    $session = R::dispense('sessions');
                    $session->username = $username;
                    $session->token = $token;
                    $storeSession = R::store($session);
                    session_start();
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    $_SESSION["token"] = $token;
                    header('Location: ../../todo/todoIndex');
                } elseif ($password != $woord) {
                    echo "foutmelding verkeerd wachtwoord";
                }
            } elseif ($username != $naam) {
                echo "foutmelding verkeerd gebruikersnaam";
            }
        }
    }
}
?>