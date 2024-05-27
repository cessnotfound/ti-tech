<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

$loggedin_userid = $_SESSION['userid'];

include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_photo']) && $_FILES['background_photo']['error'] == UPLOAD_ERR_OK) {
    $tempname = $_FILES['background_photo']['tmp_name'];
    $file_name = basename($_FILES['background_photo']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = $loggedin_userid . '_' . time() . '.' . $file_ext;
        $folder = 'uploads/background_photos/' . $new_file_name;

        if (!is_dir('uploads/background_photos/')) {
            mkdir('uploads/background_photos/', 0777, true);
        }

        if (move_uploaded_file($tempname, $folder)) {
            $sql = "UPDATE users SET background_photo = '$folder' WHERE userid = $loggedin_userid";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['background_photo'] = $folder;
                header('Location: user_profile.php');
                exit;
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
} else {
    echo "No file uploaded or there was an upload error.";
}

$conn->close();
?>
