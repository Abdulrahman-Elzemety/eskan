<?php

require_once "includes/language.php";

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["project"] ?></title>

<link rel="stylesheet" href="assets/css/style.css">

<style>

body{

    font-family:Arial;
    text-align:center;
    margin-top:100px;

}

.box{

    width:450px;
    margin:auto;
    background:white;
    padding:40px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);

}

h1{

    margin-bottom:10px;

}

p{

    margin-bottom:30px;
    color:#555;

}

.box a{

    display:block;
    margin:15px;
    padding:15px;
    text-decoration:none;
    background:#0066cc;
    color:white;
    border-radius:5px;
    font-size:17px;

}

.box a:hover{

    background:#004d99;

}

.language-switch{
    position:absolute;
    top:20px;
    right:20px;
}

html[dir="rtl"] .language-switch{
    right:auto;
    left:20px;
}

.language-btn{
    display:inline-block;
    padding:8px 16px;
    background:#ffffff;
    color:#333;
    text-decoration:none;
    border:1px solid #ddd;
    border-radius:20px;
    font-size:14px;
    font-weight:bold;
    box-shadow:0 2px 5px rgba(0,0,0,.15);
    transition:.2s;
}

.language-btn:hover{
    background:#0066cc;
    color:white;
}

</style>

</head>

<body>

<div class="language-switch">

<?php if($_SESSION["lang"]=="en"){ ?>

<a class="language-btn"
href="change_language.php?lang=ar&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
العربية
</a>

<?php } else { ?>

<a class="language-btn"
href="change_language.php?lang=en&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
English
</a>

<?php } ?>

</div>

<div class="box">

<h1><?= $lang["project"] ?></h1>

<p>

<?= $lang["choose_option"] ?>

</p>

<a href="auth/login.php">

<?= $lang["customer_login"] ?>

</a>

<a href="auth/register.php">

<?= $lang["customer_registration"] ?>

</a>

<a href="auth/specialist_login.php">

<?= $lang["specialist_login"] ?>

</a>

</div>

</body>

</html>