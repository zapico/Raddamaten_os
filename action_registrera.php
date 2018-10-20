<?php
require("db_conn.php");
$radio = $_POST['radio'];
$namn = $_POST['namn'];
$orgnr = $_POST['orgnr'];
$adress = $_POST['adress'];
$ort = $_POST['ort'];
$email = $_POST['email'];
$telefon = $_POST['telefon'];
$username = $_POST['username'];
$passwd = $_POST['passwd'];


switch ($radio) {
    case "företag":
        $type = 1;
        break;
    case "privat":
        $type = 2;
        break;
}

$passwd_hash = password_hash($passwd, PASSWORD_DEFAULT);
$sql = "INSERT INTO user_list (namn, orgnr, adress, ort, email, telefon, username, passwd, usertype)
  VALUES ('$namn','$orgnr','$adress','$ort','$email','$telefon','$username','$passwd_hash','$type')";

if(mysqli_query($conn, $sql)){

    // header('Location: registrera.php?alert=success');

} else{
  if (mysqli_errno($conn) == 1062) {
     // header('Location: registrera.php?alert=email');
  }
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);

}

// if ($conn->query($sql) === TRUE) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }

?>
