<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 18.11.2016
 * Time: 12:50
 */


$host = 'test';
$database = 'test';
$user = 'root';
$password = '' ;


$link=mysqli_connect($host, $user, $password,$database)
      or die ("Ошибка" . mysqli_error($link));


//Фильтрация данных:
function clean($value = "") {
    $value = trim($value);               // Используется функция trim для удаления пробелов из начала и конца строки.
    $value = stripslashes($value);       // Используется функция stripslashes для удаления экранированных символов.
    $value = strip_tags($value);         // Используется функция strip_tags для удаления HTML и PHP тегов.
    $value = htmlspecialchars($value);   // Используется функция htmlspecialchars для преобразования специальных символов в HTML сущности.

    return $value;
}


// Далее применяется функция,где:
// Используется функция mb_strlen для проверки длины строки.
// Первый параметр, $value это строка, которую нужно проверить.
// Второй параметр $min минимально допустимая длинна строки.
// Третий параметр $max - максимально допустимая длина.

function check_length($value = "", $min, $max) {
    $result_check_length = (mb_strlen($value) < $min || mb_strlen($value) > $max); //$value это строка, которую нужно проверить, второй параметр $min минимально допустимая длинна строки, третий параметр $max - максимально допустимая длинна.
    return !$result_check_length;
}





