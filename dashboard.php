<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

$loggedin_userid = $_SESSION['userid'];

include 'conn.php';


if (!isset($_SESSION['fname'])) {
    $sql_fetch_fname = "SELECT fname FROM username WHERE nameid = $loggedin_userid";
    $result_fname = $conn->query($sql_fetch_fname);
    if ($result_fname->num_rows > 0) {
        $row_fname = $result_fname->fetch_assoc();
        $_SESSION['fname'] = $row_fname['fname'];
    }

}

// Handle the like button click event
if (isset($_POST['like_user_id'])) {
    $liked_user_id = $_POST['like_user_id'];

    // Check if the like already exists
    $sql_check_like = "SELECT * FROM likes WHERE sender_id = $loggedin_userid AND receiver_id = $liked_user_id";
    $result_check_like = $conn->query($sql_check_like);

    if ($result_check_like->num_rows == 0) {
        // Insert into likes table with status 'Pending'
        $sql_insert_like = "INSERT INTO likes (sender_id, receiver_id, status) VALUES ($loggedin_userid, $liked_user_id, 'Pending')";
        $conn->query($sql_insert_like);

        

        // Add a notification for the liked user
        $notification_text = "{$_SESSION['fname']} liked you! Accept or reject?";
        $sql_add_notification = "INSERT INTO notifications (recipient_id, sender_id, notification_text) VALUES ($liked_user_id, $loggedin_userid, '$notification_text')";
        $conn->query($sql_add_notification);
    }else {
        // Delete the like if it already exists (unlike functionality)
        $sql_delete_like = "DELETE FROM likes WHERE sender_id = $loggedin_userid AND receiver_id = $liked_user_id";
        $conn->query($sql_delete_like);

        // Remove the notification
        $sql_delete_notification = "DELETE FROM notifications WHERE sender_id = $loggedin_userid AND recipient_id = $liked_user_id AND notification_text = '{$_SESSION['fname']} liked you! Accept or reject?'";
        $conn->query($sql_delete_notification);
    }
}

// Handle accept/reject button click event
if (isset($_POST['notification_id']) && isset($_POST['action'])) {
    $notification_id = $_POST['notification_id'];
    $action = $_POST['action'];

    // Get the sender_id and recipient_id from notifications
    $sql_get_notification = "SELECT sender_id, recipient_id FROM notifications WHERE notification_id = $notification_id";
    $result_notification = $conn->query($sql_get_notification);
    $notification = $result_notification->fetch_assoc();
    $sender_id = $notification['sender_id'];
    $recipient_id = $notification['recipient_id'];

    if ($action == 'accept') {
        // Update the likes table with status 'Accepted'
        $sql_update_like = "UPDATE likes SET status = 'Accepted' WHERE sender_id = $sender_id AND receiver_id = $recipient_id";
        $conn->query($sql_update_like);

        // Check if the other user also liked back with status 'Accepted'
        $sql_check_like_back = "SELECT * FROM likes WHERE sender_id = $recipient_id AND receiver_id = $sender_id AND status = 'Accepted'";
        $result_check_like_back = $conn->query($sql_check_like_back);

        $sql_insert_friend1 = "INSERT INTO friends (user_id1, user_id2) VALUES ($sender_id, $recipient_id)";
        $conn->query($sql_insert_friend1);

        $sql_insert_friend2 = "INSERT INTO friends (user_id1, user_id2) VALUES ($recipient_id, $sender_id)";
        $conn->query($sql_insert_friend2);

        // Add a notification for the liked back user
        $notification_text = "{$_SESSION['fname']} liked you back!";
        $sql_add_notification = "INSERT INTO notifications (recipient_id, sender_id, notification_text) VALUES ($sender_id, $recipient_id, '$notification_text')";
        $conn->query($sql_add_notification);

    } elseif ($action == 'reject') {
        // Delete the like from the database
        $sql_delete_like = "DELETE FROM likes WHERE sender_id = $sender_id AND receiver_id = $recipient_id";
        $conn->query($sql_delete_like);
    
        // Remove the notification
        $sql_delete_notification = "DELETE FROM notifications WHERE notification_id = $notification_id";
        $conn->query($sql_delete_notification);
    

    }
    

    // Remove the notification
    $sql_delete_notification = "DELETE FROM notifications WHERE notification_id = $notification_id";
    $conn->query($sql_delete_notification);
}

