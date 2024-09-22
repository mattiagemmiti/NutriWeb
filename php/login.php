<?php
// Start the session to manage user authentication states.
session_start();

// Establish a connection to the SQLite database.
$db = new SQLite3('../db/nutriweb.db');

// Retrieve the username and password from the POST request.
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare an SQL query to fetch the user details based on the provided username.
$query = $db->prepare('SELECT id, password, role FROM users WHERE username = :username');
// Bind the username value to the SQL query to prevent SQL injection.
$query->bindValue(':username', $username, SQLITE3_TEXT);
// Execute the query to get the user record from the database.
$result = $query->execute();

// Fetch the user's details as an associative array.
$user = $result->fetchArray(SQLITE3_ASSOC);

// Check if the user exists and if the provided password matches the hashed password in the database.
if ($user && password_verify($password, $user['password'])) {
    // Store the user ID and role in session variables for later use.
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    
    // Redirect the user based on their role (e.g., nutritionist or regular user).
    if ($user['role'] === 'nutritionist') {
        // Redirect nutritionists to their dashboard.
        header('Location: ../nutritionist_dashboard.html');
        exit;
    } else {
        // Redirect regular users to their dashboard.
        header('Location: ../user.html'); 
        exit;
    }
} else {
    // Output an error message if the username or password is incorrect.
    echo "Invalid username or password!";
}
?>
