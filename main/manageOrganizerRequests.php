<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


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
            <div class="heading he2">Manage Join Requests</div>
        </div>
        <div class="container">
            <?php
                echo displayOrgRequests();
            ?>    
        </div>
    </section>
</body>
</html>

<?php
    function displayOrgRequests(){
        global $currentUser;
        $requests = getOrganizerRequestData();

        $events = getEventsData();

        $requestarr = array();

        foreach($requests as $request){

            foreach($events as $event){
                if ($event['id'] == $request['event_id']){
                    $request['event_id'] = $event;
                    break;
                }
            }
            
            if ($request['event_id']['organizerId'] == $currentUser['id']){
                $requestarr[] = $request;
            }
        }

        $str = '';
        foreach($requestarr as $request){
            $str .= '
                <div class="manageParticipantRequest">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body" style="flex:none;">
                        <h3 class="card-title">REQUEST TO JOIN EVENT</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Event: '. $request['event_id']['name'] .'</li>
                            <li class="list-group-item">Username: '. $request['username'] .' </li>
                            <li class="list-group-item">First Name: '. $request['firstName'] .'</li>
                            <li class="list-group-item">Last Name: '. $request['lastName'] .' </li>
                        </ul>
                        <div class="card-body" style="flex: none;">
                        <div class="eventBtnContainer">
                            <form method="POST" action="../helpers/manageOrganizerRequestsHandler.php">
                                <input style="display: none;" name="requestId" value="'. $request['id'].'">
                                <input style="display: none;" name="eventId" value="'. $request['event_id']['id'].'">
                                <input style="display: none;" name="userId" value="'. $request['uid'].'">
                                <input style="display: none;" name="eventName" value="'. $request['event_id']['name'].'">
                                <button type="submit" name="acceptParticipantRequest" class="btn btn-outline-dark">ACCEPT</button>
                            </form>
                            <form method="POST" action="../helpers/manageOrganizerRequestsHandler.php">
                                <input style="display: none;" name="requestId" value="'. $request['id'].'">
                                <input style="display: none;" name="eventId" value="'. $request['event_id']['id'].'">
                                <input style="display: none;" name="userId" value="'. $request['uid'].'">
                                <input style="display: none;" name="eventName" value="'. $request['event_id']['name'].'">
                                <button type="submit" name="declineParticipantRequest" class="btn btn-dark">REJECT</button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            ';
        }

        return $str;
    }
?>