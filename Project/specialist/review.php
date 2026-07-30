<?php

require_once "../config/database.php";

require_once "../includes/language.php";


if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/specialist_login.php");
    exit();

}

$specialistID = $_SESSION["PersonID"];

if (!isset($_GET["id"])) {

    die($lang["invalid_request"]);

}

$requestID = (int)$_GET["id"];

/* If Pending, change to In Progress */

$stmt = $pdo->prepare("

UPDATE ServiceRequest

SET Status='In Progress'

WHERE RequestID=?

AND Status='Pending'

");

$stmt->execute([$requestID]);

/* Save Review */

/* Save or Update Review */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $decision = $_POST["decision"];
    $reviewNotes = trim($_POST["review_notes"]);

    $check = $pdo->prepare("

        SELECT ReviewID

        FROM Review

        WHERE RequestID=?

    ");

    $check->execute([$requestID]);

    $existingReview = $check->fetch(PDO::FETCH_ASSOC);

    if($existingReview){

        $updateReview = $pdo->prepare("

            UPDATE Review

            SET

                SpecialistID=?,
                ReviewDate=NOW(),
                ReviewNotes=?,
                Decision=?

            WHERE RequestID=?

        ");

        $updateReview->execute([

            $specialistID,
            $reviewNotes,
            $decision,
            $requestID

        ]);

    }else{

        $insert = $pdo->prepare("

            INSERT INTO Review

            (

                RequestID,
                SpecialistID,
                ReviewDate,
                ReviewNotes,
                Decision

            )

            VALUES

            (

                ?, ?, NOW(), ?, ?

            )

        ");

        $insert->execute([

            $requestID,
            $specialistID,
            $reviewNotes,
            $decision

        ]);

    }

    $update = $pdo->prepare("

        UPDATE ServiceRequest

        SET Status=?

        WHERE RequestID=?

    ");

    $update->execute([

        $decision,
        $requestID

    ]);

    header("Location: history.php");

    exit();

}

/* Request Information */

$sql = "

SELECT

sr.RequestID,
sr.TrackingNumber,
sr.SubmissionDate,
sr.Status,

p.FirstName,
p.LastName,
p.Email,
p.Phone,
p.Gender,
p.Address,
p.DateOfBirth,

s.ServiceName,
s.Description

FROM ServiceRequest sr

JOIN Customer c
ON sr.CustomerID=c.PersonID

JOIN Person p
ON c.PersonID=p.PersonID

JOIN Service s
ON sr.ServiceID=s.ServiceID

WHERE sr.RequestID=?

";

$stmt=$pdo->prepare($sql);

$stmt->execute([$requestID]);

$request=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$request){

    die($lang["request_not_found"]);

}

/* Family Snapshot */

$stmt=$pdo->prepare("

SELECT *

FROM RequestFamilyInformation

WHERE RequestID=?

");

$stmt->execute([$requestID]);

$family=$stmt->fetch(PDO::FETCH_ASSOC);

/* Employment Snapshot */

$stmt=$pdo->prepare("

SELECT *

FROM RequestEmploymentInformation

WHERE RequestID=?

");

$stmt->execute([$requestID]);

$employment=$stmt->fetch(PDO::FETCH_ASSOC);

/* Financial Snapshot */

$stmt=$pdo->prepare("

SELECT *

FROM RequestFinancialInformation

WHERE RequestID=?

");

$stmt->execute([$requestID]);

$financial=$stmt->fetch(PDO::FETCH_ASSOC);

/* Existing Review */

$stmt=$pdo->prepare("

SELECT

r.*,

CONCAT(p.FirstName,' ',p.LastName) AS SpecialistName

FROM Review r

JOIN Specialist s
ON r.SpecialistID=s.PersonID

JOIN Person p
ON s.PersonID=p.PersonID

WHERE r.RequestID=?

");

$stmt->execute([$requestID]);

$review=$stmt->fetch(PDO::FETCH_ASSOC);

function value($x){

    global $lang;

    return ($x !== null && $x !== "")
        ? htmlspecialchars($x)
        : $lang["not_provided"];

}

?>



<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["review_request"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:10px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:850px;
    max-width:92%;
    margin:10px auto;
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 22px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#1f4fa0;
    font-size:28px;
    margin:0 0 18px;
}

h2{
    color:#1f4fa0;
    font-size:18px;
    margin:18px 0 10px;
    padding-bottom:5px;
    border-bottom:2px solid #edf2fb;
}

.info-box{
    background:#f8f9fc;
    border-left:4px solid #1f4fa0;
    border-radius:10px;
    padding:12px 16px;
    margin-bottom:10px;
}

.info-box p{
    margin:7px 0;
    font-size:14px;
    line-height:1.5;
    color:#444;
}

.info-box strong{
    color:#1f4fa0;
}

.status{
    display:inline-block;
    padding:5px 11px;
    border-radius:16px;
    font-size:11px;
    font-weight:bold;
}

.status-pending{
    background:#fff3cd;
    color:#856404;
}

.status-progress{
    background:#dbeafe;
    color:#0c63e4;
}

.status-approved{
    background:#d4edda;
    color:#155724;
}

.status-rejected{
    background:#f8d7da;
    color:#721c24;
}

.status-completed{
    background:#e2d9f3;
    color:#5a2ea6;
}

textarea,
select{
    width:100%;
    padding:12px;
    border:1px solid #d9d9d9;
    border-radius:10px;
    font-size:14px;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

textarea{
    min-height:140px;
    resize:vertical;
}

textarea:focus,
select:focus{
    outline:none;
    border-color:#1f4fa0;
    box-shadow:0 0 0 3px rgba(31,79,160,.12);
}

.button{
    display:inline-block;
    background:#1f4fa0;
    color:#fff;
    border:none;
    border-radius:10px;
    padding:10px 22px;
    font-size:14px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

.button:hover{
    background:#173d7f;
}

.back-link{
    display:inline-block;
    margin-top:15px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
}

.back-link:hover{
    text-decoration:underline;
}

.language-switch{
    position:fixed;
    top:18px;
    right:18px;
    z-index:100;
}

html[dir="rtl"] .language-switch{
    right:auto;
    left:18px;
}

.language-btn{
    display:inline-block;
    padding:8px 15px;
    background:#fff;
    color:#333;
    text-decoration:none;
    border-radius:22px;
    border:1px solid #ddd;
    box-shadow:0 3px 8px rgba(0,0,0,.12);
    font-size:13px;
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

<h1><?= $lang["review_request"] ?></h1>

<h2><?= $lang["request_information"] ?></h2>

<div class="info-box">

<p>
<strong><?= $lang["tracking_number"] ?>:</strong>
<?= value($request["TrackingNumber"]) ?>
</p>

<p>
<strong><?= $lang["service"] ?>:</strong>
<?= value($request["ServiceName"]) ?>
</p>

<?php

$status = $request["Status"];
$class = "";

switch($status){

    case "Pending":
        $class = "status-pending";
        break;

    case "In Progress":
        $class = "status-progress";
        break;

    case "Approved":
        $class = "status-approved";
        break;

    case "Rejected":
        $class = "status-rejected";
        break;

    case "Completed":
        $class = "status-completed";
        break;

}

?>

<p>

<strong><?= $lang["status"] ?>:</strong>

<span class="status <?= $class ?>">
<?= htmlspecialchars($status) ?>
</span>

</p>

<p>
<strong><?= $lang["submission_date"] ?>:</strong>
<?= value($request["SubmissionDate"]) ?>
</p>

</div>

<h2><?= $lang["personal_information"] ?></h2>

<div class="info-box">

<p>
<strong><?= $lang["customer_name"] ?>:</strong>
<?= value($request["FirstName"]) ?>
<?= value($request["LastName"]) ?>
</p>

<p>
<strong><?= $lang["email"] ?>:</strong>
<?= value($request["Email"]) ?>
</p>

<p>
<strong><?= $lang["phone"] ?>:</strong>
<?= value($request["Phone"]) ?>
</p>

<p>
<strong><?= $lang["gender"] ?>:</strong>
<?= value($request["Gender"]) ?>
</p>

<p>
<strong><?= $lang["date_of_birth"] ?>:</strong>
<?= value($request["DateOfBirth"]) ?>
</p>

<p>
<strong><?= $lang["address"] ?>:</strong>
<?= value($request["Address"]) ?>
</p>

</div>

<h2><?= $lang["family_information"] ?></h2>

<div class="info-box">

<p>
<strong><?= $lang["marital_status"] ?>:</strong>
<?= value($family["MaritalStatus"]) ?>
</p>

<p>
<strong><?= $lang["family_members"] ?>:</strong>
<?= value($family["FamilyMembers"]) ?>
</p>

<p>
<strong><?= $lang["dependents"] ?>:</strong>
<?= value($family["Dependents"]) ?>
</p>

</div>

<h2><?= $lang["employment_information"] ?></h2>

<div class="info-box">

<p>
<strong><?= $lang["employer"] ?>:</strong>
<?= value($employment["Employer"]) ?>
</p>

<p>
<strong><?= $lang["job_title"] ?>:</strong>
<?= value($employment["JobTitle"]) ?>
</p>

<p>
<strong><?= $lang["employment_status"] ?>:</strong>
<?= value($employment["EmploymentStatus"]) ?>
</p>

<p>
<strong><?= $lang["years_of_service"] ?>:</strong>
<?= value($employment["YearsOfService"]) ?>
</p>

</div>

<h2><?= $lang["financial_information"] ?></h2>

<div class="info-box">

<p>
<strong><?= $lang["monthly_income"] ?>:</strong>
<?= value($financial["MonthlyIncome"]) ?>
</p>

<p>
<strong><?= $lang["monthly_expenses"] ?>:</strong>
<?= value($financial["MonthlyExpenses"]) ?>
</p>

<p>
<strong><?= $lang["liabilities"] ?>:</strong>
<?= value($financial["Liabilities"]) ?>
</p>

</div>

<h2>

<?= $review ? $lang["edit_review"] : $lang["submit_review"] ?>

</h2>

<form method="POST">

<div class="info-box">

<p>

<strong><?= $lang["specialist_notes"] ?>:</strong>

</p>

<textarea
name="review_notes"
required><?= htmlspecialchars($review["ReviewNotes"] ?? "") ?></textarea>

<br><br>

<p>

<strong><?= $lang["decision"] ?>:</strong>

</p>

<select
name="decision"
required>

<option

value="Approved"

<?= (($review["Decision"] ?? "") == "Approved") ? "selected" : "" ?>

>

<?= $lang["approved"] ?>

</option>

<option

value="Rejected"

<?= (($review["Decision"] ?? "") == "Rejected") ? "selected" : "" ?>

>

<?= $lang["rejected"] ?>

</option>

</select>

<br><br>


</div>

<br>
<button

class="button"

type="submit">

<?= $review ? $lang["update_review"] : $lang["save_review"] ?>

</button>

</form>


<br>

<a class="back-link" href="requests.php">

← <?= $lang["back_requests"] ?>

</a>

</div>

</body>

</html>