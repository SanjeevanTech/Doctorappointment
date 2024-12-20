<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdWl3w58Bvv0RmZr1r4R6hh5C3m5eF3R/qJ3" crossorigin="anonymous">
        
    <title>Patient Dashboard</title>
    <style>
        .dashboard-section {
            animation: transitionIn-Y-over 0.7s;
            padding: 20px;
            transition: margin-top 0.3s ease;
        }
        .welcome-section {
            position: relative;
            color: black;
            background-image: url(../img/b8.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            text-align: left;
            padding: 20px;
            margin-bottom: 20px;
        }

        .status-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .status-item {
            flex: 1;
            margin: 10px;
            padding: 20px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            text-align: center;
        }
        .session-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .session-table {
            width: 100%;
            border-collapse: collapse;
        }
        .session-table th, .session-table td {
            padding: 10px;
            border: 1px solid #e0e0e0;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION["user"]) && $_SESSION['usertype'] === 'p') {
        $useremail = $_SESSION["user"];
    } else {
        header("location: ../login.php");
        exit;
    }

    include("../connection.php");
    $userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    // Queries for counts
    $patientrow = $database->query("SELECT * FROM patient");
    $doctorrow = $database->query("SELECT * FROM doctor");
    $appointmentrow = $database->query("SELECT * FROM appointment WHERE appodate >= CURDATE()");
    $schedulerow = $database->query("SELECT * FROM schedule WHERE scheduledate = CURDATE()");
    ?>

    <div class="container-fluid p-0">
        <?php include("Patient_navbar.php"); ?>

        <div class="dashboard-section">
            <div class="welcome-section">
                <h3>Welcome!</h3>
                <h1><?php echo htmlspecialchars($username); ?>.</h1>
                <p>Haven't any idea about doctors? No problem! Jump to 
                    <a href="doctors.php" class="non-style-link"><b>"All Doctors"</b></a> or 
                    <a href="schedule.php" class="non-style-link"><b>"Sessions"</b></a><br>
                    Track your past and future appointments history.<br>Also find out the expected arrival time of your doctor or medical consultant.<br><br>
                </p>

  
                <div class="search-container" style="margin-top: 20px;">
    <form action="schedule.php" method="post" style="display: flex;">
        <input type="search" name="search" class="form-control me-2" placeholder="Search Doctor" list="doctors" style="width: 70%;">
        <datalist id="doctors">
            <?php
                $list11 = $database->query("SELECT docname, docemail FROM doctor;");
                while ($row00 = $list11->fetch_assoc()) {
                    $d = $row00["docname"];
                    echo "<option value='$d'>";
                }
            ?>
        </datalist>
        <input type="submit" value="Search" class="btn btn-primary" style="padding: 10px 20px; margin-left: 10px;">
    </form>
</div>


            </div>

            <div class="status-container">
                <div class="status-item">
                    <h2><?php echo $doctorrow ? $doctorrow->num_rows : 0; ?></h2>
                    <p>All Doctors</p>
                </div>
                <div class="status-item">
                    <h2><?php echo $patientrow ? $patientrow->num_rows : 0; ?></h2>
                    <p>All Patients</p>
                </div>
                <div class="status-item">
                    <h2><?php echo $appointmentrow ? $appointmentrow->num_rows : 0; ?></h2>
                    <p>New Bookings</p>
                </div>
                <div class="status-item">
                    <h2><?php echo $schedulerow ? $schedulerow->num_rows : 0; ?></h2>
                    <p>Today Sessions</p>
                </div>
            </div>

            <div class="session-container">
                <h2 class="text-center my-4">Your Upcoming Bookings</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Appoint. Number</th>
                                <th>Session Title</th>
                                <th>Doctor</th>
                                <th>Scheduled Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nextweek = date("Y-m-d", strtotime("+1 week"));
                            $sqlmain = "SELECT * FROM schedule 
                                         INNER JOIN appointment ON schedule.scheduleid = appointment.scheduleid 
                                         INNER JOIN patient ON patient.pid = appointment.pid 
                                         INNER JOIN doctor ON schedule.docid = doctor.docid  
                                         WHERE patient.pid = $userid AND schedule.scheduledate >= CURDATE() 
                                         ORDER BY schedule.scheduledate ASC";

                            $result = $database->query($sqlmain);

                            if ($result->num_rows == 0) {
                                echo '<tr>
                                        <td colspan="4" class="text-center">No upcoming bookings found.</td>
                                    </tr>';
                            } else {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>' . htmlspecialchars($row["apponum"]) . '</td>
                                            <td>' . htmlspecialchars(substr($row["title"], 0, 30)) . '</td>
                                            <td>' . htmlspecialchars(substr($row["docname"], 0, 20)) . '</td>
                                            <td>' . htmlspecialchars(substr($row["scheduledate"], 0, 10)) . ' ' . htmlspecialchars(substr($row["scheduletime"], 0, 5)) . '</td>
                                        </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
       
       
        </div>
    </div>

    <script src="script.js"></script>
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>
