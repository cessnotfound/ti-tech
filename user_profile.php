


<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

$loggedin_userid = $_SESSION['userid'];

include 'conn.php';

$sql_friends_count = "SELECT COUNT(*) as friend_count FROM friends WHERE user_id1 = $loggedin_userid";
$result_friends_count = $conn->query($sql_friends_count);
$friend_count = $result_friends_count->fetch_assoc()['friend_count'];

$sql = "SELECT users.*, username.fname, username.mname, username.lname, username.nickname, users.birthday, users.gender, preferences.age_preference, preferences.gender_preference, preferences.interest, preferences.preferred_location FROM users
        JOIN username ON users.nameid = username.nameid
        JOIN preferences ON users.preferences = preferences.prefid
        WHERE users.userid = $loggedin_userid";

$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION['background_photo'] = $row['background_photo'];
    $_SESSION['profile_picture'] = $row['profile_picture'];
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

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    


    
    <style>
        
             body {
            background-color: #f8e0e6; /* Light pink background */
            font-family: 'Poppins', sans-serif; /* Use Poppins font */
            color: #333; /* Default text color */

        }

.container {
    position: relative;
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 15px;
}

.profile-overlay {
    position: absolute;
    top: 50%;
    left: 0;
    transform: translate(0, -50%);
    width: 100%;
    text-align: center;
}

.profile-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
    margin: auto;
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    max-width: 800px; /* Adjusted for responsiveness */
    background-color: rgba(255, 255, 255, 0.9);
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
    margin: auto;
    margin-top: -84px;
    text-align: center;
    font-size: 18px;
}

.profile-details p {
    margin-bottom: 10px;
}

.bio {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 8px;
    text-align: left;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    font-size: 18px;
    margin: 20px auto;
    max-width: 600px; /* Adjusted for responsiveness */
}

.friends-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-top: -4%;
}

.friend-profile {
    display: inline-block;
    text-align: center;
    margin: 10px;
}

.friend-profile .photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 10px;
    border: 3px solid whitesmoke;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.friend-profile .photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media screen and (max-width: 768px) {
    .container {
        padding: 0 10px;
    }
    .profile-image {
        width: 150px;
        height: 150px;
    }
    .profile-details {
        font-size: 16px;
    }
    .bio {
        font-size: 16px;
    }
    .friend-profile .photo {
        width: 80px;
        height: 80px;
    }
}

        .profile-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid whitesmoke;
    position: relative;
    z-index: 1;
    margin-left: auto;
    margin-right: auto;
}

.settings-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.5em;
    cursor: pointer;
}

.settings-dropdown {
    display: none;
    position: absolute;
    top: 40px;
    right: 10px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    z-index: 1000;
}

.settings-dropdown a {
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    color: black;
    border-bottom: 1px solid #ddd;
}

.settings-dropdown a:last-child {
    border-bottom: none;
}

.settings-dropdown a:hover {
    background-color: #f0f0f0;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    margin-left: calc(50% + -150px); /* Adjust the value to shift more to the right */
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    animation: slideIn 0.3s ease;
}

/* CSS for buttons */
.modal-content .button-container {
    text-align: center;
}

