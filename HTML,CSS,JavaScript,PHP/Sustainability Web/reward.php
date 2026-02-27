<?php
    /**Includes the navigation panel.*/
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    /** Retrieves the student ID of the student who logged in. */
    $accountID = $_SESSION['accountID'];
    $sql = "SELECT studentID FROM student WHERE accountID = '$accountID';";
    $result = mysqli_fetch_array(mysqli_query($connect, $sql));
    $studentID = $result['studentID'];

    /** Process to delete the redeem reward request. */
    if (isset($_POST['deleteRequest'])) {
        $redeemID = $_POST['redeemID'];
        $sql = "SELECT r.points 
                FROM redeem q LEFT JOIN reward r ON q.rewardID = r.rewardID WHERE q.redeemID = '$redeemID';";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));
        $points = $result['points'];

        $sql = "DELETE FROM redeem WHERE redeemID = '$redeemID';";
        if (!mysqli_query($connect, $sql)) {
            die('Error'.mysqli_error($connect));
        }
        $sql = "INSERT INTO history (studentID, description, points, flagStatus)
                VALUES ('$studentID', 'Points returned.', $points, 'notFlag');";
        if (!mysqli_query($connect, $sql)) {
            die('Error'.mysqli_error($connect));
        }

        echo "<script>
        alert('Your request for the reward has been cancelled and points returned.');
        window.location.href='/RWDD/quest/reward/reward.php?mode=reward';
        </script>";
    }

    /** Process to send a redeem reward request to the vendor. */
    if (isset($_POST['redeemReward'])) {
        $rewardID = $_POST['rewardID'];
        $sql = "SELECT title, points FROM reward WHERE rewardID = '$rewardID';";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));
        $pointsNeeded = $result['points'];
        $rewardName = $result['title'];

        $sql = "SELECT SUM(points) AS total FROM history WHERE studentID = '$studentID';";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));
        $studentPoints = $result['total'];

        if ($studentPoints >= $pointsNeeded ) {
            $sql = "INSERT INTO redeem (studentID, rewardID, status) VALUES ('$studentID', '$rewardID', 'Pending');";
            if (!mysqli_query($connect, $sql)) {
                die('Error'.mysqli_error($connect));
            }
            $sql = "INSERT INTO history (studentID, description, points, flagStatus) VALUES ('$studentID', 'Redeem reward $rewardName', -$pointsNeeded, 'notFlag');";
            if (!mysqli_query($connect, $sql)) {
                die('Error'.mysqli_error($connect));
            }
            echo "<script>
            alert('Redeem request submitted.');
            window.location.href='/RWDD/quest/reward/reward.php?mode=reward';
            </script>";
        } else {
            echo "<script>alert('Not enough points.');</script>";
        }
    }

    /**Determines which tab is open and set the highlight colour.*/
    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'reward':
                $style = ['#0A89F2', 'white'];
                break;
            case 'request':
                $style = ['white', '#0A89F2'];
                break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward Management</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/reward/reward.css" />
</head>

<!-- Function to return back to the browsing through reward mode. -->
<script>
    /** Function for previewing the reward selected. */
    function previewReward(event, rewardID) {
        if (event.target.value != 'Redeem') {
            fetch ('/RWDD/quest/reward/rewardData.php?rewardID=' + rewardID)
            .then(response => response.json())
            .then(data => {
                document.getElementById('imagePreview').src = "/RWDD/quest/reward/picture/" + data.image;
                document.getElementById('rewardTitle').innerHTML = data.title;
                document.getElementById('vendorName').innerHTML = "reward by " + data.vendorName;
                document.getElementById('rewardPoints').innerHTML = data.points;
                document.getElementById('rewardDescription').innerHTML = data.description;
            });
            document.getElementById('tint').style.display = 'flex';
        }
    }
</script>

