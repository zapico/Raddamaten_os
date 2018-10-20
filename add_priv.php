<?php
error_reporting(0);
ini_set('display_errors', '1');
require "db_conn.php";
require("checkStuff.php");

checkSession();

loggedIn();

if (isset($_POST["submit"])) {

  if ($_FILES["fileToUpload"]["name"]) {
    // Image handling
    $target_dir = "/var/www/Raddamaten/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $temp_file = $_FILES["fileToUpload"]["tmp_name"];
    $ext = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
    $picfilename = $_SESSION['user_id'] . "_" . time() . "_thump." . $ext;
    $sourceProperties = getimagesize($temp_file);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $imageResourceId = imagecreatefromjpeg($temp_file);
    $width = $sourceProperties[0];
    $height = $sourceProperties[1];
    $targetLayer = imageResize($imageResourceId, $width, $height);
    $exif = exif_read_data($temp_file);
    $ort = $exif['Orientation'];
    switch ($ort) {
        case 3:
            //rotate +90°
            $rotated_src = $targetLayer;
            break;
        case 6:
            //rotate -90° . Better a positive value: 270°
            $rotated_src = imagerotate($targetLayer, 270, 0);
            break;
        default;
            $rotated_src = $targetLayer;
    }

    imagejpeg($rotated_src, $target_dir . $picfilename);

    // Check if page request comes from a post submit
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file " . $target_file . " was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($temp_file, $target_file)) {
            // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo $_FILES['fileToUpload']['error'] . "   Sorry, there was an error uploading your file. " . $target_file . "temp file " . $_FILES["fileToUpload"]["tmp_name"];
        }
    }
  }
  else {
    // echo "Pic".$picfilename;
    $picfilename=$_POST['picture'];
  }
  $row_id = $_POST['row_id'];
  $namn = $_POST['namn_matratt'];
  $beskrivning = $_POST['beskrivning'];
  $portioner = $_POST['portioner'];
  $avdelning = $_POST['avdelning'];
  $datefrom = $_POST['datefrom'];
  $dateto = $_POST['dateto'];
  $hamtning = $_POST['hamtning'];
  $portioner = $_POST['portioner'];
  if ($row_id){
    $sql = $conn->prepare("UPDATE matlista_priv SET namn_matratt=? , beskrivning=?, avdelning=?, portioner=?, startportioner=?,`datefrom`=?, `dateto`=?, `hamtning`=?, `bildnamn`=?, `user_id`=? WHERE id=?");
    $sql->bind_param('sssiissssii', $namn, $beskrivning, $avdelning ,$portioner, $portioner, $datefrom, $dateto, $hamtning, $picfilename, $_SESSION['user_id'], $row_id);
  }
  else {
    $sql = $conn->prepare("INSERT INTO matlista_priv (namn_matratt, beskrivning, avdelning, portioner, startportioner, datefrom, dateto, hamtning, bildnamn, user_id) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $sql->bind_param('sssiissssi', $namn, $beskrivning, $avdelning ,$portioner, $portioner, $datefrom, $dateto, $hamtning, $picfilename, $_SESSION['user_id']);
  }
  $sql->execute();
  $last_id = $sql->insert_id;
  echo $sql->error;
  header('Location: /tack.php');
} elseif (isset($_GET["id"])) {
    $sql2 = $conn->prepare("SELECT * FROM `matlista_priv` where user_id=? and id=?");
    $sql2->bind_param('ii', $_SESSION['user_id'], $_GET["id"]);
    $sql2->execute();
    $result = $sql2->get_result();
    $row = mysqli_fetch_assoc($result);
    $namn = $row['namn_matratt'];
    $beskrivning = $row['beskrivning'];
    $datefrom = $row['datefrom'];
    $dateto = $row['dateto'];
    $hamtning = $row['hamtning'];
    $bildnamn = $row['bildnamn'];
    $avdelning = $row['avdelning'];
    $portioner = $row['portioner'];
    $picfilename = $bildnamn;
    $last_id = $_GET["id"];
}
else {
  $datefrom = date("Y-m-d");
}

