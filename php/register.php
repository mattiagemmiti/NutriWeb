<?php
// Assuming POST method is used for form submission to register a new user.

// Establish a connection to the SQLite database.
$db = new SQLite3('../db/nutriweb.db');

// Retrieve the username, password, and role from the POST request.
$username = $_POST['username'];
$password = $_POST['password'];  // Password will be hashed before storing in the database.
$role = $_POST['role'];  // User role, for example: 'user' or 'nutritionist'.

// Prepare an SQL statement to insert the new user into the 'users' table.
$query = $db->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');

// Bind the username to the prepared statement to avoid SQL injection.
$query->bindValue(':username', $username, SQLITE3_TEXT);

// Hash the password using password_hash() to ensure it is securely stored in the database.
$query->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), SQLITE3_TEXT);

// Bind the role to the prepared statement.
$query->bindValue(':role', $role, SQLITE3_TEXT);

// Execute the query and check if the user was successfully registered.
if ($query->execute()) {
    // If the registration is successful, display a success message with a link to the login page.
    echo "User registered successfully! <a href='../login.html'>Click here to log in</a>";
} else {
    // If the registration fails, display an error message.
    echo "Registration failed!";
}
?>
