<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 21.11.2016
 * Time: 20:21
 */
require_once 'settings.php';
session_start();
// Проверяем на сессию:
if(isset($_SESSION['login'])){
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        $query = "SELECT * FROM feedback WHERE id = '$id'";
        $result = mysqli_query($link,$query);
        $row = mysqli_fetch_array($result);

    }
}else {
    // Если пользователь не авторизован, но попытался зайти на страничку,выводим:
    echo "У вас недостаточно прав";
}


if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST['publish'])){ //Проверяем,отличные ли от Null пришли данные.
// Фильтруем данные (о функции 'clean' подробности в файле settings.php)
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $email_validate = filter_var($email, FILTER_VALIDATE_EMAIL);
    $message = clean($_POST['message']);
    $publish = $_POST['publish'];
    if(check_length($name, 2, 25) && check_length($message, 10, 1000) && $email_validate) {
    // Обновляем базу данных, если все хорошо.
    $query ="UPDATE feedback SET name='$name', email='$email_validate', message='$message', publish = '$publish' WHERE id='$id'";
    mysqli_query($link,$query) or die ("Ошибка" . mysqli_error($link));
    }
    if($_FILES['picture']['type'] == "image/gif" || $_FILES['picture']['type'] == "image/jpeg" || $_FILES['picture']['type'] == "image/png"){
        // Путь загрузки файлов:
        $uploaddir = 'View/img/';
        // Имя файла:
        $uploadfile = $uploaddir . time();
        // Шифруем имя файла, дабы избежать одинаковых имен файлов в будущем:
        $uploadfile = $uploaddir . md5($uploadfile) . rand(999,100000) . "." . basename($_FILES['picture']['type']);
        move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile);
        $image = $uploadfile ;
        $query ="UPDATE feedback SET image='$image' WHERE id='$id'";
        mysqli_query($link,$query) or die ("Ошибка" . mysqli_error($link));
    }
    header("Location: /edit.php?id=$id");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
<?php if(isset($_SESSION['login'])):// Если администратор авторизован, пускаем его к редактору ?>
<?php require_once 'title.php' ?>
<h1 align="center">Редактор отзыва</h1>
<div class="center">
<blockquote>
<h3><?php echo $row['name']?></h3>
<h4><?php echo $row['email']?></h4>
<p><img src="<?php echo $row['image']?>" width="320" height="240" ></p>
<p><?php echo $row['message']?></p>
<small><?php echo $row['date']?></small>
<?php if($row['publish']==1):?>
    <p>Опубликовано <span class="glyphicon glyphicon-ok"></span></p>
<?php else:?>
    <p>Не опубликовано <span class="glyphicon glyphicon-remove"></span></p>
<?php endif; ?>
</blockquote>
</div>
<hr>
<br>


<form method="post" enctype="multipart/form-data" class="form-horizontal" role="form">

    <div class="form-group">
        <label class="col-sm-4 control-label">Имя</label>
        <div class="col-sm-4">
    <input type="text" name="name" class="form-control" value="<?php echo $row['name']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Email</label>
        <div class="col-sm-4">
    <input type="text" name="email" class="form-control" value="<?php echo $row['email']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Фото</label>
        <div class="col-sm-4">
    <input type="file" name="picture" placeholder="Новая картинка">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Сообщение</label>
        <div class="col-sm-4">
    <textarea name="message" class="form-control" placeholder="Новое сообщение"><?php echo $row['message']?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Публикация</label>
        <div class="col-sm-4">
    <select name="publish" class="form-control">
        <option value="0">Скрыть отзыв</option>
        <option value="1">Опубликовать отзыв</option>
    </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-10">
    <input type="submit" name="send" class="btn btn-default" value="Сохранить">
        </div>
    </div>
</form>
<?php endif;?>

    </div>
</div>
</body>
</html>
