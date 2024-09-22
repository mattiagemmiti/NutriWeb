<?php
// Starts a new or resumes an existing session to access session variables
session_start();

// Sets the content type of the response to JSON for client-side compatibility
header('Content-Type: application/json');

// Defines the data source name for the SQLite database connection
$dsn = 'sqlite:../db/nutriweb.db';

try {
    // Tries to establish a database connection with the specified data source name
    $pdo = new PDO($dsn);
    // Sets the PDO error mode to exception to handle potential SQL errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Catches and encodes the database connection error as JSON, then exits the script
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Checks if the user ID is stored in the session to verify user authentication
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    // Prepares a SQL statement to fetch feedback messages for the authenticated user
    $sql = 'SELECT message, date FROM feedback_messages WHERE user_id = :user_id ORDER BY date DESC';
    $stmt = $pdo->prepare($sql);
    // Executes the SQL query with the user_id parameter bound to it
    $stmt->execute(['user_id' => $user_id]);
    // Fetches all resulting feedback messages as an associative array
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Encodes and sends the fetched feedback messages as a JSON response
    echo json_encode($feedbacks);
} else {
    // Returns an error message as JSON if the user ID is missing or not set
    echo json_encode(['error' => 'User ID is missing.']);
}
?>
