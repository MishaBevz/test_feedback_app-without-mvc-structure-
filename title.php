<?php

if(isset($_GET['logout'])){
    unset($_SESSION['login']);
    session_destroy();
    header('Location: /');
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>
    <link href="/View/css/sign-in.css" rel="stylesheet">
    <link href="/View/css/bootstrap.css" rel="stylesheet">
    <link href="/View/fonts/glyphicons-halflings-regular.eot">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/View/js/bootstrap.min.js" type="text/javascript"></script>

</head>
<body>
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Test</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <?php if(isset($_SESSION['login'])):?>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>  <?php echo $_SESSION['login'] ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/?logout">Выйти</a></li>

                    </ul>
                </li>
                <?php else: ?>
                <li><a href="/login_form.php"><span class="glyphicon glyphicon-off"></span></a></li>
                <?php endif;?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<!-- Begin page content -->
<div class="container">
    <div class="page-header">
