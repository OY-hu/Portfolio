<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['delete'])) {
        $ticketID = $_POST['ticketID'];
        $sql = "DELETE FROM ticket WHERE ticketID = '$ticketID';";
        mysqli_query($connect, $sql);

        echo "<script>
        alert('Delete successfully.');
        window.location.href='/RWDD/help/helpDesk.php?mode=pending';
        </script>";
    }

    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'pending':
                $style = ['#0A89F2', 'white'];
                break;
            case 'resolved':
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
    <title>Help Desk</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/help/helpDesk.css" />
</head>

<body>
    <h1>Help Desk</h1>
    <div id="wrapper">
        <div class="tab">
            <div class="option" style="background-color: <?= $style[0] ?>"><a href="/RWDD/help/helpDesk.php?mode=pending">Pending</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>"><a href="/RWDD/help/helpDesk.php?mode=resolved">Resolved</a></div>
        </div>

        <div class="create">
            <form action="/RWDD/help/ticketForm.php" method="post">
                <input type="submit" name="createTicket" value="+" />
            </form>
        </div>

        <div id="ticketArea">
            <?php
            $accountID = $_SESSION['accountID'];
            if ($_GET['mode'] === 'pending') {
                $sql = "SELECT * FROM ticket WHERE status='pending' AND accountID='$accountID'";
                $statement = mysqli_query($connect, $sql);
                while ($ticket = mysqli_fetch_array($statement)) {
                    $title = $ticket['title'];
                    $timestamp = $ticket['timestamp'];
                    $ticketID = $ticket['ticketID'];

                    echo "<form class='ticket' method='post'>";
                    echo "<div class='ticketText'>";
                    echo "<p><strong>$title</strong><br />$timestamp</p>";
                    echo "</div>";
                    echo "<input type='hidden' name='ticketID' value='$ticketID' />";
                    echo "<input type='submit' name='delete' value=' ' />";
                    echo "</form>";
                }
            } else {
                $sql = "SELECT * FROM ticket WHERE status != 'pending' AND accountID='$accountID'";
                $statement = mysqli_query($connect, $sql);
                while ($ticket = mysqli_fetch_array($statement)) {
                    $title = $ticket['title'];
                    $timestamp = $ticket['timestamp'];
                    $ticketID = $ticket['ticketID'];
                    $status = $ticket['status'];

                    echo "<div class='ticket' method='post'>";
                    echo "<div class='ticketText'>";
                    echo "<p><strong>$title</strong><br />$timestamp</p>";
                    echo "</div>";
                    echo "<div class='status'>$status</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>