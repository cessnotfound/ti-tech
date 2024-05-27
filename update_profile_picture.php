<?php
session_start();
include 'conn.php';

$loggedin_userid = $_SESSION['userid'];

// Fetch current profile picture path
$sql = "SELECT profile_picture FROM users WHERE userid = '$loggedin_userid'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $old_profile_picture = $row['profile_picture'];
    
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profile_pictures/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file is an image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            // Delete old profile picture if it exists and is not the default placeholder
            if ($old_profile_picture && $old_profile_picture != 'path_to_default_placeholder_image.jpg') {
                if (file_exists($old_profile_picture)) {
                    unlink($old_profile_picture);
                }
            }

            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update the database
                $sql = "UPDATE users SET profile_picture='$target_file' WHERE userid='$loggedin_userid'";
                if ($conn->query($sql) === TRUE) {
                    // Update session variable
                    $_SESSION['profile_picture'] = $target_file;
                    header("Location: user_profile.php");
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }
} else {
    echo "Error fetching current profile picture.";
}

$conn->close();
?>
