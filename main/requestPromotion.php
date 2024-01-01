<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel='stylesheet' href='styles.css'>
</head>
<body>
    <?php
        include_once("sidebar.php");
    ?>

    <section class="rightSide">
        <div class="pageTitle">
            <div class="heading he2">Become an Organizer!</div>
        </div>
        <section class="CreateNLog">
            <div>
                <?php
                    $requests = getAdminRequestData();

                    $not_requested = 'true';

                    foreach($requests as $request){
                        if ($request['uid'] === $currentUser['id'] && $request['requestType'] === 'promote_as_organizer'){
                            $not_requested = 'false';
                            break;
                        }
                    }

                    if ($not_requested === 'true'){
                        echo getRequestForm();
                    } else {
                        echo getCancelForm();
                    }
                ?>      
            </div>
        </section>
    </section>
</body>
</html>

<?php
    function getRequestForm(){
        $display = '
            <form action="requestPromotion.php" method="post">
                <div class="formsch">
                    <div class="form-group">
                        <label for="reasonForRequest">State your reason for promotion: </label>
                        <textarea class="form-control" name="reasonForRequest" id="reasonForRequest" placeholder="e.g. I want to hold an event for my bias in Seventeen." required></textarea>
                    </div>
                    <button type="submit" name="promote_as_organizer" role="button" class="btn btn-outline-dark btn-lg btn-block">Submit Request</button>
                </div>
            </form>
        ';

        return $display;
    }

    function getCancelForm(){
        $display = '
            <p style="font-size: 35px; text-align: center;">Your request to become an <b>ORGANIZER</b> is being processed at the moment.
                                If you wish to cancel your request, you may do so by clicking the cancel button below.</p>
            <form action="requestPromotion.php" method="post">
                <div class="formsch">
                    <button type="submit" name="cancelRequest" role="button" class="btn btn-outline-danger btn-lg btn-block">CANCEL REQUEST</button>
                </div>
            </form>
        ';

        return $display;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        global $adminRequestJSON;
        $requestForAdmin = getAdminRequestData();

        if (isset($_POST['promote_as_organizer'])){
            $new_request_id = getNewId($requestForAdmin);

            $new_request = array(
                "id" => $new_request_id,
                "requestType" => "promote_as_organizer",
                "uid" => $currentUser['id'],
                "details" => $_POST['reasonForRequest']
            );
    
            $requestForAdmin[] = $new_request;
        }

        if (isset($_POST['cancelRequest'])){
            foreach($requestForAdmin as $index => $request){

                if ($request['uid'] === $currentUser['id'] && $request['requestType'] === 'promote_as_organizer'){
                    unset($requestForAdmin[$index]);
                    break;
                }
            }
        }

        file_put_contents($adminRequestJSON, json_encode($requestForAdmin, JSON_PRETTY_PRINT));
        header("Location: requestPromotion.php");
        exit();
    }
?>