.modal-content .button-container a {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px;
    background-color: #007bff; /* Blue color, you can change it */
    color: #fff;
    text-decoration: none;
    border: 1px solid #007bff; /* Blue color border */
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.modal-content .button-container a:hover {
    background-color: #0056b3; /* Darker shade of blue on hover */
    border-color: #0056b3; /* Darker shade of blue border on hover */
}




.preview-img {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 10px auto;
    border: 3px solid #ddd;
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.modal form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.modal form label {
    font-weight: bold;
}

.modal form input[type="text"],
.modal form input[type="date"],
.modal form textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
}

.modal form input[type="submit"] {
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #28a745;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
}

.modal form input[type="submit"]:hover {
    background-color: #218838;
}

.friend-profile-modal {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.friend-profile-modal img {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.friend-profile-modal p {
    text-align: center;
    margin: 0;
}

.friend-profile-modal .bio {
    text-align: left;
}

.friend-profile-modal .bio p {
    margin-top: 10px;
}

.friend-details-container {
    margin-top: -75px;
}

.background-photo {
width: 800px; /* Same width as profile details */
height: 300px; /* Adjust height as needed */
object-fit: cover;
position: absolute;
left: 58.9%;
top: 8.5%;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
transform: translateX(-50%);
z-index: -1; /* Set z-index to move the background photo behind other elements */
}

.profile-container {

margin-top: 250px; /* Adjust the margin top to create space */
margin-bottom: 50px; /* Add margin bottom for spacing between the profile and content below */
}
.background-photo-preview {
    width: 100%; /* Set the width to 100% to fit the container */
    height: auto; /* Let the height adjust automatically to maintain aspect ratio */
    max-width: 900px; /* Set a maximum width to prevent stretching */
    max-height: 300px; /* Set a maximum height to prevent stretching */
    display: block;
    margin: 10px auto;
    border: 3px solid #ddd;
    border-radius: 0; /* Remove border-radius to make it rectangular */
    object-fit: cover; /* Show the actual look of the image in a 900x300 preview */
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
                <li><a href="dashboard.php" class="nav-link scrollto"><i class="bx bx-home"></i> <span>Go Back</span></a></li>
                <li><a href="user_profile.php" class="nav-link scrollto active"><i class="bx bx-user"></i> <span>View Profile</span></a></li>
            </ul>
        </nav>
    </div>
</header>


<main id="main">

<img src="<?php echo $_SESSION['background_photo']; ?>" class="background-photo" alt="Background Photo">
<div class="profile-container"> 
    <div class="profile-image">
    <img src="<?php echo $_SESSION['profile_picture']; ?>" class="user-photo" alt="Profile Picture">
    </div>
    <div class="profile-details">
        <p><br><br><br><b><?php echo $row['fname'] . " " . $row['mname'] . " " . $row['lname'] . " (" . $row['nickname'] . ")"; ?></b></p>
        <p><?php echo $row['gender']; ?></p>
        <p><?php echo $row['location']; ?></p>
        <p><?php echo $row['birthday']; ?></p>
        <p><?php echo $row['age']; ?></p><br>
        <p><b>Interest: </b><?php echo implode(", ", explode(",", $row['interest'])); ?><br></p><br>
        <div class="bio">
            <p><b>Bio</b><br><?php echo $row['bio']; ?></p>
        </div>
        <br><br>
        <i class="bi bi-gear settings-icon" onclick="toggleDropdown()" title="Settings"></i>
        <div class="settings-dropdown" id="settingsDropdown" >
            <a href="#" onclick="document.getElementById('editModal').style.display='block'">Edit Details</a>
            <a href="#" onclick="document.getElementById('editProfilePictureModal').style.display='block'">Change Profile Picture</a>
            <a href="#" onclick="document.getElementById('editBackgroundPhotoModal').style.display='block'">Change Background Photo</a>
            <a href="#" onclick="confirmLogout()">Logout</a>
        </div>
    </div>
</div>

<br><br>
 <!-- Friends Section -->
<div class="friends-container">
    <h3><b>Your Friends (<?php echo $friend_count; ?>)</b></h3>
    <?php
    $sql_friends = "SELECT users.userid, username.fname, username.mname, username.lname, users.profile_picture 
                    FROM friends 
                    JOIN users ON (friends.user_id2 = users.userid)
                    JOIN username ON users.nameid = username.nameid
                    WHERE friends.user_id1 = $loggedin_userid";

    $result_friends = $conn->query($sql_friends);

    if ($result_friends->num_rows > 0) {
        while ($row_friend = $result_friends->fetch_assoc()) {
            echo "<div class='friend-profile'>";
            echo "<img src='{$row_friend['profile_picture']}' alt='Profile Picture' class='photo'>";
            echo "<br><p id='fname'>{$row_friend['fname']} {$row_friend['mname']} {$row_friend['lname']}</p>";
            echo "<a href='#' class='view-profile-link' data-friend-id='{$row_friend['userid']}'>View Profile</a>";
            echo "</div>";
        }
    } else {
        echo "<p>You have no friends yet.</p>";
    }
    ?>
</div>

<!-- Friend Profile Modal -->
<div id="friendProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('friendProfileModal').style.display='none'">&times;</span>
        <div class="friend-profile-modal">
            <div id="friendProfileImage"></div>
            <div class="friend-details-container" id="friendProfileDetails"></div>
        </div>
        <!-- Message Button and Unfriend Button -->
        <div class="button-container">
            <a id="messageButton" href="#">Message</a>
            <a id="unfriendButton" href="#" onclick="unfriend()">Unfriend</a>
        </div>
    </div>
</div>





<!-- Edit Profile Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <form id="editForm" onsubmit="return validateForm()" action="update_profile.php" method="post">
            Edit Details    
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" value="<?php echo $row['fname']; ?>" ><br>
            <label for="mname">Middle Name:</label>
            <input type="text" id="mname" name="mname" value="<?php echo $row['mname']; ?>" ><br>
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" value="<?php echo $row['lname']; ?>" ><br>
            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" value="<?php echo $row['nickname']; ?>"><br>
            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo $row['birthday']; ?>" ><br>
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" rows="4" required><?php echo $row['bio']; ?></textarea><br>
            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>

<!-- Edit Profile Picture Modal -->
<div id="editProfilePictureModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editProfilePictureModal').style.display='none'">&times;</span>
        <form id="editProfilePictureForm" action="update_profile_picture.php" method="post" enctype="multipart/form-data">
            <label for="profile_picture">Change Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required onchange="previewProfilePicture(event)"><br>
            <img id="profile_picture_preview" class="preview-img" src="#" alt="Profile Picture Preview" style="display: none;">
            <input type="submit" value="Upload">
        </form>
    </div>
</div>

<!-- Edit Background Photo Modal -->
<div id="editBackgroundPhotoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editBackgroundPhotoModal').style.display='none'">&times;</span>
        <form id="editBackgroundPhotoForm" action="update_background_photo.php" method="post" enctype="multipart/form-data">
            <label for="background_photo">Change Background Photo:</label>
            <input type="file" id="background_photo" name="background_photo" accept="image/*" required onchange="previewBackgroundPhoto(event)"><br>
            <img id="background_photo_preview" class="background-photo-preview" src="#" alt="Background Photo Preview" style="display: none;">
            <input type="submit" value="Upload">
        </form>
    </div>
</div>



</main>

<script src="assets/js/main.js"></script>
<script src="assets/js/prof.js"></script>

<script>

function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "logout.php";
    }
}


function unfriend() {
    const friendId = document.querySelector('.view-profile-link').getAttribute('data-friend-id');

    if (confirm("Are you sure you want to unfriend this user?")) {
        fetch(`unfriend.php?friend_id=${friendId}`)
            .then(response => {
                if (response.ok) {
                    // Reload the page after successful unfriending
                    window.location.reload();
                } else {
                    throw new Error('Network response was not ok.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

document.querySelectorAll('.view-profile-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const friendId = this.getAttribute('data-friend-id');
        fetch(`fetch_friend_profile.php?friend_id=${friendId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('friendProfileImage').innerHTML = `<img src="${data.profile_picture}" alt="Profile Picture" class="photo">`;
                document.getElementById('friendProfileDetails').innerHTML = `<p>${data.fname} ${data.mname} ${data.lname}</p><p>${data.bio}</p>`;
                document.getElementById('messageButton').setAttribute('href', `message.php?friend_id=${friendId}`); // Set the href attribute of the message button
                document.getElementById('friendProfileModal').style.display = 'block';
            });
    });
});



function previewProfilePicture(event) {
    var input = event.target;
    var preview = document.getElementById('profile_picture_preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}

function previewBackgroundPhoto(event) {
    var input = event.target;
    var preview = document.getElementById('background_photo_preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
function toggleDropdown() {
    var dropdown = document.getElementById('settingsDropdown');
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}

window.onclick = function(event) {
    if (!event.target.matches('.settings-icon')) {
        var dropdowns = document.getElementsByClassName('settings-dropdown');
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
}
</script>
</body>
</html>

<?php
} else {
    echo "Error: User profile not found.";
}

$conn->close();
?>
