<?php
    /** Include navigation panel. */
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    /** Checks if the user accessing the page is authorized. */
    $accountID = $_SESSION['accountID'];
    $sql = "SELECT role FROM account WHERE accountID = '$accountID';";
    $result = mysqli_fetch_array(mysqli_query($connect, $sql));

    /** Checks if the user logging in is an admin. */
    if ($result['role'] != 'Admin') {
        echo "<script>
        alert('Must be an admin to access this page.');
        window.location.href='/RWDD/setup/signOut.php';
        </script>";
    }

    /** Process to edit the student's points. */ 
    if (isset($_POST['editStudentPoints'])) {
        $studentID = $_POST['studentID'];
        $newPoints = $_POST['newPoints'];
        $adminNote = mysqli_real_escape_string($connect, $_POST['adminNote']);
        
        /** Retrieves the student's current points. */
        $sql = "SELECT SUM(points) AS points FROM history WHERE studentID = '$studentID';";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));
        $currentPoints = $result['points'];

        /** Calculates the difference between the student points that was supposed to be and the current points.
         * The difference is stored in the history with the admin note.
         */
        $difference = $newPoints - $currentPoints;
        $sql = "INSERT INTO history (studentID, description, points, flagStatus)
                VALUES ('$studentID', 'Admin - $adminNote', $difference, 'notFlag');";
        if (mysqli_query($connect, $sql)) {
            echo "<script>
            alert('Student point successfully updated.');
            window.location.href='/RWDD/quest/points/studentPoints.php?mode=points';
            </script>";
        } else {
            die('Error'.mysqli_error($connect));
        }
    }

    /** Process to change the flag status of the activity. */
    if (isset($_GET['historyID'])) {
        $historyID = $_GET['historyID'];
        $sql = "SELECT flagStatus FROM history WHERE historyID = '$historyID';";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));

        if ($result['flagStatus'] === 'flag') {
            $sql = "UPDATE history SET flagStatus = 'notFlag' WHERE historyID = '$historyID';";
        } else {
            $sql = "UPDATE history SET flagStatus = 'flag' WHERE historyID = '$historyID';";
        }

        if (mysqli_query($connect, $sql)) {
            echo "<script>window.location.href='/RWDD/quest/points/studentPoints.php?mode=log'</script>";
        } else {
            die('Error'.mysqli_error($connect));
        }
    }

    /** Variables for storing style for tab based on mode. */
    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'points':
                $style = ['#0A89F2', 'white'];
                break;
            case 'log':
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
    <title>Student Points</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/points/studentPoints.css" />
</head>

<script>
    function displayEdit(event, studentID) {
        document.getElementById('tpNumber').innerHTML = studentID;
        document.getElementById('studentID').value = studentID;
        document.getElementById('editStudent').style.display = 'block';
    }
</script>

