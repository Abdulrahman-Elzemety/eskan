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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $dateOfBirth = $_POST["date_of_birth"];
    $today = new DateTime();
    $birthDate = new DateTime($dateOfBirth);
    $age = $today->diff($birthDate)->y;

    if ($age < 18) {

        die("<script>
            alert('" . $lang["must_be_18"] . "');
            window.history.back();
        </script>");

    }

    $maritalStatus = $_POST["marital_status"];
    if ($maritalStatus === "") {
    $maritalStatus = null;
    }
    $familyMembers = $_POST["family_members"];
    if ($familyMembers === "") {
    $familyMembers = null;
    }
    $dependents = $_POST["dependents"];
    if ($dependents === "") {
    $dependents = null;
    }

    $employmentStatus = $_POST["employment_status"];
    if ($employmentStatus === "") {
    $employmentStatus = null;
    }
    $employer = trim($_POST["employer"]);
    $jobTitle = trim($_POST["job_title"]);
    $yearsOfService = $_POST["years_of_service"];
    if ($yearsOfService === "") {
    $yearsOfService = null;
    }

    $monthlyIncome = $_POST["monthly_income"];
    if ($monthlyIncome === "") {
    $monthlyIncome = null;
    }
    $monthlyExpenses = $_POST["monthly_expenses"];
    if ($monthlyExpenses === "") {
    $monthlyExpenses = null;
    }
    $liabilities = $_POST["liabilities"];
    if ($liabilities === "") {
    $liabilities = null;
    }

    if (!preg_match("/^[A-Za-z ]+$/", $firstName)) {

        die($lang["first_name_letters"]);

    }

    if (!preg_match("/^[A-Za-z ]+$/", $lastName)) {

        die($lang["last_name_letters"]);

    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    die($lang["invalid_email"]);

    }

    $checkEmail = $pdo->prepare("

    SELECT PersonID

    FROM Person

    WHERE Email = ?

    AND PersonID != ?

    ");

    $checkEmail->execute([$email, $personID]);

    if ($checkEmail->fetch()) {

    die($lang["email_exists"]);

    }

    if ($employer != "" && !preg_match("/^[A-Za-z0-9 .,&'-]+$/", $employer)) {

    die($lang["employer_letters"]);

    }

    if ($jobTitle != "" && !preg_match("/^[A-Za-z ]+$/", $jobTitle)) {

    die($lang["job_title_letters"]);

    }

    if (!preg_match('/^\+971\d{9}$/', $phone)) {

        die($lang["phone_rules"]);

    }

    $validEmploymentStatus = [
    "Employed",
    "Unemployed",
    "Self-Employed",
    "Retired",
    "Student"
    ];

    if ($employmentStatus != "" && !in_array($employmentStatus, $validEmploymentStatus)) {

    die($lang["invalid_employment_status"]);

    }

    $validMaritalStatus = [
    "Single",
    "Married",
    "Divorced",
    "Widowed"
    ];

    if ($maritalStatus != "" && !in_array($maritalStatus, $validMaritalStatus)) {

    die($lang["invalid_marital_status"]);

    }

    if (
    (int)$familyMembers < 0 ||
    (int)$dependents < 0 ||
    (int)$yearsOfService < 0 ||
    (float)$monthlyIncome < 0 ||
    (float)$monthlyExpenses < 0 ||
    (float)$liabilities < 0
) {

    die($lang["negative_numbers"]);

} 


    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("

            UPDATE Person

            SET

                FirstName=?,
                LastName=?,
                Phone=?,
                Email=?,
                Address=?,
                DateOfBirth=?

            WHERE PersonID=?

        ");

        $stmt->execute([
            $firstName,
            $lastName,
            $phone,
            $email,
            $address,
            $dateOfBirth,
            $personID
        ]);

        $check = $pdo->prepare("

            SELECT CustomerFamilyID

            FROM CustomerFamilyInformation

            WHERE PersonID=?

        ");

        $check->execute([$personID]);

        if ($check->fetch()) {

            $stmt = $pdo->prepare("

                UPDATE CustomerFamilyInformation

                SET

                    MaritalStatus=?,
                    FamilyMembers=?,
                    Dependents=?

                WHERE PersonID=?

            ");

            $stmt->execute([
                $maritalStatus,
                $familyMembers,
                $dependents,
                $personID
            ]);

        } else {

            $stmt = $pdo->prepare("

                INSERT INTO CustomerFamilyInformation

                (
                    PersonID,
                    MaritalStatus,
                    FamilyMembers,
                    Dependents
                )

                VALUES (?,?,?,?)

            ");

            $stmt->execute([
                $personID,
                $maritalStatus,
                $familyMembers,
                $dependents
            ]);

        }

        $check = $pdo->prepare("

            SELECT CustomerEmploymentID

            FROM CustomerEmploymentInformation

            WHERE PersonID=?

        ");


        $check->execute([$personID]);

        if ($check->fetch()) {

    $stmt = $pdo->prepare("

        UPDATE CustomerEmploymentInformation

        SET

            Employer=?,
            JobTitle=?,
            YearsOfService=?,
            EmploymentStatus=?

        WHERE PersonID=?

    ");

    $stmt->execute([

        $employer,
        $jobTitle,
        $yearsOfService,
        $employmentStatus,
        $personID

    ]);

} else {

    $stmt = $pdo->prepare("

        INSERT INTO CustomerEmploymentInformation

        (

            PersonID,
            Employer,
            JobTitle,
            YearsOfService,
            EmploymentStatus

        )

        VALUES (?,?,?,?,?)

    ");

    $stmt->execute([

        $personID,
        $employer,
        $jobTitle,
        $yearsOfService,
        $employmentStatus

    ]);

}


    $check = $pdo->prepare("

    SELECT CustomerFinancialID

    FROM CustomerFinancialInformation

    WHERE PersonID=?

");

$check->execute([$personID]);

if ($check->fetch()) {

    $stmt = $pdo->prepare("

        UPDATE CustomerFinancialInformation

        SET

            MonthlyIncome=?,
            MonthlyExpenses=?,
            Liabilities=?

        WHERE PersonID=?

    ");

    $stmt->execute([

        $monthlyIncome,
        $monthlyExpenses,
        $liabilities,
        $personID

    ]);

} else {

    $stmt = $pdo->prepare("

        INSERT INTO CustomerFinancialInformation

        (

            PersonID,
            MonthlyIncome,
            MonthlyExpenses,
            Liabilities

        )

        VALUES (?,?,?,?)

    ");

    $stmt->execute([

        $personID,
        $monthlyIncome,
        $monthlyExpenses,
        $liabilities

    ]);

}



        $pdo->commit();

        header("Location: profile.php?success=1");
        
        exit();

    } catch (PDOException $e) {

        $pdo->rollBack();

        die($e->getMessage());

    }

}
?>



<!DOCTYPE html>
<html lang="<?= $_SESSION["lang"] ?>" dir="<?= $dir ?>">

<head>

<meta charset="UTF-8">

<title><?= $lang["edit_profile"] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    padding:20px 0;
    font-family:Arial,sans-serif;
}

.container{
    width:760px;
    margin:20px auto;
    background:#fff;
    border-radius:16px;
    padding:28px;
    box-shadow:0 10px 24px rgba(0,0,0,.15);
}

h1{
    text-align:center;
    color:#1f4fa0;
    font-size:28px;
    font-weight:700;
    margin-bottom:25px;
}

.section-title{
    grid-column:1 / -1;
    color:#1f4fa0;
    font-size:19px;
    font-weight:700;
    border-bottom:3px solid #edf2fb;
    padding-bottom:8px;
    margin:18px 0 8px;
}

.row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px 20px;
}

.field{
    display:flex;
    flex-direction:column;
}

.full{
    grid-column:1 / -1;
}

label{
    font-size:13px;
    font-weight:700;
    color:#1f4fa0;
    margin-bottom:5px;
}

input,
select{
    width:100%;
    padding:10px 12px;
    font-size:13px;
    border:1px solid #d9d9d9;
    border-radius:8px;
    background:#f8f9fc;
    color:#222;
    box-sizing:border-box;
    transition:.2s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#1f4fa0;
    background:#ffffff;
    box-shadow:0 0 0 3px rgba(31,79,160,.12);
}

button{
    width:100%;
    margin-top:16px;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#0d6efd;
    color:#fff;
    font-size:14px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    background:#0b5ed7;
}

.back-link{
    display:inline-block;
    margin-top:18px;
    color:#0d6efd;
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
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
    padding:8px 16px;
    background:#fff;
    color:#333;
    text-decoration:none;
    border-radius:22px;
    border:1px solid #ddd;
    box-shadow:0 4px 10px rgba(0,0,0,.12);
    font-size:13px;
    transition:.2s;
}

.language-btn:hover{
    background:#0d6efd;
    color:#fff;
}

.field.empty{
    visibility:hidden;
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

<h1><?= $lang["edit_profile"] ?></h1>

<?php if(isset($_GET["success"])): ?>
<p style="color:green;">
    <?= $lang["profile_updated"] ?>
</p>
<?php endif; ?>

<form method="POST">

<div class="row">

<h2 class="section-title"><?= $lang["personal_information"] ?></h2>

<div class="field">

<label><?= $lang["first_name"] ?></label>

<input
type="text"
name="first_name"
value="<?= htmlspecialchars($profile["FirstName"]) ?>"
required
oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')">

</div>

<div class="field">

<label><?= $lang["last_name"] ?></label>

<input
type="text"
name="last_name"
value="<?= htmlspecialchars($profile["LastName"]) ?>"
required
oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')">

</div>

<div class="field">

<label><?= $lang["email"] ?></label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($profile["Email"]) ?>"
required>

</div>

<div class="field">

<label><?= $lang["date_of_birth"] ?></label>

<input
type="date"
name="date_of_birth"
id="date_of_birth"
value="<?= htmlspecialchars($profile["DateOfBirth"]) ?>"
required>

</div>

<div class="field">

<label><?= $lang["phone"] ?></label>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($profile["Phone"]) ?>"
maxlength="13"
pattern="\+971\d{9}"
title="<?= $lang["phone_hint"] ?>"
required>

</div>

<div class="field full">

<label><?= $lang["address"] ?></label>

<input
type="text"
name="address"
value="<?= htmlspecialchars($profile["Address"]) ?>"
required>

</div>

<h2 class="section-title"><?= $lang["family_information"] ?></h2>

<div class="field">

<label><?= $lang["marital_status"] ?></label>

<select name="marital_status">

<option value=""><?= $lang["select"] ?></option>

<option value="Single"
<?= ($profile["MaritalStatus"]=="Single")?"selected":"" ?>>
<?= $lang["single"] ?>
</option>

<option value="Married"
<?= ($profile["MaritalStatus"]=="Married")?"selected":"" ?>>
<?= $lang["married"] ?>
</option>

<option value="Divorced"
<?= ($profile["MaritalStatus"]=="Divorced")?"selected":"" ?>>
<?= $lang["divorced"] ?>
</option>

<option value="Widowed"
<?= ($profile["MaritalStatus"]=="Widowed")?"selected":"" ?>>
<?= $lang["widowed"] ?>
</option>

</select>

</div>

<div class="field">

<label><?= $lang["family_members"] ?></label>

<input
type="number"
name="family_members"
min="0"
value="<?= htmlspecialchars($profile["FamilyMembers"]) ?>">

</div>

<div class="field">

<label><?= $lang["dependents"] ?></label>

<input
type="number"
name="dependents"
min="0"
value="<?= htmlspecialchars($profile["Dependents"]) ?>">

</div>


<h2 class="section-title"><?= $lang["employment_information"] ?></h2>

<div class="field">

<label><?= $lang["employment_status"] ?></label>

<select name="employment_status">

<option value=""><?= $lang["select"] ?></option>

<option value="Employed"
<?= ($profile["EmploymentStatus"]=="Employed")?"selected":"" ?>>
<?= $lang["employed"] ?>
</option>

<option value="Unemployed"
<?= ($profile["EmploymentStatus"]=="Unemployed")?"selected":"" ?>>
<?= $lang["unemployed"] ?>
</option>

<option value="Self-Employed"
<?= ($profile["EmploymentStatus"]=="Self-Employed")?"selected":"" ?>>
<?= $lang["self_employed"] ?>
</option>

<option value="Retired"
<?= ($profile["EmploymentStatus"]=="Retired")?"selected":"" ?>>
<?= $lang["retired"] ?>
</option>

<option value="Student"
<?= ($profile["EmploymentStatus"]=="Student")?"selected":"" ?>>
<?= $lang["student"] ?>
</option>

</select>

</div>

<div class="field">

<label><?= $lang["employer"] ?></label>

<input
type="text"
name="employer"
value="<?= htmlspecialchars($profile["Employer"] ?? "") ?>"
oninput="this.value=this.value.replace(/[^A-Za-z0-9 .,&'-]/g,'')">

</div>

<div class="field">

<label><?= $lang["job_title"] ?></label>

<input
type="text"
name="job_title"
value="<?= htmlspecialchars($profile["JobTitle"] ?? "") ?>"
oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')">

</div>

<div class="field">

<label><?= $lang["years_of_service"] ?></label>

<input
type="number"
name="years_of_service"
min="0"
value="<?= htmlspecialchars($profile["YearsOfService"] ?? "") ?>">

</div>


<h2 class="section-title"><?= $lang["financial_information"] ?></h2>

<div class="field">

<label><?= $lang["monthly_income"] ?></label>

<input
type="number"
name="monthly_income"
step="0.01"
min="0"
value="<?= htmlspecialchars($profile["MonthlyIncome"] ?? "") ?>">

</div>

<div class="field">

<label><?= $lang["monthly_expenses"] ?></label>

<input
type="number"
name="monthly_expenses"
step="0.01"
min="0"
value="<?= htmlspecialchars($profile["MonthlyExpenses"] ?? "") ?>">

</div>

<div class="field">

<label><?= $lang["liabilities"] ?></label>

<input
type="number"
name="liabilities"
step="0.01"
min="0"
value="<?= htmlspecialchars($profile["Liabilities"] ?? "") ?>">

</div>

<div class="field empty"></div>



<div class="full">

<button type="submit">

<?= $lang["save_changes"] ?>

</button>

</div>

</div>

<br><br>

</form>

<a class="back-link" href="profile.php">
← <?= $lang["back_to_profile"] ?>
</a>

</div>
<script>

const phone = document.querySelector('input[name="phone"]');

phone.addEventListener('input', function () {

    if (!this.value.startsWith('+971')) {

        this.value = '+971';

    }

    this.value = '+971' + this.value.slice(4).replace(/\D/g,'').slice(0,9);

});

phone.addEventListener('keydown', function(e){

    if(this.selectionStart<=4 &&
       (e.key==="Backspace" || e.key==="Delete")){

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