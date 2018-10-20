<?php
function checkSession () {
  if (session_status() == PHP_SESSION_NONE) {

    session_start();
    // ini_set('session.gc_maxlifetime', 3600);
    // session_set_cookie_params(3600);
  }
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
function loggedIn() {
  if (isset($_SESSION['user_id'])) {
    return TRUE;
  } else {
      header("Location: login.php");
  }
}
 ?>
