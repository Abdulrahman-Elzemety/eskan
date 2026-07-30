<?php

require_once "../config/database.php";

require_once "../includes/language.php";

if (!isset($_SESSION["PersonID"])) {

    header("Location: ../auth/specialist_login.php");
    exit();

}

/* Search Values */

$tracking = trim($_GET["tracking"] ?? "");
$customer = trim($_GET["customer"] ?? "");
$service = $_GET["service"] ?? "";

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

if($page < 1){
    $page = 1;
}

$recordsPerPage = 10;

$offset = ($page - 1) * $recordsPerPage;

/* Load Services */

$serviceStmt = $pdo->prepare("

SELECT

ServiceID,
ServiceName

FROM Service

ORDER BY ServiceName

");

$serviceStmt->execute();

$services = $serviceStmt->fetchAll(PDO::FETCH_ASSOC);

/* Load Requests */

$sql = "

SELECT

sr.RequestID,
sr.TrackingNumber,
sr.SubmissionDate,
sr.Status,

CONCAT(p.FirstName,' ',p.LastName) AS CustomerName,

s.ServiceID,
s.ServiceName

FROM ServiceRequest sr

JOIN Customer c
ON sr.CustomerID = c.PersonID

JOIN Person p
ON c.PersonID = p.PersonID

JOIN Service s
ON sr.ServiceID = s.ServiceID

WHERE sr.Status IN ('Pending','In Progress')

";

$params = [];

if ($tracking != "") {

    $sql .= "

    AND sr.TrackingNumber LIKE ?

    ";

    $params[] = "%$tracking%";

}

if ($customer != "") {

    $sql .= "

    AND CONCAT(p.FirstName,' ',p.LastName) LIKE ?

    ";

    $params[] = "%$customer%";

}

if ($service != "") {

    $sql .= "

    AND s.ServiceID = ?

    ";

    $params[] = $service;

}

$sql .= "

ORDER BY sr.SubmissionDate ASC

LIMIT $offset, $recordsPerPage

";

$countSql = "

SELECT COUNT(*)

FROM ServiceRequest sr

JOIN Customer c
ON sr.CustomerID = c.PersonID

JOIN Person p
ON c.PersonID = p.PersonID

JOIN Service s
ON sr.ServiceID = s.ServiceID

WHERE sr.Status IN ('Pending','In Progress')

";

$countParams = [];

if ($tracking != "") {

    $countSql .= "

    AND sr.TrackingNumber LIKE ?

    ";

    $countParams[] = "%$tracking%";

}

if ($customer != "") {

    $countSql .= "

    AND CONCAT(p.FirstName,' ',p.LastName) LIKE ?

    ";

    $countParams[] = "%$customer%";

}

if ($service != "") {

    $countSql .= "

    AND s.ServiceID = ?

    ";

    $countParams[] = $service;

}

$countStmt = $pdo->prepare($countSql);

$countStmt->execute($countParams);

$totalRecords = $countStmt->fetchColumn();

$totalPages = ceil($totalRecords / $recordsPerPage);

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["pending_requests"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:30px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:1050px;
    max-width:94%;
    margin:30px auto;
    background:#fff;
    border-radius:16px;
    padding:30px;
    box-shadow:0 10px 25px rgba(0,0,0,.14);
}

h1{
    text-align:center;
    color:#1f4fa0;
    font-size:32px;
    font-weight:700;
    margin-bottom:28px;
}

/* Search */

.search-box{
    background:#f8f9fc;
    border:1px solid #e5e5e5;
    border-radius:14px;
    padding:20px;
    margin-bottom:28px;
}

.search-row{
    display:flex;
    gap:16px;
    align-items:flex-end;
    flex-wrap:wrap;
}

.search-group{
    flex:1;
    min-width:200px;
}

.search-group label{
    display:block;
    margin-bottom:6px;
    color:#1f4fa0;
    font-weight:700;
    font-size:14px;
}

.search-group input,
.search-group select{
    width:100%;
    padding:10px 12px;
    border:1px solid #d9d9d9;
    border-radius:8px;
    background:#fff;
    font-size:14px;
    box-sizing:border-box;
    transition:.2s;
}

.search-group input:focus,
.search-group select:focus{
    outline:none;
    border-color:#1f4fa0;
    box-shadow:0 0 0 3px rgba(31,79,160,.12);
}

.search-actions{
    display:flex;
    gap:12px;
}

.search-actions button,
.clear-btn{
    padding:10px 20px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
    cursor:pointer;
    transition:.2s;
}

.search-actions button{
    background:#1f4fa0;
    color:#fff;
}

.search-actions button:hover{
    background:#173d7f;
}

.clear-btn{
    background:#777;
    color:#fff;
}

.clear-btn:hover{
    background:#555;
}

/* Table */

table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.06);
}

