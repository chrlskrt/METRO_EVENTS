<?php
    include_once("helper.php");

    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        global $eventsJSON;

        $events = getEventsData();
        $updatedEventsArr = array();

        foreach($events as $event){
            if ($event['id'] === intval($_POST['eventId'])){
                $updatedReviews = array();

                foreach($event['reviews'] as $review){
                    if ($review['id'] === intval($_POST['review_id'])){
                        continue;
                    }

                    $updatedReviews[] = $review;
                }

                $event['reviews'] = $updatedReviews;
            }

            $updatedEventsArr[] = $event;
        }

        file_put_contents($eventsJSON, json_encode($updatedEventsArr, JSON_PRETTY_PRINT));
        
        header("Location: ../main/viewEvent.php?eventId=" . $_POST['eventId']);
        exit();
    }
?>