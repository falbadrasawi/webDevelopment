<?php
header('Content-Type: application/json'); //Set content type of the response to JSON

$host = 'localhost'; //The hostname of the MySQL server
$dbname = 'user_management'; //The name of the database
$username = 'root'; //The MySQL username (replace with actual username)
$password = ''; //The MySQL password (replace with your actual password)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); //Create a new PDO instance to connect to the databse
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Set the PDO error mode to exception handle errors
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]); //If the connection fails, send an error message to JSON
    exit; //Stop the script execution
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Check if the request method is POST
    $username = $_POST['username'] ?? ''; //Retrieve the 'username' from the POST data, or set it to an empty string if not set 
    $password = $_POST['password'] ?? ''; //Retrieve the 'password' from the POST data, or set  it to an empty string if not set

    if (empty($username) || empty($password)) { //Check if either the username or password is empty
        echo json_encode(['success' => false, 'message' => 'Username and password are required.']); //Send an error message as JSON 
        exit; //Stop the script execution
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?"); //Prepare an SQL statement to check if the username already exists
    $stmt->execute([$username]); //Executre the statement with the provided username 
    if ($stmt->rowCount() > 0) { //Check if any rows were returned (i.e the username already exists)
        echo json_encode(['success' => false, 'message' => 'Username is already taken']); //Send an error message as JSON
        exit; //Stop the script execution
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password using the bycript algorithm

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)"); //Prepare an SQL statement to insert a new user
    if ($stmt->execute([$username, $hashedPassword])) { //Execute the statement with the provided username and hashed password
        echo json_encode(['success' => true, 'message' => 'User registed successfully']); //Send a success message as JSON
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to register user.']); //Send an error message as JSON if the insert failed
    }
}
?>