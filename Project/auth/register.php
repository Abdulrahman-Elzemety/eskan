<?php

require_once "../config/database.php";

require_once "../includes/language.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $gender = $_POST["gender"];
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $dateOfBirth = $_POST["date_of_birth"];

    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password)
    ) {

        $message = $lang["password_rules"];

    } elseif (!preg_match("/^[A-Za-z ]+$/", $firstName)) {

        $message = $lang["first_name_letters"];

    } elseif (!preg_match("/^[A-Za-z ]+$/", $lastName)) {

        $message = $lang["last_name_letters"];

    } elseif (!preg_match('/^\+971\d{9}$/', $phone)) {

        $message = $lang["phone_rules"];

    } elseif ($password != $confirmPassword) {

        $message = $lang["passwords_not_match"];

    } else {

        $age = date_diff(date_create($dateOfBirth), date_create('today'))->y;

        if ($age < 18) {

            $message = $lang["must_be_18"];

        } else {

            $check = $pdo->prepare("SELECT PersonID FROM Person WHERE Email = ?");

            $check->execute([$email]);

            if ($check->fetch()) {

                $message = $lang["email_exists"];

            } else {

                try {

                    $pdo->beginTransaction();

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO Person
                    (
                        FirstName,
                        LastName,
                        Email,
                        PasswordHash,
                        Gender,
                        Phone,
                        Address,
                        DateOfBirth,
                        RegistrationDate
                    )
                    VALUES
                    (
                        ?,?,?,?,?,?,?,?,NOW()
                    )";

                    $stmt = $pdo->prepare($sql);

                    $stmt->execute([
                        $firstName,
                        $lastName,
                        $email,
                        $passwordHash,
                        $gender,
                        $phone,
                        $address,
                        $dateOfBirth
                    ]);

                    $personID = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO Customer(PersonID) VALUES (?)");

                    $stmt->execute([$personID]);

                    $pdo->commit();

                    header("Location: login.php?registered=1");

                    exit();

                } catch (PDOException $e) {

                    $pdo->rollBack();

                    $message = $lang["unexpected_error"];

                }

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

    <meta charset="UTF-8">
    
    <title><?= $lang["register"] ?></title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
body{
    font-family:Arial,sans-serif;
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

.container{
    width:820px;
    max-width:90%;
    padding:30px;
    border-radius:18px;
    background:white;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#1d3f75;
    margin:0 0 30px;
    font-size:34px;
}

.row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.field{
    display:flex;
    flex-direction:column;
}

.full{
    grid-column:1 / -1;
}

label{
    font-weight:bold;
    color:#444;
    margin-bottom:8px;
}

input,
select{
    width:100%;
    padding:10px 12px;
    border:1px solid #d9d9d9;
    border-radius:8px;
    font-size:15px;
    box-sizing:border-box;
    transition:.2s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#0d6efd;
    box-shadow:0 0 0 3px rgba(13,110,253,.15);
}

button{
    width:100%;
    margin-top:20px;
    padding:15px;
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

p{
    text-align:center;
}

p a{
    color:#0d6efd;
    text-decoration:none;
    font-weight:bold;
}

p a:hover{
    text-decoration:underline;
}

.language-switch{
    position:fixed;
    top:25px;
    right:25px;
    z-index:100;
}

html[dir="rtl"] .language-switch{
    right:auto;
    left:25px;
}

.language-btn{
    display:inline-block;
    padding:10px 18px;
    background:#fff;
    color:#333;
    text-decoration:none;
    border-radius:25px;
    border:1px solid #ddd;
    font-size:14px;
    font-weight:bold;
    box-shadow:0 4px 12px rgba(0,0,0,.15);
    transition:.2s;
}

.language-btn:hover{
    background:#0d6efd;
    color:#fff;
}

@media(max-width:700px){

.row{
    grid-template-columns:1fr;
}

.container{
    padding:25px;
}

h1{
    font-size:28px;
}

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

<h1><?= $lang["customer_registration"] ?></h1>

<?php if($message){ ?>

<p style="color:red;text-align:center;">

<?= $message ?>

</p>

<?php } ?>

<form method="POST">

<div class="row">

<div class="field">

<label><?= $lang["first_name"] ?></label>

<input
type="text"
name="first_name"
value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
required
oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')">

</div>

<div class="field">

<label><?= $lang["last_name"] ?></label>

<input
type="text"
name="last_name"
value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
required
oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')">

</div>

<div class="field">

<label><?= $lang["email"] ?></label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
required>

</div>

<div class="field">

<label><?= $lang["phone"] ?></label>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($_POST['phone'] ?? '+971') ?>"
maxlength="13"
pattern="\+971\d{9}"
title="<?= $lang["phone_hint"] ?>"
required>

</div>

<div class="field">

<label><?= $lang["password"] ?></label>

<input
type="password"
name="password"
required
oninput="this.value=this.value.replace(/\s/g,'')">

</div>

<div class="field">

<label><?= $lang["confirm_password"] ?></label>

<input
type="password"
name="confirm_password"
required
oninput="this.value=this.value.replace(/\s/g,'')">

</div>

<div class="field">

<label><?= $lang["gender"] ?></label>

<select name="gender" required>

<option value=""><?= $lang["select_gender"] ?></option>

<option value="Male"
<?= (($_POST['gender'] ?? '') == 'Male') ? 'selected' : '' ?>>

<?= $lang["male"] ?>

</option>

<option value="Female"
<?= (($_POST['gender'] ?? '') == 'Female') ? 'selected' : '' ?>>

<?= $lang["female"] ?>

</option>

</select>

</div>

<div class="field">

<label><?= $lang["date_of_birth"] ?></label>

<input
type="date"
name="date_of_birth"
id="date_of_birth"
value="<?= htmlspecialchars($_POST['date_of_birth'] ?? '') ?>"
required>

</div>

<div class="field full">

<label><?= $lang["address"] ?></label>

<input
type="text"
name="address"
value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
required>

</div>

</div>

<button type="submit">

<?= $lang["register"] ?>

</button>

</form>

<p>

<?= $lang["already_have_account"] ?>

<a href="login.php">

<?= $lang["login"] ?>

</a>

</p>

<p>

<a href="../index.php">

← <?= $lang["back_home"] ?>

</a>

</p>

</div>

<script>
const phone = document.querySelector('input[name="phone"]');

phone.addEventListener('input', function () {
    if (!this.value.startsWith('+971')) {
        this.value = '+971';
    }

    this.value = '+971' + this.value.slice(4).replace(/\D/g, '').slice(0, 9);
});

phone.addEventListener('keydown', function (e) {
    if ((this.selectionStart <= 4 && (e.key === "Backspace" || e.key === "Delete"))) {
        e.preventDefault();
    }
});

const dob = document.getElementById("date_of_birth");

dob.addEventListener("input", function () {

    const birthDate = new Date(this.value);
    const today = new Date();

    let age = today.getFullYear() - birthDate.getFullYear();

    const monthDifference = today.getMonth() - birthDate.getMonth();

    if (
        monthDifference < 0 ||
        (monthDifference === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--;
    }

    if (age < 18) {
        this.setCustomValidity("<?= $lang["must_be_18"] ?>");
    } else {
        this.setCustomValidity("");
    }

});
</script>

</body>

</html>