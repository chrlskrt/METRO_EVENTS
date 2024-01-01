<?php
    include_once("../helpers/api.php");
?>

<section id="sidebar">
  <div>
      <p class="heading">Metro Events</p>
  </div>
            
  <?php
    if (!$currentUser){
      echo '<div class="navBar">
          <section>
          </section>

          <section>
            <a href="login.php" class="btn btn-outline-success btn-block">Log In</a>
            <a href="signup.php" class="btn btn-warning btn-block">Sign Up</a>
          </section>
      </div>';
    } else {
      echo '<div class="navBar">
          <section>
              <a href="index.php" class="navv">命 Home</a>
              <a href="notifications.php" class="navv">🕭 Notifications</a>';

              if($currentUser['userType'] === 'user'){
                echo '<a href="requestPromotion.php" class="navv">✎ Become an Organizer!</a>';
              }

              if ($currentUser['userType'] != 'user'){
                echo '<a href="createEvent.php" class="navv">✎ Create Event</a>';
                echo '<a href="manageEvents.php" class="navv">⎙ Manage Events</a>';
                echo '<a href="manageOrganizerRequests.php" class="navv">ਏਓ₊˚ Manage Event Join Requests</a>';
              }
              
              if ($currentUser['userType'] == 'administrator'){
                echo '<a href="manageAdminRequests.php" class="navv">ਏਓ₊˚ Manage Requests</a>';
                echo '<a href="manageUsers.php" class="navv">ꔫ Manage Users</a>';
              }
      echo '</section>

          <section>
              <button type="button" class="btn btn-outline-dark btn-block">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                      <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                      <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>
                  </svg>
                  '.$currentUser['username'].'
              </button>

              <a href="logout.php" class="btn btn-dark btn-block">Log Out</a>
          </section>
      </div>';
    }
  ?>
</section>