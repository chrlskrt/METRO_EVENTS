<?php
    include_once("api.php");
    include_once("helper.php");

    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        global $eventsJSON;

        $events = getEventsData();
        $updatedEventsArr = array();

        foreach($events as $event){
            if ($event['id'] === intval($_POST['eventId'])){

                if (in_array($currentUser['id'], $event['upvotes'], true)){
                    $eventUpvotes = array();

                    foreach($event['upvotes'] as $uid){
                        if ($uid === $currentUser['id']){
                            continue;
                        }

                        $eventUpvotes[] = $uid;
                    }

                    $event['upvotes'] = $eventUpvotes;
                } else {
                    $event['upvotes'][] = $currentUser['id'];
                }
                
            }

            $updatedEventsArr[] = $event;
        }

        file_put_contents($eventsJSON, json_encode($updatedEventsArr, JSON_PRETTY_PRINT));
        
        header("Location: ../main/viewEvent.php?eventId=" . $_POST['eventId']);
        exit();
    }
?>