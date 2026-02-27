<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['delete'])) {
        $contentID = $_POST['confirmContent'];
        $sql = "DELETE FROM content WHERE contentID = '$contentID';";
        mysqli_query($connect, $sql);
        echo "<script>
        alert('Delete successfully.');
        window.location.href='/RWDD/community/community.php?mode=manage';
        </script>";
    }

    if(isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'community':
                $style = ['#0A89F2', 'white'];
                break;
            case 'manage':
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
    <title>Community</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/community/community.css" />
</head>

<script>
    function confirmDelete(contentID) {
        document.getElementById("confirmMessage").innerHTML = "Do you really want to delete this post?";
        document.getElementById("confirmContent").value = contentID;
        document.getElementById("confirmDelete").classList.toggle("open");
        return false;
    }

    function viewPost(event, contentID, mode) {
        if (event.target.value === 'Delete') {
            confirmDelete(contentID);
        } else if (event.target.tagName.toLowerCase() === 'input') {
            return;
        } else {
            window.location.href='/RWDD/community/community.php?mode=' + mode + '&content=' + contentID;
        }
    }

    function back(event, mode) {
        if (event.target.id === 'preview') {
            window.location.href='/RWDD/community/community.php?mode=' + mode;
        }
    }
</script>

<body>
    <div id="wrapper">
        <h1>Community</h1>
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>"><a href="/RWDD/community/community.php?mode=community">Community</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>"><a href="/RWDD/community/community.php?mode=manage">My Uploads</a></div>
        </div>

        <?php
        if ($_GET['mode'] == 'manage') {
            echo "<div class='create'>";
            echo "<form method='post' action='/RWDD/community/contentForm.php'>";
            echo "<input type='submit' value='+' name='uploadContent' />";
            echo "</form>";
            echo "</div>";
        }
        ?>

        <div class="search">
            <form method="get">
                <input type="hidden" name="mode" value="<?= $_GET['mode'] ?>" />
                <input type="text" placeholder="Search..." name="search" />
                <input type="submit" value="Search" />
            </form>
        </div>

        <div id="contentArea">
            <?php
            if ($_GET['mode'] === 'community' && isset($_GET['search'])) {
                $searchInput = $_GET['search'];
                $sql = "SELECT c.contentID, c.media
                        FROM content c
                        LEFT JOIN account a ON c.accountID = a.accountID
                        LEFT JOIN student s ON c.accountID = s.accountID
                        LEFT JOIN vendor v ON c.accountID = v.accountID
                        WHERE c.title LIKE '%$searchInput%' OR c.caption LIKE '%$searchInput%'
                        OR s.name LIKE '%$searchInput%' OR v.vendorName LIKE '%$searchInput%';";
            } elseif ($_GET['mode'] === 'community') {
                $sql = "SELECT contentID, media FROM content;";
            } elseif ($_GET['mode'] === 'manage' && isset($_GET['search'])) {
                $accountID = $_SESSION['accountID'];
                $searchInput = $_GET['search'];
                $sql = "SELECT contentID, media FROM content
                        WHERE title LIKE '%$searchInput%' OR caption LIKE '%$searchInput%' AND accountID = '$accountID'";
            } else {
                $accountID = $_SESSION['accountID'];
                $sql = "SELECT contentID, media FROM content WHERE accountID = '$accountID'";
            }
            
            $mode = $_GET['mode'];
            $statement = mysqli_query($connect, $sql);
            while ($content = mysqli_fetch_array($statement)) {
                $picture = $content['media'];
                $contentID = $content['contentID'];
                echo "<form class='content' method='post' onclick=\"return viewPost(event, '$contentID', '$mode')\">";
                echo "<img src='/RWDD/community/picture/$picture' />";
                echo "<input type='hidden' value='$contentID' name='contentID' />";
                echo "<input type='hidden' name='pageOrigin' value='/RWDD/community/community.php?mode=manage' />";
                if ($_GET['mode'] === 'manage') {
                    echo "<div class='buttons'>";
                    echo "<input type='submit' value='Edit' name='editPost' formaction = '/RWDD/community/contentForm.php' />";
                    echo "<input type='button' value='Delete' />";
                    echo "</div>";
                }
                echo "</form>";
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_GET['content'])) {
        $mode = $_GET['mode'];
        $contentID = $_GET['content'];
        $sql = "SELECT c.*, a.username
                FROM content c
                LEFT JOIN account a ON c.accountID = a.accountID
                WHERE c.contentID = '$contentID';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);

        $media = $result['media'];
        $title = $result['title'];
        $caption = $result['caption'];
        $username = $result['username'];
        $time = $result['timestamp'];

        echo "<div id = 'preview' onclick='back(event, \"$mode\")'>";
        echo "<div id='post'>";
        echo "<div id='image'>";
        echo "<img src='/RWDD/community/picture/$media' />";
        echo "</div>";
        echo "<p id='title'>$title</p>";
        echo "<p id='owner'>Posted by $username at $time</p>";
        echo "<p id='caption'>$caption</p>";
        echo "</div>";
        echo "</div>";
    }
    ?>

    <div id="confirmDelete">
        <form method="post">
            <label id="confirmMessage"></label><br />
            <input type="hidden" id="confirmContent" name="confirmContent" />
            <input type="button" id="cancelButton" value="Cancel" onclick="document.getElementById('confirmDelete').classList.toggle('open')" />
            <input type="submit" id="confirmButton" value="Confirm" name="delete" />
        </form>
    </div>
</body>
</html>