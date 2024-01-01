<?php
    include_once("helper.php");
    if ($_SERVER["REQUEST_METHOD"]==="POST"){
        global $adminRequestJSON;
        $requestsForAdmin = getAdminRequestData();

        $new_request_id = getNewId($requestsForAdmin);
        
        $new_request = array(
            "id" => $new_request_id,
            "requestType" => "post_event",
            "uid" => $currentUser['id'],
            "details" => array(
                "name" => $_POST['eventName'],
                "detail"=> $_POST['eventDescription'],
                "date" => $_POST['eventDate'],
                "time" => $_POST['eventTime'],
                "place" => $_POST['eventVenue']
            )
        );

        if (addNewAdminRequest($new_request)){
            header("Location: ../main/createEvent.php?request_success");
            exit();
        }

        header("Location: ../main/createEvent.php?request_error");
        exit();
    }
?>