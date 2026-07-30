<?php

require_once "../config/database.php";

require_once "../includes/language.php";

if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/login.php");
    exit();

}

$personID = $_SESSION["PersonID"];

if (!isset($_GET["service"])) {

    die($lang["invalid_service"]);

}

$serviceID = (int)$_GET["service"];

/* Get service information */

$sql = "

SELECT

s.ServiceID,
s.ServiceName,
s.Description,
se.AdditionalRequirements

FROM Service s

LEFT JOIN ServiceEligibility se
ON s.ServiceID = se.ServiceID

WHERE s.ServiceID = ?

";

$stmt = $pdo->prepare($sql);

$stmt->execute([$serviceID]);

$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {

    die($lang["service_not_found"]);

}

/* Verify the customer is eligible */

$sql = "

SELECT s.ServiceID

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

WHERE s.ServiceID = ?

AND
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

";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $personID,
    $serviceID
]);

if (!$stmt->fetch()) {

    die($lang["not_eligible_service"]);

}

/* Submit Request */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $check = $pdo->prepare("

    SELECT Status

    FROM ServiceRequest

    WHERE CustomerID = ?

    AND ServiceID = ?

    AND Status IN ('Pending','In Progress','Approved','Completed')

");

    $check->execute([

        $personID,
        $serviceID

    ]);

    $existingRequest = $check->fetch(PDO::FETCH_ASSOC);

    if ($existingRequest) {

        if (
            $existingRequest["Status"] == "Pending" ||
            $existingRequest["Status"] == "In Progress"
        ) {

            die($lang["active_request_exists"]);

        }

        if (
            $existingRequest["Status"] == "Approved" ||
            $existingRequest["Status"] == "Completed"
        ) {

            die($lang["service_already_completed"]);

        }

    }

    $trackingNumber = "SR-" . date("YmdHis") . "-" . rand(1000,9999);

    $stmt = $pdo->prepare("

        INSERT INTO ServiceRequest

        (

            CustomerID,
            ServiceID,
            TrackingNumber,
            SubmissionDate,
            Status

        )

        VALUES

        (

            ?, ?, ?, NOW(), 'Pending'

        )

    ");

    $stmt->execute([

        $personID,
        $serviceID,
        $trackingNumber

    ]);

    $requestID = $pdo->lastInsertId();

    /* Copy Family Information */

    $stmt = $pdo->prepare("

    SELECT

    MaritalStatus,
    FamilyMembers,
    Dependents

    FROM CustomerFamilyInformation

    WHERE PersonID = ?

    ");

    $stmt->execute([$personID]);

    $family = $stmt->fetch(PDO::FETCH_ASSOC);

    if($family){

        $stmt = $pdo->prepare("

        INSERT INTO RequestFamilyInformation

        (

            RequestID,
            MaritalStatus,
            FamilyMembers,
            Dependents

        )

        VALUES

        (

            ?, ?, ?, ?

        )

        ");

        $stmt->execute([

            $requestID,
            $family["MaritalStatus"],
            $family["FamilyMembers"],
            $family["Dependents"]

        ]);

    }

    /* Copy Employment Information */

    $stmt = $pdo->prepare("

    SELECT

    Employer,
    JobTitle,
    YearsOfService,
    EmploymentStatus

    FROM CustomerEmploymentInformation

    WHERE PersonID = ?

    ");

    $stmt->execute([$personID]);

    $employment = $stmt->fetch(PDO::FETCH_ASSOC);

    if($employment){

        $stmt = $pdo->prepare("

        INSERT INTO RequestEmploymentInformation

        (

            RequestID,
            Employer,
            JobTitle,
            YearsOfService,
            EmploymentStatus

        )

        VALUES

        (

            ?, ?, ?, ?, ?

        )

        ");

        $stmt->execute([

            $requestID,
            $employment["Employer"],
            $employment["JobTitle"],
            $employment["YearsOfService"],
            $employment["EmploymentStatus"]

        ]);

    }

    /* Copy Financial Information */

    $stmt = $pdo->prepare("

    SELECT

    MonthlyIncome,
    MonthlyExpenses,
    Liabilities

    FROM CustomerFinancialInformation

    WHERE PersonID = ?

    ");

    $stmt->execute([$personID]);

    $financial = $stmt->fetch(PDO::FETCH_ASSOC);

    if($financial){

        $stmt = $pdo->prepare("

        INSERT INTO RequestFinancialInformation

        (

            RequestID,
            MonthlyIncome,
            MonthlyExpenses,
            Liabilities

        )

        VALUES

        (

            ?, ?, ?, ?

        )

        ");

        $stmt->execute([

            $requestID,
            $financial["MonthlyIncome"],
            $financial["MonthlyExpenses"],
            $financial["Liabilities"]

        ]);

    }

    header("Location: history.php?type=active&success=1");

    exit();

}

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["submit_service_request"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:40px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:900px;
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
    margin-bottom:30px;
}

.service-description{
    font-size:17px;
    color:#555;
    line-height:1.8;
    margin-bottom:30px;
}

.requirements{
    background:#f8f9fc;
    border-left:5px solid #1f4fa0;
    border-radius:12px;
    padding:20px;
    margin-bottom:35px;
    color:#444;
    line-height:1.8;
}

html[dir="rtl"] .requirements{
    border-left:none;
    border-right:5px solid #1f4fa0;
}

.requirements strong{
    display:block;
    color:#1f4fa0;
    font-size:20px;
    margin-bottom:10px;
}

.submit-btn{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    background:#1f4fa0;
    color:#fff;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

.submit-btn:hover{
    background:#173d7f;
}

.back-link{
    display:inline-block;
    margin-top:25px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
}

.back-link:hover{
    text-decoration:underline;
}

/* Language Switch */

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
    font-weight:bold;
    transition:.2s;
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

<h1><?= htmlspecialchars($service["ServiceName"]) ?></h1>

<p class="service-description">
<?= htmlspecialchars($service["Description"]) ?>
</p>

<?php if (!empty($service["AdditionalRequirements"])) { ?>

<div class="requirements">

<strong><?= $lang["additional_requirements"] ?></strong>

<?= nl2br(htmlspecialchars($service["AdditionalRequirements"])) ?>

</div>

<?php } ?>

<form method="POST">

<button type="submit" class="submit-btn">
<?= $lang["submit_service_request"] ?>
</button>

</form>

<a class="back-link" href="services.php">
← <?= $lang["back_to_available_services"] ?>
</a>

</div>



</body>

</html>