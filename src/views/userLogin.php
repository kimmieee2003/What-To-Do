<?php
require_once "..//vendor/autoload.php";
require_once "./index.php";
require_once 'rb.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="user" method="POST">
        <input type="text" name="username" id="username"></input>
        <input type="password" name="password" id="password"></input>
        <input type="submit" name="submit" id="submit"></input>
    </form>
</body>
</html>