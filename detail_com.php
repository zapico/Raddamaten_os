<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
require "db_conn.php";
require("checkStuff.php");

checkSession();
if (isset($_GET["id"])) {
    $sql2 = $conn->prepare("select * from (select  `user_list`.`namn` AS `namn`, `matlista_com`.`id` AS `id`, `matlista_com`.`namn_matratt` AS `namn_matratt`, `matlista_com`.`beskrivning` AS `beskrivning`, `matlista_com`.`datefrom` AS `datefrom`, `matlista_com`.`dateto` AS `dateto`, `matlista_com`.`hamtning` AS `hamtning`, `matlista_com`.`portioner` AS `portioner`, `matlista_com`.`pris` AS `pris`, `matlista_com`.`bildnamn` AS `bildnamn`, `matlista_com`.`user_id` AS `user_id`,date_format( `matlista_com`.`timestamp`,'%y-%m-%d %H:%i') AS `timestamp`,concat( `matlista_com`.`laktos`, `matlista_com`.`milk`, `matlista_com`.`egg`, `matlista_com`.`nuts`, `matlista_com`.`fish`, `matlista_com`.`meat`, `matlista_com`.`veg`) AS `ingr` from ( `user_list` join  `matlista_com`) where ( `user_list`.`id` =  `matlista_com`.`user_id`))a where id=?");
    $sql2->bind_param('i', $_GET["id"]);
    $sql2->execute();
    $result = $sql2->get_result();
    $row = mysqli_fetch_assoc($result);
    $namn = $row['namn_matratt'];
    $beskrivning = $row['beskrivning'];
    $pris = $row['pris'];
    $datefrom = $row['datefrom'];
    $dateto = $row['dateto'];
    $hamtning = $row['hamtning'];
    $bildnamn = $row['bildnamn'];
    $username = $row['namn'];
    $published = $row['timestamp'];
    $portioner = $row['portioner'];
    $picfilename = $bildnamn;
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    </head>

    <body>
        <div class="container w-100">
          <?php include 'menu.php';?>

            <div>
                <h4 class="">
                    <?php echo $namn ?>
                </h4>
                <h5>
                    <?php echo $pris."kr" ?>
                </h5>
                <h5>
                    <?php echo $username ?>
                </h5>
            </div>
            <div class="col mt-3 mx-0  rounded">
                <?php echo $beskrivning ?>
            </div>
            <div class="col mt-3 mx-0 row ">
                <div class="col-4  text-right">Publicerat</div>
                <div class="mx-0 px-1 col border"><?php echo $published ?></div>
                <div class="col-1 text-right"><i class="fas fa-calendar-alt"></i></div>
            </div>
            <div class="col pt-3">
            <img class="img-fluid rounded border col"
            <?php
            if ($temp_file || $bildnamn) {
                echo 'src="uploads/' . $picfilename . '"';
            } else {
            echo 'src="images/no-camera.png"';
            }?>
            alt="food_image ">
            </div>
            <div class="mt-3 pt-0 col "><?php echo $hamtning ?></div>

            <div class="row col px-0 mx-0 pt-3">
                <div class="col px-0"><button type="button" class="btn btn-danger" onclick="history.back()">Tillbaka</button></div>
                    <!-- <a href="deln_priv.php"><button type="button" class="border btn btn-danger col">Tillbaka </button></a> -->
                <div class="col px-0">
                    <form action="order_com.php" method="post" role="form">
                            <input type="hidden" value=<?php echo $_GET["id"];?> name="id" />
                            <label class="small" for="inlineFormCustomSelect">portioner: </label>
                            <select class="custom-select col-6 px-1" id="inlineFormCustomSelect" name="portion">
                                <option selected value="1">1</option>
                                <?php
                                for ($x=2; $x <= $portioner; $x++) {
                                    echo "<option value=".$x.">".$x."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- <div class="col px-0"> -->
                            <button type="submit" class="btn btn-success">
                                Beställ
                            </button>
                        <!-- </div> -->
                    </form>

            </div>

        </div>
    </body>

    </html>
