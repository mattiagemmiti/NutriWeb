<?php
// Start or resume a session. This is essential for accessing session variables, if needed.
session_start();

// Set the content type of the HTTP response to JSON to ensure proper handling by the client-side code.
header('Content-Type: application/json');

// Establish a connection to the SQLite database 
$db = new SQLite3('../db/nutriweb.db');

// Execute a SQL query to retrieve distinct user IDs and usernames of all users with the role of 'user'.
$result = $db->query("SELECT DISTINCT id, username FROM users WHERE role = 'user'");

// Initialize an array to store user data.
$users = [];

// Iterate through each row of the result set.
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Append each user's ID and username to the users array.
    $users[] = [
        'user_id' => $row['id'],    // Maps 'id' from the database to 'user_id' in the response
        'username' => $row['username'] // Include the username
    ];
}

// Encode the users array into JSON format and output it.
// This provides a structured response format that can be easily processed by the client.
echo json_encode($users);
?>
