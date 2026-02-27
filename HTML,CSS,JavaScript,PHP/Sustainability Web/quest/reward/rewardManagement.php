<?php
    /**Includes the navigation panel.*/
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    $accountID = $_SESSION['accountID'];
    $sql = "SELECT vendorID FROM vendor WHERE accountID = $accountID;";
    $result = mysqli_fetch_array(mysqli_query($connect, $sql));
    $vendorID = $result['vendorID'];

    /**Delete selected reward.*/
    if (isset($_POST['deleteReward'])) {
        $rewardID = $_POST['rewardID'];
        $sql = "DELETE FROM reward WHERE rewardID = $rewardID;";
        mysqli_query($connect, $sql);

        echo "<script>
        alert('Reward deleted successfully.');
        window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=reward';
        </script>";
    }

    /** Process for updating the redeem reward status for the selected redeem request. */
    if (isset($_GET['redeem'])) {
        $redeemID = $_GET['redeem'];
        $status = $_GET['status'];
        $sql = "UPDATE redeem SET status = '$status' WHERE redeemID = '$redeemID';";
        mysqli_query($connect, $sql);

        if ($status === 'Rejected') {
            $sql = "SELECT r.points, q.studentID
                    FROM redeem q LEFT JOIN reward r ON q.rewardID = r.rewardID WHERE q.redeemID = '$redeemID';";
            $result = mysqli_fetch_array(mysqli_query($connect, $sql));
            $points = $result['points'];
            $studentID = $result['studentID'];

            $sql = "INSERT INTO history (studentID, description, points, flagStatus)
                    VALUES ('$studentID', 'Points returned.', $points, 'notFlag');";
            if (!mysqli_query($connect, $sql)) {
                die('Error'.mysqli_error($connect));
            }
        }
        echo "<script>window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=request';</script>";
    }

    /**Determines which tab is open and set the highlight colour.*/
    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'reward':
                $style = ['#0A89F2', 'white'];
                break;
            case 'request':
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
    <title>Reward Management</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/quest/reward/reward.css" />
    <link rel="stylesheet" href="/RWDD/quest/reward/rewardManagement.css" />
</head>

<script>
    /** Function for previewing the reward selected. */
    function previewReward(event, rewardID) {
        /** Checks if the vendor is clicking on the delete button. */
        if (event.target.value === 'Delete') {
            fetch ('/RWDD/quest/reward/rewardData.php?rewardID=' + rewardID)
            .then(response => response.json())
            .then(data => {
                document.getElementById('message').innerHTML = "You are about to delete reward (" + data.title + "). Please confirm you want to delete this reward.";
                document.getElementById('rewardID').value = data.rewardID;
            });
            document.getElementById('confirmDelete').style.display = 'grid';
        } else if (event.target.value != 'Edit') {  /** If not clicking on edit button, it means the user wants to view the reward details. */
            fetch ('/RWDD/quest/reward/rewardData.php?rewardID=' + rewardID)
            .then(response => response.json())
            .then(data => {
                document.getElementById('imagePreview').src = "/RWDD/quest/reward/picture/" + data.image;
                document.getElementById('rewardTitle').innerHTML = data.title;
                document.getElementById('rewardStock').innerHTML = "Stock: " + data.stock;
                document.getElementById('rewardPoints').innerHTML = data.points;
                document.getElementById('rewardDescription').innerHTML = data.description;
            });
            document.getElementById('tint').style.display = 'flex';
        }
    }
</script>