function imageResize($imageResourceId, $width, $height)
{

    $maxWidth = 250;
    $maxHeight = 250;

    if ($width > $maxWidth || $height > $maxHeight) {
        if ($width > $height) {
            $targetHeight = floor(($height / $width) * $maxWidth);
            $targetWidth = $maxWidth;
        } else {
            $targetWidth = floor(($width / $height) * $maxHeight);
            $targetHeight = $maxHeight;
        }
    }

    $targetLayer = imagecreatetruecolor($targetWidth, $targetHeight);
    imagecopyresampled($targetLayer, $imageResourceId, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

    return $targetLayer;
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/css/tempusdominus-bootstrap-4.min.css"
    />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg"
      crossorigin="anonymous">
    <script>
    var input = document.getElementById("file-input");
    var infoArea = document.getElementById( 'file-upload-filename' );
    input.addEventListener( 'change', showFileName );
    function showFileName( event ) {
      // the change event gives us the input it occurred in
      var input = event.srcElement;
      // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
      var fileName = input.files[0].name;
      // use fileName however fits your app best, i.e. add it into a div
      infoArea.textContent = 'Du har laddat upp: ' + fileName;
    }
    </script>

  </head>

  <body>
    <div class="container  w-100">

      <?php include 'menu.php';?>


      <form action="add_priv.php" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" value=<?php echo $last_id;?> name="row_id" />
      <input type="hidden" value=<?php echo $picfilename;?> name="picture" />
        <div class="form-group pt-3">
          <input class="form-control mb-2" type="text" name="namn_matratt" id="namn" placeholder="Namn på maträtt" VALUE="<?php echo $namn ?>">
          <textarea class="form-control" aria-label="With textarea" placeholder="Beskrivning, ingredienser, råvaror, etc" name="beskrivning"><?php if ($beskrivning != null) {echo $beskrivning;}?></textarea>
          <label class="pt-3 small">Tillgängligt från</label>
          <div class="input-group date col" id="datetimepicker1" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="datefrom" VALUE="<?php echo $datefrom ?>">
            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
            </div>
          </div>
          <br/>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <label class="input-group-text" for="portioner">Portioner</label>
            </div>
              <select class="custom-select" id="portioner" name="portioner">
              <option selected>Antal...</option>
              <option value="1" <?php echo $portioner == "1" ? "selected" : "" ?> >1</option>
              <option value="2" <?php echo $portioner == "2" ? "selected" : "" ?> >2</option>
              <option value="3" <?php echo $portioner == "3" ? "selected" : "" ?> >3</option>
              <option value="4" <?php echo $portioner == "4" ? "selected" : "" ?> >4</option>
              <option value="5" <?php echo $portioner == "5" ? "selected" : "" ?> >5</option>
              <option value="6" <?php echo $portioner == "6" ? "selected" : "" ?> >6</option>
              <option value="7" <?php echo $portioner == "7" ? "selected" : "" ?> >7</option>
              <option value="8" <?php echo $portioner == "8" ? "selected" : "" ?> >8</option>
              <option value="9" <?php echo $portioner == "9" ? "selected" : "" ?> >9</option>
              <option value="10" <?php echo $portioner == "10" ? "selected" : "" ?> >10</option>
            </select>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <label class="input-group-text" for="avdelning">Avdelning</label>
            </div>
            <select class="custom-select" id="avdelning" name="avdelning">
              <option selected>Välj...</option>
              <option value="KTH SEED" <?php echo $avdelning == "KTH SEED" ? "selected" : "" ?>>KTH SEED</option>
              <option value="KTH MID" <?php echo $avdelning == "KTH MID" ? "selected" : "" ?>>KTH MID</option>
              <option value="KTHS" <?php echo $avdelning == "KTHS" ? "selected" : "" ?>>KTH Sustainability</option>
            </select>
          </div>
          <div class="mt-3">
            <textarea class="form-control" aria-label="With textarea" name="hamtning" placeholder="Beskriv var maten hämtas på KTH"><?php if ($beskrivning != null) {
    echo $hamtning;
}
?></textarea>
          </div>

          <div class=" mt-3">
            <label class="pt-3 small">Ladda upp en bild: </label>

              <input type="file" accept="image/*" capture="environment" id="file-input" name="fileToUpload"/>

              <br/>
              <!-- rotateimg180 style="height: 200px;-->
              <img class="img-fluid rounded border" id="picturefood" <?php
              if ($temp_file || $picfilename) {
                echo 'src="uploads/' . $picfilename . '"';
              } else {
                echo 'src="images/no-camera.png"';
              }?>
                alt="food_image ">
            </div>

          <script type="text/javascript">
            $(function () {
              $('#datetimepicker1').datetimepicker({
                format: 'YYYY-MM-DD'
              });

            });
            $(function () {
              $('#datetimepicker2').datetimepicker({
                format: 'YYYY-MM-DD'
              });

            });
          </script>
        </div>
        <div class="row col mx-0" align=center>
          <a href="tabort.php?id=<?php echo $last_id ?>" class="btn btn-danger col-4 px-0 " role="button">Ta bort</a>
        <!-- <div class="col"><button type="button" class="btn btn-danger" onclick="history.back()">Tillbaka</button></div> -->
          <div class="col-4">
          </div>
            <button type="submit" class="btn btn-primary px-0 col-4" name="submit">Publicera</button>
        </div>
      </form>
    </div>
  </body>
</html>
