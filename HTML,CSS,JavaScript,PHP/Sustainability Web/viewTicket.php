<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_GET['status'])) {
        $ticketID = $_GET['ticket'];
        $status = $_GET['status'];
        $sql = "UPDATE ticket SET status='$status' WHERE ticketID = '$ticketID';";
        mysqli_query($connect, $sql);
    }

    if (isset($_GET['ticket'])) {
        $ticketID = $_GET['ticket'];
        $sql = "SELECT * FROM ticket WHERE ticketID = '$ticketID';";
        $statement = mysqli_query($connect, $sql);
        $ticket = mysqli_fetch_array($statement);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Ticket</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/help/ticketForm.css" />
</head>
<body>
    <h1>Help Desk</h1>
    <form id="wrapper" method="post">
        <div class="text">
            <label>Title of Issue</label><br />
            <input type="text" name="title" value="<?= $ticket['title'] ?>" readonly /><br />
        </div>

        <div class="text">
            <label>Describe the Issue</label><br />
            <textarea name="description" readonly><?= $ticket['issue'] ?></textarea>
        </div>

        <div id="proof">
            <img src="/RWDD/help/picture/<?= $ticket['image'] ?>" />
        </div>

        <div id="buttons">
            <input type="button" value="Back" onclick="window.location.href='/RWDD/help/manageTicket.php?mode=pending'"/>
            <select onchange="window.location.href='/RWDD/help/viewTicket.php?ticket=<?= $_GET['ticket'] ?>&status='+this.value">
                <option value="pending" <?php if($ticket['status'] === 'pending') { echo "selected"; } ?>>Pending</option>
                <option value="Resolved" <?php if($ticket['status'] === 'Resolved') { echo "selected"; } ?>>Resolved</option>
                <option value="Rejected" <?php if($ticket['status'] === 'Rejected') { echo "selected"; } ?>>Rejected</option>
            </select>
        </div>
</form>
</body>
</html>