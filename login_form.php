<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 19.11.2016
 * Time: 16:20
 */
require_once 'settings.php';
session_start();

if(isset($_POST['login']) && isset($_POST['password'])){

    $login = $_POST['login'];
    $password = $_POST['password'];
    $query = mysqli_query($link,"SELECT * FROM users WHERE login = '$login'");
    $data = mysqli_fetch_assoc($query);
    if($data['login'] == $login && $data['password']==$password){
        $_SESSION['login'] = $login;
        header('Location: /') ;

    }
    else{
        echo "Неверные данные";
        $_SESSION["is_auth"] = false;
    }
}

?>

</<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php require_once 'title.php' ?>
<div class="container">
<form method="post" class="form-signin" role="form">
    <h2 class="form-signin-heading">Вход в систему</h2>
    <input type="text" name="login" class="form-control" id="inputLogin" placeholder="Логин" required>
    <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Пароль" required>
    <button type="submit" name="send"  class="btn btn-default">Войти</button>

</form>
    <h3 align="center">Не зарегистрированы?</h3>
    <h3 align="center"><a href="register_form.php">Регистрация</a></h3>

</div>

</body>
</html>
