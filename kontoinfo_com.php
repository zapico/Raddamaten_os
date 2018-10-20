<?php
require("checkStuff.php");

checkSession();

if ( isset( $_SESSION['user_id'] ) ) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
    require("db_conn.php");
    $sql = $conn->prepare("SELECT * FROM user_list WHERE id=?") ;
    $sql->bind_param('s', $_SESSION['user_id']);
    $sql->execute();
    $result = $sql->get_result();
    $user = $result->fetch_object();

} else {
    // Redirect them to the login page
    header("Location: login.php");
}

if ( ! empty( $_POST ) ) {
  if ( $_POST['username'] && $_POST['passwd'] ) {
    $namn = $_POST['namn'];
    $orgnr = $_POST['orgnr'];
    $adress = $_POST['adress'];
    $ort = $_POST['ort'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $passwd_hash = password_hash($passwd, PASSWORD_DEFAULT);
    $sql = $conn->prepare("UPDATE user_list SET namn=?,orgnr=?,adress=?,ort=?,email=?,telefon=?,username=?,passwd=?  WHERE id=?") ;
    $sql->bind_param('ssssssssi', $namn, $orgnr, $adress, $ort, $email, $telefon, $username, $passwd_hash, $_SESSION['user_id']);
    $sql->execute();
    $sql = $conn->prepare("SELECT * FROM user_list WHERE id=?") ;
    $sql->bind_param('s', $_SESSION['user_id']);
    if($sql->execute()){
      $result = $sql->get_result();
      $user = $result->fetch_object();

      echo '<div class="alert alert-success">
          Användaruppgifterna har uppdaterats.
          </div>';
    }
    else {
      echo '<div class="alert alert-danger">
          <strong>Fel!</strong> Kunde inte uppdatera.
      </div>';
    }
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="app.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container  w-100">

      <div class="row mx-0 pt-1">
        <a href="." class="btn btn-outline-primary col-2 " role="button"><i class="fa fa-home"></i><a>
        <a href="kontoinfo.php" class="btn btn-outline-primary col px-1 active" aria-pressed="true" role="button"><small>Profil</small></a>
        <a href="list_orders_com.php" class="btn btn-outline-primary col px-1 "  role="button"><small>Beställningar</small></a>
        <a href="lista.php" class="btn btn-outline-primary col px-1" role="button"><small>Min mat</small></a>
        <a href="add.php" class="btn btn-outline-primary col px-1" role="button"><small>Ny</small></a>
      </div>
      <form action="" method="post" role="form">
        <fieldset class="form-group">
          <div class="form-group pt-3">
                <input class="form-control mb-2" type="text" name="namn" id="namn" placeholder="Namn" VALUE="<?php echo $user->namn ?> ">
                <input class="form-control mb-2" type="text" name="orgnr" id="orgnr" placeholder="Org.nr (endast företag)" <?php if (isset($user->orgnr)) echo "VALUE="."'$user->orgnr'" ; ?> >
                <input class="form-control mb-2" type="text" name="adress" id="adress" placeholder="Adress" VALUE="<?php echo $user->adress ?> ">
                <input class="form-control mb-2" type="text" name="ort" id="ort" placeholder="Ort" VALUE="<?php echo $user->ort ?> ">
                <input class="form-control mb-2" type="email" name="email" id="email" placeholder="Mailadress" VALUE="<?php echo $user->email ?> ">
                <input class="form-control" type="text" name="telefon" id="telefon" placeholder="Telefon (mobil)" VALUE="<?php echo $user->telefon ?> ">
                <input class="form-control mt-5 mb-2" type="text" name="username" id="username" placeholder="Användarnamn" VALUE="<?php echo $user->username ?> ">
                <input type="password" class="form-control" name="passwd" id="reg_passwd" placeholder="Password">
          </div>
        </fieldset>
        <div class="row mx-0">
          <a class="btn btn-danger" href="logout.php" role="button">Logga ut</a>
          <div class="col-2">

          </div>
          <button type="submit" class="btn col-4 btn-success">
            Uppdatera
          </button>
        </div>
      </form>
    </div>
  </body>
</html>
