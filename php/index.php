<?php 
// Execute the creation of the database Script by http request
// if tables already exist, will be handled by the script itself
file_get_contents('http://localhost/NutriWeb-main/php/init_db.php');

// Login html page redirect
header('Location: ../login.html');
exit();
?>