// Fetch current preferences
$sql_pref = "SELECT gender_preference, preferred_location, interest, age_preference FROM preferences WHERE prefid = (SELECT preferences FROM users WHERE userid = $loggedin_userid)";
$result_pref = $conn->query($sql_pref);
$current_pref = $result_pref->fetch_assoc();
$current_gender_pref = $current_pref['gender_preference'];
$current_location_pref = $current_pref['preferred_location'];
$current_interest_pref = $current_pref['interest'];
$current_age_pref = $current_pref['age_preference'];

// Handle preference update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_preferences'])) {
    // Retrieve the new preference values from the form
    $new_gender_preference = $_POST['gender_preference'];
    $new_location_preference = $_POST['preferred_location'];
    $new_age_preference = $_POST['age_preference'];
    
    // Handle interests
    $new_interest_preference = isset($_POST['interests']) ? implode(",", $_POST['interests']) : 'Default';
    
    // Update the preferences in the database
    $sql_update_preferences = "UPDATE preferences 
                               SET gender_preference = '$new_gender_preference', 
                                   preferred_location = '$new_location_preference',
                                   age_preference = '$new_age_preference',
                                   interest = '$new_interest_preference'
                               WHERE prefid = (SELECT preferences FROM users WHERE userid = $loggedin_userid)";
    if ($conn->query($sql_update_preferences) === TRUE) {
        // Set session variable to indicate success
        $_SESSION['preference_updated'] = true;
    } else {
        // Set session variable to indicate failure
        $_SESSION['preference_updated'] = false;
    }
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['notification_id']) && isset($_POST['action'])) {
    $notification_id = $_POST['notification_id'];
    $action = $_POST['action'];

    $sql_get_notification = "SELECT sender_id, recipient_id FROM notifications WHERE notification_id = $notification_id";
    $result_notification = $conn->query($sql_get_notification);
    $notification = $result_notification->fetch_assoc();
    $sender_id = $notification['sender_id'];
    $recipient_id = $notification['recipient_id'];

    if ($action == 'accept') {
        $sql_update_like = "UPDATE likes SET status = 'Accepted' WHERE sender_id = $sender_id AND receiver_id = $recipient_id";
        $conn->query($sql_update_like);

        $sql_check_like_back = "SELECT * FROM likes WHERE sender_id = $recipient_id AND receiver_id = $sender_id AND status = 'Accepted'";
        $result_check_like_back = $conn->query($sql_check_like_back);

        $sql_insert_friend1 = "INSERT INTO friends (user_id1, user_id2) VALUES ($sender_id, $recipient_id)";
        $conn->query($sql_insert_friend1);

        $sql_insert_friend2 = "INSERT INTO friends (user_id1, user_id2) VALUES ($recipient_id, $sender_id)";
        $conn->query($sql_insert_friend2);

        $notification_text = "{$_SESSION['fname']} liked you back!";
        $sql_add_notification = "INSERT INTO notifications (recipient_id, sender_id, notification_text) VALUES ($sender_id, $recipient_id, '$notification_text')";
        $conn->query($sql_add_notification);
    } elseif ($action == 'reject') {
        $sql_delete_like = "DELETE FROM likes WHERE sender_id = $sender_id AND receiver_id = $recipient_id";
        $conn->query($sql_delete_like);
    }

    $sql_delete_notification = "DELETE FROM notifications WHERE notification_id = $notification_id";
    $conn->query($sql_delete_notification);
}

