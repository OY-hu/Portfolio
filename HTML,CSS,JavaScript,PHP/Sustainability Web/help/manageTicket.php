<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

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
            <div class="option" style="background-color: <?= $style[0] ?>"><a href="/RWDD/help/manageTicket.php?mode=pending">Pending</a></div>
            <div class="option" style="background-color: <?= $style[1] ?>"><a href="/RWDD/help/manageTicket.php?mode=resolved">Resolved</a></div>
        </div>

        <div id="ticketArea">
            <?php
            if ($_GET['mode'] === 'pending') {
                $sql = "SELECT t.ticketID, t.title, t.timestamp, a.username, a.email, a.phone
                        FROM ticket t
                        LEFT JOIN account a ON t.accountID = a.accountID
                        WHERE t.status='pending'";
            } else {
                $sql = "SELECT t.ticketID, t.title, t.timestamp, t.status, a.username, a.email, a.phone
                        FROM ticket t 
                        LEFT JOIN account a ON t.accountID = a.accountID
                        WHERE t.status!='pending'";
            }
            $statement = mysqli_query($connect, $sql);
            while ($ticket = mysqli_fetch_array($statement)) {
                $title = $ticket['title'];
                $timestamp = $ticket['timestamp'];
                $ticketID = $ticket['ticketID'];
                $username = $ticket['username'];
                $email = $ticket['email'];
                $phone = $ticket['phone'];

                if ($_GET['mode'] === 'pending') {
                    $clickable = "onclick='window.location.href=\"/RWDD/help/viewTicket.php?ticket=$ticketID\"'";
                } else {
                    $clickable = "";
                }

                echo "<div class='ticket' $clickable>";
                echo "<div class='ticketText'>";
                echo "<p><strong>$title from $username</strong><br />$timestamp, $email, $phone</p>";
                echo "</div>";
                if ($_GET['mode'] === 'resolved') {
                    $status = $ticket['status'];
                    echo "<div class='status'>$status</div>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>