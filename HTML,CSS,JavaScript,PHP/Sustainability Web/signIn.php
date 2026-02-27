<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/connect.php';

    if (isset($_POST['signIn'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $remember = isset($_POST['remember']);

        $sql = "SELECT accountID, role FROM account WHERE username = '$username' AND password = '$password'";
        $statement = mysqli_query($connect, $sql);
        $user = mysqli_fetch_array($statement);

        if (!$user) {
            echo "<script>
            alert('Log in unsuccessful. Incorrect username or password.');
            window.location.href='/RWDD/index.php';
            </script>";
        } else {
            $_SESSION['accountID'] = $user['accountID'];
            $_SESSION['role'] = $user['role'];
            if ($remember) {
                setcookie('username', $username, time() + (86400 * 30), "/");
                setcookie('password', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('username', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }
        }

        switch ($_SESSION['role']) {
            case "Admin":
                echo "<script>window.location.href='/RWDD/landing/admin.php';</script>";
                break;
            case "Vendor":
                echo "<script>window.location.href='/RWDD/landing/vendor.php';</script>";
                break;
            case "Student":
                echo "<script>window.location.href='/RWDD/landing/studentLanding.php';</script>";
                break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/css/form.css" />
    <link rel="stylesheet" href="/RWDD/setup/signIn.css" />
</head>

<body>
    <div id="wrapper">
        <div id="leftSection">
            <img id="logo" src="/RWDD/setup/icon/logo2.png" /><br />
            <h1>Welcome to EcoQuest</h1>
            <p>Working our way to a greener future.</br/>Join us and make a change!</p>
        </div>
        
        <div id="rightSection">
            <form method="post">
                <h1>Sign In</h1>
                <div class="user">
                    <label>Username</label><br />
                    <input type="text" name="username" placeholder="Username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" required/>
                    <br />
                </div>

                <div class="password">
                    <label>Password</label> <br />
                    <input type="password" name="password" placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" required/>
                    <br />
                </div>

                <div id="remember">
                    <input type="checkbox" id="rememberBox" name="remember" />
                    <label for="rememberBox">Remember me?</label>
                </div>

                <input type="submit" name="signIn" value="Sign In" />
                <p>New student? Sign up <a href="/RWDD/profile/signUp.php">here</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>