$sql_notifications = "SELECT * FROM notifications WHERE recipient_id = $loggedin_userid";
$result_notifications = $conn->query($sql_notifications);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="assets/img/logo2.png" type="image/x-icon">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="assets/css/style.css" rel="stylesheet">
   
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
      
      body {
            background-color: #f8e0e6; /* Light pink background */
            font-family: 'Poppins', sans-serif; /* Use Poppins font */
            color: #333; /* Default text color */
            margin: 0;
            padding: 0;
        }

        .logo-image {
            max-width: 15%;
            height: auto;
            position: absolute;
            top: 40px;
            left: 370px;

        } /* Adjust the top position as needed */
    /* Adjust the left position as needed */

/* Style for disabled button */
button[disabled] {
  background-color: #dddddd; /* Light gray background */
  color: #666666; /* Dark gray text color */
  cursor: not-allowed; /* Cursor indicating not clickable */
  border: none; /* Remove border */
  padding: 10px 20px; /* Adjust padding */
  border-radius: 5px; /* Add border radius for rounded corners */
}


    .swiper-container-wrapper {
        width: 60%;
        margin: auto;
        margin-right: 200px; 
        position: relative;
       
      /* Adjust bottom margin */
      padding: 20px; /* Add padding */
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #ff69b4;
        display: flex;
        justify-content: center;
        align-items: center;

    }
    .swiper {
        width: 100%;
        height: 100%;
    }
    .user-profile {
        background-color: #F9F9F9; /* Edit background color here */
        align-items: center;
        gap: 20px;
        padding: 20px; /* Add padding to space out the content */
        border-radius: 15px; /* Add border radius to match the card */
    }

    .user-profile img {
        width: 350px; /* Set width */
        height: 350px; /* Set height */
        border-radius: 15px; /* Make it square */
        object-fit: cover;
    }

    .user-details {
        text-align: left;
        padding: 20px;
        width: 350px;
        word-wrap: break-word;     
        border-radius: 15px;
       
    }
    
    h3 {
    font-family: 'Poppins', sans-serif; /* Use Poppins font */
    font-size: 35px; /* Slightly larger font size */
    color: #A020F0; /* Deep purple color */
    text-align: center; /* Center alignment */
    margin: 0 auto; /* Center horizontally */
    text-shadow: palevioletred;
    position: relative;
    margin-top: 150px; 
    margin-left: 16%;;
    font-weight: bold; /* Make the font bold */
}

    .button-container {
        display: flex;
        justify-content: space-between;
    }

    button[type="submit"] {
        background-color: #A020F0;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color:  #ff69b4;;
    }

    .notification-popup {
        background-color: #fff;
        border: 1px solid  #ff69b4;
        border-radius: 8px;
    }

    .modal-content {
        border-radius: 15px;
    }

    .close, .close-btn {
        color: #A020F0;
    }




/* Ensure the modal covers the full screen and prevents scrolling */
.modal {
  display: none;
  position: fixed;
margin-left: 25%;
margin-top: 2%;
  width: 60%;
  height: 100%;
  overflow: hidden;
  z-index: 1000;
}

.notification-popup {
    display: none;
    position: fixed;
    top: 80px; /* Adjust top position as needed */
    right: 20px; /* Adjust right position as needed */
    background-color: #f9f9f9;
    min-width: 500px;
    padding: 12px 16px;
    border-radius: 8px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    width: 400px; /* Adjust the width as needed */
    z-index: 999;
}


/* Modal content styling */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  margin-left: 30%;
  margin-top: 5%;
}

/* Close button styling */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* Prevent body scrolling when modal is open */
body.modal-open {
  overflow: hidden;
}



.notification-popup p {
    margin: 0;
}

.notification-popup.show {
    display: block;
}

.close-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 20px;
    cursor: pointer;
    color: #888;
}
@media screen and (max-width: 768px) {
        .swiper-container-wrapper {
            width: 100%;
            margin-right: 5%;
        }

        .modal-content {
            width: 80%;
            margin-left: 10%;
        }

        .notification-popup {
            min-width: unset;
            width: 80%;
        }

        .user-profile img {
            max-width: 50%;
        }

        .user-details {
            max-width: 50%;
        }
    }

    .edit-icons {
    position: absolute;
    top: 30px; /* Adjust this value to your liking */
    right: 30px; /* Adjust this value to your liking */
    display: flex;
    gap: 10px; /* Space between the icons */
}

