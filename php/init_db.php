<?php
// Establish a connection to the SQLite database file.
$db = new SQLite3('../db/nutriweb.db');

// Create the 'users' table if it doesn't already exist, with columns for user ID, username, password, and role.
// The ID is the primary key and auto-increments with each new entry.
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    username TEXT NOT NULL, 
    password TEXT NOT NULL,
    role TEXT NOT NULL
)");

// Comments explaining the structure and purpose of the 'users' table:
// - 'username': Stores the username, must be a non-empty text.
// - 'password': Stores the password, must be a non-empty text.
// - 'role': Stores the role of the user (e.g., 'user', 'admin'), must be a non-empty text.

// Create the 'food_logs' table to store dietary entries for each user.
// This table includes a foreign key that references the 'id' column of the 'users' table.
$db->exec("CREATE TABLE IF NOT EXISTS food_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    user_id INTEGER,
    date DATE NOT NULL,
    food TEXT NOT NULL,
    portion INTEGER NOT NULL,
    calories INTEGER,
    carbs INTEGER,
    proteins INTEGER,
    fiber INTEGER,
    fats INTEGER,
    FOREIGN KEY(user_id) REFERENCES users(id)
)");

// Comments explaining the structure and purpose of the 'food_logs' table:
// - 'date': Records the date of the food log.
// - 'food': Describes the food item.
// - 'portion': Quantifies the amount of food in an integer.
// - Other fields like 'calories', 'carbs', 'proteins', 'fiber', 'fats' represent nutritional information.

// Create the 'feedback_messages' table to store feedback from nutritionists to users.
// This table includes a timestamp for when the message was created.
$db->exec("CREATE TABLE IF NOT EXISTS feedback_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    message TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

// Comments explaining the structure and purpose of the 'feedback_messages' table:
// - 'message': Text of the feedback provided.
// - 'date': Automatically records the current timestamp when the feedback is entered.

// Output a message to indicate successful database initialization.
echo "Database initialized successfully!";
?>
