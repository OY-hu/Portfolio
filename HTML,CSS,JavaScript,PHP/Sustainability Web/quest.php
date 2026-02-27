<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quests</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/quest/quest.css" />
</head>

<script>
    function viewDetails(event, questID) {
        window.location.href='/RWDD/quest/quest/quest.php?quest=' + questID;
    }

    function back(event) {
        if (event.target.id === 'viewQuest') {
            window.location.href='/RWDD/quest/quest/quest.php';
        }
    }
</script>

<body>
    <div id="wrapper">
        <h1>Quests</h1>

        <div class="search">
            <form method="post">
                <input type="text" placeholder="Search..." name="searchInput" />
                <input type="submit" value="Search" name="search" />
            </form>
        </div>
            
        <?php if (isset($_POST['search'])) {
            $search = trim($_POST['searchInput']);
            $sql = "SELECT q.* 
                    FROM quest q
                    LEFT JOIN vendor v ON v.vendorID = q.vendorID
                    WHERE q.title LIKE '%$search%' OR q.description LIKE '%$search%';";
            $statement = mysqli_query($connect, $sql);
        } else {
            $sql = "SELECT * FROM quest";
            $statement = mysqli_query($connect, $sql);
        }
        ?>
        <div id="manage">
            <?php 
                while ($quest = mysqli_fetch_array($statement)) {
                    $picture = $quest['banner'];
                    $name = $quest['title'];
                    $description = $quest['description'];
                    $points = $quest['points'];
                    $questID = $quest['questID'];
                    echo "<form class='quest' method='post' onclick=\"return viewDetails(event, '$questID')\">";
                    echo "<label class='questBanner'>";
                    echo "<span class='bannerPicture'><div class='picture' style=\"background-image: url('/RWDD/quest/quest/picture/$picture\")\|></div></span>";
                    echo "<span class='badge'><img src='/RWDD/quest/icon/badge.png' /></span>";
                    echo "</label>";
                    echo "<h4>$name</h4>";
                    echo "<p>$description</p>";
                    echo "<div class='questPoints' style='justify-content: left'>";
                    echo "<img src='/RWDD/quest/icon/quest.png' />";
                    echo "<label>$points</label>";
                    echo "</div>";
                    echo "</form>";
                } ?>
        </div>
    </div>

    <?php
        if (isset($_GET['quest'])) {
            $questID = $_GET['quest'];
            $sql = "SELECT * FROM quest WHERE questID = '$questID'";
            $statement = mysqli_query($connect, $sql);
            $quest = mysqli_fetch_array($statement);
            $picture = $quest['banner'];
            $name = $quest['title'];
            $description = $quest['description'];
            $description = str_replace("\n", "<br/>", $description);
            $points = $quest['points'];
            $vendorID = $quest['vendorID'];
            $sql = "SELECT vendorName FROM vendor WHERE vendorID = '$vendorID';";
            $statement = mysqli_query($connect, $sql);
            $vendor = mysqli_fetch_array($statement);
            $vendorName = $vendor['vendorName'];

            echo "<div id='viewQuest' onclick='back(event)'>";
            echo "<div id='details'>";
            echo "<label class='questBanner'>";
            echo "<span class='bannerPicture'><div class='picture' style=\"background-image: url('/RWDD/quest/quest/picture/$picture')\" ></div></span>";
            echo "<span class='badge'><img src='/RWDD/quest/icon/badge.png' /></span>";
            echo "</label>";
            echo "<h4>$name</h4>";
            echo "<p>$description</p>";
            echo "<div class='questPoints'>";
            echo "<img src='/RWDD/quest/icon/quest.png' />";
            echo "<label id='points'>$points</label>";
            echo "<label id='creator'>Created by $vendorName</label>";
            echo "</div>";
            echo "</div>";
        }
    ?>
</body>
</html>