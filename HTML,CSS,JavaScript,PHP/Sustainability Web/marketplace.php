<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['delete'])) {
        $itemID = $_POST['confirmitem'];
        $sql = "DELETE FROM item WHERE itemID = '$itemID';";
        mysqli_query($connect, $sql);
        echo "<script>
        alert('Delete successfully.');
        window.location.href='/RWDD/marketplace/marketplace.php?mode=manage';
        </script>";
    }

    if(isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'marketplace':
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
    <meta name="viewport" item="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/marketplace/marketplace.css" />
</head>

<script>
    function confirmDelete(itemID) {
        document.getElementById("confirmMessage").innerHTML = "Do you really want to delete this post?";
        document.getElementById("confirmitem").value = itemID;
        document.getElementById("confirmDelete").classList.toggle("open");
        return false;
    }

    function viewPost(event, itemID, mode) {
        if (event.target.value === 'Delete') {
            confirmDelete(itemID);
        } else if (event.target.tagName.toLowerCase() === 'input') {
            return;
        } else {
            window.location.href='/RWDD/marketplace/marketplace.php?mode=' + mode + '&item=' + itemID;
        }
    }

    function back(event, mode) {
        if (event.target.id === 'preview') {
            window.location.href='/RWDD/marketplace/marketplace.php?mode=' + mode;
        }
    }
</script>

<body>
    <div id="wrapper">
        <h1>Marketplace</h1>
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>"><a href="/RWDD/marketplace/marketplace.php?mode=marketplace">Marketplace</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>"><a href="/RWDD/marketplace/marketplace.php?mode=manage">My Uploads</a></div>
        </div>

        <?php
        if ($_GET['mode'] == 'manage') {
            echo "<div class='create'>";
            echo "<form method='post' action='/RWDD/marketplace/itemForm.php'>";
            echo "<input type='submit' value='+' name='uploadItem' />";
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

        <div id="itemArea">
            <?php
            if ($_GET['mode'] === 'marketplace' && isset($_GET['search'])) {
                $searchInput = $_GET['search'];
                $sql = "SELECT i.itemID, i.media
                        FROM item i
                        LEFT JOIN account a ON i.accountID = a.accountID
                        LEFT JOIN student s ON i.accountID = s.accountID
                        LEFT JOIN vendor v ON i.accountID = v.accountID
                        WHERE i.name LIKE '%$searchInput%' OR i.description LIKE '%$searchInput%'
                        OR s.name LIKE '%$searchInput%' OR v.vendorName LIKE '%$searchInput%'
                        AND i.status = 'Available';";
            } elseif ($_GET['mode'] === 'marketplace') {
                $sql = "SELECT itemID, media FROM item WHERE status = 'Available';";
            } elseif ($_GET['mode'] === 'manage' && isset($_GET['search'])) {
                $accountID = $_SESSION['accountID'];
                $searchInput = $_GET['search'];
                $sql = "SELECT itemID, media FROM item
                        WHERE name LIKE '%$searchInput%' OR description LIKE '%$searchInput%' AND accountID = '$accountID'";
            } else {
                $accountID = $_SESSION['accountID'];
                $sql = "SELECT itemID, media FROM item WHERE accountID = '$accountID'";
            }
            
            $mode = $_GET['mode'];
            $statement = mysqli_query($connect, $sql);
            while ($item = mysqli_fetch_array($statement)) {
                $picture = $item['media'];
                $itemID = $item['itemID'];
                echo "<form class='item' method='post' onclick=\"return viewPost(event, '$itemID', '$mode')\">";
                echo "<img src='/RWDD/marketplace/picture/$picture' />";
                echo "<input type='hidden' value='/RWDD/marketplace/marketplace.php?mode=manage' name='pageOrigin' />";
                echo "<input type='hidden' value='$itemID' name='itemID' />";
                if ($_GET['mode'] === 'manage') {
                    echo "<div class='buttons'>";
                    echo "<input type='submit' value='Edit' name='editItem' formaction = '/RWDD/marketplace/itemForm.php' />";
                    echo "<input type='button' value='Delete' />";
                    echo "</div>";
                }
                echo "</form>";
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_GET['item'])) {
        $mode = $_GET['mode'];
        $itemID = $_GET['item'];
        $sql = "SELECT i.*, a.username, a.email, a.phone
                FROM item i
                LEFT JOIN account a ON i.accountID = a.accountID
                WHERE i.itemID = '$itemID';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);

        $media = $result['media'];
        $name = $result['name'];
        $price = $result['price'];
        $description = $result['description'];
        $itemCondition = $result['itemCondition'];
        $status = $result['status'];
        $username = $result['username'];
        $email = $result['email'];
        $phone = $result['phone'];

        echo "<div id = 'preview' onclick='back(event, \"$mode\")'>";
        echo "<div id='post'>";
        echo "<div id='image'>";
        echo "<img src='/RWDD/marketplace/picture/$media' />";
        echo "</div>";
        echo "<p id='title'>$name</p>";
        echo "<p id='owner'>
            Posted by $username, RM $price, $itemCondition, $status <br />
            <a href='mailto:$email'>$email</a><br />
            <a href='tel:$phone'>$phone</a><br />
            </p>";
        echo "<p id='caption'>$description</p>";
        echo "</div>";
        echo "</div>";
    }
    ?>

    <div id="confirmDelete">
        <form method="post">
            <label id="confirmMessage"></label><br />
            <input type="hidden" id="confirmitem" name="confirmitem" />
            <input type="button" id="cancelButton" value="Cancel" onclick="document.getElementById('confirmDelete').classList.toggle('open')" />
            <input type="submit" id="confirmButton" value="Confirm" name="delete" />
        </form>
    </div>
</body>
</html>