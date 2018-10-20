<?php
// error_reporting(E_ALL & ~E_NOTICE);
// ini_set('display_errors', '1');
require "db_conn.php";
require("checkStuff.php");

checkSession();

if (isset($_GET["id"],$_GET["user_id"])) {
    $sql = $conn->prepare("select * from (select  `orders_com`.`user_id` AS `user_id`, `orders_com`.`mat_id` AS `mat_id`, `orders_com`.`timestamp` AS `timestamp`, `matlista_com`.`namn_matratt` AS `namn_matratt`, `matlista_com`.`user_id` AS `rest_id`, `orders_com`.`portioner` AS `portioner` from ( `orders_com` left join  `matlista_com` on(( `orders_com`.`mat_id` =  `matlista_com`.`id`))))a where user_id=? and mat_id=?");
    $sql->bind_param('ii', $_GET["user_id"],$_GET["id"]);
    $sql->execute();
    $result = $sql->get_result();
    if($result){
        $row = mysqli_fetch_assoc($result);
        $ordered = $row['timestamp'];
        $best_portioner = $row['portioner'];
    }
    else {
        echo "no results returned";
    }

    $sql2 = $conn->prepare("select * from (select  `user_list`.`namn` AS `namn`, `matlista_com`.`id` AS `id`, `matlista_com`.`namn_matratt` AS `namn_matratt`, `matlista_com`.`beskrivning` AS `beskrivning`, `matlista_com`.`datefrom` AS `datefrom`, `matlista_com`.`dateto` AS `dateto`, `matlista_com`.`hamtning` AS `hamtning`, `matlista_com`.`portioner` AS `portioner`, `matlista_com`.`pris` AS `pris`, `matlista_com`.`bildnamn` AS `bildnamn`, `matlista_com`.`user_id` AS `user_id`,date_format( `matlista_com`.`timestamp`,'%y-%m-%d %H:%i') AS `timestamp`,concat( `matlista_com`.`laktos`, `matlista_com`.`milk`, `matlista_com`.`egg`, `matlista_com`.`nuts`, `matlista_com`.`fish`, `matlista_com`.`meat`, `matlista_com`.`veg`) AS `ingr` from ( `user_list` join  `matlista_com`) where ( `user_list`.`id` =  `matlista_com`.`user_id`))a where id=?");
    $sql2->bind_param('i', $_GET["id"]);
    $sql2->execute();
    $result2 = $sql2->get_result();
    $row2 = mysqli_fetch_assoc($result2);
    $namn = $row2['namn_matratt'];
    $beskrivning = $row2['beskrivning'];
    $pris = $row2['pris'];
    $datefrom = $row2['datefrom'];
    $dateto = $row2['dateto'];
    $hamtning = $row2['hamtning'];
    $bildnamn = $row2['bildnamn'];
    $username = $row2['namn'];
    $published = $row2['timestamp'];
    $portioner = $row2['portioner'];
    $picfilename = $bildnamn;

    $sql3 = $conn->prepare("SELECT * FROM `user_list` where id=?");
    $sql3->bind_param('i', $_GET["user_id"]);
    $sql3->execute();
    $result3 = $sql3->get_result();
    if($result3){
        $row3 = mysqli_fetch_assoc($result3);
        $best_namn = $row3['namn'];

    }
    else {
        echo "no results returned";
    }
    mysqli_close($conn);
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

            <div class="border bg-light mt-3 pl-1 rounded">
                <h4 class="">
                    <?php echo $namn." ".$pris."kr" ?>
                </h4>
                <h5>

                    <?php
                        if ($best_portioner > 1) {
                            echo $best_portioner." portioner <br>beställd av ".$best_namn ;
                        }
                        else {
                            echo $best_portioner." portion <br>beställd av ".$best_namn;
                        }
                    ?>
                </h5>
                <h5>
                    <?php echo $ordered ?>
                </h5>
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

            <div class="col px-0 pt-3">
                <?php echo "Antal portioner: ".$portioner ?>
            </div>
            <div class="col px-0 pt-3"><button type="button" class="btn btn-danger" onclick="history.back()">Tillbaka</button></div>


            </div>

        </div>
    </body>

    </html>
