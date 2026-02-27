<!-- 
This file contains the HTML code for the header UI.
This file also contains the PHP code to determine the options in the header.
-->

<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/connect.php';

    $role = $_SESSION['role'];
    switch ($role) {
        case "Admin":
            $navigation = ["User<br/>Management" => "/RWDD/profile/userManagement.php",
                        "Student<br/>Monitoring" => "/RWDD/quest/points/studentPoints.php?mode=points", 
                        "Media<br/>Monitoring" => "/RWDD/landing/mediaMonitoring.php?mode=community", 
                        "Help<br/>Desk" => "/RWDD/help/manageTicket.php?mode=pending"];
            $home = "/RWDD/landing/admin.php";
            break;
        case "Vendor":
            $navigation = ["Quest<br/>Management" => "/RWDD/landing/vendorLanding.php", 
                        "Community" => "/RWDD/community/community.php?mode=community", 
                        "Marketplace" => "/RWDD/marketplace/marketplace.php?mode=marketplace", 
                        "Help<br/>Desk" => "/RWDD/help/helpDesk.php?mode=pending"];
        $home = "/RWDD/landing/vendor.php";
            break;
        case "Student":
            $navigation = ["Quest" => "/RWDD/landing/studentLanding.php", 
                        "Community" => "/RWDD/community/community.php?mode=community", 
                        "Marketplace" => "/RWDD/marketplace/marketplace.php?mode=marketplace", 
                        "Help Desk" => "/RWDD/help/helpDesk.php?mode=pending"];
            $home = "/RWDD/landing/studentLanding.php";
            break;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/setup/navigation.css" />
    <link rel="icon" type="image/png" href="/RWDD/setup/icon/logo2.png" />
</head>

<script>
    function openMenu() {
        document.getElementById("logo").classList.toggle("open");
        document.getElementById("navigation").classList.toggle("open");
        document.getElementById("userNav").classList.toggle("open");
        document.getElementById("menu").classList.toggle("open");
        document.getElementById("menuButton").classList.toggle("open");
    }
</script>

<body>
    <button id="menuButton" onclick="openMenu()">&#9776;</button><br />
    <div id="menu">
        <img id="logo" src="/RWDD/setup/icon/logo.png" onclick="window.location.href='<?= $home ?>'"/>

        <div id="navigation">
            <ul>
                <?php
                    foreach ($navigation as $nav => $link) {
                        echo "<li><a href='$link'>$nav</a></li>";
                    }
                ?>
            </ul>
        </div>
        
        <?php if ($role == "Student") { 
            $accountID = $_SESSION['accountID'];
            $sql = "SELECT studentID FROM student WHERE accountID = '$accountID'";
            $statement = mysqli_query($connect, $sql);
            $result = mysqli_fetch_array($statement);
            $studentID = $result['studentID'];

            $sql = "SELECT SUM(points) AS total FROM history WHERE studentID = '$studentID'";
            $statement = mysqli_query($connect, $sql);
            $result = mysqli_fetch_array($statement);
            $points = $result['total'];

            if (!$points) {
                $points = 0;
            }

            echo "
            <div id='userPoints'>
                <img id='pointIcon' src='/RWDD/setup/icon/leaf.png' />
                <label id='points'>$points</label>
            </div>";
        } ?>

        <div id="userNav">
            <ul>
                <li><a href="/RWDD/setup/signOut.php"><img id="logout" src="/RWDD/setup/icon/logout.png" /></a></li>
                <li><a href="/RWDD/profile/profile.php"><img id="profile" src="/RWDD/setup/icon/profile.png" /></a></li>
            </ul>
        </div>
    </div>
    <br />
</body>
</html>