<?php
    // Debug
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    session_start();
    $username = $_SESSION['username'];

    // Get info from POST variables
    $template = $_POST['template'];

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
        // Get user info from database
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

        if ($template == "template1") {
            // $latex_style = file_get_contents('../latex/casual.cls');
            $latex_content = file_get_contents('../latex/casual.tex');
        }
        if ($template == "template2") {
            // $latex_style = file_get_contents('../latex/columns.cls');
            $latex_content = file_get_contents('../latex/columns.tex');
        }
        if ($template == "template3") {
            // $latex_style = file_get_contents('../latex/direct.cls');
            $latex_content = file_get_contents('../latex/direct.tex');
        }

        // Replace placeholders in latex file with user info
        // if ($user_row['minor'] != "") {
        //     $latex_content = str_replace("%AAAAAAAAAA", "\item{Minor - [minor]}", $latex_content);
        // }
        
        // if ($user_row['company_2'] != "") {
        //     $latex_content = str_replace("%BBBBBBBBBB", "\begin{desc}{[job\_title\_2] - [company\_2]} \item{Years of experience: [years\_of\_experience\_2]}\\end{desc}", $latex_content);
        // }

        // if ($user_row['company_3'] != "") {
        //     $latex_content = str_replace("%CCCCCCCCCC", "\begin{desc}{[job\_title\_3] - [company\_3]}\item{Years of experience: [years\_of\_experience\_3]}\\end{desc}", $latex_content);
        // }

        // if ($user_row['skill_2'] != "") {
        //     $latex_content = str_replace("%DDDDDDDDDD", "\item {[skill\_2]}", $latex_content);
        // }
        
        // if ($user_row['skill_3'] != "") {
        //     $latex_content = str_replace("%EEEEEEEEEE", "\item {[skill\_3]}", $latex_content);
        // }

        // Contact Info
        $latex_content = str_replace("[first\_name]", $user_row['first_name'], $latex_content);
        $latex_content = str_replace("[last\_name]", $user_row['last_name'], $latex_content);
        $latex_content = str_replace("[email]", $user_row['email'], $latex_content);
        $latex_content = str_replace("[phone\_number]", $user_row['phone_number'], $latex_content);
        $latex_content = str_replace("[city]", $user_row['city'], $latex_content);
        $latex_content = str_replace("[state]", $user_row['state'], $latex_content);
        $latex_content = str_replace("[zip\_code]", $user_row['zip_code'], $latex_content);

        // Skills
        $num = 1;
        while ($skill_row = $skills_result->fetch_assoc()) {
            $latex_content = str_replace("[skill\_$num]", $skill_row['skill_name'] . " - " . $skill_row['skill_description'], $latex_content);
            $num++;
        }

        // Employment
        // $latex_content = str_replace("[company\_1]", $user_row['company_1'], $latex_content);
        // $latex_content = str_replace("[job\_title\_1]", $user_row['job_title_1'], $latex_content);
        // $latex_content = str_replace("[years\_of\_experience\_1]", $user_row['years_of_experience_1'], $latex_content);
        $num = 1;
        while ($prev_employment_row = $prev_employment_result->fetch_assoc()) {
            $latex_content = str_replace("[company\_$num]", $prev_employment_row['company'], $latex_content);
            $latex_content = str_replace("[job\_title\_$num]", $prev_employment_row['job_title'], $latex_content);
            $latex_content = str_replace("[years\_of\_experience\_$num]", $prev_employment_row['years_of_experience'], $latex_content);
            $num++;
        }

        // Education
        $num = 1;
        while ($education_row = $education_result->fetch_assoc()) {
            // $latex_content = str_replace("[university\_college\_$num]", $education_row['university_college'], $latex_content);
            // $latex_content = str_replace("[major\_$num]", $education_row['major'], $latex_content);
            // $latex_content = str_replace("[year\_of\_graduation\_$num]", $education_row['year_of_graduation'], $latex_content);
            // $latex_content = str_replace("[gpa\_$num]", $education_row['gpa'], $latex_content);
            // $latex_content = str_replace("[degree\_$num]", $education_row['degree'], $latex_content);
            // $latex_content = str_replace("[minor\_$num]", $education_row['minor'], $latex_content);
            $latex_content = str_replace("[university\_college]", $education_row['university'], $latex_content);
            $latex_content = str_replace("[major]", $education_row['major'], $latex_content);
            $latex_content = str_replace("[year\_of\_graduation]", $education_row['graduation_year'], $latex_content);
            $latex_content = str_replace("[gpa]", $education_row['gpa'], $latex_content);
            $latex_content = str_replace("[degree]", $education_row['degree'], $latex_content);
            $latex_content = str_replace("[minor]", $education_row['minor'], $latex_content);
            $num++;
        }

        // Convert the latex file to a pdf
        $latex_file = fopen("../latex/user/$username.tex", "w") or die("Unable to open file!");
        fwrite($latex_file, $latex_content);
        fclose($latex_file);

        // Compile the latex file using pdflatex
        chdir("../latex/user");
        $current_dir = getcwd();
        // C:\miktex-portable\texmfs\install\miktex\bin\x64
        $command = "C:\\miktex-portable\\texmfs\\install\\miktex\\bin\\x64\\pdflatex.exe -interaction=nonstopmode -output-directory=\"$current_dir\" \"$username.tex\"";
        $proc = proc_open($command, array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w"),  // stderr
        ), $pipes, $current_dir, null, array('bypass_shell' => true));
        if (is_resource($proc)) {
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            $return_value = proc_close($proc);
        }
        // echo $stdout;
        // echo "<br>";
        // echo $stderr;
        // echo "<br>";
        // echo $return_value;
        chdir("../../actions");

        // Download the pdf
        $file = "../latex/user/$username.pdf";
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile ($file);
        // exit;

        // Redirect to userinfo.php
        $_SESSION['toast'] = "Your resume has been created successfully!";
        // header("Refresh:5; url=../new.php");
    }
    else {
        die ("Connection failed: " . mysqli_connect_error());
    }
?>