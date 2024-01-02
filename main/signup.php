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
        include("sidebar.php");
    ?>
    <section class="CreateNLog">
        <div>
            <p class="heading he2">Sign Up</p>
            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
                <div class="formsch">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name" required>
                        <label for="firstName">First Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name" required>
                        <label for="lastName">Last Name</label>
                    </div>

                    <div class="form-floating mb-3"> 
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>

                    <div class="form-floating mb-3"> 
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                
                    <div class="form-floating mb-3"> 
                        <input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="Confirm Password" required>
                        <label for="cpassword">Confirm Password</label>
                    </div>
            
                
                    <button type="submit" role="button" class="btn btn-outline-warning btn-lg btn-block">Sign in</button>
                </div>

                <?php
                    if (isset($_GET['password_error'])){
                        echo '<div class="alert alert-danger mt-3" role="alert">Password not matching.</div>';
                    }

                    if (isset($_GET['signup_error'])){
                        echo '<div class="alert alert-danger mt-3" role="alert">Sign-up failed. User already exists.</div>';
                    }
                ?>
            </form>
        </div>
    </section>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        if ($_POST['password'] === $_POST['cpassword']){
            if (handleSignUp()){
                header("Location: index.php");
                exit();
            }
        }   else {
            header("Location: ?password_error");
            exit();
            }

        header("Location: ?signup_error");
        exit();
    }
?>