th{
    background:#1f4fa0;
    color:#fff;
    padding:15px;
    text-align:left;
    font-size:15px;
    font-weight:700;
}

td{
    padding:15px;
    border-bottom:1px solid #edf2fb;
    color:#444;
    font-size:14px;
}

tbody tr{
    transition:.2s;
}

tbody tr:hover{
    background:#f8f9fc;
}

tr:last-child td{
    border-bottom:none;
}

/* Review Button */

.button{
    display:inline-block;
    background:#1f4fa0;
    color:#fff;
    padding:8px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
    transition:.2s;
}

.button:hover{
    background:#173d7f;
}

/* Status */

.status-pending{
    display:inline-block;
    padding:6px 14px;
    background:#fff3cd;
    color:#b26a00;
    border-radius:20px;
    font-weight:bold;
}

.status-approved{
    display:inline-block;
    padding:6px 14px;
    background:#d4edda;
    color:#2e7d32;
    border-radius:20px;
    font-weight:bold;
}

.status-rejected{
    display:inline-block;
    padding:6px 14px;
    background:#f8d7da;
    color:#d32f2f;
    border-radius:20px;
    font-weight:bold;
}

.status-completed{
    display:inline-block;
    padding:6px 14px;
    background:#e8ddff;
    color:#6a1b9a;
    border-radius:20px;
    font-weight:bold;
}

.status-progress{
    display:inline-block;
    padding:6px 14px;
    background:#dbeafe;
    color:#0d6efd;
    border-radius:20px;
    font-weight:bold;
    font-size:14px;
}

/* Pagination */

.pagination{
    margin-top:25px;
    text-align:center;
}

.pagination a{
    display:inline-block;
    padding:8px 14px;
    margin:0 3px;
    background:#1f4fa0;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    transition:.2s;
    font-size:14px;
}

.pagination a:hover{
    background:#173d7f;
}

.pagination strong{
    display:inline-block;
    padding:8px 14px;
    margin:0 3px;
    background:#edf2fb;
    color:#1f4fa0;
    border-radius:8px;
    font-size:14px;
}

/* Empty */

.empty-message{
    text-align:center;
    padding:40px;
    color:#666;
    font-size:16px;
}

/* Back */

.back-link{
    display:inline-block;
    margin-top:25px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
    font-size:15px;
}

.back-link:hover{
    text-decoration:underline;
}

/* Language Switch */

.language-switch{
    position:fixed;
    top:20px;
    right:20px;
    z-index:100;
}

html[dir="rtl"] .language-switch{
    right:auto;
    left:20px;
}

.language-btn{
    display:inline-block;
    padding:8px 16px;
    background:#fff;
    color:#333;
    text-decoration:none;
    border:1px solid #ddd;
    border-radius:22px;
    font-size:14px;
    font-weight:bold;
    box-shadow:0 4px 10px rgba(0,0,0,.12);
    transition:.2s;
}

.language-btn:hover{
    background:#1f4fa0;
    color:#fff;
}

