<?php
// Get username from POST variable
$username = $_POST['username'];

// Get password from POST variable
$password = $_POST['password'];

// Get database info from json file in db_config.json
$db_config = json_decode(file_get_contents('../keys/DB_CREDENTIALS.json'), true);

// Connect to database
$host = $db_config['host'];
$user = $db_config['user'];
$pass = $db_config['pass'];
$db = $db_config['db'];
$port = $db_config['port'];

$conn = mysqli_connect ($host, $user, $pass, $db, $port);

if ($conn) {
    // Check if username and password are correct
    $sql = "SELECT * FROM user_details WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);

    if ($numrows == 1) {
        // If username and password are correct, set session variables and redirect to dashboard.php
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $conn->close();
        echo "<script type='text/javascript'>alert('Welcome back, $username!');</script>";
        // header("Location: ../screens/PickTemplate.html");
    }
    else {
        $info = "LOGIN INCORRECT";
        $conn->close();
        echo "<script type='text/javascript'>alert('$info');</script>";
        // header("Location: ../login.php?info=$info");
    }
}
else {
    die ("Connection failed: " . mysqli_connect_error());
    $info = "CONNECTION FAILED";
    echo "<script type='text/javascript'>alert('$info');</script>";
    // header('Location: ../login.php?info=$info');
}