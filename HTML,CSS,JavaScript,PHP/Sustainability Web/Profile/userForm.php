<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';

    if (isset($_POST['create'])) {
        if ($_POST['tpNumber']) {
            $tpNumber = $_POST['tpNumber'];
            $sql = "SELECT studentID FROM student WHERE studentID='$studentID';";
            $statement = mysqli_query($connect, $sql);
            $studentIDCheck = mysqli_fetch_array($statement);
            if ($studentIDCheck) {
                echo "<script>
                alert('Student already has an account.');
                window.location.href='/RWDD/profile/userForm.php';
                </script>";
            }
        }

        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $password = trim($_POST['password']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];
        $text1 = mysqli_real_escape_string($connect, trim($_POST['text1']));
        $text2 = mysqli_real_escape_string($connect,trim($_POST['text2']));

        $sql = "SELECT username FROM account WHERE username = '$username';";
        $statement = mysqli_query($connect, $sql);
        $check = mysqli_fetch_array($statement);
        
        if ($check) {
            echo "<script>
            alert('Username taken please choose another.');
            window.location.href='/RWDD/profileuserManagement.php';
            </script>";
        }

        $sql = "INSERT INTO account (username, password, email, phone, picture, role)
                VALUES ('$username', '$password', '$email', '$phone', 'user.png', '$role');";
        $statement = mysqli_query($connect, $sql);

        $sql = "SELECT accountID FROM account WHERE username ='$username' AND password = '$password';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $accountID = $result['accountID'];
                
        switch ($role) {
            case 'Vendor':
                $sql = "INSERT INTO vendor (accountID, vendorName, managerName) VALUES ('$accountID', '$text1', '$text2');";
                $statement = mysqli_query($connect, $sql);
                break;
            case 'Student':
                $sql = "INSERT INTO student VALUES ('$tpNumber', '$accountID', '$text1', '$text2')";
                $statement = mysqli_query($connect, $sql);
                break;
        }

        echo "<script>
        alert('User created successfully. Redirecting back to user management.');
        window.location.href='/RWDD/profile/userManagement.php';
        </script>";
    }

    if (isset($_POST['edit'])) {
        $accountID = $_POST['accountID'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $text1 = $_POST['text1'];
        $text2 = $_POST['text2'];

        $sql = "SELECT username FROM account WHERE username = '$username' AND accountID != '$accountID';";
        $statement = mysqli_query($connect, $sql);
        $check = mysqli_fetch_array($statement);
        
        if ($check) {
            echo "<script>
            alert('Username taken please choose another.');
            window.location.href='/RWDD/profile/userManagement.php';
            </script>";
        }

        $sql = "UPDATE account
                SET username = '$username', password = '$password', email = '$email', phone = '$phone'
                WHERE accountID = '$accountID';";
        $statement = mysqli_query($connect, $sql);

        switch ($role) {
            case 'Student':
                $sql = "UPDATE student SET name = '$text1', course = '$text2' WHERE accountID = '$accountID';";
                $statement = mysqli_query($connect, $sql);
                break;
            case 'Vendor':
                $sql = "UPDATE vendor SET vendorName = '$text1', managerName = '$text2' WHERE accountID = '$accountID';";
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
        alert('User updated.');
        window.close();
        </script>";
    }

    if (isset($_POST['createUser'])) {
        $role = $_POST['userRole'];
        $username = '';
        $password = '';
        $phone = '';
        $email = '';
        $text1 = '';
        $text2 = '';
        $tpNumber = '';
        $picture = 'user.png';
        $mode = 'create';
        $buttonName = 'Create';
    } elseif (isset($_POST['editUser'])) {
        $accountID = $_POST['accountID'];
        $sql = "SELECT * FROM account WHERE accountID = '$accountID'";
        $statement = mysqli_query($connect, $sql);
        $account = mysqli_fetch_array($statement);

        $username = $account['username'];
        $password = $account['password'];
        $phone = $account['phone'];
        $email = $account['email'];
        $picture = $account['picture'];
        $role = $account['role'];

        switch ($role) {
            case 'Admin':
                $text1 = 'Profile';
                $text2 = '';
                break;
            case 'Vendor':
                $sql = "SELECT vendorName, managerName FROM vendor WHERE accountID = '$accountID';";
                $statement = mysqli_query($connect, $sql);
                $result = mysqli_fetch_array($statement);
                $text1 = $result['vendorName'];
                $text2 = $result['managerName'];
                break;
            case 'Student':
                $sql = "SELECT studentID, name, course FROM student WHERE accountID = '$accountID';";
                $statement = mysqli_query($connect, $sql);
                $result = mysqli_fetch_array($statement);
                $text1 = $result['name'];
                $text2 = $result['course'];
                $tpNumber = $result['studentID'];
                break;
        }

        $mode = 'edit';
        $buttonName = 'Edit';
    } 

    switch ($role) {
        case 'Admin':
            $text1Placeholder = 'Profile';
            $text2Placeholder = '';
            break;
        case 'Vendor':
            $text1Placeholder = 'Vendor Name';
            $text2Placeholder = 'Manager Name';
            break;
        case 'Student':
            $text1Placeholder = 'Student Name';
            $text2Placeholder = 'Course';
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
            <h1>User Profile</h1>
            <div id="topSection">
                <label id="profilePicture">
                    <span id="userPicture"><img id="picture" src="/RWDD/profile/picture/<?= $picture ?>" alt="user picture" /></span>
                    <span id="pictureInput"><img src="/RWDD/css/icon/camera.png" /></span>
                    <input type="file" name="picture" accept = "image/jpeg, image/png, image/jpg" onchange="imagePreview(event)" <?php if ($mode === 'create') { echo "disabled"; } ?>/>
                </label>
                <input type="text" id="text1" name="text1" placeholder="<?= $text1Placeholder ?>" value="<?= $text1 ?>" <?php if ($role === 'Admin') { echo "readonly"; } else { echo "required"; } ?> />
                <input type="text" id="text2" name="text2" placeholder="<?= $text2Placeholder ?>" value="<?= $text2 ?>" <?php if ($role === 'Admin') { echo "readonly"; } else { echo "required"; } ?> />
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
                        <input type="password" name="password" value="<?= $password ?>" placeholder="Password" required  />
                    </div>

                    <div class="phone" id="phone">
                        <label>Phone</label><br />
                        <input type="tel" name="phone" value="<?= $phone ?>" placeholder="Phone" required />
                    </div>

                    <div class="user" id="role">
                        <label>User Role</label><br />
                        <input type="text" name="role" value="<?= $role ?>" placeholder="<?= $role ?>" readonly />
                    </div>

                    <?php if ($role === 'Student') {
                        if ($mode === 'create') {
                            $status = 'required';
                        } else {
                            $status = 'readonly';
                        }
                        echo "<div class='user' id='tpNumber'>
                        <label>TP Number</label>
                        <input type='text' name='tpNumber' value='$tpNumber' placeholder='TP Number' $status />
                    </div>";
                    } ?>
                </div>
                <div class="button">
                    <?php
                        if ($mode === 'edit') {
                            echo "<input type='hidden' name='accountID' value='$accountID' />";
                        }
                    ?>
                    <input type="submit" name="<?= $mode ?>" value="<?= $buttonName ?>" />
                </div>
            </div>
        </form>
    </div>
</body>
</html>