@media(max-width:900px){

    .container{
        padding:22px;
    }

    .search-row{
        flex-direction:column;
    }

    .search-group{
        width:100%;
    }

    .search-actions{
        width:100%;
    }

    .search-actions button,
    .clear-btn{
        flex:1;
        text-align:center;
    }

    table{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
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

<h1><?= $lang["pending_requests"] ?></h1>

<div class="search-box">

<form method="GET">

<div class="search-row">

<div class="search-group">

<label><?= $lang["tracking_number"] ?></label>

<input
type="text"
name="tracking"
placeholder="<?= $lang["tracking_number"] ?>"
value="<?= htmlspecialchars($tracking) ?>"
>

</div>

<div class="search-group">

<label><?= $lang["customer_name"] ?></label>

<input
type="text"
name="customer"
placeholder="<?= $lang["customer_name"] ?>"
value="<?= htmlspecialchars($customer) ?>"
>

</div>

<div class="search-group">

<label><?= $lang["service"] ?></label>

<select name="service">

<option value=""><?= $lang["all_services"] ?></option>

<?php foreach($services as $s){ ?>

<option
value="<?= $s["ServiceID"] ?>"
<?= ($service == $s["ServiceID"]) ? "selected" : "" ?>
>

<?= htmlspecialchars($s["ServiceName"]) ?>

</option>

<?php } ?>

</select>

</div>

<div class="search-actions">

<button type="submit">

<?= $lang["search"] ?>

</button>

<a
class="clear-btn"
href="requests.php"
>

<?= $lang["clear"] ?>

</a>

</div>

</div>

</form>

</div>

<?php if(count($requests)==0){ ?>

<div class="empty-message">

<?= $lang["no_matching_requests"] ?>

</div>

<?php } else { ?>

<table>

<thead>
<tr>

<th><?= $lang["tracking_number"] ?></th>

<th><?= $lang["customer_name"] ?></th>

<th><?= $lang["service"] ?></th>

<th><?= $lang["submission_date"] ?></th>

<th><?= $lang["status"] ?></th>

<th><?= $lang["action"] ?></th>

</tr>
</thead>

<tbody>

<?php foreach($requests as $request){ ?>

<tr>

<td><?= htmlspecialchars($request["TrackingNumber"]) ?></td>

<td><?= htmlspecialchars($request["CustomerName"]) ?></td>

<td><?= htmlspecialchars($request["ServiceName"]) ?></td>

<td><?= htmlspecialchars($request["SubmissionDate"]) ?></td>

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
href="review.php?id=<?= $request["RequestID"] ?>">

<?= $lang["review"] ?>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

<br>

<div class="pagination">

<?php if($page > 1){ ?>

<a

class="button"

href="?page=<?= $page-1 ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>">

<?= $lang["previous"] ?>

</a>

<?php } ?>

<?php

$startPage = max(1, $page - 1);
$endPage = min($totalPages, $page + 1);

if ($startPage > 1) {

?>

<a

class="button"

style="margin:0 2px;"

href="?page=1&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>">

1

</a>

<?php

if ($startPage > 2) {

echo "...";

}

}

for($i = $startPage; $i <= $endPage; $i++){

if($i == $page){

echo "<strong style='margin:0 8px;'>$i</strong>";

}else{

?>

<a

class="button"

style="margin:0 2px;"

href="?page=<?= $i ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>">

<?= $i ?>

</a>

<?php

}

}

if ($endPage < $totalPages) {

if ($endPage < $totalPages - 1) {

echo "...";

}

?>

<a

class="button"

style="margin:0 2px;"

href="?page=<?= $totalPages ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>">

<?= $totalPages ?>

</a>

<?php

}

?>

<?php if($page < $totalPages){ ?>

<a

class="button"

href="?page=<?= $page+1 ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>">

<?= $lang["next"] ?>

</a>

<?php } ?>

</div>

<a class="back-link" href="dashboard.php">

← <?= $lang["back_to_dashboard"] ?>

</a>

</div>

</body>

</html>