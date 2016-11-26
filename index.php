<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 18.11.2016
 * Time: 12:50
 */
require_once 'settings.php';
session_start();

// если значения переменных $key и $key2 не определены - присваиваем им значения по умолчанию.
if(!isset($key)){
    $key = "date";
}
if(!isset($key2)){
    $key2 = "DESC";
}

// Если GET запрос передает определенное значение - присваиваем переменным $key и $key2 нужные нам значения.
if(isset($_GET['date'])){
    $key = "date";
    $key2 = "ASC";
    //echo "Сортировка по дате(сначала старые)";
}

if (isset($_GET['name'])){
    $key = "name";
    $key2 = "ASC";
    //echo "Сортировка по имени в алфавитном порядке";
}

if (isset( $_GET['email'])){
    $key = "email";
    $key2 = "ASC";
    //echo "Сортировка по email в алфавитном порядке";
}

if (isset($_SESSION['login'])){
    $query = "SELECT * FROM feedback ORDER BY $key $key2 "; // Выбираем нужные нам данные.Переменная $key отвечает за выбор поля таблицы,а переменная $key2 - в каком направлении данные будут сортироваться.
    $result = mysqli_query($link,$query)
              or die ("Ошибка" . mysqli_error($link));
}else{
    $query = "SELECT * FROM feedback WHERE publish = 1 ORDER BY $key $key2 "; // Выбираем нужные нам данные.Переменная $key отвечает за выбор поля таблицы,а переменная $key2 - в каком направлении данные будут сортироваться.
    $result = mysqli_query($link,$query)
              or die ("Ошибка" . mysqli_error($link));
}



if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){ //Проверяем,отличные ли от Null пришли данные.
    // Фильтруем данные (о функции 'clean' подробности в файле settings.php)
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $email_validate = filter_var($email, FILTER_VALIDATE_EMAIL);
    $message = clean($_POST['message']);
    $date = date("Y-m-d H:i:s");


    // Далее делаем проверку на наличие загруженных файлов.
    // Проверяем тип файлов.
    if($_FILES['picture']['type'] == "image/gif" || $_FILES['picture']['type'] == "image/jpeg" || $_FILES['picture']['type'] == "image/png"){

        // Путь загрузки файлов:
        $uploaddir = 'View/img/';

        // Имя файла:
        $uploadfile = $uploaddir . time();

        // Шифруем имя файла, дабы избежать одинаковых имен файлов в будущем:
        $uploadfile = $uploaddir . md5($uploadfile) . rand(999,100000) . "." . basename($_FILES['picture']['type']);

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile)) {
            echo "Файл корректен и был успешно загружен.\n";
        } else {
            echo "Возможная атака с помощью файловой загрузки!\n";
        }

        $image = $uploadfile ;

    }




    // Проверка данных на валидность(о функции 'check_length' подробности в файле settings.php) :
    if(check_length($name, 2, 25) && check_length($message, 10, 1000) && $email_validate) {
        // Если все хорошо,добавляем данные в таблицу:
        $query_feedbackForm = "INSERT INTO feedback VALUES ('','$name','$email_validate','$image','$message','$date','')";
        mysqli_query($link,$query_feedbackForm)
            or die ("Ошибка" . mysqli_error($link));
        // И отправляем сообщение на электронную почту:
        $to = "";
        $subject = "Отзыв от:" . " " . $name . " " . "<$email_validate>";
        $mail_message = $message;
        mail($to, $subject, $message);
        header('Location: /');
        exit;

    }

    else {
        echo "Введенные данные некорректные";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Отзывы</title>
    <style>
        .center {
            text-align: center;
        }
    </style>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>
    <script>
        $(document).ready(function () {
            $("#imagePreview").hide();
            $("#previewbutton").click(function () {
                $("#preview").text("Предварительный просмотр:");
                $("#imagePreview").show();
            });
            $("#name").keyup(function () {
                var value = $(this).val();
                $("#previewbutton").click(function () {
                    $("#namePreview").text(value);
                });
            });
            $("#email").keyup(function () {
                var value = $(this).val();
                $("#previewbutton").click(function () {
                    $("#emailPreview").text(value);
                }).keyup();
            });


                function handleFileSelect(evt) {
                    var files = evt.target.files; // FileList object
                    var output = [];
                    // Loop through the FileList and render image files as thumbnails.
                    for (var i = 0, f; f = files[i]; i++) {

                        output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                            f.size, ' bytes, last modified: ',
                            f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                            '</li>');

                        document.getElementById('hm').innerHTML = '<ul>' + output.join('') + '</ul>';



                        // Only process image files.
                        if (!f.type.match('image.*')) {
                            continue;
                        }

                        var reader = new FileReader();

                        // Closure to capture the file information.
                        reader.onload = (function(theFile) {
                            return function(e) {
                                // Render thumbnail.
                                var span = document.createElement('span');
                                span.innerHTML = ['<img class="thumb" width="320px" height="240px" src="', e.target.result,
                                    '" title="', theFile.name, '"/>'].join('');
                                document.getElementById('list').insertBefore(span, null);
                            };
                        })(f);

                        // Read in the image file as a data URL.
                        reader.readAsDataURL(f);
                    }
                }

                document.getElementById('image').addEventListener('change', handleFileSelect, false);


            $("#message").keyup(function () {
                var value = $(this).val();
                $("#previewbutton").click(function () {
                    $("#messagePreview").text(value);
                }).keyup();
            });
        });
    </script>


