<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
require "db_conn.php";
require("checkStuff.php");

checkSession();
if (isset($_SESSION['user_id'])) {
    $sql = $conn->prepare("SELECT * FROM `matlista_priv` where user_id=?");
    $sql->bind_param('s', $_SESSION['user_id']);
    $sql->execute();
    $result = $sql->get_result();
    // $list = $result->fetch_object();
} else {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="app.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container w-100">
      <?php include 'menu.php';?>
      <br/>
      <div class="row mx-0 pt-1">
        <a href="list_orders_priv.php" class="btn btn-outline-primary col px-1 "  role="button"><small>Mina Beställningar</small></a>
        <a href="lista.php" class="btn btn-outline-primary col px-1" role="button"><small>Min mat</small></a>
      </div>
      <div class="mt-3 col-12 px-0">
        <div class=""><small>Klicka på maten för att ändra</small></div>
        <table class="col coltable table-striped table-sm table-bordered  ">
          <thead>
            <tr>
              <th scope="col-6">Maträtt</th>
              <th scope="col-6">&nbsp&nbsp&nbspUpplagd&nbsp&nbsp&nbsp</th>
            </tr>
          </thead>
          <tbody>
            <?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // iconv("UTF-8", "ISO-8859-1", $row["namn_matratt"])
        echo "<tr class='clickable-row' data-href='add_priv.php?id=" . $row["id"] . "'><td class='col'><small>" . $row["namn_matratt"] . "</small></td><td class='col-6'><small>" . $row["datefrom"] . "</small></td></tr>";
    }
} else {
    echo "0 results";
}
?>
            <script>
              jQuery(document).ready(function($) {
                $(".clickable-row").click(function() {
                window.location = $(this).data("href");
                });
              });
            </script>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
