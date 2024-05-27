<?php
include 'conn.php';

$chatroom_id = $_POST['chatroom_id'];
$sender_id = $_POST['sender_id'];
$message = $_POST['message'];

$sql_insert_message = "INSERT INTO messages (chatroom_id, sender_id, message) VALUES ($chatroom_id, $sender_id, '$message')";
if ($conn->query($sql_insert_message) === TRUE) {
    echo "Message sent successfully";
} else {
    echo "Error: " . $sql_insert_message . "<br>" . $conn->error;
}

$conn->close();
?>
