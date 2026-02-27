<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['Create'])) {
        $title = mysqli_real_escape_string($connect, $_POST['title']);
        $caption = mysqli_real_escape_string($connect, $_POST['caption']);
        $accountID = $_SESSION['accountID'];

        $sql = "INSERT INTO content (accountID, media, title, caption) VALUES ($accountID, 'temp', '$title', '$caption');";
        mysqli_query($connect, $sql);
        $contentID = mysqli_insert_id($connect);

        if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
            $extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $file = $contentID . "-" . $accountID . "." . $extension;
            $temp = $_FILES['media']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);
        }

        $sql = "UPDATE content SET media = '$file' WHERE contentID = '$contentID'";
        mysqli_query($connect, $sql);

        echo "<script>
        alert('Post uploaded!');
        window.location.href='/RWDD/community/community.php?mode=manage';
        </script>";
    } 
    
    if (isset($_POST['Edit'])) {
        $pageOrigin = $_POST['pageOrigin'];
        $accountID = $_SESSION['accountID'];
        $contentID = $_POST['contentID'];
        $title = mysqli_real_escape_string($connect, $_POST['title']);
        $caption = mysqli_real_escape_string($connect, $_POST['caption']);

        $sql = "UPDATE content
                SET title = '$title', caption = '$caption'
                WHERE contentID = '$contentID';";
        mysqli_query($connect, $sql);

        if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
            $extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $file = $contentID . "-" . $accountID . "." . $extension;
            $temp = $_FILES['media']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);
            $sql = "UPDATE content SET media = '$file' WHERE contentID = '$contentID'";
            mysqli_query($connect, $sql);
        }

        echo "<script>
        alert('Update successful.');
        window.location.href='$pageOrigin';
        </script>";
    }

    if (isset($_POST['uploadContent'])) {
        $picture = "upload.png";
        $title = "";
        $caption = "";
        $mode = "Create";
    } elseif (isset($_POST['editPost'])) {
        $contentID = $_POST['contentID'];
        $sql = "SELECT * FROM content WHERE contentID = '$contentID'";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $picture = $result['media'];
        $title = $result['title'];
        $caption = $result['caption'];
        $mode = "Edit";
        $pageOrigin = $_POST['pageOrigin'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Content</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/community/contentForm.css" />
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
    <h1><?= $mode ?> Post</h1>
    <form id="wrapper" method="post" enctype="multipart/form-data">
        <div id="image">
            <input id="imageUpload" type="file" onchange="imagePreview(event)" name="media" accept="image/jpeg, image/png, image/jpg" <?php if ($mode ==='Create') { echo "required"; } ?> />
            <label for="imageUpload"><img id="preview" src="/RWDD/community/picture/<?= $picture ?>" /></label>
        </div>

        <div class="text">
            <label>Title</label><br />
            <input type="text" name="title" placeholder="Title" value="<?= $title ?>"/><br />
        </div>

        <div class="text">
            <label>Caption</label><br />
            <textarea name="caption" placeholder="Caption..."><?= $caption ?></textarea><br />
        </div>

        <?php
        if ($mode === 'Edit') {
            echo "<input type='hidden' name='contentID' value='$contentID' />";
            echo "<input type='hidden' name='pageOrigin' value='$pageOrigin' />";
        }
        ?>

        <div id="buttons">
            <input type="button" value="Cancel" onclick="window.location.href='<?php if ($mode === 'Edit') { echo $pageOrigin; } else { echo '/RWDD/community/community.php?mode=manage'; } ?>'" />
            <input type="submit" value="<?= $mode ?>" name="<?= $mode ?>" />
        </div>
    </form>
</body>
</html>