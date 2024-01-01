<?php
    include_once("helper.php");

    global $organizerRequestJSON;

    $requestForOrganizer = getOrganizerRequestData();

    $new_request_id = getNewId($requestForOrganizer);
    $not_requested = 'true';

    foreach($requestForOrganizer as $request){
        if ($request['event_id'] == intval($_POST['eventId']) && 
            $request['uid'] == $currentUser['id']){
                $not_requested = 'false';
                break;
        }
    }

    if ($not_requested === 'true'){
        $new_request = array(
            "id" => $new_request_id,
            "event_id" => intval($_POST['eventId']),
            "uid" => $currentUser['id'],
            "username" => $currentUser['username'],
            "firstName" => $currentUser['firstName'],
            "lastName" => $currentUser['lastName']
        );

        $requestForOrganizer[] = $new_request;
        file_put_contents($organizerRequestJSON, json_encode($requestForOrganizer, JSON_PRETTY_PRINT));
    }

    if (isset($_POST['fromView'])){
        header("Location: ../main/".$_POST['fromView']);
        exit();
    }

    header("Location: ../main/index.php");
    exit();
?>