<?php
// error_reporting(E_ALL & ~E_NOTICE ) ;
// ini_set('display_errors', '1');
// require("db_conn.php");
require("checkStuff.php");

checkSession();
// If session has a user_id then the user is logged in else redirect to login page
if ( isset( $_SESSION['user_id'] , $_SESSION['usertype']) ) {
  if ($_SESSION['usertype'] == 1) {
    header("Location: lista_com.php");
  }
  else {
    header("Location: lista_priv.php");
  }
} else {
  header("Location: login.php");
}
