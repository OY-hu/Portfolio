<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';
    if (isset($_POST['delete'])) {
        $accountID = $_POST['confirmAccount'];

        $sql = "SELECT role FROM account WHERE accountID = '$accountID';";
        $statement = mysqli_query($connect, $sql);
        $role = mysqli_fetch_array($statement);
        $sql = "DELETE FROM account WHERE accountID = '$accountID'";
        $statement = mysqli_query($connect, $sql);

        echo "<script>
        alert('Deletion successful.');
        window.location.href='/RWDD/profile/userManagement.php';
        </script>";
    }

    if (isset($_POST['search'])) {
        $searchInput = trim($_POST['searchInput']);
        $sql = "SELECT CASE
                WHEN role = 'Admin' THEN a.username
                WHEN role = 'Vendor' THEN v.vendorName
                WHEN role = 'Student' THEN s.name
                END AS name, a.accountID
                FROM account a
                LEFT JOIN student s ON a.accountID = s.accountID
                LEFT JOIN vendor v ON a.accountID = v.accountID
                WHERE a.username LIKE '%$searchInput%' OR v.vendorName LIKE '%$searchInput%' OR s.name LIKE '%$searchInput%'";
        $tabStyle = ["white", "white", "white"];                                                                                                               
    } elseif (isset($_GET['filter'])) {
        switch ($_GET['filter']) {
            case 'admin':
                $sql = "SELECT username AS name, accountID FROM account WHERE role='Admin';";
                $tabStyle = ["#0A89F2", "white", "white"];
                $role = 'Admin';
                break;
            case 'vendor':
                $sql = "SELECT vendorName AS name, accountID FROM vendor";
                $tabStyle = ["white", "#0A89F2", "white"];
                $role = 'Vendor';
                break;
            case 'student':
                $sql = "SELECT name, accountID FROM student";
                $tabStyle = ["white", "white", "#0A89F2"];
                $role = 'Student';
                break;
        }
    } else {
        $sql = "SELECT username AS name, accountID FROM account WHERE role='Admin';";
        $tabStyle = ["#0A89F2", "white", "white"];
        $role = 'Admin';
    }

    $statement = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/profile/profileForm.css" />
    <link rel="stylesheet" href="/RWDD/profile/userManagement.css" />
</head>

<script>
    function confirmDelete(name, accountID) {
        document.getElementById("confirmMessage").innerHTML = "Confirm delete for user (" + name + ")";
        document.getElementById("confirmAccount").value = accountID;
        document.getElementById("confirmDelete").classList.toggle("open");
        return false;
    }
</script>

<body>
    <div id="wrapper">
        <h1>User Management</h1>
        
        <div class="tab">
            <div class="option" style="background-color:<?= $tabStyle[0] ?>"><a href="/RWDD/profile/userManagement.php?filter=admin">Admin</a></div>
            <div class="option" style="background-color:<?= $tabStyle[1] ?>"><a href="/RWDD/profile/userManagement.php?filter=vendor">Vendor</a></div>
            <div class="option" style="background-color:<?= $tabStyle[2] ?>"><a href="/RWDD/profile/userManagement.php?filter=student">Student</a></div>
        </div>

        <div class="create">
            <form action="/RWDD/profile/userForm.php" method="post">
                <input type="hidden" name="userRole" value="<?= $role ?>" />
                <input type="submit" name="createUser" value="+" formaction="/RWDD/profile/userForm.php" />
            </form>
        </div>

        <div class="search">
            <form action="" method="post">
                <input type="text" name="searchInput" placeholder="Search..." />
                <input type="submit" name="search" value="Search" />
            </form>
        </div>

        <div id="content">
            <?php
                while ($user = mysqli_fetch_array($statement)) {
                    $name = $user['name'];
                    $accountID = $user['accountID'];
                    echo "<div class='data'>
                    <p>$name</p>
                    <form method='post'>
                    <input type='hidden' name='accountID' value='$accountID' />
                    <button type='submit' name='editUser' class='editButton' value=' ' formaction='/RWDD/profile/userForm.php' formtarget='_blank'/>
                    <button type='submit' class='deleteButton' value=' ' onclick='return confirmDelete(\"$name\", \"$accountID\")'/>
                    </form>
                    </div>";
                }
            ?>
        </div>
    </div>

    <div id="confirmDelete">
        <form method="post">
            <label id="confirmMessage"></label><br />
            <input type="hidden" id="confirmAccount" name="confirmAccount" />
            <input type="button" id="cancelButton" value="Cancel" onclick="document.getElementById('confirmDelete').classList.toggle('open')" />
            <input type="submit" id="confirmButton" value="Confirm" name="delete" />
        </form>
    </div>
</body>
</html>