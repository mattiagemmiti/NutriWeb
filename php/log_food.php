<?php
// Start a session to access session variables.
session_start();

// Establish a connection to the SQLite database.
$db = new SQLite3('../db/nutriweb.db');

// Retrieve user ID from session, assuming the user is already logged in.
$user_id = $_SESSION['user_id'];

// Retrieve food-related data sent via POST request.
$food = $_POST['foodName'];
$portion = $_POST['portionSize'];

// Calculate the nutritional content based on the portion size entered.
$calories = $_POST['calories'] * ($portion / 100);
$carbs = $_POST['carbs'] * ($portion / 100);
$proteins = $_POST['proteins'] * ($portion / 100);
$fats = $_POST['fats'] * ($portion / 100);
$fiber = $_POST['fiber'] * ($portion / 100);

// Use the current date for the log entry.
$date = date('Y-m-d');

// Prepare an SQL statement to insert food log data into the 'food_logs' table.
$query = $db->prepare('INSERT INTO food_logs (user_id, date, food, portion, calories, carbs, proteins, fats, fiber) VALUES (:user_id, :date, :food, :portion, :calories, :carbs, :proteins, :fats, :fiber)');

// Bind values to the SQL statement to avoid SQL injection.
$query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$query->bindValue(':date', $date, SQLITE3_TEXT);
$query->bindValue(':food', $food, SQLITE3_TEXT);
$query->bindValue(':portion', $portion, SQLITE3_INTEGER);
$query->bindValue(':calories', $calories, SQLITE3_FLOAT);
$query->bindValue(':carbs', $carbs, SQLITE3_FLOAT);
$query->bindValue(':proteins', $proteins, SQLITE3_FLOAT);
$query->bindValue(':fats', $fats, SQLITE3_FLOAT);
$query->bindValue(':fiber', $fiber, SQLITE3_FLOAT);

// Execute the query and check for success.
if ($query->execute()) {
    echo "Food log added successfully!";
} else {
    echo "Failed to add food log.";
}
?>
