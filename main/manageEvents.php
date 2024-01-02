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
            <div class="heading he2">Manage Events</div>
        </div>
        <div class="container">
            <?php
                echo displayEvents();
            ?>    
        </div>
    </section>
</body>
</html>

<?php
    function displayEvents(){
        global $currentUser;
        $users = getUsersData();

        $events = getEventsData();

        $eventsarr = array();

        foreach($events as $event){
            foreach($users as $user){
                if ($user['id'] == $event['organizerId']){
                    $event['organizerId'] = $user;
                    break;
                }
            }

            if ($currentUser['userType'] === 'administrator' || ($currentUser['userType'] === 'organizer' && $currentUser['id'] === $event['organizerId']['id'])){
                $eventsarr[] = $event;
            }
        }

        
        $str = "";
        foreach($eventsarr as $event){
            $date = toDisplayDate($event['date']);
            $time = toDisplayTime($event['time']);

        
            $str .= '
                <div class="eventPostContainer">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body" style="flex:none;">
                        <h3 class="card-title">' . $event['name'] . '</h3>
                        <h6 class="card-subtitle mb-1 text-muted">Organized by: '. $event['organizerId']['firstName'] . ' '. $event['organizerId']['lastName'] .'</h6>
                        <p class="card-text"> '. $event['detail'] .'</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Where</b>: '. $event['place'] .'</li>
                            <li class="list-group-item"><b>When</b>: '. $date . ' ' . $time .'</li>
                            <li class="list-group-item dropdown"><b>Participants</b> ( '. count($event['participants']) .' ) 
                                            <div class="list-group-item dropdown-options">';
                                                foreach($event['participants'] as $pa){
                                                    $str .= $pa['firstName'].' '.$pa['lastName'].'<br>';
                                                }
                                        $str .= '</div></li>
                        </ul>
                        <div class="card-body" style="flex: none;">
                        <div class="eventBtnContainer">'.
                                (($event['status'] != 'complete') ? 
                                    '<form method="POST" action="../helpers/manageEventsHandler.php" style="width:100%;">
                                            <input style="display: none;" name="eventName" value="'. $event['name'] .'">
                                            <input style="display: none;" name="eventId" value="'. $event['id'] .'">
                                            <div class="formsch" style="width: 100%;">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="reason" id="reason" placeholder="Enter reason for cancellation..." required>
                                                </div>
                                                <button type="submit" name="cancelEvent" class="btn btn-outline-dark">Cancel Event</button>
                                            </div>
                                    </form>': '')  
                            .'
                        </div>
                        </div>
                    </div>
                </div>';
        }

        return $str;
    }
?>