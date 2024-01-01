<?php
    include_once("helper.php");
    if ($_SERVER["REQUEST_METHOD"]  === "POST"){
        $notifsArr = getNotificationsData();
        $new_notif_id = getNewId($notifsArr);
        $new_notification = array(
            "id" => $new_notif_id,
            "notifType" => "event_request",
            "targetId" => intval($_POST['userId']),
            "notifHeading" => "",
            "notifDetail" => ""
        );

        $requestForOrganizer = getOrganizerRequestData();
        if (isset($_POST['acceptParticipantRequest'])){
            addParticipant(intval($_POST['userId']), intval($_POST['eventId']));
            $new_notification['notifHeading'] = "PARTICIPATION GRANTED";
            $new_notification['notifDetail'] = 'Your request in participating the event "<b>'. $_POST['eventName'].'</b>" has been granted.';
        }

        if (isset($_POST['declineParticipantRequest'])){
            $new_notification['notifHeading'] = "PARTICIPATION REJECTED";
            $new_notification['notifDetail'] = 'Your request in participating the event "<b>'. $_POST['eventName'].'</b>" has been rejected.';
        }

        addNewNotification($new_notification);
        deleteOrganizerRequest(intval($_POST['requestId']));
        header("Location: ../main/manageOrganizerRequests.php");
        exit();
    }
?>