<?php

require_once "../config/database.php";

require_once "../includes/language.php";


$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "
        SELECT
            p.PersonID,
            p.FirstName,
            p.LastName,
            p.PasswordHash
        FROM Person p
        INNER JOIN Customer c
            ON p.PersonID = c.PersonID
        WHERE p.Email = ?
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["PasswordHash"])) {

        $_SESSION["PersonID"] = $user["PersonID"];
        $_SESSION["FirstName"] = $user["FirstName"];
        $_SESSION["LastName"] = $user["LastName"];

        header("Location: ../customer/dashboard.php");
        exit();

    } else {

        $message = $lang["invalid_login"];

    }

}

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="../assets/css/style.css">

    <title><?= $lang["customer_login"] ?></title>

    <style>

body{
    margin:0;
    font-family:Arial,sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-card{
    width:420px;
    background:#fff;
    padding:45px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
}

.login-card h1{
    margin:0;
    color:#184b99;
    font-size:38px;
    text-align:center;
}

.subtitle{
    text-align:center;
    color:#777;
    margin:10px 0 35px;
}

label{
    display:block;
    margin-bottom:8px;
    color:#444;
    font-weight:bold;
}

input{
    width:100%;
    padding:14px;
    border:1px solid #d9d9d9;
    border-radius:8px;
    font-size:15px;
    box-sizing:border-box;
    margin-bottom:20px;
}

input:focus{
    outline:none;
    border-color:#0d6efd;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:#0d6efd;
    color:#fff;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    background:#0b5ed7;
}

.create-account{
    display:block;
    margin-top:20px;
    padding:14px;
    border:2px solid #0d6efd;
    border-radius:8px;
    text-align:center;
    text-decoration:none;
    color:#0d6efd;
    font-weight:bold;
    transition:.2s;
}

.create-account:hover{
    background:#0d6efd;
    color:#fff;
}

.or{
    text-align:center;
    margin:25px 0;
    color:#888;
    position:relative;
}

.or:before,
.or:after{
    content:"";
    position:absolute;
    top:50%;
    width:40%;
    height:1px;
    background:#ddd;
}

.or:before{
    left:0;
}

.or:after{
    right:0;
}

.message{
    text-align:center;
    margin-bottom:20px;
}

.success{
    color:green;
}

.error{
    color:red;
}

.back-home{
    display:block;
    text-align:center;
    margin-top:25px;
    color:#0d6efd;
    text-decoration:none;
    font-weight:bold;
    transition:.2s;
}

.back-home:hover{
    text-decoration:underline;
    color:#0b5ed7;
}

.language-switch{
    position:fixed;
    top:25px;
    right:25px;
}

html[dir="rtl"] .language-switch{
    right:auto;
    left:25px;
}

.language-btn{
    display:inline-block;
    padding:10px 18px;
    background:#fff;
    border-radius:25px;
    text-decoration:none;
    color:#333;
    border:1px solid #ddd;
    box-shadow:0 4px 10px rgba(0,0,0,.12);
}

.language-btn:hover{
    background:#0d6efd;
    color:#fff;
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

<div class="login-card">

<h1><?= $lang["welcome_back"] ?></h1>

<p class="subtitle">
<?= $lang["sign_in_account"] ?>
</p>

<?php if(isset($_GET["registered"])): ?>

<div class="message success">

<?= $lang["registration_successful"] ?>

</div>

<?php endif; ?>

<?php if($message): ?>

<div class="message error">

<?= htmlspecialchars($message) ?>

</div>

<?php endif; ?>

<form method="POST">

<label><?= $lang["email"] ?></label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
required>

<label><?= $lang["password"] ?></label>

<input
type="password"
name="password"
required
oninput="this.value=this.value.replace(/\s/g,'')">

<button type="submit">

<?= $lang["login"] ?>

</button>

</form>

<div class="or"><?= $lang["or"] ?></div>

<a class="create-account" href="register.php">

<?= $lang["register"] ?>

</a>

<a class="back-home" href="../index.php">

← <?= $lang["back_home"] ?>

</a>

</div>

</body>

</html>