<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Metro Events</title>
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
            <div class="heading he2">Host an Event!</div>
        </div>
        <section class="CreateNLog">
            <div>
                <form action="../helpers/createEventHandler.php" method="post">
                    <div class="formsch">
                        <div class="form-group">
                            <label for="eventName">Event Name </label>
                            <input type="text" class="form-control" name="eventName" id="eventName" placeholder="Enter event name..." required>
                        </div>

                        <div class="form-group">
                            <label for="eventDate">Date</label>
                            <input type="date" class="form-control" name="eventDate" id="eventDate" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="eventTime">Time</label>
                            <input type="time" class="form-control" name="eventTime" id="eventTime" min="07:00:00" max="22:00:00" required>
                        </div>

                        <div class="form-group">
                            <label for="eventVenue">Event Venue </label>
                            <input type="text" class="form-control" name="eventVenue" id="eventVenue" placeholder="Enter event venue..." required>
                        </div>

                        <div class="form-group">
                            <label for="eventDescription">Description</label>
                            <textarea class="form-control" name="eventDescription" id="eventDescription" style="height: 100px" placeholder="Enter event description..." required></textarea>
                        </div>

                        <button type="submit" role="button" class="btn btn-outline-success btn-lg btn-block">Submit event request</button>
                    </div>

                    <?php
                        if (isset($_GET['request_success'])){
                            echo '<div class="alert alert-danger mt-4" role="alert">Creation of Event is now being processed.</div>';
                        }

                        if (isset($_GET['request_error'])){
                            echo '<div class="alert alert-danger mt-3" role="alert">There seems to be an error in requesting for event creation.</div>';
                        }
                    ?>
                </form>
            </div>
        </section>
    </section>
</body>
</html>

