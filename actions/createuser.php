<?php
// Debug
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

// Get info from POST variables
$username = $_POST['username'];
$password = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip_code = $_POST['zip_code'];

// Get database info from json file in db_config.json
$db_config = json_decode(file_get_contents('../key/db_config.json'), true);

// Connect to database
$host = $db_config['host'];
$user = $db_config['user'];
$pass = $db_config['pass'];
$db = $db_config['db'];
$port = $db_config['port'];

$conn = mysqli_connect ($host, $user, $pass, $db, $port);
session_start();

if ($conn) {
    // Check if username already exists in table
    $sql = "SELECT * FROM user_details WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        $_SESSION['toast'] = "Username already exists";
        header("Location: ../signup.php");
    }
    else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO user_details (username, password, first_name, last_name, email, phone_number, city, state, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $username, $hashed_password, $first_name, $last_name, $email, $phone_number, $city, $state, $zip_code);
        $stmt->execute() or trigger_error($stmt->error);

        // Log user in
        $stmt->close();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $hashed_password;
        $_SESSION['toast'] = "Account created successfully";
        header("Location: ../main.php");
    }
}
else {
    die ("Connection failed: " . mysqli_connect_error());
}

?>
