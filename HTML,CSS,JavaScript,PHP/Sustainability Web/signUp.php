<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/connect.php';

    if (isset($_POST['signUp'])) {
        $studentID = trim($_POST['studentID']);
        $name = trim($_POST['name']);
        $course = trim($_POST['course']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $sql = "SELECT accountID FROM account WHERE username='$username';";
        $statement = mysqli_query($connect, $sql);
        $usernameCheck = mysqli_fetch_array($statement);
        $sql = "SELECT studentID FROM student WHERE studentID='$studentID';";
        $statement = mysqli_query($connect, $sql);
        $studentIDCheck = mysqli_fetch_array($statement);
        if ($studentIDCheck) {
            echo "<script>
            alert('Student already has an account.');
            window.location.href='/RWDD/profile/signUp.php';
            </script>";
        } elseif ($usernameCheck) {
            echo "<script>
            alert('The username you chose has been taken. Please choose another.');
            window.location.href='/RWDD/profile/signUp.php';
            </script>";
        }

        $sql = "INSERT INTO account (username, password, email, phone, picture, role)
                VALUES ('$username', '$password', '$email', '$phone', 'user.png', 'Student');";
        if (!mysqli_query($connect, $sql)) {
            die('Error: '.mysqli_error($connect));
        }
        
        $sql = "SELECT accountID FROM account WHERE username ='$username' AND password = '$password';";
        $statement = mysqli_query($connect, $sql);
        $result = mysqli_fetch_array($statement);
        $accountID = $result['accountID'];
        $sql = "INSERT INTO student VALUES ('$studentID', '$accountID', '$name', '$course');";
        if (!mysqli_query($connect, $sql)) {
            die('Error: '.mysqli_error($connect));
        }

        echo "<script>
        alert('Sign up successful. Bringing you back to sign in page.');
        window.location.href='/RWDD/index.php';</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/profile/profileForm.css" />
    <link rel="stylesheet" href="/RWDD/profile/signUp.css" />
</head>

<script>
    function displayMessage(title, message) {
        document.getElementById("title").innerHTML = title;
        document.getElementById("text").innerHTML = message;
        document.getElementById("tint").classList.toggle("open");
    }

    function validatePassword() {
        var password = document.getElementById("password");
        var confirm = document.getElementById("confirm");

        if (password.value === confirm.value) {
            return true;
        } else {
            displayMessage("Password Mismatch", "Password and confirm password does not match.");
            return false;
        }
    }
</script>

<body>
    <div id="wrapper">
        <form method="post" onsubmit="return validatePassword()">
            <h1>Get Started Now</h1>

            <div class="user">
                <label>TP Number</label><br />
                <input type="text" name="studentID" placeholder="TP012345" required />
                <br />
            </div>

            <div class="user">
                <label>Name</label><br />
                <input type="text" name="name" placeholder="Name" required />
                <br />
            </div>

            <div class="course">
                <label>Course</label><br />
                <input type="text" name="course" placeholder="Course" required />
                <br />
            </div>

            <div class="phone">
                <label>Phone</label><br />
                <input type="tel" name="phone" placeholder="+12345678" required />
                <br />
            </div>

            <div class="email">
                <label>Email</label><br />
                <input type="email" name="email" placeholder="example@gmail.com" required />
                <br />
            </div>

            <div class="user">
                <label>Username</label><br />
                <input type="text" name="username" placeholder="Username" required />
                <br />
            </div>

            <div class="password">
                <label>Password</label><br />
                <input type="password" id="password" name="password" placeholder="Password" required />
                <br />
            </div>

            <div class="password">
                <label>Confirm Password</label><br />
                <input type="password" id="confirm" name="confirm" placeholder="Confirm Password" required />
            <br />
            </div>

            <input type="submit" name="signUp" value="Sign Up" /><br />
            <p>Have an account? Sign in <a href="/RWDD/index.php">here</a></p>
        </form>
    </div>
    <div id="tint">
        <div id="message">
            <h3 id="title"></h3>
            <p id="text"></p>
            <button onclick="displayMessage()">OK</button>
        </div>
    </div>
</body>
</html>