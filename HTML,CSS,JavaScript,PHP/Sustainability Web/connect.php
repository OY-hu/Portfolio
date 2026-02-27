<?php
    $connect = mysqli_connect("localhost", "root", "");
    if(!$connect) {
        die('Connection to database failed.'.mysqli_connect_error());
    }
    mysqli_select_db($connect, 'ecoquest');
    session_start();
?>