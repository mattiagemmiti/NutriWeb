<?php
// Start or resume a session to access session variables.
session_start();

// Set the content type of the response to JSON to facilitate the handling of responses in client-side JavaScript.
header('Content-Type: application/json');

// Establish a connection to the SQLite database stored at the specified path.
$db = new SQLite3('../db/nutriweb.db');

// Check if a user ID is provided in the GET request and ensure it is not empty.
if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    // Escapes the user ID to prevent SQL Injection and ensures it is a safe string for database queries.
    $id = SQLite3::escapeString($_GET['user_id']);

    // Execute a query to retrieve all entries from 'food_logs' where the user ID matches the provided ID.
    $result = $db->query("SELECT * FROM food_logs WHERE user_id = '$id'");

    // Initialize an array to store the details fetched from the database.
    $details = [];

    // If the result is valid, fetch each row as an associative array and add it to the details array.
    if ($result) {
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $details[] = $row;
        }
    }

    // Encode the details array into JSON format and send it back to the client.
    echo json_encode($details);
} else {
    // Return an empty JSON array if no user ID was provided or if it was empty.
    echo json_encode([]);

    // Log an error message indicating that no user ID was provided or it was empty.
    error_log("No user ID provided or user ID is empty");
}

// Log any database-related errors that occurred during the fetching process.
error_log("Error fetching details: " . $db->lastErrorMsg());

?>
