<?php
    include __DIR__.'/../setup/navigation.php';

    $accountID = $_SESSION['accountID'];
    $sql = "SELECT vendorName FROM vendor WHERE accountID = '$accountID';";
    $vendor = mysqli_fetch_array(mysqli_query($connect, $sql));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Home Page</title>
    <link rel="stylesheet" href="/RWDD/landing/landing.css" />
    <link rel="stylesheet" href="/RWDD/landing/vendorLanding.css" />
</head>

<body>
    <div id="wrapper">
        <h1>Welcome back, <?= $vendor['vendorName'] ?>!</h1>
        <div id="questOption" onclick="window.location.href='/RWDD/quest/quest/questManagement.php?mode=manage'">
            <div class="hat" style="background-color: #64CA2D"></div>
            <div id="quest" class="option" style="background-color: #FFFFFF">
                <img src="/RWDD/landing/icon/quest.png" />
                <p>Quests</p>
            </div>
        </div>

        <div id="rewardOption" onclick="window.location.href='/RWDD/quest/reward/rewardManagement.php?mode=reward'">
            <div class="hat" style="background-color: #4043CE"></div>
            <div id="reward" class="option" style="background-color: #FFFFFF" >
                <img src="/RWDD/landing/icon/reward.png" />
                <p>Rewards</p>
            </div>
        </div>

        <div id="communityOption" onclick="window.location.href='/RWDD/community/community.php?mode=community'">
            <div class="hat" style="background-color: #E5D202"></div>
            <div id="community" class="option" style="background-color: #FFFFFF">
                <img src="/RWDD/landing/icon/community.png" />
                <p>Community</p>
            </div>
        </div>

        <div id="marketplaceOption" onclick="window.location.href='/RWDD/marketplace/marketplace.php?mode=marketplace'">
            <div class="hat" style="background-color: #97C25C"></div>
            <div id="marketplace" class="option" style="background-color: #FFFFFF">
                <img src="/RWDD/landing/icon/marketplace.png" />
                <p>Marketplace</p>
            </div>
        </div>
    </div>
</body>
</html>