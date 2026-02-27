<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['delete'])) {
        $questID = $_POST['confirmQuest'];
        $sql = "DELETE FROM quest WHERE questID = '$questID';";
        $statement = mysqli_query($connect, $sql);

        echo "<script>
        alert('Delete quest successful.');
        window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage';
        </script>";
    }

    if (isset($_POST['award'])) {
        $accountID = $_SESSION['accountID'];
        $sql = "SELECT vendorName FROM vendor WHERE accountID = '$accountID';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $vendor = $result['vendorName'];

        $studentID = $_POST['studentID'];
        $questID = explode("|", $_POST['quest'])[0];
        $points = $_POST['points'];

        $sql = "SELECT title FROM quest WHERE questID = '$questID';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $quest = $result['title'];

        $sql = "SELECT studentID FROM student WHERE studentID = '$studentID';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        if (!$result) {
            echo "<script>
            alert('In correct student ID.');
            </script>";
        } else {
            $description = "Participated in $quest by $vendor.";
            if ($points > 1000) {
                $status = 'flag';
            } else {
                $status = 'notFlag';
            }
            $sql = "INSERT INTO history (studentID, description, points, flagStatus)
                    VALUES ('$studentID', '$description', $points, '$status');";
            $statement = mysqli_query($connect, $sql);
            echo "<script>
            alert('$points points awarded successfully');
            </script>";
        }
    }

    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'manage':
                $style = ['#0A89F2', 'white'];
                break;
            case 'award':
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
    <title>Quest Management</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/quest/quest.css" />
    <link rel="stylesheet" href="/RWDD/quest/quest/awardPoints.css" />
</head>

<script>
    function confirmDelete(name, questID) {
        document.getElementById("confirmMessage").innerHTML = "Confirm delete for quest (" + name + ")";
        document.getElementById("confirmQuest").value = questID;
        document.getElementById("confirmDelete").classList.toggle("open");
        return false;
    }

    function viewDetails(event, name, questID) {
        if (event.target.value === 'Delete') {
            confirmDelete(name, questID);
        } else if (event.target.tagName.toLowerCase() === 'input') {
            return;
        } else {
            window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage&quest=' + questID;
        }
    }

    function back(event) {
        if (event.target.id === 'viewQuest') {
            window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage';
        }
    }

    function selectQuest(value) {
        var data = value.split("|");
        window.location.href="/RWDD/quest/quest/questManagement.php?mode=award&selection=" + data[0];
    }

    function previewPoints() {
        var base = document.getElementById("questSelection").value;
        var data = base.split("|");
        var unitInput = document.getElementById("unit").value;
        var points = data[2] / data[1] * unitInput;
        document.getElementById("calculated").value = Math.round(points);
    }
</script>

