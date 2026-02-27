<?php
    include __DIR__.'/../setup/navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
    <link rel="stylesheet" href="/RWDD/css/layout.css" />
    <link rel="stylesheet" href="/RWDD/landing/admin.css" />
</head>

<body>
    <div id="wrapper">
        <h1>Welcome back, Admin</h1>
        <div class="adminList"><a href="/RWDD/profile/userManagement.php">User Management</a></div>
        <div class="adminList"><a href="/RWDD/quest/points/studentPoints.php?mode=points">Student Monitoring</a></div>
        <div class="adminList"><a href="/RWDD/landing/mediaMonitoring.php?mode=community">Media Monitoring</a></div>
        <div class="adminList"><a href="/RWDD/help/manageTicket.php?mode=pending">Help Desk</a></div>
    </div>
</body>
</html>