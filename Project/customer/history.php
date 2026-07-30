<?php

require_once "../config/database.php";

require_once "../includes/language.php";

if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/login.php");
    exit();

}

$personID = $_SESSION["PersonID"];

$type = $_GET["type"] ?? "active";

if ($type == "history") {

    $statusCondition = "('Approved','Rejected','Completed')";
    $title = "Request History";

} else {

    $statusCondition = "('Pending','In Progress')";
    $title = "My Active Requests";

}

$sql = "

SELECT

sr.RequestID,
sr.TrackingNumber,
s.ServiceName,
sr.SubmissionDate,
sr.Status

FROM ServiceRequest sr

JOIN Service s
ON sr.ServiceID = s.ServiceID

WHERE sr.CustomerID = ?

AND sr.Status IN $statusCondition

ORDER BY sr.SubmissionDate DESC

";

$stmt = $pdo->prepare($sql);

$stmt->execute([$personID]);

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $type == "history" ? $lang["request_history"] : $lang["my_active_requests"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:40px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:1200px;
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
    margin-bottom:35px;
}

.success-message{
    background:#eaf7ec;
    color:#2e7d32;
    border:1px solid #b7e4c7;
    padding:14px 18px;
    border-radius:10px;
    margin-bottom:25px;
    font-weight:bold;
}

.empty-message{
    text-align:center;
    padding:40px;
    color:#666;
    font-size:18px;
}

table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    overflow:hidden;
    border-radius:14px;
    box-shadow:0 5px 15px rgba(0,0,0,.06);
}

th{
    background:#1f4fa0;
    color:#fff;
    padding:18px;
    text-align:left;
    font-size:18px;
    font-weight:700;
}

td{
    padding:18px;
    border-bottom:1px solid #edf2fb;
    font-size:16px;
    color:#444;
}

tr:last-child td{
    border-bottom:none;
}

tbody tr{
    transition:.2s;
}

tbody tr:hover{
    background:#f8f9fc;
}

.button{
    display:inline-block;
    background:#1f4fa0;
    color:#fff;
    text-decoration:none;
    padding:10px 22px;
    border-radius:10px;
    font-weight:bold;
    transition:.2s;
}

.button:hover{
    background:#173d7f;
}

.back-link{
    display:inline-block;
    margin-top:30px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
    font-size:16px;
}

.back-link:hover{
    text-decoration:underline;
}

/* Status badges (optional) */
.status-pending,
.status-progress,
.status-approved,
.status-rejected,
.status-completed{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    font-weight:bold;
    font-size:14px;
}

.status-pending{
    background:#fff4d6;
    color:#b26a00;
}

.status-progress{
    background:#d9ecff;
    color:#0066cc;
}

.status-approved{
    background:#dff5e4;
    color:#2e7d32;
}

.status-rejected{
    background:#fde2e2;
    color:#d32f2f;
}

.status-completed{
    background:#ece4ff;
    color:#5e35b1;
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

th:last-child,
td:last-child{
    text-align:center;
}

th:nth-child(4),
td:nth-child(4){
    text-align:center;
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

@media (max-width:900px){

    .container{
        padding:25px;
    }

    table{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
    }

    h1{
        font-size:30px;
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

<h1><?= $type == "history" ? $lang["request_history"] : $lang["my_active_requests"] ?></h1>

<?php if(isset($_GET["success"])) { ?>

<div class="success-message">
    <?= $lang["request_submitted_successfully"] ?>
</div>

<?php } ?>

<?php if(count($requests)==0){ ?>

<div class="empty-message">

<?php
if($type == "history"){
    echo $lang["no_completed_requests"];
}else{
    echo $lang["no_active_requests"];
}
?>

</div>

<?php } else { ?>

<table>

<thead>
<tr>

<th><?= $lang["tracking_number"] ?></th>

<th><?= $lang["service"] ?></th>

<th><?= $lang["submission_date"] ?></th>

<th><?= $lang["status"] ?></th>

<th><?= $lang["action"] ?></th>

</tr>
</thead>

<tbody>


<?php foreach($requests as $request){ ?>

<tr>

<td>

<?= htmlspecialchars($request["TrackingNumber"]) ?>

</td>

<td>

<?= htmlspecialchars($request["ServiceName"]) ?>

</td>

<td>

<?= htmlspecialchars($request["SubmissionDate"]) ?>

</td>

<td>

<?php
$status = htmlspecialchars($request["Status"]);

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

<span class="<?= $class ?>">
    <?= $status ?>
</span>

</td>

<td>

<a class="button"
href="request_details.php?id=<?= $request["RequestID"] ?>">

<?= $lang["view"] ?>

</a>

</td>

</tr>

<?php } ?>

</tbody>
</table>

<?php } ?>

<br>

<a class="back-link" href="dashboard.php">
    ← <?= $lang["back_to_dashboard"] ?>
</a>

</div>

</body>

</html>