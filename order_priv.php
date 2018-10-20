<?php
error_reporting(1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
require "db_conn.php";

require("checkStuff.php");

checkSession();
// If session has a user_id then the user is logged in else redirect to login page
if (isset($_SESSION['user_id'])) {
    $sql = $conn->prepare("SELECT * FROM user_list WHERE id=?");
    $sql->bind_param('i', $_SESSION['user_id']);
    $sql->execute();
    $result = $sql->get_result();
    $user = $result->fetch_object();
    $best_namn = $user->namn;
    $best_email = $user->email;
} else {
    header('Location: login.php?id='.$_GET["id"]);
}
if (isset($_GET["id"])) {
    $sql2 = $conn->prepare("select * from (select  `user_list`.`namn` AS `namn`, `user_list`.`email` AS `dealer_email`, `matlista_priv`.`id` AS `id`, `matlista_priv`.`namn_matratt` AS `namn_matratt`, `matlista_priv`.`beskrivning` AS `beskrivning`, `matlista_priv`.`avdelning` AS `avdelning`, `matlista_priv`.`portioner` AS `portioner`, `matlista_priv`.`datefrom` AS `datefrom`, `matlista_priv`.`dateto` AS `dateto`, `matlista_priv`.`hamtning` AS `hamtning`, `matlista_priv`.`bildnamn` AS `bildnamn`, `matlista_priv`.`user_id` AS `user_id`,date_format( `matlista_priv`.`timestamp`,'%y-%m-%d %H:%i') AS `timestamp` from ( `user_list` join  `matlista_priv`) where ( `user_list`.`id` =  `matlista_priv`.`user_id`) ) a where id=?" );
    $sql2->bind_param('i', $_GET["id"]);
    $sql2->execute();
    $result = $sql2->get_result();
    $row = mysqli_fetch_assoc($result);
    $namn_matratt = $row['namn_matratt'];
    $username = $row['namn'];
    $dealer_email = $row['dealer_email'];
    $hamtning = $row['hamtning'];
    $avdelning = $row['avdelning'];
    $portioner = $row['portioner'];
    $dateto = $row['dateto'];
    $beskrivning = $row['beskrivning'];

    $to = $dealer_email;
    $subject = "Beställning av ".$namn_matratt;
    $dealer_msg = $best_namn." kommer att hämta <b>".$namn_matratt."</b>. Hen kan kontaktas på: ".$best_email;

    $sql = $conn->prepare("INSERT INTO `orders_priv`(`user_id`, `mat_id`) VALUES (?,?)");
    $sql->bind_param('ii', $_SESSION['user_id'],$_GET["id"]);
    if($sql->execute()){

        //Update portions
        $portionerupd = $portioner - 1;
        $sql3 = $conn->prepare("UPDATE matlista_priv SET portioner=?  WHERE id=?");
        $sql3->bind_param('ii', $portionerupd, $_GET["id"]);
        $sql3->execute();


        ?><div class="alert alert-success">
            Du har gjort en beställning.
            </div>
            <?php
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            try {
                //Server settings
                // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'mail.name.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'info@raddamaten.com';                 // SMTP username
                $mail->Password = 'KTHSommar18';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;
                $mail->CharSet = 'UTF-8';                                // TCP port to connect to

                //Recipients
                $mail->setFrom('info@raddamaten.com', 'Rädda maten på KTH');
                $mail->addAddress($dealer_email);     // Add a recipient

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $dealer_msg;
                // $mail->AltBody = 'This is the body in plain t0ext for non-HTML mail clients';

                $mail->send();

            } catch (Exception $e) {
                echo '<div class="alert alert-danger">
                    <strong>Fel!</strong> Mailet kunde inte skickas.
                </div>', $mail->ErrorInfo;
            }
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
   <div class="container">
             <?php include 'menu.php';?>
   <?php
//    mail($to,$subject,$customer_msg,$headers)
   if(1==1) {
        ?>
       <div class="container">
           <h4>
             Nu har du bokat:
               <?php echo $namn_matratt ?>
           </h4>
           <h5>Du kan hämta den på: <br>
                <?php echo $avdelning ?> <?php echo $hamtning ?>
           </h5>
           <h5>
           Tillgänglig från: <?php echo $datefrom ?>
           </h5>
           <br>Extra information om maträtten: <br>
           <?php echo $beskrivning ?>

           <p>Ett email har skickats till delaren  <?php echo $username?>. Du kan kontakta delaren via <?php echo $dealer_email ?></p>
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