</head>
<body>
<?php require_once 'title.php' ?>

<div class="container">
    <div class="row">
<ul>
    <h1>Отзывы:<br></h1>
    <li><a href="/">Сортировать по дате(сначала новые,стоит по умолчанию)</a></li>
    <li><a href="index.php?date=sort">Сортировать по дате(сначала старые)</a></li>
    <li><a href="index.php?name=sort">Сортировать по имени (в алфавитном порядке)</a></li>
    <li><a href="index.php?email=sort">Сортировать по email (в алфавитном порядке)</a></li>
</ul>

<br>

<hr>


<?php while($row = mysqli_fetch_array($result)): //Вывод отзывов на страничку ?>

<div class="center">

<blockquote>
<h3><?php echo $row['name']?></h3>
<h4><?php echo $row['email']?></h4>
<p><img src="<?php echo $row['image']?>" width="320px" height="240px"></p>
<p><?php echo $row['message']?></p>
<small><?php echo $row['date']?></small>
</blockquote>

<?php if(isset($_SESSION['login'])):?>
    <?php if($row['publish']==1):?>
        <p>Опубликовано <span class="glyphicon glyphicon-ok"></span></p>
    <?php else:?>
        <p>Не опубликовано <span class="glyphicon glyphicon-remove"></span></p>
    <?php endif; ?>
    <p><a href="edit.php?id=<?php echo $row['id']?>">Редактировать отзыв  <span class="glyphicon glyphicon-pencil"></span></a>  </p>
<?php endif;?>
<?php endwhile; ?>
</div>
<hr>
<br>

<div class="center">
    <h3>Форма обратной связи:</h3<br>
</div>
<div class="col-md-12 col-md-offset-4">
<form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" >
    <div class="form-group">
        <label class="col-sm-1 control-label">Имя</label>
        <div class="col-sm-3">
    <input type="text" name="name" class="form-control" id="name" placeholder="Имя" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-1 control-label">Email</label>
        <div class="col-sm-3">
    <input type="email" name="email" class="form-control" id="email" placeholder="Ваш email" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-1 control-label">Фото</label>
        <div class="col-sm-3">
    <input type="file" name="picture" id="image"><output id="hm"></output>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-1 control-label">Сообщение</label>
        <div class="col-sm-3">
    <textarea name="message" class="form-control" id="message" placeholder="Введите сообщение"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-5">
    <input type="submit" name="send" class="btn btn-default"> <input type="button" name="button" class="btn btn-default" id="previewbutton" value="Предварительный просмотр">
            </div>
        </div>
</form>
    </div>
</div>

<br>
<div class="center">
<h2 id="preview"></h2>
<h3 id="namePreview"></h3>
<h4 id="emailPreview"></h4>
<p id="imagePreview"><output id="list"></output></p>
<p id="messagePreview"></p>
</div>
    </div>
</div>
</body>
</html>
