<?php
    include_once("api.php");
    include_once("helper.php");

    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        global $eventsJSON;

        $events = getEventsData();
        $updatedEventsArr = array();

        foreach($events as $event){
            if ($event['id'] === intval($_POST['eventId'])){
                $new_review_id = getNewId($event['reviews']);

                $new_review = array(
                    "id" => $new_review_id,
                    "uid" => $currentUser['id'],
                    "username" => $currentUser['username'],
                    "reviewBody" => $_POST['reviewBody']
                );

                $event['reviews'][] = $new_review;
            }

            $updatedEventsArr[] = $event;
        }

        file_put_contents($eventsJSON, json_encode($updatedEventsArr, JSON_PRETTY_PRINT));
        
        header("Location: ../main/viewEvent.php?eventId=" . $_POST['eventId']);
        exit();
    }
?>