<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
// <!-- smtprelay1.telia.com -->
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
require("db_conn.php");

if(isset($_POST['submit'])){

  $sql = $conn->prepare("SELECT * FROM user_list WHERE email=?") ;
  //$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $sql->bind_param('s', $_POST['email']);
  $sql->execute();
  $result = $sql->get_result();
  $user = $result->fetch_object();
  if (mysqli_num_rows($result)==1){
    // echo $user->namn;
    // echo $user->username;
    $my_passwords = randomPassword(10,1,"lower_case,upper_case,numbers");
    $passwd_hash = password_hash($my_passwords[0], PASSWORD_DEFAULT);
    // echo $my_passwords[0];

    $sql2 = $conn->prepare("update user_list set passwd=? WHERE email=?") ;
    //$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql2->bind_param('ss', $passwd_hash, $_POST['email']);
    if ($sql2->execute()){

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
          $mail->addAddress($_POST['email']);     // Add a recipient
          // $mail->addAddress('sermed@goodminton.se');               // Name is optional

          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'Rädda maten på KTH';
          $mail->Body    = 'Tack '.$user->namn.' för att du räddar maten på KTH<br>Avändarnamn <b>'.$user->username.'</b> har nya lösenordet <b>'.$my_passwords[0].'</b>';
          // $mail->AltBody = 'This is the body in plain t0ext for non-HTML mail clients';

          $mail->send();
          echo '<div class="alert alert-success">
              Användaruppgifterna har skickats.
              </div>';
              header("Location: login.php?nyttpasswd");
      } catch (Exception $e) {
          echo '<div class="alert alert-danger">
              <strong>Fel!</strong> Mailet kunde inte skickas.
          </div>', $mail->ErrorInfo;
      }
    }
    else {
      echo '<div class="alert alert-danger">
          <strong>Fel!</strong> Kunde inte skapa nytt lösenord.
      </div>';
    }
  }
  else {
    echo '<div class="alert alert-danger">
        <strong>Fel!</strong> Mailadressen är inte registrerad.
    </div>';
  }
}

function randomPassword($length,$count, $characters) {

// $length - the length of the generated password
// $count - number of passwords to be generated
// $characters - types of characters to be used in the password

// define variables used within the function
    $symbols = array();
    $passwords = array();
    $used_symbols = '';
    $pass = '';

// an array of different character types
    $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
    $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $symbols["numbers"] = '1234567890';
    $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

    $characters = explode(",",$characters); // get characters types to be used for the passsword
    foreach ($characters as $key=>$value) {
        $used_symbols .= $symbols[$value]; // build a string with all characters
    }
    $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1

    for ($p = 0; $p < $count; $p++) {
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $symbols_length); // get a random character from the string with all characters
            $pass .= $used_symbols[$n]; // add the character to the password string
        }
        $passwords[] = $pass;
    }

    return $passwords; // return the generated password
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container  w-100">
      <form action="" method="post" role="form">

        <fieldset class="form-group">
          <div class="form-group pt-3">
            <label class="col">Fyll i mailadressen för att ställa om lösenordet</label>
            <input class="form-control  mb-2 col" type="email" name="email" id="email" placeholder="Mailadress" required>
          </div>
        </fieldset>
        <button type="submit" class="btn btn-primary col" name="submit">
          Skicka
        </button>
      </form>
    </div>
  </body>
</html>
