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

        // getting event from events JSON
        $events = getEventsData();
        $event = array();

        foreach($events as $e){
            if ($e['id'] === intval($_POST['eventId'])){
                $event = $e;
                break;
            }
        }

        // creating a notification for the event organizer 
        // user is requesting to join the event
        $notifsArr = getNotificationsData();
        $new_notif_id = getNewId($notifsArr);
        $new_notification = array(
            "id" => $new_notif_id,
            "notifType" => "event_join_request",
            "targetId" => $event['organizerId'],
            "notifHeading" => "A USER WANTS TO JOIN YOUR EVENT!",
            "notifDetail" => '<b>'.$currentUser['firstName'].' '. $currentUser['lastName'].'</b> wants to join <b>'.$event['name'].'</b>. Check it out on the <b> Manage Event Join Requests </b> tab.'
        );

        addNewNotification($new_notification);
    } 


    if (isset($_POST['fromView'])){
        header("Location: ../main/".$_POST['fromView']);
        exit();
    }

    header("Location: ../main/index.php");
    exit();
?>