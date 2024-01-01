<?php
    include_once("helper.php");
    if ($_SERVER["REQUEST_METHOD"]  === "POST"){
        $notifsArr = getNotificationsData();
        $new_notif_id = getNewId($notifsArr);
        $new_notification = array(
            "id" => $new_notif_id,
            "notifType" => "",
            "targetId" => intval($_POST['userId']),
            "notifHeading" => "",
            "notifDetail" => ""
        );
        
        if (isset($_POST['promotion'])){
            $new_notification['notifType'] = 'organizer_request';

            if ($_POST['promotion'] === 'accept'){
                $new_notification['notifHeading'] = "YOU ARE NOW AN ORGANIZER !!!";
                $new_notification['notifDetail'] = 'We are happy to share that your request to become an event organizer has been granted.';

                promoteToOrganizer(intval($_POST['userId']));
            } else {
                $new_notification['notifHeading'] = "PROMOTION REJECTED";
                $new_notification['notifDetail'] = 'We are sorry to share that your request to become an event organizer has been rejected. Please try requesting again if you think you really deserved to be promoted.';
            }
        }

        if (isset($_POST['event_approval'])){
            $new_notification['notifType'] = 'event_request';

            if ($_POST['event_approval'] === 'accept'){
                $new_notification['notifHeading'] = "EVENT IS NOW POSTED !!!";
                $new_notification['notifDetail'] = 'We are happy to share that your event proposal for "<b>'. $_POST['eventName'].'</b>" has been accepted and the event is now posted for everyone to see.';
                
                addNewEvent(intval($_POST['requestId']));
            } else {
                $new_notification['notifHeading'] = "EVENT DECLINED :<<";
                $new_notification['notifDetail'] = 'We are sorry to share that your event proposal for "<b>'. $_POST['eventName'].'</b>" has been rejected.';
            }
        }

        addNewNotification($new_notification);
        deleteAdminRequest(intval($_POST['requestId']));
        header("Location: ../main/manageAdminRequests.php");
        exit();
    }
?>