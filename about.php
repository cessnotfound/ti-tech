<?php
session_start();


$loggedin_userid = $_SESSION['userid'];

include 'conn.php';
// Ensure $loggedin_userid is set and is a valid value
$loggedin_userid = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="assets/img/logo2.png" type="image/x-icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About OnlyYou</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
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
        h1, h2 {
            color: #ff4081;
        }
        h1, h3 {
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
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
                <li><a href="about.php" class="nav-link scrollto active"><i class="bi bi-info-circle"></i> <span>About Us</span></a></li>           
                <li><a href="contacts.php" class="nav-link scrollto "><i class='bx bx-phone'></i> <span>Contact Us</span></a></li>
            </ul>
        </nav>
    </div>
</header>
<script src="assets/js/main.js"></script>
<main id="main">
    <div class="container">
        <h1><b>About OnlyYou!</b></h1><br>
        <h3>Welcome to OnlyYou - Where Every Connection is Unique!</h3>
        <p>At OnlyYou, our mission is to help you find genuine connections and meaningful relationships in a fast-paced digital world. We believe in the power of individuality and strive to create a platform where every connection is unique, just like you.</p>
        
        <h2>Our Mission</h2>
        <p>At OnlyYou, we are dedicated to helping you find that special someone. We understand that every person is unique, and so are their preferences in a partner. Our mission is to connect hearts by celebrating individuality and fostering meaningful relationships.</p>
        
        <h2>Unique Features</h2>
        <ul>
            <li><strong>Advanced Matching Algorithm:</strong> Our state-of-the-art algorithm takes into account your preferences, interests, and personality traits to suggest the most compatible matches.</li>
            <li><strong>Personalized Profiles:</strong> Showcase your true self with customizable profiles that highlight your interests, hobbies, and what makes you unique.</li>
            <li><strong>Real-Time Chat:</strong> Connect with your matches instantly with our seamless real-time chat feature, making it easier to get to know each other.</li>
        </ul>
        
        <h2>User Experience</h2>
        <p>OnlyYou is designed with you in mind. Our intuitive interface ensures that you can navigate the app effortlessly, focusing more on building connections rather than figuring out how to use the app.</p>
        
        <h2>Safety and Security</h2>
        <p>Your safety is our priority. OnlyYou employs top-notch encryption to protect your data and privacy. Our dedicated team constantly monitors the platform to ensure a secure and trustworthy environment for all users.</p>
        
        <h2>Community and Values</h2>
        <p>We are committed to fostering a diverse and inclusive community. At OnlyYou, everyone is welcome. We celebrate diversity and aim to create a space where everyone feels respected and valued.</p>
        
        <h2>How It Works</h2>
        <p>Getting started with OnlyYou is simple:</p>
        <ul>
            <li><strong>Create a Profile:</strong> Sign up and create a profile that truly represents you.</li>
            <li><strong>Browse Matches:</strong> Explore profiles and find potential matches based on your preferences.</li>
            <li><strong>Start Chatting:</strong> Connect instantly with our real-time chat feature and get to know your matches.</li>
        </ul>
        
        <div class="cta">
            <p>Ready to find your perfect match? join OnlyYou today and start your journey to love!</p>
        </div>
        
        
    </div>
</main>
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
            <p>&copy; 2024 OnlyYou!.TI-TECH GROUP. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>



<?php
$conn->close();
?>
