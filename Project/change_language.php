<?php

session_start();

if (isset($_GET["lang"])) {

    if ($_GET["lang"] == "en" || $_GET["lang"] == "ar") {

        $_SESSION["lang"] = $_GET["lang"];

    }

}

if (isset($_GET["return"]) && !empty($_GET["return"])) {

    header("Location: " . $_GET["return"]);

} else {

    header("Location: index.php");

}

exit();

?>


