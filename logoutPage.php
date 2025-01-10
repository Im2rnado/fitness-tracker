<?php
include_once 'dbconnect.php';
$table1 = "users";
$valid_user = 0;
session_start();
?>
<?php
if (isset($_SESSION['valid_user']) && $_SESSION['valid_user'] == "1") {
    session_destroy();
    header("Location: loginPage.php");
}

?>