<?php
// error_reporting(E_ALL & ~E_NOTICE);
// error_reporting(-1);
// ini_set('display_errors', '1');
// set_error_handler("var_dump");
require "db_conn.php";

require("checkStuff.php");

checkSession();
// If session has a user_id then the user is logged in else redirect to login page
loggedIn();

if (isset($_POST["id"])) {

    $sql2 = $conn->prepare("select * from (select  `user_list`.`namn` AS `namn`, `matlista_com`.`id` AS `id`, `matlista_com`.`namn_matratt` AS `namn_matratt`, `matlista_com`.`beskrivning` AS `beskrivning`, `matlista_com`.`datefrom` AS `datefrom`, `matlista_com`.`dateto` AS `dateto`, `matlista_com`.`hamtning` AS `hamtning`, `matlista_com`.`portioner` AS `portioner`, `matlista_com`.`pris` AS `pris`, `matlista_com`.`bildnamn` AS `bildnamn`, `matlista_com`.`user_id` AS `user_id`,date_format( `matlista_com`.`timestamp`,'%y-%m-%d %H:%i') AS `timestamp`,concat( `matlista_com`.`laktos`, `matlista_com`.`milk`, `matlista_com`.`egg`, `matlista_com`.`nuts`, `matlista_com`.`fish`, `matlista_com`.`meat`, `matlista_com`.`veg`) AS `ingr` from ( `user_list` join  `matlista_com`) where ( `user_list`.`id` =  `matlista_com`.`user_id`))a where id=?");
    $sql2->bind_param('i', $_POST["id"]);
    $sql2->execute();
    $result = $sql2->get_result();
    $row = mysqli_fetch_assoc($result);
    // console_log( $_POST["id"]);
    $namn = $row['namn_matratt'];
    $beskrivning = $row['beskrivning'];
    $datefrom = $row['datefrom'];
    $dateto = $row['dateto'];
    $hamtning = $row['hamtning'];
    $bildnamn = $row['bildnamn'];
    $username = $row['namn'];
    $published = $row['timestamp'];
    $picfilename = $bildnamn;
    $to = "web-102aw@mail-tester.com";
    $subject = "Beställning ".$namn;
    $customer_msg = "Du har beställt ".$namn."\nEtt mail har skickats till delaren, du kan hämta din beställning";
    $headers = "From: " . "\r\n" . "CC: ";
    $sql = $conn->prepare("INSERT INTO `orders_com`(`user_id`, `mat_id`, portioner) VALUES (?,?,?)");
    $sql->bind_param('iii', $_SESSION['user_id'],$_POST["id"], $_POST["portion"] );
    if($sql->execute()){
        ?><div class="alert alert-success">
            Du har gjort en beställning.
            </div>
            <?php
          // header('Location: registrera.php?alert=success');

      } else{
        if (mysqli_errno($conn) == 1062) {?>
          <div class="alert alert-danger">
              Du har redan beställt denna mat.
          </div>
          <?php
           // header('Location: registrera.php?alert=email');
        }
          //echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);

      }

} else {

}
 ?>
 <!DOCTYPE html>
 <html>

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta http-equiv="x-ua-compatible" content="ie=edge">
     <title></title>
     <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"> -->
     <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
     <link rel="stylesheet" href="app.css">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
     <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> -->
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

 </head>

   <body>
   <div class="container w-100">
    <a href="." class="btn btn-outline-primary col-2" role="button"><i class="fa fa-home"></i><a>
   <?php
//    mail($to,$subject,$customer_msg,$headers)
   if(1==1) {
        ?>
       <div class="border bg-light mt-3 pl-1 rounded">
           <h5>
             Du har beställt <?php echo $namn ?> i <?php echo $username ?>
           </h5>
           <h5>
             Maten är tillgänglig idag <br>Betalning sker vid hämtning
          </h5>
       </div>
       <div class="col mt-3 mx-0 border rounded">
           Ett email har skickats till båda dig och säljaren
       </div>
       <?php
   }
   else {
       ?>
    <div class="col mt-3 mx-0 border rounded">
        Mailet kunde inte skickas
    </div>
<?php
   }
   ?>
   <button type="button" class="border btn btn-danger col-3 mt-5" onclick="history.back()">Tillbaka </button>
     </div>
   </body>
 </html>