<body>
    <div id="wrapper">
        <h1>Student Points Monitoring</h1>
        <!-- To switch between Points Tab and Log Tab. -->
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>"><a href="/RWDD/quest/points/studentPoints.php?mode=points">Points</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>"><a href="/RWDD/quest/points/studentPoints.php?mode=log">Log</a></div>
        </div>

        <?php if ($_GET['mode'] === 'points') { ?>
        <!-- Search bar. -->
        <div class="search">
            <form action="/RWDD/quest/points/studentPoints.php" method="get">
                <input type="hidden" name="mode" value="points" />
                <input type="text" name="search" placeholder="Search..." />
                <input type="submit" value="Search" />
            </form>
        </div>
        
        <!-- Displays if the tab selected is Points. -->
        <div id="content">
        <?php
        if (isset($_GET['search'])) {
            $searchKey = $_GET['search'];
            $sql = "SELECT s.studentID, s.name, SUM(h.points) AS points
                    FROM student s LEFT JOIN history h ON h.studentID = s.studentID
                    WHERE s.name LIKE '%$searchKey%' OR s.studentID LIKE '%$searchKey%'
                    GROUP BY s.studentID ORDER BY s.studentID;";
        } else {
            $sql = "SELECT s.studentID, s.name, SUM(h.points) AS points
                    FROM student s LEFT JOIN history h ON h.studentID = s.studentID
                    GROUP BY s.studentID ORDER BY s.studentID;";
        }
        $statement = mysqli_query($connect, $sql);
        while ($student = mysqli_fetch_array($statement)) { ?>
        <div class="student">
            <p class="studentDetail"><?= $student['name'] ?><br /><?= $student['studentID'] ?></p>
            <p class="studentPoints"><?php if ($student['points']) { echo $student['points']; } else { echo 0; } ?></p>
            <input type="button" class="editButton" value=" " onclick="displayEdit(event, '<?= $student['studentID'] ?>')" />
        </div>
        <?php } ?>
        </div>
        <?php } else { ?>
        <select onchange="window.location.href='/RWDD/quest/points/studentPoints.php?mode=log&filter=' + this.value">
            <option value="" <?php if(isset($_GET['filter']) && $_GET['filter'] === '') { echo "selected"; } ?>>All</option>
            <option value="flag" <?php if(isset($_GET['filter']) && $_GET['filter'] === 'flag') { echo "selected"; } ?>>Flagged</option>
            <option value="notFlag" <?php if(isset($_GET['filter']) && $_GET['filter'] === 'notFlag') { echo "selected"; } ?>>Not Flagged</option>
        </select>

        <!-- Displays if the tab selected is Log -->
        <div id="content">
        <?php
        if (isset($_GET['filter']) && ($_GET['filter'])) {
            $filter = $_GET['filter'];
            $sql = "SELECT h.historyID, h.description, s.studentID, s.name, h.points, h.timestamp, h.flagStatus
                    FROM history h LEFT JOIN student s ON h.studentID = s.studentID
                    WHERE h.flagStatus = '$filter'
                    ORDER BY h.historyID DESC;";
        } else {
            $sql = "SELECT h.historyID, h.description, s.studentID, s.name, h.points, h.timestamp, h.flagStatus
                    FROM history h LEFT JOIN student s ON h.studentID = s.studentID
                    ORDER BY h.historyID DESC";
        }
        $statement = mysqli_query($connect, $sql);
        while ($activity = mysqli_fetch_array($statement)) { ?>
        <div class="activity" style="background-color: <?php if ($activity['flagStatus'] === 'flag') { echo "#FFD9D9"; } ?>">
            <p class="activityDetail">
                <span><strong><?= $activity['description'] ?></strong></span><br />
                <span style="font-size: 12px;"><?= $activity['studentID'] ?>, <?= $activity['name'] ?><br /><?= $activity['timestamp'] ?></span>
            </p>
            <p class="activityPoints" style="color: <?php if($activity['points'] > 0) { echo "#27A120"; } else { echo "#F45252"; } ?>" ><?= $activity['points'] ?></p>
            <input type="button" class="flag" value =" " style="background: url('../../quest/points/icon/<?= $activity['flagStatus'] ?>') no-repeat;" onclick="window.location.href='/RWDD/quest/points/studentPoints.php?mode=log&historyID=<?= $activity['historyID'] ?>'" />
        </div>
        <?php } ?>
        </div>
        <?php } ?>
    </div>
    
    <!-- Pop up that allows Admin to set the student's total points. -->
    <form id="editStudent" method="post">
        <div id="visibleArea">
            <p id="tpNumber"><strong>TP Number</strong><br /></p>
            <div class="text">
                <label>Points</label><br />
                <input type="number" min="0" placeholder="New Student Point Total" name="newPoints" required /><br /> 
            </div>

            <div class="text">
                <label>Note</label><br />
                <input type="text" placeholder="Note" name="adminNote" required /><br />
            </div>

            <div id="buttons">
                <input id="studentID" type="hidden" name="studentID" />
                <input type="button" style="background-color: #E34444" value="Cancel" onclick="document.getElementById('editStudent').style.display = 'none'" />
                <input type="submit" style="background-color: #00308F" value="Save" name="editStudentPoints" />
            </div>
        </div>
    </form>
</body>
</html>