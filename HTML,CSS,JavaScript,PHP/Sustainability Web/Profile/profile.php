<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';
    $accountID = $_SESSION['accountID'];

    if (isset($_POST['save'])) {
        $text1 = $_POST['text1'];
        $text2 = $_POST['text2'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $sql = "SELECT username FROM account WHERE username = '$username' AND accountID != '$accountID';";
        $statement = mysqli_query($connect, $sql);
        $check = mysqli_fetch_array($statement);

        if ($check) {
            echo "<script>
            alert('Username taken please choose another.');
            window.location.href='/RWDD/profile/profile.php';
            </script>";
        }

        $sql = "UPDATE account
                SET username = '$username', email = '$email', phone = '$phone'
                WHERE accountID = '$accountID';";
        $statement = mysqli_query($connect, $sql);

        $sql = "SELECT role FROM account WHERE accountID = '$accountID';";
        $statement = mysqli_query($connect, $sql);
        $check = mysqli_fetch_array($statement);

        switch($check['role']) {
            case 'Student':
                $sql = "UPDATE student
                        SET name = '$text1', course = '$text2'
                        WHERE accountID = '$accountID';";
                $statement = mysqli_query($connect, $sql);
                break;
            case 'Vendor':
                $sql = "UPDATE vendor
                        SET vendorName = '$text1', managerName = '$text2'
                        WHERE accountID = '$accountID';";
                $statement = mysqli_query($connect, $sql);
                break;
        }

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $sql = "SELECT username FROM account WHERE accountID = '$accountID';";
            $statement = mysqli_query($connect, $sql);
            $result = mysqli_fetch_array($statement);
            
            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $file = $accountID . "-" . $result['username'] . "." . $extension;
            $temp = $_FILES['picture']['tmp_name'];
            $path = "picture/" . $file;
            move_uploaded_file($temp, $path);

            $sql = "UPDATE account SET picture = '$file' WHERE accountID = '$accountID';";
            $statement = mysqli_query($connect, $sql);
        }

        echo "<script>
        alert('Profile updated.');
        </script>";
    }

    if (isset($_POST['changePassword'])) {
        $oldPassword = trim($_POST['oldPassword']);
        $sql = "SELECT password FROM account WHERE accountID = '$accountID';";
        $statement = mysqli_query($connect, $sql);
        $check = mysqli_fetch_array($statement);

        $newPassword = trim($_POST['newPassword']);
        $confirmPassword = trim($_POST['confirmPassword']);

        if ($newPassword === $confirmPassword && $oldPassword === $check['password']) {
            $sql = "UPDATE account SET password = '$newPassword' WHERE accountID='$accountID';";
            $statement = mysqli_query($connect, $sql);
            echo "<script>
            alert('Password change successful.');
            </script>";
        } else {
            echo "<script>
            alert('Password change fail.');
            </script>";
        }
    }

    $title = 'My Profile';
    $sql = "SELECT * FROM account WHERE accountID='$accountID';";
    $statement = mysqli_query($connect, $sql);
    $account = mysqli_fetch_array($statement);
    $username = $account['username'];
    $password = $account['password'];
    $email = $account['email'];
    $phone = $account['phone'];
    $picture = $account['picture'];
    $role = $account['role'];

    switch($role) {
        case 'Admin':
            $text1 = $username;
            $text2 = 'Admin';
            break;
        case 'Vendor':
            $sql = "SELECT vendorName, managerName FROM vendor WHERE accountID='$accountID';";
            $statement = mysqli_query($connect, $sql);
            $personal = mysqli_fetch_array($statement);
            $text1 = $personal['vendorName'];
            $text2 = $personal['managerName'];
            break;
        case 'Student':
            $sql = "SELECT studentID, name, course FROM student WHERE accountID='$accountID';";
            $statement = mysqli_query($connect, $sql);
            $personal = mysqli_fetch_array($statement);
            $text1 = $personal['name'];
            $text2 = $personal['course'];
            $tpNumber = $personal['studentID'];
            break;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/profile/profileForm.css" />
    <link rel="stylesheet" href="/RWDD/profile/profile.css" />
</head>

<script>
    function imagePreview(event) {
        var preview = document.getElementById('picture');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.onload = function() {
            URL.revokeObjectURL(preview.src);
        }
    }
</script>

<body>
    <div id="wrapper">
        <form action="" method="post" enctype="multipart/form-data">
            <h1><?= $title ?></h1>
            <div id="topSection">
                <label id="profilePicture">
                    <span id="userPicture"><img id="picture" src="/RWDD/profile/picture/<?= $picture ?>" alt="user picture" /></span>
                    <span id="pictureInput"><img src="/RWDD/css/icon/camera.png" /></span>
                    <input type="file" name="picture" accept = "image/jpeg, image/png, image/jpg" onchange="imagePreview(event)" />
                </label>
                <input type="text" id="text1" name="text1" value="<?= $text1 ?>" <?php if ($role === 'Admin') { echo "readonly"; } else { echo "required"; } ?> />
                <input type="text" id="text2" name="text2" value="<?= $text2 ?>" <?php if ($role === 'Admin') { echo "readonly"; } else { echo "required"; } ?> />
            </div>

            <h3>Personal Information</h3>
            <div id="bottomSection">
                <div id="personal">
                    <div class="user" id="username">
                        <label>Username</label><br />
                        <input type="text" name="username" value="<?= $username ?>" placeholder="Username" required/>
                    </div>

                    <div class="email" id="email">
                        <label>Email</label><br />
                        <input type="email" name="email" value="<?= $email ?>" placeholder="Email" required />
                    </div>

                    <div class="password" id="password">
                        <label>Password</label><br />
                        <input type="password" name="password" value="<?= $password ?>" placeholder="Password" disabled  />
                    </div>

                    <div class="phone" id="phone">
                        <label>Phone</label><br />
                        <input type="tel" name="phone" value="<?= $phone ?>" placeholder="Phone" required />
                    </div>

                    <div class="user" id="role">
                        <label>User Role</label><br />
                        <input type="text" name="role" value="<?= $role ?>" placeholder="Role" disabled />
                    </div>

                    <?php if ($role === 'Student') {
                        echo "<div class='user' id='tpNumber'>
                        <label>TP Number</label>
                        <input type='text' name='tpNumber' value='$tpNumber' disabled/>
                    </div>";
                    } ?>
                </div>
                <div class="button">
                    <input type="button" value="Change Password" onclick="document.getElementById('changePassword').classList.toggle('open')" />
                    <input type="submit" class="save" name="save" value="Save" />
                </div>
            </div>
        </form>
    </div>

    <div id="changePassword">
        <form method="post">
            <h3>Change Password</h3>
            <div class="password">
                <label>Old Password</label>
                <input type="password" name="oldPassword" placeholder="Old Password" required />
                <br />
            </div>

            <div class="password">
                <label>New Password</label>
                <input type="password" name="newPassword" placeholder="New Password" required />
                <br />
            </div>

            <div class="password">
                <label>Confirm New Password</label>
                <input type="password" name="confirmPassword" placeholder="Confirm New Password" required />
                <br />
            </div>

            <div class="button">
                <input type="button" value="Cancel" onclick="document.getElementById('changePassword').classList.toggle('open')" />
                <input type="submit" name="changePassword" value="Save" class="save" />
            </div>
        </form>
    </div>
</body>
</html>