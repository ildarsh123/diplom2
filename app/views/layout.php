<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->e($title)?></title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/welcome/marlindev/diplom2/app/views/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/welcome/marlindev/diplom2/app/views/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/welcome/marlindev/diplom2/app/views/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="/welcome/marlindev/diplom2/app/views/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/welcome/marlindev/diplom2/app/views/css/fa-brands.css">
</head>
<body class="mod-bg-1 mod-nav-link">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="/welcome/marlindev/diplom2/public/users"><img alt="logo" class="d-inline-block align-top mr-2" src="/welcome/marlindev/diplom2/app/views/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/welcome/marlindev/diplom2/public/users">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/welcome/marlindev/diplom2/public/login">Войти</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/welcome/marlindev/diplom2/public/logout">Выйти</a>
            </li>
        </ul>
    </div>
</nav>


<?=$this->section('content')?>

