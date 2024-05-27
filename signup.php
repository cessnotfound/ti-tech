
<?php
session_start();
include 'conn.php';

$showPopup = false; // Define $showPopup with initial value false
$passwordsMatch = true; // Define $passwordsMatch with initial value true

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat_password'];

    // Check if passwords match
    if ($password !== $repeatPassword) {
        $passwordsMatch = false;
    } else {
        $sql = "SELECT * FROM accounts WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $showPopup = true;
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            header('Location: user_creation.php');
            exit;
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - OnlyYou!</title>
    <link rel="icon" href="assets/img/logo2.png" type="image/x-icon">

    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: white;
}

.container {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    text-align: center;
    max-width: 400px;
    width: 100%;
    margin: 1rem;
    color: #ff1493;
}

.logo-image {
    max-width: 300px; /* Ensure the logo has a maximum width */
    width: 30%; /* Adjust this as needed for your design */
    height: auto;
    position: absolute;
    top: 40px;
    left: 110px;
}

.cover-image {
    flex: 1;
    height: 100vh;
    background: url('assets/img/bgimg.png') no-repeat 73% center;
    background-size: cover;
}

h2 {
    margin-bottom: 1rem;
    color: #ff1493;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    text-align: left;
    color: #ff1493;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid #ff1493;
    border-radius: 5px;
    background-color: #fff;
    color: #ff1493;
    box-sizing: border-box;
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

.error {
    color: red;
    font-size: 0.8rem;
    text-align: left;
    margin-top: -0.5rem;
    margin-bottom: 0.5rem;
}

p {
    margin-top: 1rem;
    color: #ff1493;
}

a {
    color: #800080;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

@media (min-width: 768px) {
    .container {
        margin-right: 10%;
    }
}

/* Popup styles */
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.popup-content {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    width: 100%;
    max-height: 80%;
    overflow-y: auto;
    position: relative; /* Set relative position for positioning the close button */
}

.popup-content h1, .popup-content h2, .popup-content h3, .popup-content p {
    color: #333;
}

.popup-content h2 {
    margin-top: 20px;
}

.popup-content p {
    margin-top: 10px;
}

.close-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 20px;
    color: #ff1493;
    cursor: pointer;
}


    </style>
</head>
<body>
<img src="assets/img/logo1.png" alt="Logo" class="logo-image">

<div class="cover-image"></div>
<div class="container">
    <img src="assets/img/logo2.png" alt="Logo" style="max-width: 100%; height: auto;">
    <p>By tapping Log In, you agree to our <a href="#" id="termsLink">Terms</a>. Learn how we process your data in our <a href="#" id="privacyLink">Privacy Policy</a>.</p>
    <h2>Create an Account</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <?php
        if ($showPopup) {
            echo "<p class='error'>*This email is already registered. Please use a different email.</p>";
        }
        ?>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="repeat_password">Repeat Password:</label>
        <input type="password" id="repeat_password" name="repeat_password" required>
        <?php
        if (!$passwordsMatch) {
            echo "<p class='error'>*Passwords do not match.</p>";
        }
        ?>
        <input type="submit" value="Signup">
    </form>
    <p>Already have an account? <a href="signin.php">Log In</a></p>
</div>

<div class="popup" id="termsPopup">
    <div class="popup-content">
        <span class="close-btn" onclick="document.getElementById('termsPopup').style.display = 'none';">&#10006;</span>
        <h1 style="text-align: center;">OnlyYou - Terms of Service</h1>
        <p>Welcome to OnlyYou, the premier online dating platform designed to help you find meaningful connections. OnlyYou is operated by TI-TECH. By accessing or using OnlyYou, you agree to be bound by these Terms of Service ("Terms"). Please read these Terms carefully before using OnlyYou.</p>
        
        <h2>1. Acceptance of Terms</h2>
        <p>By accessing or using OnlyYou, you agree to these Terms and our Privacy Policy. If you do not agree with these Terms or our Privacy Policy, you may not use OnlyYou.</p>
        
        <h2>2. Eligibility</h2>
        <p>You must be at least 19 years old to use OnlyYou. By accessing or using OnlyYou, you represent and warrant that you are at least 19 years old.</p>
        
        <h2>3. Registration and Account</h2>
        <p>To access certain features of OnlyYou, you may be required to create an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.</p>
        
        <h2>4. User Conduct</h2>
        <p>You are solely responsible for your conduct while using OnlyYou. You agree to use OnlyYou in compliance with all applicable laws and regulations.</p>
        <!-- Add the rest of the user conduct section here -->
        
        <h2>5. Content</h2>
        <p>OnlyYou allows users to post content such as profiles, photos, and messages. By posting content on OnlyYou, you grant us a non-exclusive, transferable, sub-licensable, royalty-free, worldwide license to use, reproduce, modify, adapt, publish, translate, distribute, and display such content.</p>
        <!-- Add the rest of the content section here -->
        
        <h2>6. Safety and Security</h2>
        <p>While we strive to create a safe and secure environment, we cannot guarantee the safety and security of OnlyYou. You are responsible for taking appropriate precautions to protect yourself while using OnlyYou.</p>
        <!-- Add the rest of the safety and security section here -->
        
        <h2>7. Termination</h2>
        <p>We reserve the right to terminate or suspend your access to OnlyYou at any time, without prior notice or liability, for any reason whatsoever, including without limitation if you breach these Terms.</p>
        <!-- Add the rest of the termination section here -->
        
        <h2>8. Modifications to Terms</h2>
        <p>We reserve the right to modify these Terms at any time. We will post the revised Terms on OnlyYou and indicate the date of the last revision. Your continued use of OnlyYou after the posting of the revised Terms constitutes your acceptance of the revised Terms.</p>
        <!-- Add the rest of the modifications to terms section here -->
        
        <h2>9. Contact Us</h2>
        <p>If you have any questions about these Terms, please contact us at onlyyou@gmail.com.</p>
    </div>
</div>


<div class="popup" id="privacyPopup">
    <div class="popup-content">
        <span class="close-btn" onclick="document.getElementById('privacyPopup').style.display = 'none';">&#10006;</span>
        <h1 style="text-align: center";>OnlyYou - Privacy Policy</h1>
        <p>Welcome to OnlyYou, the premier online dating platform designed to help you find meaningful connections. OnlyYou is operated by TI-TECH. By accessing or using OnlyYou, you agree to be bound by this Privacy Policy ("Policy"). Please read this Policy carefully before using OnlyYou.</p>
        
        <h2>1. Information We Collect</h2>
        <p>We collect information when you create an account and use OnlyYou. More information about the categories and sources of information is provided below.</p>
        <!-- Add the rest of the information we collect section here -->
        
        <h2>2. How We Use Your Information</h2>
        <p>We will use the information we collect about you based on the legal grounds described below.</p>
        <!-- Add the rest of the how we use your information section here -->
        
        <h2>3. How We Share Your Information</h2>
        <p>We are committed to maintaining your trust, and while OnlyYou does not sell personal information to third parties, we want you to understand when and with whom we may share the information we collect for business purposes.</p>
        <!-- Add the rest of the how we share your information section here -->
        
        <h2>4. Your Rights</h2>
        <p>You may submit a request to access or delete the information we have collected about you by sending your request to us at the email or physical address provided in the Contact section at the bottom of this policy.</p>
        <!-- Add the rest of the your rights section here -->
        
        <h2>5. Changes</h2>
        <p>We may update this Privacy Policy from time to time. When we update the Privacy Policy, we will notify you of this policy and posting the new Privacy Policy and providing any other notice required by applicable law.</p>
        <!-- Add the rest of the changes section here -->
        
        <h2>6. Contact</h2>
        <p>Questions, comments and requests regarding this policy should be addressed to:</p>
        <!-- Add the rest of the contact section here -->
    </div>
</div>


<script>
    // JavaScript to display the terms popup
    document.getElementById('termsLink').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        document.getElementById('termsPopup').style.display = 'flex'; // Display the popup
    });

    document.getElementById('privacyLink').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        document.getElementById('privacyPopup').style.display = 'flex'; // Display the popup
    });

</script>
</body>
</html>
