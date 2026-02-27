<?php
    /** Includes the connection to the database. */
    include $_SERVER['DOCUMENT_ROOT']."/RWDD/setup/connect.php";

    $rewardID = $_GET['rewardID'];
    $sql = "SELECT r.*, v.vendorName 
            FROM reward r LEFT JOIN vendor v ON r.vendorID = v.vendorID
            WHERE rewardID = '$rewardID';";
    $result = mysqli_fetch_assoc(mysqli_query($connect, $sql));
    echo json_encode($result);

    /** Close the connection to the database in this file. */
    mysqli_close($connect);
?>