<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: signup.php');
    exit;
}

$email = $_SESSION['email'];
$session_password = $_SESSION['password']; 

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile picture upload
    $profile_filename = $_FILES["uploadfile"]["name"];
    $profile_tempname = $_FILES["uploadfile"]["tmp_name"];
    $profile_folder = "./assets/img/" . basename($profile_filename);

    // Handle background photo upload
    $background_filename = $_FILES["backgroundfile"]["name"];
    $background_tempname = $_FILES["backgroundfile"]["tmp_name"];
    $background_folder = "./assets/img/" . basename($background_filename);

    // Other form inputs
    $fname = $conn->real_escape_string($_POST['fname']);
    $_SESSION['fname'] = $fname; // Set fname in session
    $mname = $conn->real_escape_string($_POST['mname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $nickname = $conn->real_escape_string($_POST['nickname']);
  
    $sql_username = "INSERT INTO username (fname, mname, lname, nickname) VALUES ('$fname', '$mname', '$lname', '$nickname')";
    $conn->query($sql_username);
    $nameid = $conn->insert_id; 
  
    $age_preference = $conn->real_escape_string($_POST['age_preference']);
    $gender_preference = $conn->real_escape_string($_POST['gender_preference']);
    $preferred_location = $conn->real_escape_string($_POST['preferred_location']);
    $interests = $_POST['interests'];
    $interestsString = $conn->real_escape_string(implode(",", $interests));
    $bio = $conn->real_escape_string($_POST['bio']);

    $sql_preferences = "INSERT INTO preferences (age_preference, gender_preference, preferred_location, interest) VALUES ('$age_preference', '$gender_preference', '$preferred_location', '$interestsString')";
    $conn->query($sql_preferences);
    $prefid = $conn->insert_id;
  
    $date_of_birth = $_POST['date_of_birth'];
    $dob = date("Y-m-d", strtotime($date_of_birth)); 
    $now = new DateTime();
    $age = $now->diff(new DateTime($dob))->y;  
  
    $location = $conn->real_escape_string($_POST['location']);
    $gender = $conn->real_escape_string($_POST['gender']);

    // Move and insert profile picture
    if (move_uploaded_file($profile_tempname, $profile_folder)) {
        $sql_users = "INSERT INTO users (nameid, birthday, age, location, gender, preferences, profile_picture, bio) VALUES ('$nameid', '$dob', '$age', '$location', '$gender', '$prefid', '$profile_folder', '$bio')";
        $conn->query($sql_users);
        $userid = $conn->insert_id;

        // Store profile picture in session
        $_SESSION['profile_picture'] = $profile_folder;
    } else {
        echo "Failed to upload profile image.";
    }

    // Move and insert background photo
    if (move_uploaded_file($background_tempname, $background_folder)) {
        $sql_update_background = "UPDATE users SET background_photo = '$background_folder' WHERE userid = '$userid'";
        $conn->query($sql_update_background);

        // Store background photo in session
        $_SESSION['background_photo'] = $background_folder;
    } else {
        echo "Failed to upload background image.";
    }

    // Insert into accounts table
    $sql_accounts = "INSERT INTO accounts (accountid, email, password) VALUES ('$userid', '$email', '$session_password')";
    if ($conn->query($sql_accounts) === TRUE) {
        $_SESSION['loggedin'] = true;
        $_SESSION['userid'] = $userid;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $sql_accounts . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="icon" href="assets/img/logo2.png" type="image/x-icon">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">


    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8e0e6;
        }


        
        h2 {
            font-size: 28px;
            color: #A020F0;
            text-align: center;
            margin-top: 20px;
        }

        form {
            background: #fff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        h3 {
    font-family: 'Poppins', sans-serif; /* Use Poppins font */
    font-size: 25px; /* Slightly larger font size */
    color: #A020F0;
    text-align: center; /* Center alignment */
    margin: 0 auto; /* Center horizontally */
    text-shadow: palevioletred;
    position: relative;
     
    font-weight: bold; /* Make the font bold */
}
        input[type="text"], input[type="date"], input[type="file"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        .navigation-buttons {
            justify-content: space-between;
            text-align: center;
        }

        button, input[type="submit"], .button {
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        button:hover, input[type="submit"]:hover, .button:hover {
            background-color: plum;
        }

        button:disabled, input[type="submit"]:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .red-heart-checkbox {
            display: none;
        }
        
        .red-heart-checkbox + label {
            position: relative;
            padding-left: 40px;
            cursor: pointer;
            user-select: none;
        }
        
        .red-heart-checkbox + label:before {
            content:"\f004"; /* Open heart */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 0;
            color: lightgrey;
            font-size: 25px;
            transition: transform 0.2s ease;
        }
        
        .red-heart-checkbox:checked + label:before {
            content: "\f004"; /* Solid heart */
            color: #ff69b4;
        }
        
        .red-heart-checkbox + label:hover:before {
            transform: scale(1.2);
        }

        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            input[type="text"], input[type="date"], input[type="file"], select {
                width: calc(100% - 20px);
            }

            .navigation-buttons {
                flex-direction: column;
            }

            button, input[type="submit"], .button {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        /* CSS for the return icon/button */
.return-link {
    position: absolute;
    top: 20px;
    left: 20px;
    text-decoration: none;
    color: #000; /* Change color as needed */
    font-size: 20px; /* Adjust font size as needed */
    z-index: 9999; /* Ensure it's above other content */
}

.return-link:hover {
    color: #555; /* Change color on hover as needed */
}

    </style>
</head>
<body>
<a href="index.php" class="return-link">
        <i class="fas fa-arrow-left" title="Go Back"></i> <!-- Assuming you are using Font Awesome for icons -->
    </a>
    <br><br>
    <h2><b>Create Your OnlyYou! Account</b></h2><br><br>
    <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <!-- Section 1: Personal Information -->
        <div class="form-section active" id="section1">
            <h3>Personal Information</h3><br>
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required><br>
            
            <label for="mname">Middle Name:</label>
            <input type="text" id="mname" name="mname"><br>
            
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required><br>
            
            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" required><br>
        </div>
        
        <!-- Section 2: Bio and Personal Details -->
        <div class="form-section" id="section2">
            <h3>Bio and Personal Details</h3><br>
            <label for="bio">Bio:</label>
            <input type="text" id="bio" name="bio"required><br>
            
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
            
            <label for="location">Location:</label>
            <select id="location" name="location" required><br>
                <option value="Adams">Adams</option>
                <option value="Badoc">Badoc</option>
                <option value="Bangui">Bangui</option>
                <option value="Banna">Banna</option>
                <option value="Batac">Batac</option>
                <option value="Burgos">Burgos</option>
                <option value="Carasi">Carasi</option>
                <option value="Currimao">Currimao</option>
                <option value="Dingras">Dingras</option>
                <option value="Dumalneg">Dumalneg</option>
                <option value="Laoag">Laoag</option>
                <option value="Marcos">Marcos</option>
                <option value="Nueva Era">Nueva Era</option>
                <option value="Pagudpud">Pagudpud</option>
                <option value="Paoay">Paoay</option>
                <option value="Pasuquin">Pasuquin</option>
                <option value="Piddig">Piddig</option>
                <option value="Pinili">Pinili</option>
                <option value="San Nicolas">San Nicolas</option>
                <option value="Sarrat">Sarrat</option>
                <option value="Solsona">Solsona</option>
                <option value="Vintar">Vintar</option>
            </select>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender"required><br>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="default">Other</option>
            </select>
        </div>
        
        <!-- Section 3: Preferences -->
        <div class="form-section" id="section3">
            <h3>Preferences</h3><br>
            <label for="age_preference">Age Preference (min-max):</label>
            <input type="text" id="age_preference" name="age_preference" required>
            
            <label for="gender_preference">Gender Preference:</label>
            <select id="gender_preference" name="gender_preference" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="Default">Others</option>
            </select>

            <label for="preferred_location">Preferred Location:</label>
            <select id="preferred_location" name="preferred_location" required>
            <option value="Default">Any Location</option>
                <option value="Adams">Adams</option>
                <option value="Badoc">Badoc</option>
                <option value="Bangui">Bangui</option>
                <option value="Banna">Banna</option>
                <option value="Batac">Batac</option>
                <option value="Burgos">Burgos</option>
                <option value="Carasi">Carasi</option>
                <option value="Currimao">Currimao</option>
                <option value="Dingras">Dingras</option>
                <option value="Dumalneg">Dumalneg</option>
                <option value="Laoag">Laoag</option>
                <option value="Marcos">Marcos</option>
                <option value="Nueva Era">Nueva Era</option>
                <option value="Pagudpud">Pagudpud</option>
                <option value="Paoay">Paoay</option>
                <option value="Pasuquin">Pasuquin</option>
                <option value="Piddig">Piddig</option>
                <option value="Pinili">Pinili</option>
                <option value="San Nicolas">San Nicolas</option>
                <option value="Sarrat">Sarrat</option>
                <option value="Solsona">Solsona</option>
                <option value="Vintar">Vintar</option>
            </select>

            <label for="interests">Interests:</label>
            <div class="control-group">
                <input type="checkbox" id="sports" name="interests[]" value="Sports" class="red-heart-checkbox">
                <label for="sports">Sports</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="music" name="interests[]" value="Music" class="red-heart-checkbox">
                <label for="music">Music</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="movies" name="interests[]" value="Movies" class="red-heart-checkbox">
                <label for="movies">Movies</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="books" name="interests[]" value="Books" class="red-heart-checkbox">
                <label for="books">Books</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="travel" name="interests[]" value="Travel" class="red-heart-checkbox">
                <label for="travel">Travel</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="food" name="interests[]" value="Food" class="red-heart-checkbox">
                <label for="food">Food</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="gaming" name="interests[]" value="Gaming" class="red-heart-checkbox">
                <label for="gaming">Gaming</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="art" name="interests[]" value="Art" class="red-heart-checkbox">
                <label for="art">Art</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="fashion" name="interests[]" value="Fashion" class="red-heart-checkbox">
                <label for="fashion">Fashion</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="technology" name="interests[]" value="Technology" class="red-heart-checkbox">
                <label for="technology">Technology</label>
            </div>
            <div class="control-group">
                <input type="checkbox" id="fitness" name="interests[]" value="Fitness" class="red-heart-checkbox">
                <label for="fitness">Fitness</label>
            </div>
        </div>
        
        <!-- Section 4: Upload Pictures -->
        <div class="form-section" id="section4">
            <h3>Upload Pictures</h3><br>
            <label for="uploadfile">Upload Profile Picture:</label>
            <input type="file" name="uploadfile" id="uploadfile" accept="image/*" required>
            
            <label for="backgroundfile">Upload Background Picture:</label>
            <input type="file" name="backgroundfile" id="backgroundfile" accept="image/*"  required>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="navigation-buttons">
            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
            <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
            <input type="submit" id="submitBtn" value="Submit">
        </div>
    </form>

    <script>
        let currentSection = 0; // Current tab is set to be the first tab (0)
        showSection(currentSection); // Display the current tab

        function showSection(n) {
            const sections = document.querySelectorAll(".form-section");
            sections[n].classList.add("active");
            document.getElementById("prevBtn").style.display = n == 0 ? "none" : "inline";
            document.getElementById("nextBtn").style.display = n == (sections.length - 1) ? "none" : "inline";
            document.getElementById("submitBtn").style.display = n == (sections.length - 1) ? "inline" : "none";
        }

        function nextPrev(n) {
    const sections = document.querySelectorAll(".form-section");
    if (n == 1 && !validateSection()) return false;
    if (currentSection === 1) {
        const dobInput = document.getElementById("date_of_birth");
        const dob = new Date(dobInput.value);
        const now = new Date();
        const age = now.getFullYear() - dob.getFullYear();
        if (age < 19) {
            alert("You are too young to proceed. Please make sure you are at least 19 years old.");
            return false; // Prevent moving to the next section
        }
        if (age >= 70) {
            alert("You are too old to proceed.");
            return false; // Prevent moving to the next section
        }
    }
    if (currentSection === 2) {
        const agePreferenceInput = document.getElementById("age_preference");
        const agePreference = parseInt(agePreferenceInput.value);
        if (agePreference < 19 || agePreference > 70) {
            alert("Please enter an age preference between 19 and 70.");
            return false; // Prevent moving to the next section
        }
    }
    sections[currentSection].classList.remove("active");
    currentSection += n;
    if (currentSection >= sections.length) {
        document.querySelector("form").submit();
        return false;
    }
    showSection(currentSection);
}



        function validateSection() {
            let valid = true;
            const currentInputs = document.querySelectorAll(`.form-section:nth-child(${currentSection + 1}) input`);
            currentInputs.forEach(input => {
                if (input.hasAttribute('required') && input.value === "") {
                    input.style.borderColor = "red";
                    valid = false;
                } else {
                    input.style.borderColor = "";
                }
            });
            return valid;
        }

        
    </script>
</body>
</html>