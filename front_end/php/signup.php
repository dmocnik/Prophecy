<?php
// Get info from POST variable
$username = $_POST['username'];
$password = $_POST['password'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];

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
    // $info = "CONNECTED TO DATABASE";
    // echo "<script type='text/javascript'>alert('$info');</script>";
    
    // Check if username already exists
    $sql = "SELECT * FROM user_details WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);

    // $info = "CHECKING IF USERNAME EXISTS";
    // echo "<script type='text/javascript'>alert('$info');</script>";

    if ($numrows == 0) {
        // If username does not exist, insert new user into database
        $sql = "INSERT INTO user_details (username, password, first_name, last_name, email) VALUES ('$username', '$password', '$firstname', '$lastname', '$email')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            // If username and password are correct, set session variables and redirect to dashboard.php
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $conn->close();
            echo "<script type='text/javascript'>alert('Welcome to Prophecy, $username!');</script>";
            // header("Location: dashboard.php");
        }
        else {
            // If user is not successfully inserted into database, redirect to signup.html
            echo "<script> alert('Signup failed'); </script>";
            sleep(1);
            $conn->close();
            // header("Location: signup.html");
        }
    }
    else {
        // If username already exists, redirect to login.html
        echo "<script> alert('Hey! Username already exists. Try another one.'); </script>";
        sleep(1);
        $conn->close();
        // header("Location: login.html");
    }
}
else {
    die ("Connection failed: " . mysqli_connect_error());
    echo "<script> alert('Connection failed'); </script>";
    sleep(1);
    // header('Location: signup.html');
}