.edit-icons a {
    color: purple;
    text-decoration: none;
    font-size: 40px; /* Adjust the size of the icons */
    margin-right: 5px; /* Adjust the space between the icons */

}

.edit-icons i {

    display: flex;
    justify-content: center;
    align-items: center;
}

.edit-icons a:hover {
    color: darkviolet; /* Optional: Change color on hover */
}

footer {
    background-color: #333;
    color: #f8e0e6;
    padding: 20px 0;
    text-align: center;
    width: 100%;
}

.footer-container {
    margin-left: 15%;
    margin-top: -2px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.footer-logo img {
    width: 150px;
    margin-bottom: 20px;
}

.footer-links ul,
.footer-social ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
}

.footer-links a,
.footer-social a {
    color: #f8e0e6;
    text-decoration: none;
}


.footer-contact {
    margin-top: 20px;
}

.footer-copyright {
    margin-top: 10px;
}

@media screen and (max-width: 768px) {
       
        }
        .footer.footer-container {
            max-width: 50%;
        }
    
    

</style>

</head>
<body>
<img src="assets/img/logo1.png" alt="Logo"  class="logo-image" style="max-width: 15%; height: auto;">
       
<i class="bi bi-list mobile-nav-toggle d-xl-none"></i><br>
<h3><b>Find a Match!</b></h3>

<div class="edit-icons">
    <a href="#" onclick="toggleFilterForm()">
        <i class="bi bi-pencil" title="Filter Preferences"></i> <!-- Assuming this is your box icon -->
    </a>
    <a href="#">
        <i class="bx bx-bell" title="Notifications"></i> <!-- Assuming this is your mail icon -->
    </a>
</div>



<!-- ======= Header ======= -->
<header id="header">
    <div class="d-flex flex-column">
        <div class="profile">
            <?php
            echo "<br>";
            $sql_fetch_profile_photo = "SELECT profile_picture FROM users WHERE userid = $loggedin_userid";
            $result_profile_photo = $conn->query($sql_fetch_profile_photo);
            if ($result_profile_photo->num_rows > 0) {
                $row_profile_photo = $result_profile_photo->fetch_assoc();
                $profile_photo_url = $row_profile_photo['profile_picture'];
                echo "<img src='$profile_photo_url' class='user-photo'>";
            } else {
                echo "<img src='path_to_default_placeholder_image.jpg' class='user-photo'>";
            }
            ?>
            <h1 class="text-light"><a href="dashboard.php"><?php echo isset($_SESSION['fname']) ? $_SESSION['fname'] : ''; ?></a></h1>
        </div>
        <nav id="navbar" class="nav-menu navbar">
            <ul><br>
                <li><a href="dashboard.php" class="nav-link scrollto  active"><i class="bx bx-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user_profile.php" class="nav-link scrollto "><i class="bx bx-user"></i> <span>View Profile</span></a></li>
                <li><a href="about.php" class="nav-link scrollto "><i class="bi bi-info-circle"></i> <span>About Us</span></a></li>           
                <li><a href="contacts.php" class="nav-link scrollto"><i class='bx bx-phone'></i> <span>Contact Us</span></a></li>
            </ul>
        </nav>
    </div>
</header>



<main id="main"></main>



