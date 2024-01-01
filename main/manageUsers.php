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
            <div class="heading he2">Manage Users</div>
        </div>
        <div class="container">
            <?php
                echo displayUsers();
            ?>    
        </div>
    </section>
</body>
</html>

<?php
    function displayUsers(){
        $users = getUsersData();

        $usersarr = array();

        foreach($users as $user){
            if ($user['userType'] === 'administrator'){
                continue;
            }

            $usersarr[] = $user;
        }

        $display = '';

        foreach($usersarr as $user){
            $display .= '
                <div class="manageUserContainer">
                    <div class="card" style="width: 20rem;">
                        <div class="card-body" style="flex:none;">
                            <h3 class="card-title">'.$user['username'].'</h3>
                            <h6 class="card-subtitle mb-1 text-muted" style="text-align: center;">'. $user['userType'] .'</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>ID</b>: '. $user['id'] .'</li>
                            <li class="list-group-item"><b>First Name</b>: '. $user['firstName'] .'</li>
                            <li class="list-group-item"><b>Last Name</b>: '. $user['lastName'] .'</li>
                        </ul>
                        <div class="card-body" style="flex: none;">
                            <div class="eventBtnContainer">
                                <form method="POST" action="../helpers/manageUsersHandler.php">
                                    <input style="display: none;" name="user_id" value="'. $user['id'].'">
                                    <button type="submit" name="promote" class="btn btn-outline-dark" '.(($user['userType'] === 'organizer') ? 'disabled' : '').' >PROMOTE TO ORGANIZER</button>
                                </form>
                                <form method="POST" action="../helpers/manageUsersHandler.php">
                                    <input style="display: none;" name="user_id" value="'. $user['id'].'">
                                    <button type="submit" name="demote" class="btn btn-outline-dark" '.(($user['userType'] === 'user') ? 'disabled' : '').' >DEMOTE TO USER</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        
        return $display;
    }
?>