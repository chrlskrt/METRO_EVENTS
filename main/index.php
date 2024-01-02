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
            <div class="heading he2">Home</div>
        </div>
        <div class="container">
            <?php
                echo getEvents();
            ?>    
        </div>
    </section>
    
</body>
</html>

<?php
    include_once("../helpers/helper.php");

    function getEvents(){
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
    
            $eventsarr[] = $event;
        }
    
    
        $str = "";
        foreach($eventsarr as $event){
            $date = toDisplayDate($event['date']);
            $time = toDisplayTime($event['time']);
    
           
            $str .= '<!-- start of event posts -->
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
                            <li class="list-group-item"><b>Number of participants</b>: '. count($event['participants']) .'</li>
                        </ul>
                        <div class="card-body" style="flex: none;">
                          <div class="eventBtnContainer">'.
                                (($event['status'] == 'complete') ? 
                                '<button type="button" class="btn btn-outline-dark" disabled>Event has PASSED</button>' : 
                                    '<form method="POST" action="../helpers/joinEvent.php">'.
                                        (($currentUser != null) ? ((in_array($currentUser, $event['participants'], false) || $currentUser['id'] === $event['organizerId']['id']) ?
                                            '<input style="display: none;" name="eventId" value="'. $event['id'] .'">
                                            <button type="button" class="btn btn-outline-dark" disabled>Joined event</button>' :
                                            '<input style="display: none;" name="eventId" value="'. $event['id'] .'">
                                            <button type="submit" class="btn btn-outline-dark">Join Event</button>') 
                                             : '') .
                                    '</form>')
                            .'<form method="GET" action="viewEvent.php">
                                <input style="display: none;" name="eventId" value="'.$event['id'].'">
                                <button type="submit" class="btn btn-dark">View More</button>
                            </form>
                          </div>
                        </div>
                    </div>
                </div>';
        }
        return $str;
    }
?>