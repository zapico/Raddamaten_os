<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
require "db_conn.php";

require("checkStuff.php");

checkSession();
if (isset($_GET["id"])) {
    $sql2 = $conn->prepare("select * from (select  `user_list`.`namn` AS `namn`, `user_list`.`email` AS `dealer_email`, `matlista_priv`.`id` AS `id`, `matlista_priv`.`namn_matratt` AS `namn_matratt`, `matlista_priv`.`beskrivning` AS `beskrivning`, `matlista_priv`.`avdelning` AS `avdelning`, `matlista_priv`.`portioner` AS `portioner`, `matlista_priv`.`datefrom` AS `datefrom`, `matlista_priv`.`dateto` AS `dateto`, `matlista_priv`.`hamtning` AS `hamtning`, `matlista_priv`.`bildnamn` AS `bildnamn`, `matlista_priv`.`user_id` AS `user_id`,date_format( `matlista_priv`.`timestamp`,'%y-%m-%d %H:%i') AS `timestamp` from ( `user_list` join  `matlista_priv`) where ( `user_list`.`id` =  `matlista_priv`.`user_id`) ) a
where id=?" );
    $sql2->bind_param('i', $_GET["id"]);
    $sql2->execute();
    $result = $sql2->get_result();
    $row = mysqli_fetch_assoc($result);
    $namn = $row['namn_matratt'];
    $beskrivning = $row['beskrivning'];
    $avdelning = $row['avdelning'];
    $portioner = $row['portioner'];
    $datefrom = $row['datefrom'];
    $dateto = $row['dateto'];
    $hamtning = $row['hamtning'];
    $bildnamn = $row['bildnamn'];
    $username = $row['namn'];
    $published = $row['timestamp'];
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
          <?php include 'menu.php';?><br/>

            <div class="col mt-3 mx-0">
                <h4><?php echo $namn ?></h4>
                <p>Delad av <i><?php echo $username ?></i>
            </div>
            <div class="col mt-3 mx-0  rounded">
                <p><small>Beskrivning: </small><?php echo $beskrivning ?><p>
                <p><small>Plats: </small><b><?php echo $avdelning ?></b> <?php echo $hamtning ?><p
                <p><small>Portioner kvar: </small><?php echo $portioner ?><p>
                <p><small>Tillgängliga från: </small><?php echo $datefrom ?><p>
            </div>
            <div class="col pt-3">

            <?php
            if ($temp_file || $bildnamn) {
                echo '<img class="img-fluid rounded border col" src="uploads/' . $picfilename . '" alt="food_image ">';
            } else {
            echo '';
            }?>

            </div>

            <div class="row pt-3 mx-0  ">
                <button type="button" class="border btn btn-danger col-3 px-1" onclick="history.back()">Tillbaka </button>
                    <div class="col-6"></div>
                    <a href="order_priv.php?id=<?php echo $_GET['id'] ?>" class="border btn btn-success col-3 " role="button">Beställ </a>
            </div>
        </div>
    </body>

    </html>
