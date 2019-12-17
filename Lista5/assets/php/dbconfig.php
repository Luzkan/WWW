<?php
    $servernameDB = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $dbnameDB = "kryptozakamarki";

    $con = mysqli_connect($servernameDB, $usernameDB, $passwordDB, $dbnameDB);

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>