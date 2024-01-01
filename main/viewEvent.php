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

        if($_SERVER["REQUEST_METHOD"] === "GET"){
            echo displayEvent(intval($_GET['eventId']));
        }
    ?>
</body>
</html>

<?php
    function displayEvent($event_id){
        global $currentUser;
        $events = getEventsData();
        $event = array();

        foreach($events as $e){
            if ($e['id'] === $event_id){
                $event = $e;
                break;
            }
        }

        $users = getUsersData();
        foreach($users as $user){
            if ($user['id'] == $event['organizerId']){
                $event['organizerId'] = $user;
                break;
            }
        }

        $date = toDisplayDate($event['date']);
        $time = toDisplayTime($event['time']);

        $display = '
            <div class= "container" style="flex-direction: column; flex-wrap: nowrap;">
                <div>
                    <a class="navv" href="index.php">❰ BACK</a>
                </div>
                <div class="viewEventContainer">
                    <div class="card-body eventContainer" style="flex:none;">
                        <h2 class="card-title">' . $event['name'] . '</h2>
                        <h6 class="card-subtitle mb-1 text-muted">Organized by: '. $event['organizerId']['firstName'] . ' '. $event['organizerId']['lastName'] .'</h6>
                        <p class="card-text"> '. $event['detail'] .'</p>
                        <p class="card-text">Where: '. $event['place'] .'</p>
                        <p class="card-text">When: '. $date . ' ' . $time .'</p>
                        <p class="card-text">Number of participants: '. count($event['participants']) .'</p>
                    </div>

                    <div class="eventBtnContainer">'.
                        (($event['status'] == 'complete') ? 
                            '<button type="button" class="btn btn-outline-dark" disabled>Event has PASSED</button>
                            <button type="button" class="btn btn-outline-dark" disabled> ⇧ '. $event['upvotes'] .'</button>
                            <button type="button" class="btn btn-outline-dark" disabled>Review/s : </button>' : 
                                '<form method="POST" action="../helpers/joinEvent.php">'.
                                    (($currentUser != null) ? ((in_array($currentUser, $event['participants'], false) || $currentUser['id'] === $event['organizerId']['id']) ?
                                        '<input style="display: none;" name="eventId" value="'. $event['id'] .'">
                                        <button type="button" class="btn btn-outline-dark" disabled>Joined event</button>' :
                                        '<input style="display: none;" name="eventId" value="'. $event['id'] .'">
                                        <input style="display: none;" name="fromView" value="viewEvent.php?eventId='. $event['id'] .'">
                                        <button type="submit" class="btn btn-outline-dark">Join Event</button>') 
                                        : '') .
                                '</form>'.
                                '<form method="POST" action="../helpers/updateVote.php">'. 
                                    (($currentUser != null) ? 
                                        '<input style="display: none;" name="eventId" value="'. $event['id'] .'">
                                        <button type="submit" class="btn '. ((in_array($currentUser['id'], $event['upvotes'], true)) ? 'btn-dark' : 'btn-outline-dark').'"> ⇧ '. count($event['upvotes']) .'</button>'
                                    : '<button type="button" class="btn btn-outline-dark" disabled> ⇧ '. count($event['upvotes']) .'</button>')
                                .'</form>') 
                                .'<form>
                                    <button type="button" class="btn btn-outline-dark" disabled> Review/s : '. count($event['reviews']) .'</button>
                                </form>
                    </div>'.
                    
                    (($currentUser != null) ?
                        '<div class="createReviewContainer">
                            <form action="../helpers/createReview.php" method="post">
                                <div class="formsch" style="width: 100%;">
                                    <div class="form-group">
                                        <label for="reviewBody">Write Review: </label>
                                        <textarea class="form-control" name="reviewBody" id="reviewBody" placeholder="e.g. Looking forward!"></textarea>
                                    </div>
                                    <button type="submit" name="eventId" value="'.$event['id'].'" class = "btn btn-dark">Submit Review</button>
                                </div>
                            </form> 
                        </div>'
                        :
                        '').
                    (getReviews($event['reviews'], $event['id']))  
                .'</div>
            </div>
        ';


        return $display;
    }

    function getReviews($reviews, $event_id){
        global $currentUser;
        $reviews = array_reverse($reviews);
        $str = '<div style="display: flex; flex-direction: column;">';
    
        foreach($reviews as $review){
            $str .= '
                <div class="reviewContainer" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap:10px;">
                        <div class="p-2" style="display: flex; align-items: center">
                            <img src="https://ui-avatars.com/api/?rounded=true&name=' . $review['username'] . '" alt="" class="profile-photo-sm">
                        </div>
                        <div class="reviewContent">
                            <h5>' . $review['username'] . '</h5>
                            <p style="height: fit-content">' . $review['reviewBody'] . '</p>
                        </div>
                    </div>
                    '.
                        (($currentUser['id'] === $review['uid']) ? '
                            <div>
                                <form method="POST" action="../helpers/deleteReview.php">
                                    <input style="display: none;" name="review_id" value="'. $review['id'].'">
                                    <button type="submit" name="eventId" value="'.$event_id.'" class="btn btn-outline-dark">🗑️</button>
                                </form>
                            </div>
                        ': '')
                    .' 
                </div>
            ';
        }
    
        $str .= '</div>';
        return $str;
    }
    
?>