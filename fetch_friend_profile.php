<?php
// Check if the friend ID is provided in the request
if (isset($_GET['friend_id'])) {
    include 'conn.php';

    // Retrieve the friend's ID from the request
    $friendId = $_GET['friend_id'];

    // Query to fetch the friend's profile data
    $sql = "SELECT users.*, username.fname, username.mname, username.lname, username.nickname, users.birthday, users.gender, preferences.interest, users.profile_picture 
            FROM users
            JOIN username ON users.nameid = username.nameid
            JOIN preferences ON users.preferences = preferences.prefid
            WHERE users.userid = $friendId";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Fetch the friend's profile data
        $row = $result->fetch_assoc();

        // Prepare the friend's profile data as JSON response
        $response = array(
            'success' => true,
            'fname' => $row['fname'],
            'mname' => $row['mname'],
            'lname' => $row['lname'],
            'nickname' => $row['nickname'],
            'gender' => $row['gender'],
            'location' => $row['location'],
            'birthday' => $row['birthday'],
            'age' => $row['age'],
            'interest' => explode(",", $row['interest']),
            'bio' => $row['bio'],
            'profile_picture' => $row['profile_picture']
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // If friend's profile not found, send error response
        $response = array('success' => false);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Close the database connection
    $conn->close();
} else {
    // If friend ID is not provided, send error response
    $response = array('success' => false);
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
