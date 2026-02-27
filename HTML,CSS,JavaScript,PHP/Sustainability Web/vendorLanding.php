<?php
    include $_SERVER['DOCUMENT_ROOT'].'/RWDD/setup/navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quest</title>
    <link rel="stylesheet" href="/RWDD/landing/landing.css" />
    <link rel="stylesheet" href="/RWDD/landing/vendorLanding.css" />
</head>

<body>
    <div id="wrapper">
        <h1>Quests</h1>
        <div id="questOption" onclick="window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage'">
            <div class="hat" style="background-color: #64CA2D"></div>
            <div id="quest" class="option" style="background-color: #F1FFE6">
                <img src="/RWDD/landing/icon/quest.png" />
                <p>Quests</p>
            </div>
        </div>

        <div id="rewardOption" onclick="window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=reward'">
            <div class="hat" style="background-color: #4043CE"></div>
            <div id="reward" class="option" style="background-color: #EEEFFA" >
                <img src="/RWDD/landing/icon/reward.png" />
                <p>Rewards</p>
            </div>
        </div>
    </div>
</body>
</html>