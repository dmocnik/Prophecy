<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" , shrink-to-fit="no">
    <meta name="description" content="Prophecy - Resume Generator" />
    <meta name="author" content="AgroStart" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prophecy - Personal Info</title>
    <script src="js/feather.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    /<!-- Loading spinner -->
    <div id="loading-spinner">
        <div class="spinner-background"></div>
        <!-- Div with spinner icon -->
        <div class="spinner"></div>
    </div>

    <?php
    // Debug
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);

    session_start();
    // If username session var is not set or password is not set, redirect to login page
    if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
        $disabled = "disabled";
        header("Location: login.php");
    } else {
        $disabled = "";
    }
    ?>

    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="navbar-brand" href="main.php"><img src="./img/favicon.png" alt="" width="32" height="32">
                        Prophecy</a>
                    <a class="nav-link" href="main.php"><i data-feather="home"></i><br>Home</a>
                    <a class="nav-link" href="main.php#about"><i data-feather="info"></i><br>About</a>
                    <a class="nav-link" href="#contact"><i data-feather="mail"></i><br>Contact</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="new.php"><i data-feather="file-plus"></i><br>New
                        Resume</a>
                    <a class="nav-link active <?php echo $disabled; ?>" href="#"><i data-feather="user"></i><br>Personal
                        Info</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="skills.php"><i data-feather="award"></i><br>Skills</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="prevemployment.php"><i data-feather="briefcase"></i><br>Prev Employment</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="education.php"><i data-feather="book"></i><br>Education</a>
                    <?php
                    if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
                        echo '<a class="nav-link" href="login.php"><i data-feather="log-in"></i><br>Login / Sign Up</a>';
                    } else {
                        echo '<a class="nav-link" href="actions/logout.php"><i data-feather="log-out"></i><br>Logout</a>';
                    }
                    ?>
                    <a class="nav-link rightmost" target="_blank" href="https://github.com/PhysCorp"><i
                            data-feather="github"></i><br>GitHub</a>
                </div>
            </div>
        </div>
    </nav>

    <?php
    // Get database info from json file in db_config.json
    $db_config = json_decode(file_get_contents('key/db_config.json'), true);

    // Connect to database
    $host = $db_config['host'];
    $user = $db_config['user'];
    $pass = $db_config['pass'];
    $db = $db_config['db'];
    $port = $db_config['port'];

    $conn = mysqli_connect ($host, $user, $pass, $db, $port);

    if ($conn) {
        // Get user info from database
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM user_details WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute() or trigger_error($stmt->error);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    }
    else {
        die ("Connection failed: " . mysqli_connect_error());
    }

    ?>

    <div class="row align-items-md-stretch"
        style="margin-left: 24px; margin-right: 24px; margin-top: 24px; margin-bottom: 12px; padding-top: 72px;">
        <div class="col-md-12" style="margin-top: 12px; margin-bottom: 128px;">
            <div class="h-100 p-5 bg-light border rounded-3">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <?php
                    if (isset($_SESSION['toast']) && $_SESSION['toast'] != "") {
                        echo '<div class="alert alert-warning" role="alert">';
                        echo $_SESSION['toast'];
                        echo '</div>';
                        $_SESSION['toast'] = "";
                    }
                    ?>

                    <div class="row align-items-md-stretch" id="about" name="about">
                        <div class="col-md-8">
                            <h3><i data-feather="user"></i> Personal Information</h3>
                            <form action="actions/updateuser.php" method="post">
                                <div class="row align-items-md-stretch" id="viewer_main" name="viewer_main">
                                    <div class="col-md-12">
                                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                                    
                                        <label for="first_name" class="form-label>">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            value="<?php echo $row['first_name']; ?>">

                                        <label for="last_name" class="form-label>">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="<?php echo $row['last_name']; ?>">
                                        
                                        <label for="email" class="form-label>">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo $row['email']; ?>">
                                        
                                        <label for="phone_number" class="form-label>">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                                            value="<?php echo $row['phone_number']; ?>">
                                        
                                        <label for="city" class="form-label>">City</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="<?php echo $row['city']; ?>">
                                        
                                        <label for="state" class="form-label>">State</label>
                                        <input type="text" class="form-control" id="state" name="state"
                                            value="<?php echo $row['state']; ?>">
                                        
                                        <label for="zip" class="form-label>">Zip</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code"
                                            value="<?php echo $row['zip_code']; ?>">
                                        
                                        <button type="submit" class="btn btn-primary" style="margin-top: 12px;"><i data-feather="save"></i> Save</button>
                                        <span style="display: inline-block; width: 6px;"></span>
                                        <a href="main.php" class="btn btn-secondary" style="margin-top: 12px;"><i data-feather="x"></i> Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-dark">
        <div class="container">
            <span class="text-muted">
                <p style="text-align: center;">Copyright AgroStart 2022 | Icons retrieved from Feather icons,
                    which is under
                    the MIT license <span class="badge bg-secondary">VER 22.11.0</span></p>
            </span>
        </div>
        <div class="row align-items-md-stretch">
            <div class="col-md-12">
                <div class="h-100 p-5 bg-light border rounded-3" style="margin-bottom: 24px;">
                    <h2 id="contact">Contact Us:</h2>
                    <p>If you have any questions, comments, or concerns, feel free to contact us with the information
                        below:</p>
                    <a href="mailto:mwcurtis@oakland.edu" target="_blank" style="float: left;"><i
                            data-feather="mail"></i> Email (mwcurtis@oakland.edu)</a>
                    <a href="tel:810-412-8751" target="_blank" style="float: left; margin-left: 12px;"><i
                            data-feather="phone"></i> Phone (810-412-8751)</a>
                    <a href="https://goo.gl/maps/vr2BV5TdEUXktHKS7" target="_blank"
                        style="float: left; margin-left: 12px;"><i data-feather="map-pin"></i> School (Oakland
                        University)</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap javascript -->
    <script src="./js/bootstrap.bundle.min.js"></script>

    <!-- Replace feather icons -->
    <script>
        feather.replace()
    </script>

    <!-- Close the connection -->
    <?php
    $conn->close();
    ?>

    <!-- Remove loading spinner with id loading-spinner -->
    <script>
        document.getElementById("loading-spinner").remove();
    </script>
</body>

</html>