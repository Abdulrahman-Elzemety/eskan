<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Default language */

if (!isset($_SESSION["lang"])) {
    $_SESSION["lang"] = "en";
}

/* Load language file */

$lang = require __DIR__ . "/../lang/" . $_SESSION["lang"] . ".php";

/* Page direction */

$dir = ($_SESSION["lang"] == "ar") ? "rtl" : "ltr";

?>