<?php

require("checkStuff.php");

checkSession();
unset($_SESSION['user_id']);
header("Location: .");
?>
