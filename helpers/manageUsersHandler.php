<?php
    include_once("helper.php");

    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        $notifsArr = getNotificationsData();
        $new_notif_id = getNewId($notifsArr);
        $new_notification = array(
            "id" => $new_notif_id,
            "notifType" => "",
            "targetId" => intval($_POST['user_id']),
            "notifHeading" => "",
            "notifDetail" => ""
        );

        if (isset($_POST['promote'])){
            $new_notification['notifType'] = "promotion";
            $new_notification['notifHeading'] = "YOU ARE NOW AN ORGANIZER !!!";
            $new_notification['notifDetail'] = "We are happy to share that you are promoted as an EVENT ORGANIZER.";

            $requestForAdmin = getAdminRequestData();
            foreach($requestForAdmin as $request){
                if ($request['uid'] === intval($_POST['user_id']) && $request['requestType'] === 'promote_as_organizer'){
                    deleteAdminRequest($request['id']);
                    break;
                }
            }

            promoteToOrganizer(intval($_POST['user_id']));
        }


        if (isset($_POST['demote'])){
            $new_notification['notifType'] = "demotion";
            $new_notification['notifHeading'] = "YOU HAVE BEEN DEMOTED TO AN ORDINARY USER :<<";
            $new_notification['notifDetail'] = "We are sorry to share that you have been demoted to an ordinary USER.";
            demoteToUser(intval($_POST['user_id']));
        }

        addNewNotification($new_notification);
        header("Location: ../main/manageUsers.php");
        exit();
    }
?>