<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" , shrink-to-fit="no">
    <meta name="description" content="Prophecy - Resume Generator" />
    <meta name="author" content="AgroStart" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prophecy - Home</title>
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
    session_start();
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
                    <a class="nav-link disabled" href="#contact"><i data-feather="mail"></i><br>Contact</a>
                    <a class="nav-link disabled" href="new.php"><i data-feather="file-plus"></i><br>New Resume</a>
                    <a class="nav-link disabled" href="userinfo.php"><i data-feather="user"></i><br>Personal Info</a>
                    <a class="nav-link disabled" href="skills.php"><i data-feather="award"></i><br>Skills</a>
                    <a class="nav-link disabled" href="prevemployment.php"><i data-feather="briefcase"></i><br>Prev Employment</a>
                    <a class="nav-link disabled" href="education.php"><i data-feather="book"></i><br>Education</a>
                    <a class="nav-link active" href="#"><i data-feather="log-in"></i><br>Login / Sign Up</a>
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
        // [Debug] Database commands here
    }
    else {
        die ("Connection failed: " . mysqli_connect_error());
    }

    ?>

    <div class="row align-items-md-stretch"
        style="margin-left: 24px; margin-right: 24px; margin-top: 24px; margin-bottom: 12px; padding-top: 72px;">
        <div class="col-md-12" style="margin-top: 12px; margin-bottom: 128px;">
            <div class="h-100 p-5 bg-light border rounded-3">
                <!-- <div class="h-100 p-5 bg-light border rounded-3"> -->
                <?php
                    if (isset($_SESSION['toast']) && $_SESSION['toast'] != "") {
                        echo '<div class="alert alert-warning" role="alert">';
                        echo $_SESSION['toast'];
                        echo '</div>';
                        $_SESSION['toast'] = "";
                    }
                    ?>

                <div class="row align-items-md-stretch" id="viewer_main" name="viewer_main">
                    <div class="col-md-8">
                        <h3><i data-feather="log-in"></i> Login</h3>
                        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
                        <form action="actions/checklogin.php" method="post">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input autocomplete="off" type="text" class="form-control" id="username" name="username"
                                    placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input autocomplete="off" type="password" class="form-control" id="password"
                                    name="password" placeholder="Password" required>
                            </div>
                            <p>Forgot password? <a
                                    href="mailto:mwcurtis@oakland.edu?subject=Prophecy%20Password%20Reset%20Request&body=I%20forgot%20my%20password%20to%20Prophecy.%20Please%20reset%20it%20and%20email%20me%20back.%20Thanks!">Click
                                    here</a></p>
                            <div class="d-grid gap-2 d-md-block" style="margin-top: 8px;">
                                <button type="submit" class="btn btn-primary"><i data-feather="user"></i>
                                    Login</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- </div> -->

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