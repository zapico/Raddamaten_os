<!-- <!DOCTYPE html> -->
<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
if (isset($_POST['submit']))
{
  require("db_conn.php");
  $namn = $_POST['namn'];
  $orgnr = $_POST['orgnr'];
  $adress = $_POST['adress'];
  $ort = $_POST['ort'];
  $email = $_POST['email'];
  $telefon = $_POST['telefon'];
  $username = $_POST['username'];
  $passwd = $_POST['passwd'];
  list($user, $domain) = explode('@', $email);
  if(strtoupper($domain) == 'KTH.SE') {
    $type = 2;
    $passwd_hash = password_hash($passwd, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user_list (namn, orgnr, adress, ort, email, telefon, username, passwd, usertype)
      VALUES ('$namn','$orgnr','$adress','$ort','$email','$telefon','$username','$passwd_hash','$type')";

    if(mysqli_query($conn, $sql)){
      header('Location: login.php');

    } else{
      if (mysqli_errno($conn) == 1062) {?>
        <div class="alert alert-danger">
            <strong>Fel!</strong> Email adressen används redan.
        </div>
        <?php
         // header('Location: registrera.php?alert=email');
      }
        //echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);

    }
  }
  else {
    ?>
    <div class="alert alert-danger">
        <strong>Fel!</strong> emailadressen måste vara en KTH address.
    </div>
    <?php
  }
}
else {
}
?>
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

      <div class="display-5 pt-3 pb-3">
        <strong>Registrera dig</strong>
      </div>
      <div>
        <form action="registrera.php" method="post" role="form">
          <fieldset class="form-group">
            <div class="form-group pt-3" id="company">
                  <input class="form-control mb-2" type="text" name="namn" id="namn" placeholder="Namn" required>
                  <input class="form-control mb-2" type="text" name="orgnr" id="orgnr" placeholder="Org.nr" style="display:none">
                  <input class="form-control mb-2" type="text" name="adress" id="adress" placeholder="Adress" style="display:none">
                  <input class="form-control mb-2" type="text" name="ort" id="ort" placeholder="Ort" style="display:none" >

                  <input class="form-control mb-2" type="email" name="email" id="email" placeholder="Mailadress" required>
                  <p id="kth_text"><small>Bara KTH adresser är tillåtna</small></p>
                  <!-- <input class="form-control mb-2" type="email" name="email" id="email" placeholder="Mailadress" required> -->
                  <input class="form-control" type="text" name="telefon" id="telefon" placeholder="Telefon (mobil)" style="display:none">
                  <input class="form-control mt-5 mb-2" type="text" name="username" id="username" placeholder="Användarnamn" required>
                  <input type="password" class="form-control" name="passwd" id="reg_passwd" placeholder="Password" required>
            </div>
          </fieldset>

          <div class="row">
            <div class="col">
              <a href=".">
                <button type="button" class="btn btn-danger">
                  Tillbaka
                </button>
              </a>
            </div>
            <div class="col">
              <button type="submit" name="submit" class="btn btn-primary">
                Registrera
              </button>
            </div>
        </form>
      </div>

    </div>
    <script>
      function showFields(type) {
        if (type == "privat") {
          // var x = document.getElementById("privat");
          // x.style.display = "block";
          document.getElementById("kth_text").style.display = "";
          // document.getElementById("orgnr").style.display = "none";
          // document.getElementById("orgnr").removeAttribute("required");
          document.getElementById("adress").style.display = "none";
          document.getElementById("adress").removeAttribute("required");
          // document.getElementById("ort").style.display = "none";
          // document.getElementById("ort").removeAttribute("required");
          document.getElementById("telefon").style.display = "none";
          document.getElementById("telefon").removeAttribute("required");
        }
        else if (type == "company") {
          document.getElementById("kth_text").style.display = "none";
          // document.getElementById("orgnr").style.display = "";
          // document.getElementById("orgnr").setAttribute("required","required");
          document.getElementById("adress").style.display = "";
          document.getElementById("adress").setAttribute("required","required");
          // document.getElementById("ort").style.display = "";
          // document.getElementById("ort").setAttribute("required","required");
          document.getElementById("telefon").style.display = "";
          document.getElementById("telefon").setAttribute("required","required");
        }
      }
    </script>

  </body>
</html>
