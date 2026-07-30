<?php

require_once "../config/database.php";

require_once "../includes/language.php";

if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/login.php");
    exit();

}

$personID = $_SESSION["PersonID"];


$sql = "

SELECT

p.FirstName,
p.LastName,
p.Email,
p.Gender,
p.Phone,
p.Address,
p.DateOfBirth,

cf.MaritalStatus,
cf.FamilyMembers,
cf.Dependents,

ce.Employer,
ce.JobTitle,
ce.YearsOfService,
ce.EmploymentStatus,

cfi.MonthlyIncome,
cfi.MonthlyExpenses,
cfi.Liabilities

FROM Person p

LEFT JOIN CustomerFamilyInformation cf
ON p.PersonID = cf.PersonID

LEFT JOIN CustomerEmploymentInformation ce
ON p.PersonID = ce.PersonID

LEFT JOIN CustomerFinancialInformation cfi
ON p.PersonID = cfi.PersonID

WHERE p.PersonID = ?

";

$stmt = $pdo->prepare($sql);

$stmt->execute([$personID]);

$profile = $stmt->fetch(PDO::FETCH_ASSOC);

function displayValue($value)
{
    global $lang;

    return ($value !== null && $value !== "")
        ? htmlspecialchars($value)
        : $lang["not_provided"];
}

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["my_profile"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    font-family:Arial,sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:15px 0;
}

.container{
    width:700px;
    background:#fff;
    border-radius:14px;
    padding:22px;
    box-shadow:0 8px 22px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#184b99;
    margin:0 0 18px;
    font-size:26px;
}

.section{
    margin-top:22px;
}

.section h2{
    color:#184b99;
    border-bottom:2px solid #e9eef8;
    padding-bottom:6px;
    margin-bottom:12px;
    font-size:18px;
}

.info-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px 18px;
}

.info-box{
    background:#f8f9fc;
    border:1px solid #e5e5e5;
    border-radius:8px;
    padding:10px 12px;
    font-size:13px;
}

.info-box strong{
    display:block;
    color:#184b99;
    margin-bottom:4px;
    font-size:13px;
}

.actions{
    margin-top:22px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.button{
    display:inline-block;
    background:#0d6efd;
    color:#fff;
    padding:10px 20px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    font-size:13px;
    transition:.2s;
}

.button:hover{
    background:#0b5ed7;
}

.back-link{
    color:#0d6efd;
    text-decoration:none;
    font-weight:bold;
    font-size:13px;
}

.back-link:hover{
    text-decoration:underline;
}

.success-box{
    background:#d4edda;
    color:#155724;
    border:1px solid #c3e6cb;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    font-size:13px;
}

.language-switch{
    position:fixed;
    top:15px;
    right:15px;
    z-index:100;
}

html[dir="rtl"] .language-switch{
    right:auto;
    left:15px;
}

.language-btn{
    display:inline-block;
    padding:7px 14px;
    background:#fff;
    color:#333;
    text-decoration:none;
    border:1px solid #ddd;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    box-shadow:0 3px 8px rgba(0,0,0,.12);
    transition:.2s;
}

.language-btn:hover{
    background:#0d6efd;
    color:#fff;
}

@media(max-width:900px){

.container{
    width:95%;
    padding:18px;
}

.info-grid{
    grid-template-columns:1fr;
}

.actions{
    flex-direction:column;
    gap:15px;
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

<h1><?= $lang["my_profile"] ?></h1>

<?php if (isset($_GET["success"])) { ?>

<div style="
background:#d4edda;
color:#155724;
padding:10px;
border:1px solid #c3e6cb;
margin-bottom:20px;
">
    <?= $lang["profile_updated"] ?>
</div>

<?php } ?>

<div class="section">

<h2><?= $lang["personal_information"] ?></h2>

<div class="info-grid">

<div class="info-box">
<strong><?= $lang["first_name"] ?></strong>
<?= displayValue($profile["FirstName"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["last_name"] ?></strong>
<?= displayValue($profile["LastName"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["email"] ?></strong>
<?= displayValue($profile["Email"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["gender"] ?></strong>
<?= displayValue($profile["Gender"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["phone"] ?></strong>
<?= displayValue($profile["Phone"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["date_of_birth"] ?></strong>
<?= displayValue($profile["DateOfBirth"]) ?>
</div>

<div class="info-box" style="grid-column:1/-1;">
<strong><?= $lang["address"] ?></strong>
<?= displayValue($profile["Address"]) ?>
</div>

</div>

</div>

<div class="section">

<h2><?= $lang["family_information"] ?></h2>

<div class="info-grid">

<div class="info-box">
<strong><?= $lang["marital_status"] ?></strong>
<?= displayValue($profile["MaritalStatus"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["family_members"] ?></strong>
<?= displayValue($profile["FamilyMembers"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["dependents"] ?></strong>
<?= displayValue($profile["Dependents"]) ?>
</div>

</div>

</div>

<div class="section">

<h2><?= $lang["employment_information"] ?></h2>

<div class="info-grid">

<div class="info-box">
<strong><?= $lang["employer"] ?></strong>
<?= displayValue($profile["Employer"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["job_title"] ?></strong>
<?= displayValue($profile["JobTitle"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["years_of_service"] ?></strong>
<?= displayValue($profile["YearsOfService"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["employment_status"] ?></strong>
<?= displayValue($profile["EmploymentStatus"]) ?>
</div>

</div>

</div>

<div class="section">

<h2><?= $lang["financial_information"] ?></h2>

<div class="info-grid">

<div class="info-box">
<strong><?= $lang["monthly_income"] ?></strong>
<?= displayValue($profile["MonthlyIncome"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["monthly_expenses"] ?></strong>
<?= displayValue($profile["MonthlyExpenses"]) ?>
</div>

<div class="info-box">
<strong><?= $lang["liabilities"] ?></strong>
<?= displayValue($profile["Liabilities"]) ?>
</div>

</div>

</div>

<div class="actions">

<a class="button" href="edit_profile.php">
<?= $lang["edit_profile"] ?>
</a>

<a class="back-link" href="dashboard.php">
← <?= $lang["back_to_dashboard"] ?>
</a>

</div>

</div>

</body>

</html>