<!-- Add a popup container for notifications -->
<div id="notification-popup" class="notification-popup">
    <span class="close-btn" onclick="closeNotificationPopup()">&times;</span>
    <?php
                echo "<b><br>Notifications</b><br>";

    if ($result_notifications->num_rows > 0) {
        while ($notification_row = $result_notifications->fetch_assoc()) {
            echo "<div class='notification'>";
            echo "<br><p>{$notification_row['notification_text']}</p>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='notification_id' value='{$notification_row['notification_id']}'>";
            $liked_user_id = $notification_row['sender_id'];

            $sql_check_like_back = "SELECT * FROM likes WHERE sender_id = $loggedin_userid AND receiver_id = $liked_user_id AND status = 'Accepted'";
            $result_check_like_back = $conn->query($sql_check_like_back);

            if ($result_check_like_back->num_rows == 0) {
                // Wrap accept and reject buttons in a div
                echo "<div class='button-container'>";
                echo "<button type='submit' name='action' value='accept'>Accept</button>";
                echo "<button type='submit' name='action' value='reject'>Reject</button>";
                echo "</div>"; // Close button-container div
            } else {
                // Liked back, so don't display the buttons
                echo "<p>You've been liked back!</p>";
            }
            

            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p><br>No notifications.</p>";
    }
    ?>
</div>



<div id="filter-modal" class="modal">
  <div class="modal-content">
    <!-- Close button -->
    <span class="close" onclick="closeFilterModal()">&times;</span>
    <!-- Your filter form goes here -->
    <div id="filter-form-container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateAgePreference()">

      <label for="gender_preference"><b>Gender Preference:</b></label><br>
      <select name="gender_preference" id="gender_preference">
          <option value="Male" <?php echo ($current_gender_pref == 'Male') ? 'selected' : ''; ?>>Male</option>
          <option value="Female" <?php echo ($current_gender_pref == 'Female') ? 'selected' : ''; ?>>Female</option>
          <option value="Default" <?php echo ($current_gender_pref == 'Default') ? 'selected' : ''; ?>>Default</option>
      </select>
      <br>
      <br>
      <label for="preferred_location"><b>Preferred Location:</b></label><br>
      <select name="preferred_location" id="preferred_location">
          <?php
          $locations = ['Adams', 'Badoc', 'Bangui', 'Banna', 'Batac', 'Burgos', 'Carasi', 'Currimao', 'Dingras', 'Dumalneg', 'Laoag', 'Marcos', 'Nueva Era', 'Pagudpud', 'Paoay', 'Pasuquin', 'Piddig', 'Pinili', 'San Nicolas', 'Sarrat', 'Solsona', 'Default'];
          foreach ($locations as $location) {
              $selected = ($current_location_pref == $location) ? 'selected' : '';
              echo "<option value='$location' $selected>$location</option>";
          }
          ?>
      </select>
      <br>
      <br>
      <label for="age_preference"><b>Age Preference:</b></label><br>
      <input type="text" id="age_preference" name="age_preference" value="<?php echo $current_age_pref; ?>" required><br><br>
      <label><b>Interests:</b></label><br>
      <?php
      $interests = explode(",", $current_interest_pref);
      $all_interests = ['Sports', 'Music', 'Movies', 'Books', 'Travel', 'Food', 'Gaming', 'Art', 'Fashion', 'Technology', 'Fitness'];
      foreach ($all_interests as $interest) {
          $checked = (in_array($interest, $interests)) ? 'checked' : '';
          echo "<input type='checkbox' id ='heart' name='interests[]' value='$interest' $checked> $interest<br>";
      }
      ?>
      <br>
      <div class="button-container">
        <button type="submit" name="update_preferences">Update Preferences</button>
      </div>
      </form>

    </div>
  </div>
</div>


<?php
    
    // Fetch user's preferences
    $sql_pref = "SELECT gender_preference, preferred_location, interest, age_preference FROM preferences WHERE prefid = (SELECT preferences FROM users WHERE userid = $loggedin_userid)";
    $result_pref = $conn->query($sql_pref);
    $user_pref = $result_pref->fetch_assoc();
    $gender_pref = $user_pref['gender_preference'];
    $location_pref = $user_pref['preferred_location'];
    $interest_pref = $user_pref['interest'];
    $age_preference = $user_pref['age_preference'];
    
    // Construct SQL query based on preferences
    $sql = "SELECT users.userid, username.fname, username.mname, username.lname, username.nickname, users.birthday, users.age, users.location, users.gender, preferences.interest, users.profile_picture, users.bio 
            FROM users
            JOIN username ON users.nameid = username.nameid
            JOIN preferences ON users.preferences = preferences.prefid
            WHERE users.userid != $loggedin_userid";
            
    if ($gender_pref != 'Default') {
        $sql .= " AND users.gender = '$gender_pref'";
    }
    if ($location_pref != 'Default') {
        $sql .= " AND users.location = '$location_pref'";
    }
    if ($interest_pref != 'Default') {
        $interests = explode(",", $interest_pref);
        $interestConditions = [];
        foreach ($interests as $interest) {
            $interestConditions[] = "FIND_IN_SET('$interest', preferences.interest)";
        }
        $interestSql = implode(" OR ", $interestConditions);
        $sql .= " AND ($interestSql)";
    }

    if (strpos($age_preference, '-') !== false) {
        list($min_age, $max_age) = explode('-', $age_preference);
        $sql .= " AND users.age BETWEEN $min_age AND $max_age";
    } else {
        $sql .= " AND users.age = $age_preference";
    }

    // Execute the query
    $result = $conn->query($sql);

    echo "<br>";
    echo '<div class="swiper-container-wrapper">'; // Added container div
    echo '<div class="swiper mySwiper">';
    echo '<div class="swiper-wrapper">';
    
    // Display user profiles
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo "<div class='swiper-slide user-profile'>";
          echo "<img src='{$row['profile_picture']}' alt='Profile Picture' class='user-photo'>";
          echo "<div class='user-details'>";
          echo "<br><span>{$row['fname']} <b>({$row['nickname']})</b></span><br>";
          echo "<br><span>{$row['location']}</span><br>";
          echo "<span>{$row['gender']}</span><br>";
          echo "<span>{$row['age']}</span><br>";
          echo "<b>Interests:</b> <span>" . implode(", ", explode(",", $row['interest'])) . "</span><br>";
          echo "<br><span>{$row['bio']}</span><br>";
          echo "<form method='post' action=''>";
          echo "<input type='hidden' name='like_user_id' value='{$row['userid']}'>";
          

  
          // Check if the logged-in user has already liked this user
          $sql_check_like_sent = "SELECT * FROM likes WHERE sender_id = $loggedin_userid AND receiver_id = {$row['userid']}";
          $result_check_like_sent = $conn->query($sql_check_like_sent);
  
          $sql_check_like_received = "SELECT * FROM likes WHERE sender_id = {$row['userid']} AND receiver_id = $loggedin_userid";
          $result_check_like_received = $conn->query($sql_check_like_received);
  
          if ($result_check_like_sent->num_rows == 0 && $result_check_like_received->num_rows == 0) {
              // If the like doesn't exist, display the like button
              echo "<button type='submit' name='like'>Like</button>";
          } else {
              // If the like exists, hide the like button
              echo "<button disabled>Like</button>";
          }
          echo "</div>";
          echo "</form>";
          echo "<br><br>";
          echo "</div>";
      }
  } else {
      echo "0 results";
  }
  
  echo '</div>';
    echo '<div class="swiper-button-next"></div>';
    echo '<div class="swiper-button-prev"></div>';
    echo '</div>'; // Close Swiper container
    echo '</div>'; // Close wrapper div
    

    
    $conn->close();
    ?>

    <br><br><br><br><br>
 <!-- Footer -->
 <footer>
    <div class="footer-container">
        
        <div class="footer-links">
            <ul>
                <li><a href="about.php">About Us</a></li>
                <li><a href="FAQ.php">FAQ</a></li>
                <li><a href="Privacypolicy.php">Privacy Policy</a></li>
                <li><a href="terms.php">Terms of Service</a></li>
                <li><a href="contacts.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-social">
           
            <ul>  
                <li><a href="https://facebook.com" target="_blank"><i class='bx bxl-facebook'></i></a></li>
                <li><a href="https://twitter.com" target="_blank"><i class='bx bxl-twitter'></i></a></li>
                <li><a href="https://instagram.com" target="_blank"><i class='bx bxl-instagram'></i></a></li>
                <li><a href="https://linkedin.com/company" target="_blank"><i class='bx bxl-linkedin'></i></a></li>
                <li><a href="https://youtube.com" target="_blank"><i class='bx bxl-youtube'></i></a></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <p>&copy; 2024 OnlyYou! TI-TECH GROUP. All rights reserved.</p>
        </div>
    </div>
