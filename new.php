<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" , shrink-to-fit="no">
    <meta name="description" content="Prophecy - Resume Generator" />
    <meta name="author" content="AgroStart" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prophecy - New Resume</title>
    <script src="js/feather.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <!-- Loading spinner -->
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
                    <a class="nav-link active <?php echo $disabled; ?>" href="#"><i data-feather="file-plus"></i><br>New
                        Resume</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="userinfo.php"><i
                            data-feather="user"></i><br>Personal Info</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="skills.php"><i
                            data-feather="award"></i><br>Skills</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="prevemployment.php"><i
                            data-feather="briefcase"></i><br>Prev Employment</a>
                    <a class="nav-link <?php echo $disabled; ?>" href="education.php"><i
                            data-feather="book"></i><br>Education</a>
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
        $user_result = $stmt->get_result();
        $user_row = $user_result->fetch_assoc();
        $user_num_rows = $user_result->num_rows;

        // Get skills from database
        $sql = "SELECT * FROM skills WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute() or trigger_error($stmt->error);
        $skills_result = $stmt->get_result();
        $skills_num_rows = $skills_result->num_rows;

        // Get prev employment from database
        $sql = "SELECT * FROM employment WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute() or trigger_error($stmt->error);
        $prev_employment_result = $stmt->get_result();
        $prev_employment_num_rows = $prev_employment_result->num_rows;

        // Get education from database
        $sql = "SELECT * FROM education WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute() or trigger_error($stmt->error);
        $education_result = $stmt->get_result();
        $education_num_rows = $education_result->num_rows;
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
                            <h3><i data-feather="file-plus"></i> New Resume</h3>
                            <?php
                            if ($education_num_rows < 1 || $prev_employment_num_rows < 1 || $skills_num_rows < 1) {
                                echo '<div class="alert alert-warning" role="alert">';
                                echo 'You must have at least one skill, one previous employment, and one education entry to create a resume. As such, you are unable to select a template.';
                                echo '</div>';
                                $button_disabled = "disabled";
                            } else {
                                echo '<div class="alert alert-success" role="alert">';
                                echo 'You have enough entries to create a resume. Please choose a template below.';
                                echo '</div>';
                                $button_disabled = "";
                            }
                            ?>
                            <form action="actions/create_resume.php" method="post">
                                <div class="form-check">
                                    <div class="row align-items-md-stretch">
                                        <div class="col-md-4">
                                            <input class="form-check-input" type="radio" name="template" id="template1"
                                                value="template1" checked>
                                            <label class="form-check-label" for="template1">
                                                <img src="img/template1.png" class="img-fluid" alt="Responsive image">
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-check-input" type="radio" name="template" id="template2"
                                                value="template2">
                                            <label class="form-check-label" for="template2">
                                                <img src="img/template2.png" class="img-fluid" alt="Responsive image">
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-check-input" type="radio" name="template" id="template3"
                                                value="template3">
                                            <label class="form-check-label" for="template3">
                                                <img src="img/template3.png" class="img-fluid" alt="Responsive image">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" <?php echo $button_disabled; ?>>Create
                                    Resume</button>
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