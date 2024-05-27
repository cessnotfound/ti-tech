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
    <title>Contact Us - OnlyYou!</title>
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
        h1, h3 {
            color: #ff4081;
        }
        h1 {
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
                <li><a href="contacts.php" class="nav-link scrollto active "><i class='bx bx-phone'></i> <span>Contact Us</span></a></li>
            </ul>
        </nav>
    </div>
</header>
<script src="assets/js/main.js"></script>
<main id="main">
    <div class="container">
        <h1><b>Contact Us</b></h1><br>
        
        <div class="contact-details">
            <h4>Got something you want to talk about? Contact us or email us and we promise to get back to you as soon as we can.</h4>
            <br>
            <h3>Help / Support</h3>
            <p>For all things technical and app-related.</p>
            <p><strong>Contact Us:</strong> support@onlyyou.com</p>
            <p><strong>Phone Number:</strong> 214-853-4309</p><br>

            <h3>Partnerships</h3>
            <p>Interested in partnering with OnlyYou!?</p>
            <p><strong>Email:</strong> partners@onlyyou.com</p><br>

            <h3>Press</h3>
            <p>Interested in including OnlyYou! in your next article or blog?</p>
            <p><strong>Email:</strong> press@onlyyou.com</p><br>

            <h3>Ad Sales</h3>
            <p>Interested in advertising on OnlyYou!?</p>
            <p><strong>Email:</strong> adsales@onlyyou.com</p><br>

           
            <h3>Provider Information</h3>
            <p>For Members who lives in Nueva Era:</p>
            <p>Barikir, Nueva Era, ilocos Norte</p>
            <p>Legal Representative - Liezel Ann Buraga, Owner</p><br>
            
            <p>For Members who lives in Dingrase:</p>
            <p>Puruganan, Dingras, Ilocos norte</p>
            <p>Company No. 607126</p>
           <p>Legal Representative - Ces Jerome Villanueva, Director</B>
            <p>Share Capital - €10,000,000.00</p><br>

            <p>For Members who lives in Laoag City:</p>
            <p>brgy.45, Laoag City, Ilocos Nort</p>
            <p>Legal Representative – Karl Dela Cruz, CEO</p>
            <p>Share Capital - €2,000,000.00</p><br>

           

            <p>You may view OnlyYou!'s <a href="terms.php">Terms of Use</a> here.</p>
            <p>To find out more about OnlyYou!'s policy on the protection of the personal data of its members, please consult the <a href="Privacypolicy.php">Privacy Policy</a>.</p>
            <p>NOTE: For any requests from law enforcement officials, please contact us at: <strong>legaldept@onlyyou.com</strong>. We will not respond to emails sent to this address by non-law enforcement officials.</p>
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
            <p>&copy; 2024 OnlyYou! TI-TECH GROUP. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>

<?php
$conn->close();
?>
