<?php
require_once "..//vendor/autoload.php";
require_once 'rb.php';
R::setup('mysql:host=localhost;dbname=level_3_building', 'root', '');

$loader = new Twig\Loader\FilesystemLoader('./views');
$twig = new Twig\Environment($loader, []);

$url = $_SERVER['REQUEST_URI'];

$array = explode("/", $url);

$cont = ucfirst($array[1]);
if (isset($array[2]) && is_numeric($array[2])) {
    $method = "author";
} elseif (isset($array[2])) {
    $method = ucfirst($array[2]);
} else {
    $method = "index";
}
echo $cont;
echo " ";
echo $method;
$reqmethod = $_SERVER['REQUEST_METHOD'];

if ($reqmethod == "POST") {
    if (isset($_POST['fake_method'])) {
        if ($_POST['fake_method'] === 'PUT') {
            $method = $method . 'PUT';
        } elseif ($_POST['fake_method'] === 'DELETE') {
            $method = $method . 'DELETE';
        } elseif ($_POST['fake_method'] === 'plus') {
            $method = $method . 'PLUS';
        } elseif ($_POST['fake_method'] === 'min') {
            $method = $method . 'MIN';
        } elseif ($_POST['fake_method'] === 'klaar') {
            $method = $method . 'KLAAR';
        } elseif ($_POST['fake_method'] === 'nietklaar') {
            $method = $method . 'NIETKLAAR';
        } else {
            $method = $method . $reqmethod;
        }
    } else {
        $method = $method . $reqmethod;
    }
}

if (!file_exists(".\controllers\\" . $cont . "Controller.class.php")) {
    http_response_code(404);
    echo "<h1>404 page not found</h1>";
} else {
    $link = ".\controllers\\" . $cont . "Controller.class.php";

    include_once $link;

    $hallo = $cont . "Controller";

    $instance = new $hallo($twig);
    if (!method_exists($instance, $method)) {
        http_response_code(404);
        echo "<h1>404 page not found</h1>";
    } else {
        $instance->$method($twig);
    }
}