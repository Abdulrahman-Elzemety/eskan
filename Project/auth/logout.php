<?php

session_start();

/* Keep selected language */

$lang = $_SESSION["lang"] ?? "en";

session_unset();

session_destroy();

/* Start a new session and restore language */

session_start();

$_SESSION["lang"] = $lang;

header("Location: ../index.php");
exit();

?>