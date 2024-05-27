<?php
session_start();

$loggedin_userid = $_SESSION['userid'];

include 'conn.php';
// Ensure $loggedin_userid is set and is a valid value
$loggedin_userid = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;

?><!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="assets/img/logo2.png" type="image/x-icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyYou - FAQ</title>
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
        /* Styles similar to your provided code */
        body {
            background-color: #f8e0e6; /* Light pink background */
            font-family: 'Poppins', sans-serif; /* Use Poppins font */
            color: #333; /* Default text color */
            margin-top: 50px;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            color: #ff4081;
        }
        h1, h4 {
            text-align: center;
            t
        }
        h4 {
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        h4 {
            text-align: center;
        }
       
       .cta {
            text-align: center;
            margin-top: 20px;
        }
        .cta a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff4081;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .cta a:hover {
            background-color: #e73770;
        }
        input[type="submit"] {
            background-color: #ff1493;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #ff69b4;
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
    <!-- ======= Mobile nav toggle button ======= -->
<i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

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
            <ul>
                <li><a href="dashboard.php" class="nav-link scrollto "><i class="bx bx-home"></i> <span>Dashboard</span></a></li>
                <li><a href="user_profile.php" class="nav-link scrollto "><i class="bx bx-user"></i> <span>View Profile</span></a></li>
                <li><a href="about.php" class="nav-link scrollto "><i class="bi bi-info-circle"></i> <span>About Us</span></a></li>           
                <li><a href="contacts.php" class="nav-link scrollto"><i class='bx bx-phone'></i> <span>Contact Us</span></a></li>
            </ul>
        </nav>
    </div>
</header>
<script src="assets/js/main.js"></script>
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <h4>If you’re trying to get to know us better, you’ve come to the right place. This is OnlyYou! at a glance.</h4>
    <br>  <li><strong>What is OnlyYou?</strong><br>
                OnlyYou is a dating app designed to help individuals find their perfect match based on their preferences, interests, and location.</li>
           <br> <li><strong>How do I access OnlyYou?</strong><br>
                To access OnlyYou, you need to sign in using your account credentials. If you don't have an account yet, you can create one by signing up.</li>
         <br>   <li><strong>How does OnlyYou match users?</strong><br>
                OnlyYou matches users based on their gender preference, preferred location, age preference, and interests. Users can update their preferences to refine their matches.</li>
          <br> <li><strong>Can I like other users on OnlyYou?</strong><br>
                Yes, you can like other users on OnlyYou by clicking the "Like" button on their profile. If the other user likes you back, it will create a mutual match.</li>
           <br> <li><strong>What happens if I receive a like from someone?</strong><br>
                If you receive a like from another user, you will receive a notification asking you to accept or reject the like. If you accept, it creates a mutual match.</li>
          <br>  <li><strong>How do I update my profile preferences?</strong><br>
                You can update your profile preferences by clicking on the "Filter Preferences" icon, then adjusting your gender preference, preferred location, age preference, and interests.</li>
                <br> <li><strong>Can I view notifications on OnlyYou?</strong><br>
                Yes, you can view notifications on OnlyYou by clicking on the envelope icon. Notifications include likes from other users and other important updates.</li>
                <br> <li><strong>8. How do I access the FAQ section?</strong><br>
                You can access the FAQ section by clicking on the "About Us" link in the navigation menu and then selecting the FAQ option.</li>
            <br><li><strong>How do I contact support for assistance?</strong><br>
                If you need assistance or have any questions, you can contact our support team through the "Contact Us" page on the app.</li>
           <br> <li><strong>Is OnlyYou available on mobile devices?</strong><br>
                Yes, OnlyYou is available on both desktop and mobile devices. You can download the mobile app from the App Store or Google Play Store.</li>
       
            </ul>
            
    </div>
     <!-- Footer -->
 <div class="footer-links">
            <ul>
               <br><br><br><br> </ul>
        </div>
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

</body>
</html>

<?php
$conn->close();
?>
