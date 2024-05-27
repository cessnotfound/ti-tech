<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userid = $_SESSION['userid'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $nickname = $_POST['nickname'];
    $birthday = $_POST['birthday'];
    $bio = $_POST['bio'];

    // Calculate age from birthday
    $birthdate = new DateTime($birthday);
    $today = new DateTime('today');
    $age = $birthdate->diff($today)->y;

    // Update username table
    $sql_update_username = "UPDATE username 
                            JOIN users ON username.nameid = users.nameid
                            SET fname = ?, mname = ?, lname = ?, nickname = ?
                            WHERE users.userid = ?";
    $stmt = $conn->prepare($sql_update_username);
    $stmt->bind_param('ssssi', $fname, $mname, $lname, $nickname, $userid);
    $stmt->execute();

    // Update users table
    $sql_update_users = "UPDATE users 
                         SET birthday = ?, age = ?, bio = ?
                         WHERE userid = ?";
    $stmt = $conn->prepare($sql_update_users);
    $stmt->bind_param('sisi', $birthday, $age, $bio, $userid);
    $stmt->execute();

    // Update session variables
    $_SESSION['fname'] = $fname;
    $_SESSION['mname'] = $mname;
    $_SESSION['lname'] = $lname;
    $_SESSION['nickname'] = $nickname;

    header('Location: user_profile.php');
    exit;
}

$conn->close();
?>
