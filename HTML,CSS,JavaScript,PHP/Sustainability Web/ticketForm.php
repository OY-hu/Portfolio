<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if(isset($_POST['submit'])) {
        $title = mysqli_real_escape_string($connect, $_POST['title']);
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $accountID = $_SESSION['accountID'];

        $sql = "INSERT INTO ticket (accountID, title, issue, status)
                VALUES ('$accountID', '$title', '$description', 'pending')";
        mysqli_query($connect, $sql);
        $ticketID = mysqli_insert_id($connect);

        if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
            $extension = pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION);
            $file = $ticketID . "-" . $accountID . "." . $extension;
            $temp = $_FILES['screenshot']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);

            $sql = "UPDATE ticket SET image = '$file' WHERE ticketID = '$ticketID';";
            mysqli_query($connect, $sql);
        }

        echo "<script>
        alert('Ticket submitted successfully.');
        window.location.href='/RWDD/help/helpDesk.php?mode=pending';
        </script>";
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
    <form id="wrapper" method="post" enctype="multipart/form-data">
        <div class="text">
            <label>Title of Issue</label><br />
            <input type="text" name="title" placeholder="Title" required /><br />
        </div>

        <div class="text">
            <label>Describe the Issue</label><br />
            <textarea placeholder="Description..." name="description" required></textarea>
        </div>

        <div id="proof">
            <label>Screenshot</label><br />
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="screenshot" /><br />
        </div>

        <div id="buttons">
            <input type="button" value="Cancel" onclick="window.location.href='/RWDD/help/helpDesk.php?mode=pending'"/>
            <input type="submit" value="Submit" name="submit" />
        </div>
</form>
</body>
</html>