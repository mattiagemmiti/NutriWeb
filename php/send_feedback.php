<?php
// Establish a connection to the SQLite database using PDO.
$dsn = 'sqlite:../db/nutriweb.db';

try {
    // Attempt to create a new PDO connection with error handling set to exception mode.
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, terminate the script and display an error message.
    die('Connection failed: ' . $e->getMessage());
}

// Retrieve the JSON-encoded data from the request body.
$data = json_decode(file_get_contents('php://input'), true);

// Extract the user ID and message from the decoded data.
$user_id = $data['user_id'];
$message = $data['message'];

// Prepare an SQL statement to insert the feedback message into the 'feedback_messages' table.
$sql = 'INSERT INTO feedback_messages (user_id, message) VALUES (:user_id, :message)';
$stmt = $pdo->prepare($sql);

// Execute the prepared statement with the user ID and message parameters.
$stmt->execute(['user_id' => $user_id, 'message' => $message]);

// Send a success message back to the client.
echo 'Feedback sent successfully';
?>
