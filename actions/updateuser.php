<?php
// Debug
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

session_start();
$username = $_SESSION['username'];

// Get info from POST variables
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

if ($conn) {
    // Update user info
    $sql = "UPDATE user_details SET first_name = ?, last_name = ?, email = ?, phone_number = ?, city = ?, state = ?, zip_code = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $phone_number, $city, $state, $zip_code, $username);
    $stmt->execute() or trigger_error($stmt->error);
    $stmt->close();

    // Redirect to userinfo.php
    $_SESSION['toast'] = "Successfully updated user info";
    header("Location: ../userinfo.php");
}
else {
    die ("Connection failed: " . mysqli_connect_error());
}

?>
