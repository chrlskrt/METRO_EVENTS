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
            <div class="heading he2">Requests to the Admin</div>
        </div>
        <div class="container">
            <?php
                echo displayAdminRequests();
            ?>    
        </div>
    </section>
</body>
</html>

<?php
    function displayAdminRequests(){
        $requests = getAdminRequestData();
        $requestarr = array();

        $users = getUsersData();

        foreach($requests as $request){
            foreach($users as $user){
                if ($request['uid'] === $user['id']){
                    $request['uid'] = $user;
                    break;
                }
            }

            $requestarr[] = $request;
        }

        $str = "";
        foreach($requestarr as $request){
            $str .= '
                <div class="manageUserRequest">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body" style="flex:none;">'.
                        (($request['requestType'] === 'promote_as_organizer') ? 
                            '<h3 class="card-title">REQUEST TO BECOME ORGANIZER</h5>' :
                            '<h3 class="card-title">REQUEST TO ADD EVENT</h5>')
                        .'</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Username</b>: '. $request['uid']['username'] .' </li>
                            <li class="list-group-item"><b>First Name</b>: '. $request['uid']['firstName'] .'</li>
                            <li class="list-group-item"><b>Last Name</b>: '. $request['uid']['lastName'] .' </li>'.
                            (($request['requestType'] === 'promote_as_organizer') ? 
                                '<li class="list-group-item"><b>Reason</b>: '. $request['details'] .' </li>' :
                                '<li class="list-group-item ">                   EVENT DETAILS <br>
                                                                <b>Name</b>: '. $request['details']['name'] .'<br>
                                                                <b>Description</b>: '. $request['details']['detail'].'<br>
                                                                <b>Date</b>: '. toDisplayDate($request['details']['date']) .'<br>
                                                                <b>Time</b>: '. toDisplayTime($request['details']['time']) .'<br>
                                                                <b>Venue</b>: '. $request['details']['place'] .'</li>')
                        .'</ul>
                        <div class="card-body" style="flex: none;">
                        <div class="eventBtnContainer">'.
                            (($request['requestType'] === 'promote_as_organizer') ? 
                            '<form method="POST" action="../helpers/manageAdminRequestsHandler.php">
                                <input style="display: none;" name="requestId" value="'. $request['id'].'">
                                <input style="display: none;" name="userId" value="'. $request['uid']['id'].'">
                                <button type="submit" name="promotion" value="accept" class="btn btn-outline-dark">ACCEPT</button>
                            </form>
                            <form method="POST" action="../helpers/manageAdminRequestsHandler.php">
                                <input style="display: none;" name="requestId" value="'. $request['id'].'">
                                <input style="display: none;" name="userId" value="'. $request['uid']['id'].'">
                                <button type="submit" name="promotion" value="reject" class="btn btn-dark">REJECT</button>
                            </form>' 
                            : 
                            '<form method="POST" action="../helpers/manageAdminRequestsHandler.php">
                                <input style="display: none;" name="requestId" value="'. $request['id'].'">
                                <input style="display: none;" name="userId" value="'. $request['uid']['id'].'">
                                <input style="display: none;" name="eventName" value="'. $request['details']['name'].'">
                                <button type="submit" name="event_approval" value="accept" class="btn btn-outline-dark">APPROVE</button>
                            </form>
                            <form method="POST" action="../helpers/manageAdminRequestsHandler.php">
                                <input style="display: none;" name="requestId" value="'. $request['id'].'">
                                <input style="display: none;" name="userId" value="'. $request['uid']['id'].'">
                                <input style="display: none;" name="eventName" value="'. $request['details']['name'].'">
                                <button type="submit" name="event_approval" value="reject" class="btn btn-dark">DECLINE</button>
                            </form>')
                        .'</div>
                        </div>
                    </div>
                </div>
            ';
        }

        return $str;
    }
?>