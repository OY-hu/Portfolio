<?php
    /**Includes the navigation panel.*/
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    /** Checks if there GET is fired for this page correctly. */
    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'weekly': /** SQL for calculating and sorting students based on the points collected in the current week. */
                $date = date("Y-m-d");
                $monday = date("Y-m-d", strtotime("monday this week", strtotime($date)));
                $sunday = date("Y-m-d", strtotime("sunday this week", strtotime($date)));
                $title = "Weekly Leaderboard";
                $style = ['#0A89F2', 'white'];
                $sql = "SELECT a.username, a.picture, SUM(CASE
                        WHEN h.points > 0 AND h.description NOT LIKE '%return%' THEN h.points
                        ELSE 0
                        END) AS points
                    FROM history h
                    LEFT JOIN student s ON s.studentID = h.studentID
                    LEFT JOIN account a ON a.accountID = s.accountID
                    WHERE DATE(h.timestamp) BETWEEN '$monday' AND '$sunday'
                    GROUP BY s.studentID
                    ORDER BY SUM(
                        CASE
                            WHEN h.points > 0 AND h.description NOT LIKE '%return%' THEN h.points
                            ELSE 0
                        END) DESC LIMIT 10";
                break;
            case 'alltime': /** SQL for calculating and sorting students based on the points collected without any time range. */
                $title = "All Time Leaderboard";
                $style = ['white', '#0A89F2'];
                $sql = "SELECT a.username, a.picture, SUM(CASE
                        WHEN h.points > 0 AND h.description NOT LIKE '%return%' THEN h.points
                        ELSE 0
                        END) AS points
                    FROM history h
                    LEFT JOIN student s ON s.studentID = h.studentID
                    LEFT JOIN account a ON a.accountID = s.accountID
                    GROUP BY s.studentID
                    ORDER BY SUM(
                        CASE
                            WHEN h.points > 0 AND h.description NOT LIKE '%return%' THEN h.points
                            ELSE 0
                        END) DESC LIMIT 10";
                break;
            default:
                echo "<script>window.location.href='/RWDD/quest/points/leaderboard.php?mode=weekly'</script>";
                break;
        }
    } else {
        echo "<script>window.location.href='/RWDD/quest/points/leaderboard.php?mode=weekly'</script>";
    }

    /** SQL query executed and stored in variable to be used to loop through. */
    $statement = mysqli_query($connect, $sql);
    $rank = mysqli_query($connect, $sql);

    /** Process for getting the top 3 students in the rank. */
    $counter = 0;
    $usernameRank = array();
    $usernamePicture = array();
    while ($user = mysqli_fetch_array($rank)) {
        $usernameRank[$counter] = $user['username'];
        $usernamePicture[$counter] = $user['picture'];
        if ($counter == 2) {
            break;
        }
        $counter++;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/quest/points/leaderboard.css" />
</head>

<body>
    <div id="wrapper">
        <!-- Title of the page. -->
        <h1><?= $title ?></h1>

        <!-- Tab to switch between Weekly or All Time leaderboards. -->
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>;"><a href="/RWDD/quest/points/leaderboard.php?mode=weekly">Weekly</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>;"><a href="/RWDD/quest/points/leaderboard.php?mode=alltime">All Time</a></div>
        </div>

        <!-- Top 3 students displayed in a podium type style. -->
        <div id="top3">
            <div id="secondPlace" class="topUser" style="width: 20%">
                <label class="picture">
                    <span><img class="userPicture" src="/RWDD/profile/picture/<?= $usernamePicture[1] ?>" style="border-color: #ADADAD" /></span>
                    <span><img class="badge" src="/RWDD/quest/points/icon/secondPlace.png" /></span>
                </label>
                <label class="username"><?= $usernameRank[1] ?></label>
            </div>

            <div id="firstPlace" class="topUser">
                <label class="picture">
                    <span><img class="userPicture" src="/RWDD/profile/picture/<?= $usernamePicture[0] ?>" style="border-color: #FFEB80" /></span>
                    <span><img class="badge" src="/RWDD/quest/points/icon/firstPlace.png" /></span>
                </label>
                <label class="username"><?= $usernameRank[0] ?></label>
            </div>

            <div id="thirdPlace" class="topUser" style="width: 20%">
                <label class="picture">
                    <span><img class="userPicture" src="/RWDD/profile/picture/<?= $usernamePicture[2] ?>" style="border-color: #B17F6A" /></span>
                    <span><img class="badge" src="/RWDD/quest/points/icon/thirdPlace.png" /></span>
                </label>
                <label class="username"><?= $usernameRank[2] ?></label>
            </div>
        </div>

        <!-- Leaderboard. -->
        <table>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Points</th>
            </tr>

            <?php 
            $counter = 1;
            while ($user = mysqli_fetch_array($statement)) { ?>
                <tr>
                    <td><?= $counter ?></td>
                    <td><img class="userPicture" style="width: 10%; border: none; vertical-align: middle;" 
                    src="/RWDD/profile/picture/<?= $user['picture'] ?>" />&nbsp;<?= $user['username'] ?></td>
                    <td><?= $user['points'] ?></td>
                </tr>
            <?php $counter++; } ?>
        </table>
    </div>
</body>
</html>