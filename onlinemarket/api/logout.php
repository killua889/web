<?php
session_start();
if (isset($_POST['logout']) && !empty($_POST['logout'])) {
    session_destroy();
    header("Location: ../clientside/index.html");
    exit();
}
?>
