<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');
require "db_conn.php";

$sql = $conn->prepare("select `user_list`.`namn` AS `namn`, `matlista_priv`.`avdelning` AS `avdelning`, `matlista_priv`.`portioner` AS `portioner`, `user_list`.`email` AS `dealer_email`, `matlista_priv`.`id` AS `id`, `matlista_priv`.`namn_matratt` AS `namn_matratt`, `matlista_priv`.`beskrivning` AS `beskrivning`, `matlista_priv`.`datefrom` AS `datefrom`, `matlista_priv`.`dateto` AS `dateto`, `matlista_priv`.`hamtning` AS `hamtning`, `matlista_priv`.`bildnamn` AS `bildnamn`, `matlista_priv`.`user_id` AS `user_id`,date_format( `matlista_priv`.`timestamp`,'%y-%m-%d %H:%i') AS `timestamp` from ( `user_list` join  `matlista_priv`) where ( `user_list`.`id` =  `matlista_priv`.`user_id`) AND (`matlista_priv`.`portioner` > 0)");
// $sql->bind_param('s', $_SESSION['user_id']);
$sql->execute();
$result = $sql->get_result();
  // $list = $result->fetch_object();
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

    <div class="container">
      <?php include 'menu.php';?>
      <div class="border bg-light mt-3 pl-1 col-6 rounded">
        <span><strong>Sortera</strong></span><br>
        <span onclick="sortTable(1)">Plats</span><br>
        <span onclick="sortTable(2)">Senast</span>
      </div>
      <div class="mt-3 px-0">
        <div>
          <!-- <small>Klicka på rubriken för att sortera</small><br> -->
          <small>Klicka på maten för mer info/beställa</small>
        </div>
        <!-- <table class="  "> -->
        <!-- <div class="border"> -->
          <table id="myTable" class="small col-12 coltable table-striped table-sm table-bordered">
            <thead>
              <tr>
                <th scope="col" onclick="sortTable(0)">Maträtt</th>
                <th scope="col" onclick="sortTable(1)">Plats</th>
                <th scope="col" onclick="sortTable(2)">Tillgänglig från</th>
              </tr>
            </thead>
            <tbody>
              <?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // iconv("UTF-8", "ISO-8859-1", $row["namn_matratt"])
        echo "<tr class='clickable-row' data-href='detail_priv.php?id=" . $row["id"]
            . "'><td class='px-1'>" . $row["namn_matratt"] .
            "</td><td class='px-1'>" . $row["avdelning"] . "</td><td class='px-1'>".$row["datefrom"]."</td></tr>";
    }
} else {
    echo "0 results";
}
?>

            </tbody>
          </table>
        </div>
      <!-- </div> -->
    </div>
    <script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc";
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
jQuery(document).ready(function ($) {
  $(".clickable-row").click(function () {
    window.location = $(this).data("href");
  });
});
</script>
  </body>

  </html>
