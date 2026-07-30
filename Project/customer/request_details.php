<?php

require_once "../config/database.php";

require_once "../includes/language.php";

if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/login.php");
    exit();

}

$personID = $_SESSION["PersonID"];

if (!isset($_GET["id"])) {

    die($lang["invalid_request"]);

}

$requestID = (int)$_GET["id"];

$sql = "

SELECT

sr.RequestID,
sr.TrackingNumber,
sr.SubmissionDate,
sr.Status,

s.ServiceName,
s.Description,

se.AdditionalRequirements,

r.ReviewDate,
r.ReviewNotes,
r.Decision

FROM ServiceRequest sr

JOIN Service s
ON sr.ServiceID = s.ServiceID

LEFT JOIN ServiceEligibility se
ON s.ServiceID = se.ServiceID

LEFT JOIN Review r
ON sr.RequestID = r.RequestID

WHERE

sr.RequestID = ?

AND sr.CustomerID = ?

";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    $requestID,
    $personID

]);

$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {

    die($lang["request_not_found"]);

}

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["request_details"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:10px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:700px;
    max-width:92%;
    margin:10px auto;
    background:#fff;
    border-radius:16px;
    padding:20px;
    box-shadow:0 10px 22px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#1f4fa0;
    font-size:28px;
    margin:0 0 15px;
}

h2{
    color:#1f4fa0;
    font-size:18px;
    margin:16px 0 10px;
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
    line-height:1.45;
    color:#444;
}

.info-box strong{
    color:#1f4fa0;
}

.notes-box{
    background:#fff;
    border:1px solid #e5ebf5;
    border-radius:10px;
    padding:12px;
    margin-top:8px;
    line-height:1.45;
    font-size:14px;
    color:#444;
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

.back-link{
    display:inline-block;
    margin-top:12px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
}

.back-link:hover{
    text-decoration:underline;
}

/* Language Switch */

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
    transition:.2s;
}

.language-btn:hover{
    background:#1f4fa0;
    color:#fff;
}

@media (max-width:900px){

    body{
        padding:8px 0;
    }

    .container{
        width:95%;
        padding:16px;
    }

    h1{
        font-size:24px;
    }

    h2{
        font-size:17px;
    }

    .info-box{
        padding:10px 12px;
    }

    .info-box p,
    .notes-box{
        font-size:13px;
    }

    .language-switch{
        top:12px;
        right:12px;
    }

    html[dir="rtl"] .language-switch{
        right:auto;
        left:12px;
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

<h1><?= $lang["request_details"] ?></h1>

<h2><?= $lang["request_information"] ?></h2>

<div class="info-box">

<p><strong><?= $lang["tracking_number"] ?>:</strong>
<?= htmlspecialchars($request["TrackingNumber"]) ?></p>

<p><strong><?= $lang["service"] ?>:</strong>
<?= htmlspecialchars($request["ServiceName"]) ?></p>

<p><strong><?= $lang["submission_date"] ?>:</strong>
<?= htmlspecialchars($request["SubmissionDate"]) ?></p>

<?php
$status = $request["Status"];
$class = "";

switch($status){
    case "Pending": $class="status-pending"; break;
    case "In Progress": $class="status-progress"; break;
    case "Approved": $class="status-approved"; break;
    case "Rejected": $class="status-rejected"; break;
    case "Completed": $class="status-completed"; break;
}
?>

<p>
<strong><?= $lang["status"] ?>:</strong>
<span class="status <?= $class ?>">
<?= htmlspecialchars($status) ?>
</span>
</p>

</div>

<?php if ($request["Decision"] != null) { ?>

<h2><?= $lang["review"] ?></h2>

<p>

<div class="info-box">

<p>
<strong><?= $lang["decision"] ?>:</strong>
<?= htmlspecialchars($request["Decision"]) ?>
</p>

<p>
<strong><?= $lang["review_date"] ?>:</strong>
<?= htmlspecialchars($request["ReviewDate"]) ?>
</p>

<?php if (!empty($request["ReviewNotes"])) { ?>

<p>
<strong><?= $lang["specialist_notes"] ?>:</strong>
</p>

<div class="notes-box">
<?= nl2br(htmlspecialchars($request["ReviewNotes"])) ?>
</div>

<?php } ?>

</div>

<?php } ?>

<h2><?= $lang["service_description"] ?></h2>

<div class="info-box">

<?= nl2br(htmlspecialchars($request["Description"])) ?>

</div>

<?php if(!empty($request["AdditionalRequirements"])){ ?>

<h2><?= $lang["additional_requirements"] ?></h2>

<div class="info-box">

<?= nl2br(htmlspecialchars($request["AdditionalRequirements"])) ?>

</div>

<?php } ?>

<br>

<a class="back-link" href="javascript:history.back()">

← <?= $lang["back"] ?>

</a>

</div>

</body>

</html>