<body>
    <div id="wrapper">
        <!-- Title -->
        <h1>Reward</h1>

        <!-- Tab to switch between rewards and redeem request. -->
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>" ><a href="/RWDD/quest/reward/reward.php?mode=reward">Manage Reward</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>" ><a href="/RWDD/quest/reward/reward.php?mode=request">Redeem Request</a></div>
        </div>

        <!-- Display is the mode is browing reward. -->
        <?php if ($_GET['mode'] === 'reward') { ?>
        
        <!-- Search bar only appears when in reward. -->
        <div class="search">
            <form action="/RWDD/quest/reward/reward.php?mode=reward" method="get">
                <input type="hidden" name="mode" value="reward" />
                <input type="text" name="search" placeholder="Search..." />
                <input type="submit" value="Search" />
            </form>
        </div>

        <!-- Display the reward items. -->
        <div id="reward">
            <?php
            /** SQL code to retrieve the reward information based on if there was a search input or not. */
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT r.*, v.vendorName FROM reward r
                        LEFT JOIN vendor v ON v.vendorID = r.vendorID
                        WHERE title LIKE '%$search%' OR description LIKE '%$search%'
                        ORDER BY r.rewardID DESC;";
            } else {
                $sql = "SELECT r.*, v.vendorName FROM reward r
                        LEFT JOIN vendor v ON v.vendorID = r.vendorID
                        ORDER BY r.rewardID DESC;";
            }

            /** Retrieve information about each reward. */
            $statement = mysqli_query($connect, $sql);
            while ($reward = mysqli_fetch_array($statement)) {
                $rewardID = $reward['rewardID'];
                $title = $reward['title'];
                $image = $reward['image'];
                $points = $reward['points'];
                $vendor = $reward['vendorName'];
            ?>
            <!-- Display the reward item. -->
            <form class="item" style="background-color: white" method="post" onclick="previewReward(event, <?= $rewardID ?>)">
                <div class="imageArea" style="background-color: #000000"><img src="/RWDD/quest/reward/picture/<?= $image ?>" /></div>
                <div class="details">
                    <h4><?= $title ?></h4>
                    <div class="points">
                        <img src="/RWDD/quest/icon/quest.png" />
                        <p><?= $points ?></p>
                    </div>
                    <p>reward by <?= $vendor ?></p>
                </div>
                <input type="hidden" name="rewardID" value="<?= $rewardID ?>" /> 
                <input type="submit" name="redeemReward" value="Redeem" style="background-color: #8CDF87; width: 90%; font-weight: bold;" />
            </form>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div id="redeem">
            <!-- Tab for viewing all the redeem reward request made by the student. -->
            <?php
            $sql = "SELECT q.*, r.*, v.vendorName
                    FROM redeem q
                    LEFT JOIN reward r ON q.rewardID = r.rewardID
                    LEFT JOIN vendor v ON r.vendorID = v.vendorID
                    WHERE q.studentID = '$studentID'
                    ORDER BY q.redeemID DESC;";
            $statement = mysqli_query($connect, $sql);
            while ($request = mysqli_fetch_array($statement)) {
            ?>
            <div class="request">
                <div class="details">
                    <h4><?= $request['title'] ?></h4>
                    <p><?= $request['vendorName'] ?><br /><?= $request['timestamp'] ?></p>
                </div>
                <?php if ($request['status'] === 'Pending') { ?>
                <form class="status" method="post">
                    <input type="hidden" name="redeemID" value="<?= $request['redeemID'] ?>" />
                    <input type="submit" class="deleteIcon" value="  " name="deleteRequest" />
                </form>
                <?php } else { ?>
                <div class="completedStatus">
                    <?php
                    switch ($request['status']) {
                        case 'Completed': $color = "#8CDF87"; break;
                        case 'Rejected': $color = "#FF274C"; break;
                    }
                    ?>
                    <p style="color: <?= $color ?>; font-weight: bold;"><?= $request['status'] ?></p>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>

    <!-- Pop up to display full details about selected reward. -->
    <div id="tint" onclick="document.getElementById('tint').style.display = 'none'">
        <div id="rewardDetail" style="background-color: white">
            <div class="imageArea" style="background-color: #000000"><img id="imagePreview" /></div>
            <div class="details">
                <h4 id="rewardTitle"></h4>
                <p id="vendorName"></p>
                <div class="points">
                    <img src="/RWDD/quest/icon/quest.png" />
                    <p id="rewardPoints"></p>
                </div>
                <p id="rewardDescription"></p>
            </div>
        </div>
    </div>
</body>
</html>

<!-- Close the database connection in this page. -->
<?php mysqli_close($connect) ?>