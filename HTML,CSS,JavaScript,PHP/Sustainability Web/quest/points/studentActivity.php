<?php
    /** Include navigation panel. */
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    /** Checks if the user accessing the page is authorized. */
    $accountID = $_SESSION['accountID'];
    $sql = "SELECT studentID FROM student WHERE accountID = '$accountID';";
    $result = mysqli_fetch_array(mysqli_query($connect, $sql));
    $studentID = $result['studentID'];

    if (!$studentID) {
        echo "<script>
        alert('Must be a student to view activity.');
        window.location.href='/RWDD/setup/signOut.php';
        </script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Points</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/points/studentPoints.css" />
</head>

<body>
    <div id="wrapper">
        <!-- Title -->
        <h1>Student Activity</h1>
        <!-- Displays all the student's activity history. View only. -->
        <div id="content">
        <?php
        $sql = "SELECT historyID, description, points, timestamp, flagStatus
                FROM history 
                WHERE studentID = '$studentID'
                ORDER BY historyID DESC";
        $statement = mysqli_query($connect, $sql);
        while ($activity = mysqli_fetch_array($statement)) { ?>
        <div class="studentActivity">
            <p class="studentActivityDetail">
                <span><strong><?= $activity['description'] ?></strong></span><br />
                <span><?= $activity['timestamp'] ?></span>
            </p>
            <p class="studentActivityPoints" style="text-align: right; color: <?php if($activity['points'] > 0) { echo "#27A120"; } else { echo "#F45252"; } ?>" ><?= $activity['points'] ?></p>
        </div>
        <?php } ?>
        </div>
    </div>
</body>
</html>