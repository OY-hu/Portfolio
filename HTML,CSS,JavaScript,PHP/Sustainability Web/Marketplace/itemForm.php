<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['Upload'])) {
        $accountID = $_SESSION['accountID'];
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $price = $_POST['price'];
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $itemCondition = $_POST['condition'];
        $status = $_POST['status'];

        $sql = "INSERT INTO item (accountID, name, media, price, description, itemCondition, status) 
                VALUES ($accountID, '$name', 'temp', '$price', '$description', '$itemCondition', '$status');";
        mysqli_query($connect, $sql);
        $itemID = mysqli_insert_id($connect);

        if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
            $extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $file = $itemID . "-" . $accountID . "." . $extension;
            $temp = $_FILES['media']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);
        }

        $sql = "UPDATE item SET media = '$file' WHERE itemID = '$itemID'";
        mysqli_query($connect, $sql);

        echo "<script>
        alert('Item uploaded!');
        window.location.href='/RWDD/marketplace/marketplace.php?mode=manage';
        </script>";
    } 
    
    if (isset($_POST['Edit'])) {
        $accountID = $_SESSION['accountID'];
        $itemID = $_POST['itemID'];
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $price = $_POST['price'];
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $itemCondition = $_POST['condition'];
        $status = $_POST['status'];
        $pageOrigin = $_POST['pageOrigin'];

        $sql = "UPDATE item
                SET name = '$name', price = '$price', description = '$description', itemCondition = '$itemCondition', status = '$status'
                WHERE itemID = '$itemID';";
        mysqli_query($connect, $sql);

        if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
            $extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $file = $itemID . "-" . $accountID . "." . $extension;
            $temp = $_FILES['media']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);
            $sql = "UPDATE item SET media = '$file' WHERE itemID = '$itemID'";
            mysqli_query($connect, $sql);
        }

        echo "<script>
        alert('Update successful.');
        window.location.href='$pageOrigin';
        </script>";
    }

    if (isset($_POST['uploadItem'])) {
        $picture = "upload.png";
        $name = "";
        $price = "";
        $description = "";
        $itemCondition = "";
        $status = "";
        $mode = "Upload";
    } elseif (isset($_POST['editItem'])) {
        $itemID = $_POST['itemID'];
        $sql = "SELECT * FROM item WHERE itemID = '$itemID'";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $name = $result['name'];
        $price = $result['price'];
        $description = $result['description'];
        $itemCondition = $result['itemCondition'];
        $status = $result['status'];
        $picture = $result['media'];
        $mode = "Edit";
        $pageOrigin = $_POST['pageOrigin'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload item</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/marketplace/itemForm.css" />
</head>

<script>
    function imagePreview(event) {
        var preview = document.getElementById("preview");
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.onload = function() {
            URL.revokeObjectURL(preview.src);
        }
    }
</script>

<body>
    <h1><?= $mode ?> Item</h1>
    <form id="wrapper" method="post" enctype="multipart/form-data">
        <div id="image">
            <input id="imageUpload" type="file" onchange="imagePreview(event)" name="media" accept="image/jpeg, image/png, image/jpg" <?php if ($mode ==='Upload') { echo "required"; } ?> />
            <label for="imageUpload"><img id="preview" src="/RWDD/marketplace/picture/<?= $picture ?>" /></label>
        </div>

        <div class="text">
            <label>Item</label><br />
            <input type="text" name="name" placeholder="Item Name" value="<?= $name ?>" required/><br />
        </div>

        <div class="price">
            <label>Price</label><br />
            <input type="number" step="0.01" placeholder="RM 0.00" name="price" value="<?= $price ?>" required/><br /> 
        </div>

        <div class="text">
            <label>Description</label><br />
            <textarea name="description" placeholder="Short description about item..."><?= $description ?></textarea>
        </div>

        <div class="selection">
            <label>Condition</label><br/>
            <select name="condition" required>
                <option value="">--Select Condition--</option>
                <option value="New" <?php if ($itemCondition === 'New') { echo "selected"; } ?>>New</option>
                <option value="Used" <?php if ($itemCondition === 'Used') { echo "selected"; } ?>>Used</option>
                <option value="Fixed" <?php if ($itemCondition === 'Fixed') { echo "selected"; } ?>>Fixed</option>
            </select><br />
        </div>

        <div class="selection">
            <label>Status</label>
            <select name="status" required>
                <option value="">--Select Status--</option>
                <option value="Available" <?php if ($status === 'Available') { echo "selected"; } ?>>Available</option>
                <option value="Sold" <?php if ($status === 'Sold') { echo "selected"; } ?>>Sold</option>
            </select>
        </div>

        <?php
        if ($mode === 'Edit') {
            echo "<input type='hidden' name='itemID' value='$itemID' />";
            echo "<input type='hidden' name='pageOrigin' value='$pageOrigin' />";
        }
        ?>

        <div id="buttons">
            <input type="button" value="Cancel" onclick="window.location.href='<?php if($mode === 'Edit') { echo $pageOrigin; } else { echo '/RWDD/marketplace/marketplace.php?mode=manage'; } ?>'" />
            <input type="submit" value="<?= $mode ?>" name="<?= $mode ?>" />
        </div>
    </form>
</body>
</html>