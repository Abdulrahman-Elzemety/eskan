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
$decision = $_GET["decision"] ?? "";

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

/* Load Review History */

$sql = "

SELECT

sr.RequestID,
sr.TrackingNumber,

CONCAT(p.FirstName,' ',p.LastName) AS CustomerName,

s.ServiceID,
s.ServiceName,

r.Decision,
r.ReviewDate

FROM ServiceRequest sr

JOIN Review r
ON sr.RequestID = r.RequestID

JOIN Customer c
ON sr.CustomerID = c.PersonID

JOIN Person p
ON c.PersonID = p.PersonID

JOIN Service s
ON sr.ServiceID = s.ServiceID

WHERE 1=1

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

if ($decision != "") {

    $sql .= "

    AND r.Decision = ?

    ";

    $params[] = $decision;

}

$sql .= "

ORDER BY r.ReviewDate DESC

LIMIT $offset, $recordsPerPage

";

$countSql = "

SELECT COUNT(*)

FROM ServiceRequest sr

JOIN Review r
ON sr.RequestID = r.RequestID

JOIN Customer c
ON sr.CustomerID = c.PersonID

JOIN Person p
ON c.PersonID = p.PersonID

JOIN Service s
ON sr.ServiceID = s.ServiceID

WHERE 1=1

";

$countParams = [];

if ($tracking != "") {
    $countSql .= " AND sr.TrackingNumber LIKE ? ";
    $countParams[] = "%$tracking%";
}

if ($customer != "") {
    $countSql .= " AND CONCAT(p.FirstName,' ',p.LastName) LIKE ? ";
    $countParams[] = "%$customer%";
}

if ($service != "") {
    $countSql .= " AND s.ServiceID = ? ";
    $countParams[] = $service;
}

if ($decision != "") {
    $countSql .= " AND r.Decision = ? ";
    $countParams[] = $decision;
}

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);

$totalRecords = $countStmt->fetchColumn();

$totalPages = max(1, ceil($totalRecords / $recordsPerPage));

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["review_history"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:10px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:1100px;
    max-width:95%;
    margin:10px auto;
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 22px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#1f4fa0;
    font-size:30px;
    margin:0 0 20px;
}

/* Search */

.search-box{
    background:#f8f9fc;
    border:1px solid #e5e5e5;
    border-radius:14px;
    padding:20px;
    margin-bottom:25px;
}

.search-row{
    display:flex;
    gap:18px;
    align-items:flex-end;
    flex-wrap:wrap;
}

.search-group{
    flex:1;
    min-width:200px;
}

.search-group label{
    display:block;
    margin-bottom:8px;
    color:#1f4fa0;
    font-weight:bold;
    font-size:14px;
}

.search-group input,
.search-group select{
    width:100%;
    padding:10px 12px;
    border:1px solid #d9d9d9;
    border-radius:10px;
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
    gap:10px;
}

.search-actions button,
.clear-btn{
    padding:10px 20px;
    border:none;
    border-radius:10px;
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
    border-radius:14px;
    box-shadow:0 5px 15px rgba(0,0,0,.06);
}

th{
    background:#1f4fa0;
    color:#fff;
    padding:16px;
    text-align:left;
    font-size:15px;
    font-weight:bold;
}

