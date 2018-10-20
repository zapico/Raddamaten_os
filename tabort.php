<?php
$target_dir = "uploads/";

// error_reporting(E_ALL);
// ini_set('display_errors', '1');
require "db_conn.php";
require("checkStuff.php");

checkSession();
if (isset($_SESSION['user_id'])) {
    require "db_conn.php";
    $sql1 = $conn->prepare("SELECT * FROM matlista_priv WHERE id=? and user_id=?");
    $sql1->bind_param('ii', $_GET['id'], $_SESSION['user_id']);
    $sql1->execute();
    $result = $sql1->get_result();
    if ($result) {
        $user = $result->fetch_object();
        $filename = $user->bildnamn;
        $sql2 = $conn->prepare("DELETE FROM `matlista_priv` WHERE id=? and user_id=?");
        $sql2->bind_param('ii', $_GET['id'], $_SESSION['user_id']);
        $sql2->execute();
        if ($filename) {
            $target_file = $target_dir . $filename;
            unlink($target_file);
        }
    } else {
        echo "No result from database";
    }
    header("Location: add.php");

} else {
    // Redirect them to the login page
    header("Location: login.php");
}
