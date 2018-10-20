<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
require "db_conn.php";
require("checkStuff.php");

checkSession();

loggedIn();
// echo $_POST['namn_matratt'];
// echo "test<br>";
// echo $_FILES["fileToUpload"]["name"];


if (isset($_POST["submit"])) {

  if ($_FILES["fileToUpload"]["name"]) {
    // echo "in submit";
    // Image handling
    $target_dir = "uploads/";
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
  $laktos = $_POST['laktos'];
  $milk = $_POST['milk'];
  $egg = $_POST['egg'];
  $nuts = $_POST['nuts'];
  $fish = $_POST['fish'];
  $meat = $_POST['meat'];
  $veg = $_POST['veg'];
  $datefrom = $_POST['datefrom'];
  $dateto = $_POST['dateto'];
  $hamtning = $_POST['hamtning'];
  $portion = $_POST['portion'];
  if ($row_id){
    $sql = $conn->prepare("UPDATE matlista_com SET namn_matratt=? , beskrivning=?, laktos=?, `milk`=?, `egg`=?, `nuts`=?, `fish`=?, `meat`=?, `veg`=?, `datefrom`=?, `dateto`=?, `hamtning`=?, `portioner`=?, `bildnamn`=?, `user_id`=? WHERE id=?");
    $sql->bind_param('ssssssssssssisii', $namn, $beskrivning, $laktos, $milk, $egg, $nuts, $fish, $meat, $veg, $datefrom, $dateto, $hamtning, $portion, $picfilename, $_SESSION['user_id'], $row_id);

  }
  else {
    $sql = $conn->prepare("INSERT INTO matlista_com (namn_matratt, beskrivning, laktos, `milk`, `egg`, `nuts`, `fish`, `meat`, `veg`, `datefrom`, `dateto`, `hamtning`, `portioner`, `bildnamn`, `user_id`)VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $sql->bind_param('ssssssssssssisi', $namn, $beskrivning, $laktos, $milk, $egg, $nuts, $fish, $meat, $veg, $datefrom, $dateto, $hamtning, $portion, $picfilename, $_SESSION['user_id']);

  }
  $sql->execute();
  $last_id = $sql->insert_id;
  echo $sql->error;

} elseif (isset($_GET["id"])) {
    // echo "in else submit";
    $sql2 = $conn->prepare("SELECT * FROM `matlista_com` where user_id=? and id=?");
    $sql2->bind_param('ii', $_SESSION['user_id'], $_GET["id"]);
    $sql2->execute();
    $result = $sql2->get_result();
    $row = mysqli_fetch_assoc($result);
    $namn = $row['namn_matratt'];
    $beskrivning = $row['beskrivning'];
    $laktos = $row['laktos'];
    $milk = $row['milk'];
    $egg = $row['egg'];
    $nuts = $row['nuts'];
    $fish = $row['fish'];
    $meat = $row['meat'];
    $veg = $row['veg'];
    $datefrom = $row['datefrom'];
    $dateto = $row['dateto'];
    $hamtning = $row['hamtning'];
    $bildnamn = $row['bildnamn'];
    $portion = $row['portioner'];
    $pris = $row['pris'];
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
  </head>

  <body>
    <div class="container  w-100">
       <div class="row mx-0 pt-1">
        <a href="." class="btn btn-outline-primary col-2 " role="button"><i class="fa fa-home"></i><a>
        <a href="kontoinfo.php" class="btn btn-outline-primary col px-1 "  role="button"><small>Profil</small></a>
        <a href="list_orders_com.php" class="btn btn-outline-primary col px-1 "  role="button"><small>Beställningar</small></a>
        <a href="lista.php" class="btn btn-outline-primary col px-1" role="button"><small>Min mat</small></a>
        <a href="add.php" class="btn btn-outline-primary col px-1 active" aria-pressed="true" role="button"><small>Ny</small></a>
      </div>

      <form action="add_com.php" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" value=<?php echo $last_id;?> name="row_id" />
      <input type="hidden" value=<?php echo $picfilename;?> name="picture" />
        <div class="form-group pt-3">
          <input class="form-control mb-2" type="text" name="namn_matratt" id="namn" placeholder="Namn på maträtt" VALUE="<?php echo $namn ?>">

          <textarea class="form-control" aria-label="With textarea" placeholder="Beskrivning, ingredienser, råvaror, etc" name="beskrivning"><?php if ($beskrivning != null) {echo $beskrivning;}?></textarea>

          <div class="container ">
            <div class="row">
              <div class="col-5 small">
                INNEHÅLLER
                <br> (ALLERGIER)
                <br>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="laktos" name="laktos" <?php if ($laktos == "laktos") {
    echo "checked";
}
?> > Laktos</label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="milk" name="milk" <?php if ($milk == "milk") {
    echo "checked";
}
?>> Mjölk</label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="egg" name="egg" <?php if ($egg == "egg") {
    echo "checked";
}
?>> Ägg</label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="nuts" name="nuts" <?php if ($nuts == "nuts") {
    echo "checked";
}
?>> Nötter</label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="fish" name="fish" <?php if ($fish == "fish") {
    echo "checked";
}
?>> Fisk</label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="meat" name="meat" <?php if ($meat == "meat") {
    echo "checked";
}
?>> Kött</label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="veg" name="veg" <?php if ($veg == "veg") {
    echo "checked";
}
?>> Veg</label>
                </div>
              </div>
              <div class="col-7">
                <label class="small" for="inlineFormCustomSelect">Antal portioner: </label>
                <select class="custom-select col-7" id="inlineFormCustomSelect" name="portion">
                  <option <?php if ($portion) {
                      echo "selected value=".$portion.">".$portion."</option>"; }
                      else {
                        echo "selected value=1>1</option>";
                      }
                        for ($x=1; $x <= 60; $x++) {
                            echo "<option value=".$x.">".$x."</option>";
                        }
                        ?>
                </select>
                <label class="small col px-0 mt-2" for="inlineFormCustomSelect">Pris: </label>
                <input class="form-control col-7 px-1" type="text" name="price" id="pris" placeholder="Pris " VALUE="<?php echo $pris ?>">
                <label class="pt-2 small">Tillgängligt från</label>
                <div class="input-group date col px-0" id="datetimepicker1" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input px-1" data-target="#datetimepicker1" name="datefrom" VALUE="<?php echo $datefrom ?>">
                  <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text">
                      <i class="fa fa-calendar-alt"></i>
                    </div>
                  </div>
                </div>
                <label class="pt-2 small">Tillgängligt till</label>
                <div class="input-group date col px-0" id="datetimepicker2" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input px-1" data-target="#datetimepicker2" name="dateto" VALUE="<?php echo $dateto ?>">
                  <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                    <div class="input-group-text">
                      <i class="fa fa-calendar-alt"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3">
            <textarea class="form-control" aria-label="With textarea" name="hamtning" placeholder="Beskriv var och när maten hämtas"><?php if ($beskrivning != null) {echo $hamtning;
}?></textarea>
          </div>
          <div class="row mt-2">
            <div class="col-7">
              <!-- rotateimg180 style="height: 200px;-->
              <img class="img-fluid rounded border" <?php if ($temp_file || $picfilename ) {echo 'src="uploads/' . $picfilename . '"';} else {echo 'src="images/no-camera.png"';}?> alt="food_image ">
            </div>

            <div class="col-5 ">
              <label class="fileContainer col ">
                &nbsp &nbsp &nbsp Ta foto
                <input type="file" accept="image/*" capture="environment" id="file-input" name="fileToUpload">
              </label>
              <!-- <label class="fileContainer col mt-1">
                &nbsp Mina bilder
                <input type="file" accept="image/*" id="file-input" name="fileToUpload2">
              </label> -->
              <!-- <a href="tabort_com.php?id=<?php echo $last_id ?>" class="btn btn-outline-primary mt-1 col" role="button">Ta bort</a> -->
            </div>
          </div>
          <!-- <script>
            const fileInput = document.getElementById('file-input');

            fileInput.addEventListener('change', (e) => doSomethingWithFiles(e.target.files));
          </script> -->
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
          <a href="tabort_com.php?id=<?php echo $last_id ?>" class="btn btn-danger col-4 px-0 " role="button">Ta bort</a>
        <!-- <div class="col"><button type="button" class="btn btn-danger" onclick="history.back()">Tillbaka</button></div> -->
          <div class="col-4">
          </div>
            <button type="submit" class="btn btn-primary px-0 col-4" name="submit">Publicera</button>
        </div>
      </form>
    </div>
  </body>

  </html>
