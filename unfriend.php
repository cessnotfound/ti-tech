<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

include 'conn.php';

$loggedin_userid = $_SESSION['userid'];
$friend_id = $_GET['friend_id'];

$sql_delete_friendship = "DELETE FROM friends WHERE (user_id1 = $loggedin_userid AND user_id2 = $friend_id) OR (user_id1 = $friend_id AND user_id2 = $loggedin_userid)";
$sql_delete_likes = "DELETE FROM likes WHERE (sender_id = $loggedin_userid AND receiver_id = $friend_id) OR (sender_id = $friend_id AND receiver_id = $loggedin_userid)";

if ($conn->query($sql_delete_friendship) === TRUE && $conn->query($sql_delete_likes) === TRUE) {
    echo "Unfriend successful";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
