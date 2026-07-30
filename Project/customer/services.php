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

s.ServiceID,
s.ServiceName,
s.Description,
se.AdditionalRequirements

FROM Service s

JOIN ServiceEligibility se
ON s.ServiceID = se.ServiceID

LEFT JOIN Person p
ON p.PersonID = ?

LEFT JOIN CustomerFamilyInformation cf
ON p.PersonID = cf.PersonID

LEFT JOIN CustomerEmploymentInformation ce
ON p.PersonID = ce.PersonID

LEFT JOIN CustomerFinancialInformation cfi
ON p.PersonID = cfi.PersonID

WHERE

(
    se.MinimumAge IS NULL
    OR TIMESTAMPDIFF(YEAR, p.DateOfBirth, CURDATE()) >= se.MinimumAge
)

AND
(
    se.MaximumAge IS NULL
    OR TIMESTAMPDIFF(YEAR, p.DateOfBirth, CURDATE()) <= se.MaximumAge
)

AND
(
    se.Gender IS NULL
    OR p.Gender = se.Gender
)

AND
(
    se.MaritalStatus IS NULL
    OR cf.MaritalStatus = se.MaritalStatus
)

AND
(
    se.EmploymentStatus IS NULL
    OR ce.EmploymentStatus = se.EmploymentStatus
)

AND
(
    se.MinimumIncome IS NULL
    OR cfi.MonthlyIncome >= se.MinimumIncome
)

AND
(
    se.MaximumIncome IS NULL
    OR cfi.MonthlyIncome <= se.MaximumIncome
)

ORDER BY s.ServiceName

";

$stmt = $pdo->prepare($sql);
$stmt->execute([$personID]);

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["available_services"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:40px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:1150px;
    max-width:95%;
    margin:40px auto;
    background:#fff;
    border-radius:18px;
    padding:40px;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#1f4fa0;
    font-size:38px;
    margin-bottom:40px;
}

.services{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(450px,1fr));
    gap:25px;
}

.service-card{
    background:#fff;
    border:1px solid #e6ebf5;
    border-radius:16px;
    padding:25px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    transition:.25s;
}

.service-card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(0,0,0,.12);
}

.service-card h2{
    color:#1f4fa0;
    font-size:28px;
    margin-top:0;
    margin-bottom:15px;
}

.service-card p{
    color:#555;
    font-size:16px;
    line-height:1.7;
    margin-bottom:15px;
}

.requirements{
    background:#f8f9fc;
    border-left:5px solid #1f4fa0;
    border-radius:10px;
    padding:15px;
    margin:20px 0;
    color:#444;
}

.apply-btn{
    display:inline-block;
    background:#1f4fa0;
    color:#fff;
    text-decoration:none;
    padding:12px 24px;
    border-radius:10px;
    font-weight:bold;
    transition:.2s;
}

.apply-btn:hover{
    background:#173d7f;
}

.back-link{
    display:inline-block;
    margin-top:35px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
    font-size:16px;
}

.back-link:hover{
    text-decoration:underline;
}

.no-services{
    text-align:center;
    font-size:18px;
    color:#666;
    padding:40px;
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
    box-shadow:0 4px 10px rgba(0,0,0,.12);
    transition:.2s;
    font-weight:bold;
}

.language-btn:hover{
    background:#1f4fa0;
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



<div class="container">

<h1><?= $lang["available_services"] ?></h1>

<?php if (count($services) == 0) { ?>

<p class="no-services">
    <?= $lang["no_available_services"] ?>
</p>

<?php } else { ?>

<div class="services">

<?php foreach ($services as $service) { ?>

<div class="service-card">

    <h2>
        <?= htmlspecialchars($service["ServiceName"]) ?>
    </h2>

    <p>
        <?= htmlspecialchars($service["Description"]) ?>
    </p>

    <div class="requirements">
        <strong><?= $lang["additional_requirements"] ?></strong><br><br>
        <?= nl2br(htmlspecialchars($service["AdditionalRequirements"])) ?>
    </div>

    <a class="apply-btn"
       href="request.php?service=<?= $service["ServiceID"] ?>">
        <?= $lang["apply"] ?>
    </a>

</div>

<?php } ?>

</div>

<?php } ?>

<a class="back-link" href="dashboard.php">
← <?= $lang["back_to_dashboard"] ?>
</a>

</div>
</body>

</html>