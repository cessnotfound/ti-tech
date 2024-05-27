<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

$loggedin_userid = $_SESSION['userid'];

include 'conn.php';

// Fetch the full name of the logged-in user if not already set in the session
if (!isset($_SESSION['fname'])) {
    $sql_fetch_fname = "SELECT fname FROM username WHERE nameid = $loggedin_userid";
    $result_fname = $conn->query($sql_fetch_fname);
    if ($result_fname->num_rows > 0) {
        $row_fname = $result_fname->fetch_assoc();
        $_SESSION['fname'] = $row_fname['fname'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Custom CSS File -->
    <link href="assets/css/fr.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">


</head>
<body>

    <!-- ======= Mobile nav toggle button ======= -->
    <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

    <!-- ======= Header ======= -->
    <header id="header">
        <div class="d-flex flex-column">
            <div class="profile">
                <?php
                echo "<br>";
                // Fetch the profile photo URL for the logged-in user
                $sql_fetch_profile_photo = "SELECT profile_picture FROM users WHERE userid = $loggedin_userid";
                $result_profile_photo = $conn->query($sql_fetch_profile_photo);
                if ($result_profile_photo->num_rows > 0) {
                    $row_profile_photo = $result_profile_photo->fetch_assoc();
                    $profile_photo_url = $row_profile_photo['profile_picture'];
                    // Display the profile photo
                    echo "<img src='$profile_photo_url' class='user-photo'>";
                } else {
                    // If no profile photo found, display a default placeholder
                    echo "<img src='path_to_default_placeholder_image.jpg' class='user-photo'>";
                }
                ?>
                <h1 class="text-light"><a href="dashboard.php"><?php echo isset($_SESSION['fname']) ? $_SESSION['fname'] : ''; ?></a></h1>
            </div>

            <nav id="navbar" class="nav-menu navbar">
                <ul>
                    <li><a href="user_profile.php" class="nav-link scrollto"><i class="bx bx-home"></i> <span>Go Back</span></a></li>
                    <li><a href="friends.php" class="nav-link scrollto active"><i class="bx bx-book-content"></i> <span>Friends</span></a></li>
                </ul>
            </nav><!-- .nav-menu -->
        </div>
    </header><!-- End Header -->
<br><br>
    <main id="main">
        <section>

            <h3><b>Your Friends</b></h3>

            <?php
            // Fetch friends
            $sql_friends = "SELECT users.userid, username.fname, username.mname, username.lname, users.profile_picture 
                            FROM friends 
                            JOIN users ON (friends.user_id2 = users.userid)
                            JOIN username ON users.nameid = username.nameid
                            WHERE friends.user_id1 = $loggedin_userid";

            $result_friends = $conn->query($sql_friends);

            // Display friends
            if ($result_friends->num_rows > 0) {
                while ($row = $result_friends->fetch_assoc()) {
                    echo "<div class='friend-profile'>";
                    echo "<img src='{$row['profile_picture']}' alt='Profile Picture' class='photo'>";
                    echo "<br><p id='fname'>{$row['fname']}{$row['mname']} {$row['lname']}</p> <br>";
                    echo "<a href='message.php?friend_id={$row['userid']}'>Message</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>You have no friends yet.</p>";
            }

            $conn->close();
            ?>
        </section><!-- End Section -->
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="container">
            <div class="credits">
                Designed by Ti-TECH
            </div>
        </div>
    </footer><!-- End  Footer -->

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/typed.js/typed.umd.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>
</html>
