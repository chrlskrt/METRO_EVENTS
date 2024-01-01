<?php
    include_once("api.php");
    date_default_timezone_set('Asia/Manila');
    function isExistingUser($username){
        $users = getUsersData();
        foreach($users as $user){
            if($user["username"] == $username){
                return $user["id"];
            }
        }

        return -1;  
    }

    function getNewId($array){
        if (count($array) === 0){
            return 1;
        }

        $lastElem = $array[count($array) - 1];

        return $lastElem['id'] + 1;
    }

    function toDisplayDate($strdate){
        $dateA = explode("-", $strdate);
        $date = $dateA[1] . '-' . $dateA[2] . '-' . $dateA[0];
        return $date;
    }

    function toDisplayTime($strtime){
        $timeA = explode(":", $strtime);
        $timeS = '';
        $time = '';
        if (intval($timeA[0]) >= 12){
            $timeS = ' PM';
            if (intval($timeA[0]) > 12){
                $timeA[0] = intval($timeA[0]) - 12;
            } 

            $time = $timeA[0] . ':';
        } else {
            $timeS = ' AM';
            $time = $timeA[0] . ':';
        }

        $time .= $timeA[1] . $timeS;

        return $time;
    }

    function getParticipantsId($eventId){
        $participantsId = array();

        $events = getEventsData();

        foreach($events as $event){
            if ($event['id'] === $eventId){
                
                foreach($event['participants'] as $p){
                    $participantsId[] = $p['id'];
                }

                $participantsId[] = $event['organizerId'];
                break;
            }
        }

        $requests = getOrganizerRequestData();
        foreach($requests as $request){
            if ($request['event_id'] === $eventId){
                $participantsId[] = $request['uid'];
            }
        }

        return $participantsId;
    }

?>