<body>
    <div id="wrapper">
        <h1>
        <?php if ($_GET['mode'] === 'manage') {
            echo "Quest Management";
        } else {
            echo "Award Points";
        } ?>
        </h1>

        <div class="tab">
            <div class="option" style="background-color:<?= $style[0] ?>;"><a href="/RWDD/quest/quest/questManagement.php?mode=manage">Manage Quest</a></div>
            <div class="option" style="background-color:<?= $style[1] ?>;"><a href="/RWDD/quest/quest/questManagement.php?mode=award">Award Points</a></div>
        </div>

        <?php
            if ($_GET['mode'] === 'manage') {
                echo "<div class='create'>";
                echo "<form method='post' action='/RWDD/quest/quest/questForm.php'>";
                echo "<input type='submit' value='+' name='createQuest' />";
                echo "</form>";
                echo "</div>";
                echo "<div class='search'>";
                echo "<form method='post'>";
                echo "<input type='text' placeholder='Search...' name='searchInput' />";
                echo "<input type='submit' value='Search' name='search' />";
                echo "</form>";
                echo "</div>";
            } 
        ?>

        <?php if ($_GET['mode'] === 'manage') { 
            $accountID = $_SESSION['accountID'];
            $sql = "SELECT vendorID FROM vendor WHERE accountID = '$accountID';";
            $statement = mysqli_query($connect, $sql);
            $result = mysqli_fetch_array($statement);
            $vendorID = $result['vendorID'];

            $sql = "SELECT * FROM quest WHERE vendorID = '$vendorID';";
            $statement = mysqli_query($connect, $sql);
        } elseif (isset($_POST['search'])) {
            $search = trim($_POST['searchInput']);
            $accountID = $_SESSION['accountID'];
            $sql = "SELECT q.* 
                    FROM quest q
                    LEFT JOIN vendor v ON v.vendorID = q.vendorID
                    WHERE q.title LIKE '%$search%' OR q.description LIKE '%$search%'
                    AND v.accountID = '$accountID';";
            $statement = mysqli_query($connect, $sql);
        }
        
        if ($_GET['mode'] === 'manage') {
            echo "<div id='manage'>";
            while ($quest = mysqli_fetch_array($statement)) {
                $picture = $quest['banner'];
                $name = $quest['title'];
                $description = $quest['description'];
                $points = $quest['points'];
                $questID = $quest['questID'];
                echo "<form class='quest' method='post' onclick=\"return viewDetails(event, '$name', '$questID')\">";
                echo "<label class='questBanner'>";
                echo "<span class='bannerPicture'><div class='picture' style=\"background-image: url('/RWDD/quest/quest/picture/$picture\")\|></div></span>";
                echo "<span class='badge'><img src='/RWDD/quest/icon/badge.png' /></span>";
                echo "</label>";
                echo "<h4>$name</h4>";
                echo "<p>$description</p>";
                echo "<div class='questPoints'>";
                echo "<img src='/RWDD/quest/icon/quest.png' />";
                echo "<label>$points</label>";
                echo "<input type='hidden' value='$questID' name='questID' />";
                echo "<input type='submit' value='Edit' name='editQuest' formaction='/RWDD/quest/quest/questForm.php' />";
                echo "<input type='button' value='Delete' />";
                echo "</div>";
                echo "</form>";
            } 
            echo "</div>";
        } else {
            echo "<form id='award' method='post'>";
            $accountID = $_SESSION['accountID'];
            $sql = "SELECT vendorID FROM vendor WHERE accountID = '$accountID';";
            $statement = mysqli_query($connect, $sql);
            $result = mysqli_fetch_array($statement);
            $vendorID = $result['vendorID'];

            $sql = "SELECT questID, title, unit, points FROM quest WHERE vendorID = '$vendorID';";
            $statement = mysqli_query($connect, $sql);
            echo "<div class='selection'>";
            echo "<label>Select Quest</label><br />";
            echo "<select id='questSelection' name='quest' onchange='selectQuest(this.value)' required>";
            echo "<option value=''>--Select Quest--</option>";
            while ($quest = mysqli_fetch_array($statement)) {
                $questID = $quest['questID'];
                $title = $quest['title'];
                $unit = $quest['unit'];
                $points = $quest['points'];
                if (isset($_GET['selection']) && $_GET['selection'] == $questID) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                echo "<option value='$questID|$unit|$points' $selected>$title</option>";
            }
            echo "</select><br />";
            echo "</div>";
            echo "<div id='points'>";
            echo "<label>Points</label>";
            echo "<div id='pointsInput'>";
            echo "<input type='number' id='unit' step='0.01' placeholder='1' min='0' name='unit' required oninput='previewPoints()' />";
            echo "<p>units = </p>";
            echo "<input type='number' id='calculated' placeholder='50' name='points' readonly />";
            echo "<p>points</p>";
            echo "</div>";
            echo "</div>";
            echo "<div class='user'>";
            echo "<label>Student ID</label><br />";
            echo "<input type='text' name='studentID' placeholder='Enter TP Number' required />";
            echo "</div>";
            if (isset($_GET['selection'])) {
                $questID = $_GET['selection'];
                if (!$questID) {
                    echo "<script>
                    window.location.href='/RWDD/quest/quest/questManagement.php?mode=award';
                    </script>";
                }
                $sql = "SELECT unit, points FROM quest WHERE questID = '$questID';";
                $statement = mysqli_query($connect, $sql);
                $data = mysqli_fetch_array($statement);
                $unit = $data['unit'];
                $points = $data['points'];
                echo "<p id='note'><strong>Note</strong><br />$unit units = $points points</p>";
            }
            echo "<div id='buttons'>";
            echo "<input type='reset' value='Reset' />";
            echo "<input type='submit' value='Award' name='award' />";
            echo "</div>";
            echo "</form>";
        }

        ?>
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

    <div id="confirmDelete">
        <form method="post">
            <label id="confirmMessage"></label><br />
            <input type="hidden" id="confirmQuest" name="confirmQuest" />
            <input type="button" id="cancelButton" value="Cancel" onclick="document.getElementById('confirmDelete').classList.toggle('open')" />
            <input type="submit" id="confirmButton" value="Confirm" name="delete" />
        </form>
    </div>
</body>
</html>