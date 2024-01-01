<?php
    include_once("helper.php");

    if ($_SERVER["REQUEST_METHOD"]==="POST"){
        $notifsArr = getNotificationsData();
        $new_notif_id = getNewId($notifsArr);
        $new_notification = array(
            "id" => $new_notif_id,
            "notifType" => "event_management",
            "targetId" => getParticipantsId(intval($_POST['eventId'])),
            "notifHeading" => "",
            "notifDetail" => ""
        );

        if (isset($_POST['cancelEvent'])){
            $new_notification['notifHeading'] = 'HEY :< EVENT HAS BEEN CANCELLED :<';
            $new_notification['notifDetail'] = 'The event "<b>'. $_POST['eventName'].'</b>" has been cancelled.';

            deleteEvent(intval($_POST['eventId']));
        }

        addNewNotification($new_notification);
        header("Location: ../main/manageEvents.php");
        exit();
    }
?>