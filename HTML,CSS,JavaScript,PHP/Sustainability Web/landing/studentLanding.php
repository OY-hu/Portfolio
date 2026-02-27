<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    $accountID = $_SESSION['accountID'];
    $sql = "SELECT * FROM student WHERE accountID = $accountID;";
    $student = mysqli_fetch_array(mysqli_query($connect, $sql));

    $studentID = $student['studentID'];
    $sql = "SELECT SUM(points) AS total FROM history WHERE studentID='$studentID';";
    $result = mysqli_fetch_array(mysqli_query($connect, $sql));
    $points = $result['total'];

    if (!$points) {
        $points = 0;
    }

    $date = date("Y-m-d");
    $monday = date("Y-m-d", strtotime("monday this week", strtotime($date)));
    $sunday = date("Y-m-d", strtotime("sunday this week", strtotime($date)));
    $today = new DateTime($date);
    $today = $today -> format("D, d F Y");

    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $weekActivity = ['Mon' => 'notHighlight.png',
                    'Tue' => 'notHighlight.png',
                    'Wed' => 'notHighlight.png',
                    'Thu' => 'notHighlight.png',
                    'Fri' => 'notHighlight.png',
                    'Sat' => 'notHighlight.png',
                    'Sun' => 'notHighlight.png'];
    for ($count = 0; $count < 7; $count++) {
        $date = new DateTime($monday);
        $date->modify("+$count days");
        $dateCheck = $date->format('Y-m-d');
        $sql = "SELECT timestamp FROM history WHERE DATE(timestamp) = '$dateCheck' GROUP BY timestamp;";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));
        if ($result) {
            $weekActivity[$days[$count]] = 'highlight.png';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quest</title>
    <link rel="stylesheet" href="/RWDD/landing/landing.css" />
    <link rel="stylesheet" href="/RWDD/landing/studentLanding.css" />
</head>

<body>
    <div id="wrapper">
        <h1>Welcome back, <?= $student['name'] ?></h1>

        <div id="studentPoints">
            <label><strong>Your Points</strong></label>
            <div id="total">
                <img src="/RWDD/quest/icon/points.png" />
                <p>
                    <span style="font-size: 15px; text-align: left">You currently have:</span><br />
                    <span style="font-size: 50px; text-align: center"><?= $points ?></span>
                </p>
            </div>
        </div>

        <div id="activity">
            <label id="activityTitle"><strong>Weekly Calendar</strong></label>
            <a href="/RWDD/quest/points/studentActivity.php">See Details</a>
            <div id="week">
                <p><strong><?= $today ?></strong></p>
                <div id="days">
                    <?php foreach ($weekActivity as $day => $icon) { ?>
                    <div class="dayIcon">
                        <img src="/RWDD/quest/icon/<?= $icon ?>" /><br />
                        <label><?= $day ?></label>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div id="questOption" onclick="window.location.href='/RWDD/quest/quest/quest.php'">
            <div class="hat" style="background-color: #64CA2D"></div>
            <div id="quest" class="option" style="background-color: #F1FFE6">
                <img src="/RWDD/landing/icon/quest.png" />
                <p style="color: #64CA2D">Quests</p>
            </div>
        </div>

        <div id="rewardOption" onclick="window.location.href='/RWDD/quest/reward/reward.php?mode=reward'">
            <div class="hat" style="background-color: #4043CE"></div>
            <div id="reward" class="option" style="background-color: #EEEFFA" >
                <img src="/RWDD/landing/icon/reward.png" />
                <p style="color: #4043CE">Rewards</p>
            </div>
        </div>

        <div id="leaderboardOption" onclick="window.location.href='/RWDD/quest/points/leaderboard.php?mode=weekly'">
            <div class="hat" style="background-color: #FF274C"></div>
            <div id="reward" class="option" style="background-color: #FEE3E3" >
                <img src="/RWDD/landing/icon/leaderboard.png" />
                <p style="color: #FF274C">Leaderboard</p>
            </div>
        </div>
    </div>
</body>
</html>