td{
    padding:16px;
    border-bottom:1px solid #edf2fb;
    font-size:14px;
    color:#444;
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

/* Buttons */

.button{
    display:inline-block;
    background:#1f4fa0;
    color:#fff;
    padding:8px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    transition:.2s;
}

.button:hover{
    background:#173d7f;
}

/* Decision */

.approved{
    color:#2e7d32;
    font-weight:bold;
}

.rejected{
    color:#d32f2f;
    font-weight:bold;
}

/* Empty */

.empty-message{
    text-align:center;
    padding:40px;
    color:#666;
    font-size:17px;
}

.pagination{
    margin-top:25px;
    text-align:center;
}

.pagination strong{
    display:inline-block;
    padding:8px 18px;
    margin:0 3px;
    background:#edf2fb;
    color:#1f4fa0;
    border-radius:8px;
    font-weight:bold;
}

/* Back */

.back-link{
    display:inline-block;
    margin-top:20px;
    color:#1f4fa0;
    text-decoration:none;
    font-weight:bold;
}

.back-link:hover{
    text-decoration:underline;
}

/* Language */

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

.status-approved{
    background:#d4edda;
    color:#155724;
    padding:5px 12px;
    border-radius:16px;
    font-weight:bold;
}

.status-rejected{
    background:#f8d7da;
    color:#721c24;
    padding:5px 12px;
    border-radius:16px;
    font-weight:bold;
}

/* Responsive */

@media(max-width:900px){

    .container{
        width:95%;
        padding:18px;
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
        font-size:26px;
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

<h1><?= $lang["review_history"] ?></h1>

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

<option value="">

<?= $lang["all_services"] ?>

</option>

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

<div class="search-group">

<label><?= $lang["decision"] ?></label>

<select name="decision">

<option value="">

<?= $lang["all_decisions"] ?>

</option>

<option

value="Approved"

<?= ($decision=="Approved") ? "selected" : "" ?>

>

<?= $lang["approved"] ?>

</option>

<option

value="Rejected"

<?= ($decision=="Rejected") ? "selected" : "" ?>

>

<?= $lang["rejected"] ?>

</option>

</select>

</div>

<div class="search-actions">

<button type="submit">

<?= $lang["search"] ?>

</button>

<a

href="history.php"

class="clear-btn"

>

<?= $lang["clear"] ?>

</a>

</div>

</div>

</form>

</div>

<?php if(count($requests)==0){ ?>

<div class="empty-message">

<?= $lang["no_completed_reviews"] ?>

</div>

<?php } else { ?>

<table>

<tr>

<th><?= $lang["tracking_number"] ?></th>

<th><?= $lang["customer_name"] ?></th>

<th><?= $lang["service"] ?></th>

<th><?= $lang["decision"] ?></th>

<th><?= $lang["review_date"] ?></th>

<th><?= $lang["action"] ?></th>

</tr>

<?php foreach($requests as $request){ ?>

<tr>

<td>

<?= htmlspecialchars($request["TrackingNumber"]) ?>

</td>

<td>

<?= htmlspecialchars($request["CustomerName"]) ?>

</td>

<td>

<?= htmlspecialchars($request["ServiceName"]) ?>

</td>

<td>

<?php

if($request["Decision"]=="Approved"){
    echo "<span class='status-approved'>{$lang["approved"]}</span>";
}else{
    echo "<span class='status-rejected'>{$lang["rejected"]}</span>";
}

?>

</td>

<td>

<?= htmlspecialchars($request["ReviewDate"]) ?>

</td>

<td>

<a class="button"

href="review.php?id=<?= $request["RequestID"] ?>">

<?= $lang["view"] ?>

</a>

</td>

</tr>

<?php } ?>

</table>

<?php } ?>

<br>

<div class="pagination">

<?php if($page > 1){ ?>

<a class="button"

href="?page=<?= $page-1 ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>&decision=<?= urlencode($decision) ?>">

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

href="?page=1&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>&decision=<?= urlencode($decision) ?>">

1

</a>

<?php

if ($startPage > 2) {

echo "...";

}

}

for($i = $startPage; $i <= $endPage; $i++){

if($i == $page){

echo "<strong>$i</strong>";

}else{

?>

<a

class="button"

href="?page=<?= $i ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>&decision=<?= urlencode($decision) ?>">

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

href="?page=<?= $totalPages ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>&decision=<?= urlencode($decision) ?>">
<?= $totalPages ?>

</a>

<?php

}

?>

<?php if($page < $totalPages){ ?>

<a class="button"

href="?page=<?= $page+1 ?>&tracking=<?= urlencode($tracking) ?>&customer=<?= urlencode($customer) ?>&service=<?= urlencode($service) ?>&decision=<?= urlencode($decision) ?>">

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