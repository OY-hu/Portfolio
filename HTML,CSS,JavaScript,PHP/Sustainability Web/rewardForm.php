<?php
    /**Include the navigation section.*/
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    /**Checks if the user accessing the page is authorized for it.*/
    $accountID = $_SESSION['accountID'];
    $sql = "SELECT role FROM account WHERE accountID = '$accountID';";
    $result = mysqli_fetch_array(mysqli_query($connect, $sql));
    if ($result['role'] != 'Vendor') { /** Checks if the user is not a vendor and signs them out. */
        echo "<script>
        alert('User must be a vendor. Signing out user.');
        window.location.href='/RWDD/setup/signOut.php';
        </script>";
    }

    /**Process the creation or edits of a reward.*/
    if (isset($_POST['Create']) || isset($_POST['Edit'])) {
        /** Store the inputs in variables and format where necessary. */
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $points = $_POST['points'];
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $stock = $_POST['stock'];

        $sql = "SELECT vendorID FROM vendor WHERE accountID = $accountID;";
        $result = mysqli_fetch_array(mysqli_query($connect, $sql));
        $vendorID = $result['vendorID'];

        /** SQL code based on situation - Creating or Editing. */
        if (isset($_POST['Create'])) {
            $sql = "INSERT INTO reward (vendorID, title, image, description, stock, points)
                    VALUES ($vendorID, '$name', 'temp', '$description', $stock, $points);";
            if (!mysqli_query($connect, $sql)) {
                die('Error'.mysqli_error($connect));
            }
            $rewardID = mysqli_insert_id($connect);
        } elseif (isset($_POST['Edit'])) {
            $rewardID = $_POST['rewardID'];
            $sql = "UPDATE reward
                    SET title = '$name', description = '$description', stock = $stock, points = $points
                    WHERE rewardID = $rewardID";
            if (!mysqli_query($connect, $sql)) {
                die('Error'.mysqli_error($connect));
            }
        }

        /** Handles the upload of image. */
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file = $rewardID . "-" . $vendorID . "." . $extension;
            $temp = $_FILES['image']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);

            $sql = "UPDATE reward SET image = '$file' WHERE rewardID = $rewardID;";
            if (!mysqli_query($connect, $sql)) {
                die('Error'.mysqli_error($connect));
            }
        } else {
            echo "<script>
            alert('Problem uploading image. Max image size is 2MB. Please edit the reward.');
            window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=reward';
            </script>";
        }

        /** Message based on situation - Creation or Editing and redirecting back to Reward Management. */
        if (isset($_POST['Create'])) {
            $mode = "created";
        } elseif (isset($_POST['Edit'])) {
            $mode = "edited";
        }
        echo "<script>
        alert('Reward $mode successfully.');
        window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=reward';
        </script>";
    }

    /** Default value of input based on situation - Creating or Editing. */
    if (isset($_POST['editReward'])) {
        $pageTitle = "Edit Reward";
        $mode = "Edit";

        $rewardID = $_POST['rewardID'];
        $sql = "SELECT * FROM reward WHERE rewardID = $rewardID";
        $reward = mysqli_fetch_array(mysqli_query($connect, $sql));
        $name = $reward['title'];
        $description = $reward['description'];
        $stock = $reward['stock'];
        $points = $reward['points'];
        $imageName = $reward['image'];
        $picture = "/RWDD/quest/reward/picture/$imageName";
        $status = "none";
    } else {
        $pageTitle = "Create Reward";
        $mode = "Create";

        $name = "";
        $description = "";
        $stock = "";
        $points = "";
        $picture = "/RWDD/css/icon/cloud.png";
        $status = "block";
        $rewardID = "";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/reward/rewardForm.css" />
</head>

<script>
    /** Function for displaying the uploaded image as preview. */
    function imagePreview(event) {
        var preview = document.getElementById('picture');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.onload = function () {
            URL.revokeObjectURL(preview.src);
        }
        document.getElementById('uploadLabel').style.display = 'none';
    }
</script>

<body>
    <!-- Title of the page. -->
    <h1><?= $pageTitle ?></h1>

    <!-- Reward Form. -->
    <form id="wrapper" action="/RWDD/quest/reward/rewardForm.php" method="post" enctype="multipart/form-data">
        <!-- Section to upload image and preview the uploaded image. -->
        <label id="imageUpload">
            <span><img id="picture" src="<?= $picture ?>" /></span>
            <span><h2 id="uploadLabel" style="display:<?= $status ?>">Upload Image</h2></span>
            <input type="file" id="imageInput" accept="image/png, image/jpeg" name="image" onchange="imagePreview(event)" <?php if ($mode === 'Create') { echo "required";} ?> />
        </label>

        <!-- Inputs -->
        <div class="text">
            <label>Reward Name</label><br />
            <input type="text" name="name" placeholder="Reward Name" value="<?= $name ?>" required /><br />
        </div>

        <div class="price">
            <label>Points</label><br />
            <input type="number" name="points" min="1" name="points" value="<?= $points ?>" placeholder="Points Needed to Redeem Reward" required /><br />
        </div>

        <div class="text">
            <label>Description</label><br />
            <textarea name="description" placeholder="Description..." style="height: 150px"><?= $description ?></textarea><br />
        </div>

        <div class="text">
            <label>Stock</label><br />
            <input type="number" min="0" name="stock" placeholder="Stock" value="<?= $stock ?>" required /><br />
        </div>

        <input type="hidden" name="rewardID" value="<?= $rewardID ?>" />

        <!-- Button to submit or go back to the reward management page. -->
        <div id="buttons">
            <input type="button" value="Cancel" onclick="window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=reward'" style="background-color:#E34444"/>
            <input type="submit" value="<?= $mode ?>" name="<?= $mode ?>" style="background-color:#000000"/>
        </div>
    </form>
</body>
</html>