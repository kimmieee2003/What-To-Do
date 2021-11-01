<?php
require_once "..//vendor/autoload.php";
require_once "index.php";
require_once "services/UserService.class.php";
class ToDoController
{
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function todoIndex()
    {
        $instance = new UserService($this->twig);
        $instance->validateLoggedIn();
        $id = $_SESSION["id"];
        $done = R::getAll("SELECT * FROM todo WHERE user_id = $id AND klaar = 'true' ORDER BY volgorde");
        $convertedDone = R::convertToBeans("done", $done);

        $todo = R::getAll("SELECT * FROM todo WHERE user_id = $id AND klaar = 'false' ORDER BY volgorde");
        $convertedTodo = R::convertToBeans("todo", $todo);
        $var = $this->twig->load('todoIndex.twig');

        $users = R::getAll("SELECT * FROM users WHERE id = $id");

        echo $var->render(['convertedDone' => $convertedDone, 'convertedTodo' => $convertedTodo, 'users' => $users]);
    }

    public function todoPOST()
    {
        $var = $this->twig->load('todoAdd.twig');
        echo $var->render();
    }

    public function todoAddPOST()
    {
        session_start();
        $user_naam = $_SESSION["username"];
        $user_id = $_SESSION["id"];
        $newtodo = R::dispense('todo');
        $newtodo->user_naam = $user_naam;
        $newtodo->user_id = $user_id;
        $newtodo->naam = $_POST['naam'];
        $newtodo->todo = $_POST['new_todo_name'];
        $newtodo->klaar = $_POST['klaar'];

        $todo = R::getAll("SELECT * FROM todo WHERE user_id = $user_id");
        $aantal = count($todo);
        echo $aantal;

        $newtodo->volgorde = $aantal + 1;
        $newpost = R::store($newtodo);
        header("Location: ../../todo/todoIndex");
    }

    public function todoPUT()
    {
        $id = $_POST['id'];
        $edittodo = R::getRow("SELECT * FROM todo WHERE id = $id");
        $var = $this->twig->load('todoEdit.twig');
        echo $var->render(['todo' => $edittodo]);
    }

    public function todoEditPUT()
    {
        $id = $_POST["edit_todo_id"];
        $newtodo = R::load('todo', $id);
        $newtodo->todo = $_POST["todo"];
        $newtodo->klaar = $_POST["edit_todo_klaar"];
        $newpost = R::store($newtodo);
        header("Location: ../../todo/todoIndex");
    }

    public function todoDELETE()
    {
        $id = $_POST['id'];
        $deletetodo = R::exec("DELETE FROM todo WHERE id = $id");
        header("Location: ../../todo/todoIndex");
    }

    public function todoPLUS()
    {
        $id = $_POST['id'];
        $volgorde = $_POST['volgorde'];
        $volgordeplus = $_POST['volgorde'] + 1;
        $userid = $_POST['userid'];
        $todo = $_POST['todo'];
        $bean = R::load("todo", $id);
        $bean->volgorde = $volgordeplus;
        R::store($bean);
        $changetodomin = R::exec("UPDATE todo SET volgorde = $volgorde WHERE volgorde = $volgordeplus AND id <> $id AND user_id = $userid");
        header("Location: ../../todo/todoIndex");
    }

    public function todoMIN()
    {
        $id = $_POST['id'];
        $volgorde = $_POST['volgorde'];
        $volgordemin = $_POST['volgorde'] - 1;
        $userid = $_POST['userid'];
        $username = $_POST['username'];
        $todo = $_POST['todo'];

        $bean = R::load("todo", $id);
        $bean->volgorde = $volgordemin;
        R::store($bean);
        $changetodomin = R::exec("UPDATE todo SET volgorde = $volgorde WHERE volgorde = $volgordemin AND id <> $id AND user_id = $userid");
        echo $id;
        echo $username;
        header("Location: ../../todo/todoIndex");
    }

    public function todoKLAAR()
    {
        $id = $_POST['id'];
        $bean = R::load("todo", $id);
        $bean->klaar = "true";
        R::store($bean);
        header("Location: ../../todo/todoIndex");
    }

    public function todoNIETKLAAR()
    {
        $id = $_POST['id'];
        $bean = R::load("todo", $id);
        $bean->klaar = "false";
        R::store($bean);
        header("Location: ../../todo/todoIndex");
    }
}


?>