<body>
    <div id="wrapper">
        <h1>Reward Management</h1>

        <!-- Tab to switch between rewards and redeem request. -->
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>" ><a href="/RWDD/quest/reward/rewardManagement.php?mode=reward">Manage Reward</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>" ><a href="/RWDD/quest/reward/rewardManagement.php?mode=request">Redeem Request</a></div>
        </div>

        <!-- Display is the mode is Reward Management. -->
        <?php if ($_GET['mode'] === 'reward') { ?>
        <!-- Create Button - Only appears when in reward management. -->
        <div class="create">
            <form method="post">
                <input type="submit" name="createReward" value="+" formaction="/RWDD/quest/reward/rewardForm.php" />
            </form>
        </div>
        
        <!-- Search bar only appears when in reward management. -->
        <div class="search">
            <form action="/RWDD/quest/reward/rewardManagement.php?mode=reward" method="get">
                <input type="hidden" name="mode" value="reward" />
                <input type="text" name="search" placeholder="Search..." />
                <input type="submit" value="Search" />
            </form>
        </div>

        <!-- Display the reward items. -->
        <div id="reward">
            <?php
            /** SQL code to retrieve the reward information based on if there was a search input or not. */
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT * FROM reward
                        WHERE title LIKE '%$search%' OR description LIKE '%$search%'
                        AND vendorID = $vendorID
                        ORDER BY stock ASC;";
            } else {
                $sql = "SELECT * FROM reward WHERE vendorID = $vendorID
                        ORDER BY stock ASC;";
            }

            /** Retrieve information about each reward. */
            $statement = mysqli_query($connect, $sql);
            while ($reward = mysqli_fetch_array($statement)) {
                $rewardID = $reward['rewardID'];
                $title = $reward['title'];
                $image = $reward['image'];
                $points = $reward['points'];
                $stock = $reward['stock'];
                if ($stock < 5) {
                    $background = "#FFD9D9";
                } else {
                    $background = "white";
                }
            ?>
            <!-- Display the reward item. -->
            <form class="item" style="background-color: <?= $background ?>" method="post" onclick="previewReward(event, <?=$rewardID?>)">
                <input type="hidden" name="rewardID" value="<?= $rewardID ?>" /> 
                <div class="imageArea" style="background-color: #000000"><img src="/RWDD/quest/reward/picture/<?= $image ?>" /></div>
                <div class="details">
                    <h4><?= $title ?></h4>
                    <p>Stock: <?= $stock ?></p>
                    <div class="points">
                        <img src="/RWDD/quest/icon/quest.png" />
                        <p><?= $points ?></p>
                    </div>
                </div>
                <div class="buttons">
                    <input type="submit" name="editReward" value="Edit" style="background-color: #000000" formaction="/RWDD/quest/reward/rewardForm.php" />
                    <input type="button" value="Delete" style="background-color: #F45252;" />
                </div>
            </form>
            <?php } ?>
        </div>
        <?php } else { ?>
        <!-- Tab for redeem reward request. -->
        <div id="redeem">
            <?php
            $sql = "SELECT q.*, r.*, s.*, a.*
                    FROM redeem q 
                    LEFT JOIN reward r ON q.rewardID = r.rewardID
                    LEFT JOIN vendor v ON r.vendorID = v.vendorID
                    LEFT JOIN student s ON q.studentID = s.studentID
                    LEFT JOIN account a ON a.accountID = s.accountID
                    WHERE r.vendorID = '$vendorID'
                    ORDER BY FIELD(q.status, 'Pending', 'Completed', 'Rejected');";
            $statement = mysqli_query($connect, $sql);
            while ($request = mysqli_fetch_array($statement)) {
                switch ($request['status']) {
                    case 'Pending': 
                        $selectStatus = ['selected', '', ''];
                        $style = '#FFEB6B';
                        $editable = '';
                        break;
                    case 'Completed':
                        $selectStatus = ['', 'selected', ''];
                        $style = '#0A89F2';
                        $editable = 'disabled';
                        break;
                    case 'Rejected':
                        $selectStatus = ['', '', 'selected'];
                        $style = '#0A89F2';
                        $editable = 'disabled';
                        break;
                }
            ?>
            <div class="request">
                <div class="details">
                    <h4><?= $request['title'] ?></h4>
                    <p><?= $request['name'] ?>, <?= $request['email'] ?>, <?= $request['phone'] ?><br /><?= $request['timestamp'] ?></p>
                </div>
                <select class="status" 
                style = "background-color: <?= $style ?>"
                onchange="window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=request&redeem=<?= $request['redeemID'] ?>&status=' + this.value"
                <?= $editable ?>>
                    <option value="Pending" <?= $selectStatus[0] ?>>Pending</option>
                    <option value="Completed" <?= $selectStatus[1] ?>>Completed</option>
                    <option value="Rejected" <?= $selectStatus[2] ?>>Rejected</option>
                </select>
            </div>
            <?php } ?>
        </div>
        <?php  } ?>
    </div>

    <!-- Pop up for delete confirmation. -->
    <form id="confirmDelete" method="post" action="/RWDD/quest/reward/rewardManagement.php">
        <div id="messageArea">
            <p id="message"></p>
            <div class="buttons">
                <input type="hidden" id="rewardID" name="rewardID" />
                <input type="submit" name="deleteReward" value="Delete" style="background-color: #F45252" />
                <input type="button" value="Cancel" style="background-color: #000000" onclick="document.getElementById('confirmDelete').style.display = 'none'"  />
            </div>
        </div>
    </form>

    <!-- Pop up to display full details about selected reward. -->
    <div id="tint" onclick="document.getElementById('tint').style.display = 'none'">
        <div id="rewardDetail" style="background-color: white">
            <div class="imageArea" style="background-color: #000000"><img id="imagePreview" /></div>
            <div class="details">
                <h4 id="rewardTitle"></h4>
                <p id="rewardStock"></p>
                <div class="points">
                    <img src="/RWDD/quest/icon/quest.png" />
                    <p id="rewardPoints"></p>
                </div>
                <p id="rewardDescription"></p>
            </div>
        </div>
    </div>
</body>
</html>

<!-- Close the connection to the database in this page. -->
<?php mysqli_close($connect) ?>