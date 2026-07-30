<?php

require_once "../includes/language.php";

if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/login.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

    <meta charset="UTF-8">

    <title><?= $lang["customer_dashboard"] ?></title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body{
            font-family:Arial;
        }

        .container{
            width:700px;
            margin:50px auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,.2);
        }

        h1{
            text-align:center;
        }

        p{
            text-align:center;
            font-size:18px;
        }

        .menu{
            margin-top:40px;
        }

        .menu a{

            display:block;

            margin:15px 0;

            padding:15px;

            text-align:center;

            text-decoration:none;

            background:#0066cc;

            color:white;

            border-radius:5px;

        }

        .menu a:hover{

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
href="../change_language.php?lang=ar&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
العربية
</a>

<?php } else { ?>

<a class="language-btn"
href="../change_language.php?lang=en&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
English
</a>

<?php } ?>

</div>

<div class="container">

<h1><?= $lang["customer_dashboard"] ?></h1>

<p>

<?= $lang["welcome"] ?>,

<strong>

<?= htmlspecialchars($_SESSION["FirstName"]) ?>

<?= htmlspecialchars($_SESSION["LastName"]) ?>

</strong>

</p>

<div class="menu">

<a href="profile.php">

<?= $lang["my_profile"] ?>

</a>

<a href="services.php">

<?= $lang["available_services"] ?>

</a>

<a href="history.php?type=history">

<?= $lang["request_history"] ?>  

</a>

<a href="history.php?type=active">

<?= $lang["my_active_requests"] ?>

</a>

<a href="../auth/logout.php">

<?= $lang["logout"] ?>

</a>

</div>

</div>

</body>

</html>