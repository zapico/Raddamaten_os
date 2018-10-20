<?php
  require("db_conn.php");
  require("checkStuff.php");

  checkSession();

  if (isset($_GET['nyttpasswd'])) {
    echo '<div class="alert alert-success">
        Användaruppgifterna har skickats.
        </div>';
  }
  if ( ! empty( $_POST ) ) {
    if ( $_POST['username'] && $_POST['passwd'] ) {
      $username = $conn->real_escape_string($_POST['username']);
      $passwd = $conn->real_escape_string($_POST['passwd']);

        $sql = $conn->prepare("SELECT * FROM user_list WHERE username=?") ;
        //$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $sql->bind_param('s', $username);
        $sql->execute();
        $result = $sql->get_result();
        $user = $result->fetch_object();

    	// Verify user password and set $_SESSION
    	if ( password_verify( $passwd, $user->passwd ) ) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['usertype'] = $user->usertype;
        if (isset($_GET['id'])) {
          header('Location: order_priv.php?id='.$_GET["id"]);
        }
        else {
          header('Location: index.php');
        }
    	}
      else {
        ?>
        <div class="alert alert-danger">
          Användaren eller lösenordet är fel.
        </div>
        <?php
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container  w-100">
      <form action="" method="post" role="form">

        <fieldset class="form-group">
          <div class="form-group pt-3">
            <div class="display-5 pt-3 pb-3">
              <strong>Logga in i Rädda maten på KTH</strong>
            </div>
            <input class="form-control mt-5 mb-2" type="text" name="username" id="username" placeholder="Användarnamn" required>
            <input type="password" class="form-control" name="passwd" id="reg_passwd" placeholder="Password">
          </div>
        </fieldset>
        <a href="forgot_email.php" class="col small text-info">Glömt användarnamn eller lösenordet? klicka här! </a>
        <div class="col row pt-3">
          <?php
          if (isset($_GET['id'])) {
            echo '<a href="registrera.php?id='.$_GET['id'].'" class="btn btn-success col" role="button">Registrera</a>';
          }
          else {
            echo '<a href="registrera.php" class="btn btn-success col" role="button">Registrera</a>';
          }
          ?>
          <span class="col-3"></span>
          <button type="submit" class="btn btn-primary col">
            Login
          </button>

        </div>
      </form>
    </div>
  </body>
</html>
