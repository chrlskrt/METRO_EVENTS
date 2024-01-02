<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Metro Events</title>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel='stylesheet' href='styles.css'>
</head>
<body>
    <?php
        include_once("sidebar.php");
    ?>
    
    <section class="rightSide">
        <div class="pageTitle">
            <div class="heading he2">Notifications</div>
        </div>
        <div class="notifContainer">
            <?php
                echo displayNotifs();
            ?>
        </div>
    </section>
    
</body>
</html>

<?php
    function displayNotifs(){
        global $currentUser;
        $notifs = getNotificationsData();

        $notifsarr = array();

        foreach($notifs as $notif){
            if (is_array($notif['targetId'])){
                if (in_array($currentUser['id'], $notif['targetId'], true)){
                    $notifsarr[] = $notif;
                }
            } elseif ($notif['targetId'] === $currentUser['id']) {
                $notifsarr[] = $notif;
            }
        }

        $notifsarr = array_reverse($notifsarr);

        $display = '';

        foreach($notifsarr as $notif){
            $display .= '
                <div class="card" style="width:100%; height: fit-content;">
                    <div class="card-body" style="flex:none;">
                        <h5 class="card-title">'. $notif['notifHeading'] .'</h5>
                        <p class="card-text">'. $notif['notifDetail'] .'</p>
                    </div>
                </div>
            ';
        }

        return $display;
    }
?>