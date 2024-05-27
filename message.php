<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

$loggedin_userid = $_SESSION['userid'];
$friend_id = $_GET['friend_id'];

include 'conn.php';

// Fetch the friend's name
$sql_friend_name = "SELECT username.fname FROM users JOIN username ON users.nameid = username.nameid WHERE users.userid = $friend_id";
$result_friend_name = $conn->query($sql_friend_name);
$friend_name = $result_friend_name->fetch_assoc()['fname'];

// Check if a chatroom already exists between these users
$sql_chatroom = "SELECT chatroom_id FROM chatrooms WHERE (sender_id = $loggedin_userid AND receiver_id = $friend_id) OR (sender_id = $friend_id AND receiver_id = $loggedin_userid)";
$result_chatroom = $conn->query($sql_chatroom);

if ($result_chatroom->num_rows > 0) {
    $chatroom_id = $result_chatroom->fetch_assoc()['chatroom_id'];
} else {
    // Create a new chatroom
    $chatroom_name = "Chat with " . $friend_name;
    $sql_create_chatroom = "INSERT INTO chatrooms (name, sender_id, receiver_id) VALUES ('$chatroom_name', $loggedin_userid, $friend_id)";
    $conn->query($sql_create_chatroom);
    $chatroom_id = $conn->insert_id;
}

$sql_messages = "SELECT messages.*, username.fname 
                 FROM messages 
                 JOIN users ON messages.sender_id = users.userid 
                 JOIN username ON users.nameid = username.nameid 
                 WHERE chatroom_id = $chatroom_id 
                 ORDER BY timestamp ASC";
$result_messages = $conn->query($sql_messages);


$messages = [];
if ($result_messages->num_rows > 0) {
    while ($row = $result_messages->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Fetch user's friends
$sql_friends = "SELECT users.userid, username.fname, users.profile_picture 
                FROM friends 
                JOIN users ON (friends.user_id2 = users.userid)
                JOIN username ON users.nameid = username.nameid
                WHERE friends.user_id1 = $loggedin_userid";

$result_friends = $conn->query($sql_friends);

$friends = [];
if ($result_friends->num_rows > 0) {
    while ($row = $result_friends->fetch_assoc()) {
        $friends[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <link rel="icon" href="assets/img/logo2.png" type="image/x-icon">

    <style>
          
      body {
            background-color: #f8e0e6; /* Light pink background */
            font-family: 'Poppins', sans-serif; /* Use Poppins font */
            color: #333; /* Default text color */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chat-box {
            overflow-y: scroll;
            max-height: 400px;
            border: 1px solid #ffb6c1;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .message {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            word-wrap: break-word;
        }
        .message .sender {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message .content {
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }
        .message .timestamp {
            font-size: 0.8em;
            color: #888;
            margin-top: 5px;
        }
        .message.sent {
            align-items: flex-end;
        }
        .message.sent .content {
            background-color: #ffccd5; /* Color for sent messages */
        }
        .message.received .content {
            background-color: #ffe6e6; /* Color for received messages */
        }
        .input-box {
            display: flex;
        }
        .input-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ffb6c1;
            border-radius: 5px;
            margin-right: 10px;
        }
        .input-box button {
            padding: 10px 20px;
            background-color: #ff6f91;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .header-icons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-icons a {
            text-decoration: none;
            color: #ff6f91;
            font-size: 20px;
            margin-left: 10px;
        }
        .friends-container {
            display: flex;
            overflow-x: auto; /* Horizontal scroll if friends overflow */
            margin-bottom: 20px;
        }
        .friend {
            display: flex;
            align-items: center;
            margin-right: 10px; /* Space between friends */
        }
        .profile-picture img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
        }

        /* Custom scrollbar styles */
        .chat-box::-webkit-scrollbar {
            width: 10px;
        }
        .chat-box::-webkit-scrollbar-thumb {
            background-color: #ff6f91;
            border-radius: 10px;
        }
        .chat-box::-webkit-scrollbar-thumb:hover {
            background-color: #ff4a6e;
        }
        .chat-box::-webkit-scrollbar-track {
            background-color: #ffe6e6;
        }
    </style>
</head>
<body>
    <br><br><br>
<div class="container">
    <div class="header-icons">
        <h2><?php echo htmlspecialchars($friend_name, ENT_QUOTES, 'UTF-8'); ?></h2>
        <div>
            <a href="user_profile.php" title="Go Back">🔙</a>
        </div>
    </div>

    <div class="friends-container"> <!-- Added container for friends -->
        <?php foreach ($friends as $friend): ?>
            <div class="friend">
                <a href="message.php?friend_id=<?php echo $friend['userid']; ?>">
                    <div class="profile-picture">
                        <img src="<?php echo $friend['profile_picture']; ?>" alt="<?php echo $friend['fname']; ?>">
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div> <!-- End of friends-container -->

    <div class="chat-box" id="messages">
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo ($message['sender_id'] == $loggedin_userid) ? 'sent' : 'received'; ?>">
                <div class="sender"><?php echo ($message['sender_id'] == $loggedin_userid) ? 'You' : htmlspecialchars($message['fname'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="content"><?php echo nl2br(htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8')); ?></div>
                <div class="timestamp"><?php echo date('Y-m-d H:i:s', strtotime($message['timestamp'])); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="input-box">
        <input type="text" id="messageInput" placeholder="Type your message here">
        <button id="sendButton">Send</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sendButton').click(function() {
            var message = $('#messageInput').val();
            if (message.trim() === '') {
                alert('Message cannot be empty.');
                return;
            }
            $.post('send_message.php', {
                chatroom_id: <?php echo $chatroom_id; ?>,
                sender_id: <?php echo $loggedin_userid; ?>,
                receiver_id: <?php echo $friend_id; ?>,
                message: message
            }, function(data) {
                $('#messageInput').val('');
            });
        });

        // Initial call to check for messages
        checkForNewMessages();

        function checkForNewMessages() {
            $.get('fetch_messages.php', {
                chatroom_id: <?php echo $chatroom_id; ?>
            }, function(data) {
                $('#messages').html(data);
                // Call checkForNewMessages() again after a delay of 1 second
                setTimeout(checkForNewMessages, 1000);
            });
        }
    });
</script>
</
body>
</html>