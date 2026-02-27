<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['Create'])) {
        $accountID = $_SESSION['accountID'];
        $sql = "SELECT vendorID FROM vendor WHERE accountID = '$accountID'";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $vendorID = $result['vendorID'];

        $name = mysqli_real_escape_string($connect, ($_POST['name']));
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $unit = $_POST['unit'];
        $points = $_POST['points'];

        $sql = "INSERT INTO quest (vendorID, title, description, unit, points)
                VALUES('$vendorID', '$name', '$description', $unit, $points);";
        $statement = mysqli_query($connect, $sql);
        $questID = mysqli_insert_id($connect);

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $file = $questID . "-" . $vendorID . "." . $extension;
            $temp = $_FILES['picture']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);

            $sql = "UPDATE quest SET banner = '$file' WHERE questID = '$questID';";
            $statement = mysqli_query($connect, $sql);
        }

        echo "<script>
        alert('Quest created successfully.');
        window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage';
        </script>";
    }

    if (isset($_POST['Edit'])) {
        $accountID = $_SESSION['accountID'];
        $sql = "SELECT vendorID FROM vendor WHERE accountID = '$accountID'";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $vendorID = $result['vendorID'];
        
        $questID = $_POST['questID'];
        $name = mysqli_real_escape_string($connect, ($_POST['name']));
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $unit = $_POST['unit'];
        $points = $_POST['points'];

        $sql = "UPDATE quest
                SET title = '$name',
                description = '$description',
                unit = $unit,
                points = $points
                WHERE questID = '$questID';";
        $statement = mysqli_query($connect, $sql);
        $questID = mysqli_insert_id($connect);

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $file = $questID . "-" . $vendorID . "." . $extension;
            $temp = $_FILES['picture']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);

            $sql = "UPDATE quest SET banner = '$file' WHERE questID = '$questID';";
            $statement = mysqli_query($connect, $sql);
        }

        echo "<script>
        alert('Quest updated successfully.');
        window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage';
        </script>";
    }

    if (isset($_POST['createQuest'])) {
        $picture = "";
        $name = "";
        $description = "";
        $unit = "";
        $points = "";
        $mode = "Create";
        $questID = "";
    } elseif (isset($_POST['editQuest'])) {
        $questID = $_POST['questID'];
        $sql = "SELECT * FROM quest WHERE questID = '$questID';";
        $statement = mysqli_query($connect, $sql);
        $quest = mysqli_fetch_array($statement);
        $picture = $quest['banner'];
        $name = $quest['title'];
        $description = $quest['description'];
        $unit = $quest['unit'];
        $points = $quest['points'];
        $mode = "Edit";
    } else {
        echo "<script>
        alert('Vendor must log in first.');
        window.location.href='/RWDD/setup/signOut.php';
        </script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quest</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/quest/questForm.css" />
</head>

<script>
    function imagePreview(event) {
        var preview = document.getElementById('picture');
        preview.style.backgroundImage = 'url(' + URL.createObjectURL(event.target.files[0]) + ')';
        preview.onload = function() {
            URL.revokeObjectURL(preview.src);
        }
    }
</script>

<body>
    <h1>Create Quest</h1>
    <form id="wrapper" action="" method="post" enctype="multipart/form-data">
        <div id="banner">
            <label id="questBanner">
                <span id="bannerPicture"><div id="picture" style="background-image: url('/RWDD/quest/quest/picture/<?= $picture ?>')"></div></span>
                <span id="pictureInput"><img src="/RWDD/css/icon/camera.png" /></span>
                <input type="file" name="picture" accept = "image/jpeg, image/png, image/jpg" onchange="imagePreview(event)" />
            </label>
        </div>

        <div class="text">
            <label>Quest Name</label><br />
            <input type="text" name="name" placeholder="Quest Name" value="<?= $name ?>" required /> 
            <br />
        </div>

        <div class="text">
            <label>Description</label><br />
            <textarea name="description" placeholder="Description..." maxlength="1000"><?= $description ?></textarea>
            <br />
        </div>

        <div id="points">
            <label>Points</label>
            <div id="pointsInput">
                <input type="number" step="0.01" placeholder="1" name="unit" value="<?= $unit ?>" required />
                <p>units = </p>
                <input type="number" placeholder="50" name="points" max="100" value="<?= $points ?>" required />
                <p>points</p>
            </div>
        </div>

        <div id="buttons">
            <input type="hidden" value="<?= $questID ?>" name="questID" />
            <input type="button" value="Cancel" onclick="window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage'" />
            <input type="submit" value="<?= $mode ?>" name="<?= $mode ?>" /> 
        </div>
    </form>
</body>
</html>