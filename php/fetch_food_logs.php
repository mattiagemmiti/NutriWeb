<?php
// Start the session to access session variables
session_start();

// Set the content type of the response to JSON, ensuring the client-side handles it correctly
header('Content-Type: application/json');

// Check if the user is logged in by confirming the existence of 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    // If not logged in, send an empty JSON array and terminate the script
    echo json_encode([]);
    exit;
}

// Establish a connection to the SQLite database
$db = new SQLite3('../db/nutriweb.db');

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Execute a query to retrieve all food logs for the logged-in user
$result = $db->query("SELECT * FROM food_logs WHERE user_id = $user_id");

// Initialize an array to hold the food logs
$foods = [];

// Loop through each row of the result set
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Append each food log entry to the foods array without modification
    // Data is assumed to be correctly scaled and formatted when stored
    $foods[] = [
        'date' => $row['date'],  // Date of the food log entry
        'food' => $row['food'],  // Name of the food item
        'portion' => $row['portion'],  // Portion size consumed
        'calories' => $row['calories'],  // Caloric content of the portion
        'carbs' => $row['carbs'],  // Carbohydrates content of the portion
        'proteins' => $row['proteins'],  // Proteins content of the portion
        'fats' => $row['fats'],  // Fats content of the portion
        'fiber' => $row['fiber']  // Fiber content of the portion
    ];
}

// Encode the foods array into JSON format and output it
echo json_encode($foods);
?>
