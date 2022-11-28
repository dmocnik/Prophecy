<?php
// Debug
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

session_start();
$username = $_SESSION['username'];

// Get info from POST variables
$university = $_POST['university'];
$degree = $_POST['degree'];
$major = $_POST['major'];
$minor = $_POST['minor'];
$graduation_year = $_POST['graduation_year'];
$gpa = $_POST['gpa'];

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
    // Check if education already exists where username = $username
    $sql = "SELECT * FROM education WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute() or trigger_error($stmt->error);
    $education_result = $stmt->get_result();
    $education_num_rows = $education_result->num_rows;

    // If education already exists, update education
    if ($education_num_rows > 0) {
        // Update user education
        $sql = "UPDATE education SET university = ?, degree = ?, major = ?, minor = ?, graduation_year = ?, gpa = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $university, $degree, $major, $minor, $graduation_year, $gpa, $username);
        $stmt->execute() or trigger_error($stmt->error);
        $stmt->close();
    } else {
        // Insert user education
        $sql = "INSERT INTO education (username, university, degree, major, minor, graduation_year, gpa) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $university, $degree, $major, $minor, $graduation_year, $gpa);
        $stmt->execute() or trigger_error($stmt->error);
        $stmt->close();
    }
    
    // Redirect to userinfo.php
    $_SESSION['toast'] = "Successfully updated education";
    header("Location: ../education.php");
}
else {
    die ("Connection failed: " . mysqli_connect_error());
}

?>
