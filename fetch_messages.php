<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    exit;
}

$chatroom_id = $_GET['chatroom_id'];

include 'conn.php';

$sql_messages = "SELECT messages.*, username.fname, users.userid 
                 FROM messages 
                 JOIN users ON messages.sender_id = users.userid 
                 JOIN username ON users.nameid = username.nameid 
                 WHERE messages.chatroom_id = $chatroom_id 
                 ORDER BY timestamp ASC";
$result_messages = $conn->query($sql_messages);

$messages = '';
if ($result_messages && $result_messages->num_rows > 0) {
    while ($row = $result_messages->fetch_assoc()) {
        $message_class = ($row['userid'] == $_SESSION['userid']) ? 'sent' : 'received';
        $sender_name = ($row['userid'] == $_SESSION['userid']) ? 'You' : htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8');
        $message_content = htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8');
        $timestamp = date('Y-m-d H:i:s', strtotime($row['timestamp']));

        $messages .= "<div class='message $message_class'>";
        $messages .= "<div class='sender'>$sender_name</div>";
        $messages .= "<div class='content'>$message_content</div>";
        $messages .= "<div class='timestamp'>$timestamp</div>";
        $messages .= "</div>";
    }
} else if (!$result_messages) {
    die("Error fetching messages: " . $conn->error);
}

$conn->close();

echo $messages;
?>