</footer>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- Initialize Swiper -->
  <script>
    function validateAgePreference() {
        var agePreference = document.getElementById('age_preference').value.trim();
        
        // Check if the input value is empty
        if (agePreference === '') {
            return true; // Allow form submission
        }
        
        // Check if the input value contains a hyphen ("-") indicating a range
        if (agePreference.includes('-')) {
            var ageRange = agePreference.split('-'); // Split the input value into an array
            
            // Check if the array has exactly two elements and if they are valid integer values
            if (ageRange.length === 2 && !isNaN(ageRange[0]) && !isNaN(ageRange[1])) {
                var minAge = parseInt(ageRange[0]);
                var maxAge = parseInt(ageRange[1]);
                
                // Check if both min and max ages are within the valid range and min age is less than max age
                if (minAge >= 19 && maxAge <= 70 && minAge < maxAge) {
                    return true; // Allow form submission
                }
            }
        } else {
            // If no hyphen is present, check if the value is a valid integer within the range
            var age = parseInt(agePreference);
            if (!isNaN(age) && age >= 19 && age <= 70) {
                return true; // Allow form submission
            }
        }
        
        // Display an alert for invalid input
        alert('Please enter a valid age preference (e.g., 19-70 for a range or a single age between 19 and 70).');
        return false; // Prevent form submission
    }




    var swiper = new Swiper(".mySwiper", {
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

    function toggleFilterForm() {
        var modal = document.getElementById('filter-modal');
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
    }

    function closeFilterModal() {
        var modal = document.getElementById('filter-modal');
        modal.style.display = 'none';
    }




// JavaScript for toggling the notification popup
document.addEventListener('DOMContentLoaded', (event) => {
    const notificationIcon = document.querySelector('.bx-bell');
    const notificationPopup = document.getElementById('notification-popup');
    const closeBtn = document.querySelector('.close-btn');

    // Function to toggle the notification popup
    function toggleNotificationPopup() {
        if (notificationPopup.style.display === 'block') {
            notificationPopup.style.display = 'none';
        } else {
            notificationPopup.style.display = 'block';
        }
    }

    // Function to close the notification popup
    function closeNotificationPopup() {
        notificationPopup.style.display = 'none';
    }

    // Event listener for the notification icon
    notificationIcon.addEventListener('click', toggleNotificationPopup);

    // Event listener for the close button
    closeBtn.addEventListener('click', closeNotificationPopup);

    // Close the popup when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target !== notificationPopup && !notificationPopup.contains(event.target) && event.target !== notificationIcon) {
            closeNotificationPopup();
        }
    });
});

function greetUser() {
            // Retrieve the user's first name from the session
            var firstName = "<?php echo isset($_SESSION['fname']) ? $_SESSION['fname'] : ''; ?>";

            // Check if the first name is not empty
            if (firstName.trim() !== '') {
                // Check if the user has not been greeted already
                var greeted = "<?php echo isset($_SESSION['greeted']) ? $_SESSION['greeted'] : 'false'; ?>";
                if (greeted !== 'true') {
                    // Display the alert
                    alert("Welcome, " + firstName + "!");
                    // Set the greeted flag to true
                    <?php $_SESSION['greeted'] = 'true'; ?>;
                }
            }
        }

        // Call the greetUser function when the page loads
        window.onload = greetUser;

        <?php
    if (isset($_SESSION['preference_updated'])) {
        if ($_SESSION['preference_updated'] === true) {
            echo "alert('Preferences updated successfully!')";
        } else {
            echo "alert('Failed to update preferences. Please try again.')";
        }
        // Unset the session variable
        unset($_SESSION['preference_updated']);
    }
    ?>

  </script>
</body>
</html>

