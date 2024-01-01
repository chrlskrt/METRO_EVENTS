<?php
session_start();
include_once("helper.php");

// users JSON
$usersJSON = '../data/users.json';

// events JSON
$eventsJSON = '../data/events.json';

// notifications JSON
$notificationsJSON = '../data/notifications.json';

// request forwarded to admin JSON
$adminRequestJSON = "../data/adminRequest.json";

// request forwarded to organizer JSON
$organizerRequestJSON = "../data/organizerRequest.json";

$currentUser = null;

// to store current user using the forum
if (isset($_SESSION["user"])){
    $currentUser = json_decode($_SESSION["user"], true);
}

// function get users from json
function getUsersData() {
    global $usersJSON;
    if (!file_exists($usersJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($usersJSON);
    return json_decode($data, true);
}

// function get events from json
function getEventsData() {
    global $eventsJSON;
    if (!file_exists($eventsJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($eventsJSON);
    return json_decode($data, true);
}

// function get notifications from json
function getNotificationsData() {
    global $notificationsJSON;
    if (!file_exists($notificationsJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($notificationsJSON);
    return json_decode($data, true);
}

// function get request for admin from json
function getAdminRequestData() {
    global $adminRequestJSON;
    if (!file_exists($adminRequestJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($adminRequestJSON);
    return json_decode($data, true);
}

// function get request for organizer from json
function getOrganizerRequestData() {
    global $organizerRequestJSON;
    if (!file_exists($organizerRequestJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($organizerRequestJSON);
    return json_decode($data, true);
}

// function to handle log-in
function handleLogIn(){
    $userId = isExistingUser($_POST['username']);

    if ($userId === -1){
        return 0; // user not found
    }

    $users = getUsersData();
    if($users[$userId - 1]['password'] == $_POST['password']){
        $_SESSION["user"] = json_encode($users[$userId - 1], true);
        return 1; // log in success
    }

    return -1; //password unmatch
}

// function to handle Sign Up
function handleSignUp(){
    if (isExistingUser($_POST["username"]) !== -1){
        return false; // already exists
    }

    global $usersJSON;
    $users = getUsersData();

    $new_id = getNewId($users);

    $users[] = array(
        "id" => $new_id,
        "userType" => "user",
        "firstName" => $_POST['firstName'],
        "lastName" => $_POST['lastName'],
        "username" => $_POST['username'],
        "password" => $_POST["password"],
    );
    

    file_put_contents($usersJSON, json_encode($users, JSON_PRETTY_PRINT));
    $_SESSION['user'] = json_encode($users[$new_id - 1], true);
    return true;
}

// add participant to event

function addParticipant($user_id, $event_id){
    global $eventsJSON;
    $events = getEventsData();
    $eventsarr = array();
    foreach($events as $event){
        if ($event['id'] === ($event_id)){

            $users = getUsersData();
            foreach($users as $user){
                if ($user['id'] === ($user_id)){
                    $event['participants'][] = $user;
                    break;
                }
            }
        }

        $eventsarr[] = $event;
    }

   file_put_contents($eventsJSON, json_encode($eventsarr, JSON_PRETTY_PRINT));
}


function deleteOrganizerRequest($requestId){
    global $organizerRequestJSON;
    $requests = getOrganizerRequestData();

    $updateOrgReq = array();

    foreach($requests as $request){
        if ($request['id'] === $requestId) {
            continue;
        }

        $updateOrgReq[] = $request;
    }

    file_put_contents($organizerRequestJSON, json_encode($updateOrgReq, JSON_PRETTY_PRINT));
}

function addNewNotification($new_notification){
    global $notificationsJSON;

    $notifs = getNotificationsData();

    $notifs[] = $new_notification;

    file_put_contents($notificationsJSON, json_encode($notifs, JSON_PRETTY_PRINT));
}

function promoteToOrganizer($user_id){
    global $usersJSON;

    $users = getUsersData();
    $userUpdateArr = array();

    foreach($users as $user){
        if ($user['id'] === $user_id){
            $user['userType'] = "organizer";
        }

        $userUpdateArr[] = $user;
    }

    file_put_contents($usersJSON, json_encode($userUpdateArr, JSON_PRETTY_PRINT));
}

function demoteToUser($user_id){
    global $usersJSON;

    $users = getUsersData();
    $userUpdateArr = array();

    foreach($users as $user){
        if ($user['id'] === $user_id){
            $user['userType'] = "user";
        }

        $userUpdateArr[] = $user;
    }

    file_put_contents($usersJSON, json_encode($userUpdateArr, JSON_PRETTY_PRINT));

}


function addNewEvent($request_id){
    global $eventsJSON;
    $events = getEventsData();

    $request = array();
    $requests = getAdminRequestData();
    foreach($requests as $requesti){
        if ($requesti['id'] === $request_id){
            $request = $requesti;
            break;
        }
    }

    $new_event_id = getNewId($events);
    $new_event = array(
        "id" => $new_event_id,
        "status" => "ongoing",
        "organizerId" => $request['uid'],
        "name" => $request['details']['name'],
        "detail" => $request['details']['detail'],
        "date" => $request['details']['date'],
        "time" => $request['details']['time'],
        "place" => $request['details']['place'],
        "upvotes" => [],
        "participants" => [],
        "reviews" => []
    );
    
    $events[] = $new_event;

    file_put_contents($eventsJSON, json_encode($events, JSON_PRETTY_PRINT));
    
}

function deleteEvent($event_id){
    global $eventsJSON;

    $events = getEventsData();

    $updateEventsArr = array();

    foreach($events as $event){
        if ($event['id'] === $event_id){
            continue;
        }

        $updateEventsArr[] = $event;
    }

    file_put_contents($eventsJSON, json_encode($updateEventsArr, JSON_PRETTY_PRINT));

    $requests = getOrganizerRequestData();
    foreach($requests as $request){
        if ($request['event_id'] === $event_id){
            deleteOrganizerRequest($request['id']);
        }
    }
}

function addNewAdminRequest($new_admin_request){
    if ($new_admin_request === null){
        return false;
    }
    global $adminRequestJSON;
    $requestsForAdmin = getAdminRequestData();
    $requestsForAdmin[] = $new_admin_request;
    file_put_contents($adminRequestJSON, json_encode($requestsForAdmin, JSON_PRETTY_PRINT));
    return true;
}
function deleteAdminRequest($requestId){
    global $adminRequestJSON;
    $requests = getAdminRequestData();

    $updateAdminReq = array();

    foreach($requests as $request){
        if ($request['id'] === $requestId) {
            continue;
        }

        $updateAdminReq[] = $request;
    }

    file_put_contents($adminRequestJSON, json_encode($updateAdminReq, JSON_PRETTY_PRINT));
}

?>