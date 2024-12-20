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
    
    <title>Dashboard</title>
    <style>
        .dashbord-tables {
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .scroll {
            overflow-y: auto;
        }
        @media (max-width: 768px) {
            .dashboard-items {
                flex-direction: column;
                align-items: center;
            }
            .dashboard-icons {
                margin: 0 auto;
            }
            .abc {
                width: 100%;
            }
            .input-text {
                width: 100%;
            }
        }
    </style>
    
</head>
<body>
    <?php
    session_start();
    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'a') {
            header("location: ../login.php");
        }
    } else {
        header("location: ../login.php");
    }
    include("../connection.php");
    ?>
    <div class="container_body">
        <?php include("admin_navbar.php"); ?>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style="border-spacing: 0; margin: 0; padding: 0;">
                <tr>    
                    <td colspan="2" class="nav-bar">
                        <form action="doctors.php" method="post" class="header-search d-flex">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors" style="margin-left: 10px;">&nbsp;&nbsp;
                            <?php
                                echo '<datalist id="doctors">';
                                $list11 = $database->query("select docname, docemail from doctor;");
                                
                                for ($y = 0; $y < $list11->num_rows; $y++) {
                                    $row00 = $list11->fetch_assoc();
                                    $d = $row00["docname"];
                                    $c = $row00["docemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                }
                                echo '</datalist>';
                            ?>
                            <input type="submit" value="Search" class="login-btn btn-primary-soft btn" style="padding: 10px 25px;">
                        </form>
                    </td>
                    <td width="15%" class="text-end">
                        <?php
                        date_default_timezone_set('Asia/Kolkata');

                        $today = date('Y-m-d');
                        ?>
                       
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;">Today's Date</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;"><?php echo $today; ?></p>
                    </td>
                    <td width="10%" class="text-center">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;">
                            <img src="../img/calendar.svg" width="100%">
                        </button>
                    </td>

                    <p class="heading-sub12" style="padding: 0; margin: 0;">
                            <?php 
                               
                                $patientrow = $database->query("select * from patient;");
                                $doctorrow = $database->query("select * from doctor;");
                                $appointmentrow = $database->query("select * from appointment where appodate >= '$today';");
                                $schedulerow = $database->query("select * from schedule where scheduledate = '$today';");
                            ?>
                        </p>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                        <table class="filter-container" style="border: none;" border="0">
                            <tr>
                                <td colspan="4">
                                    <p style="font-size: 20px; font-weight: 600; padding-left: 12px;">Status</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">
                                    <div class="dashboard-items" style="padding: 20px; margin: auto; width: 95%; display: flex;">
                                        <div>
                                            <div class="h1-dashboard">
                                                <?php echo $doctorrow->num_rows ?>
                                            </div><br>
                                            <div class="h3-dashboard">
                                                Doctors
                                            </div>
                                        </div>
                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                    </div>
                                </td>
                                <td style="width: 25%;">
                                    <div class="dashboard-items" style="padding: 20px; margin: auto; width: 95%; display: flex;">
                                        <div>
                                            <div class="h1-dashboard">
                                                <?php echo $patientrow->num_rows ?>
                                            </div><br>
                                            <div class="h3-dashboard">
                                                Patients
                                            </div>
                                        </div>
                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                    </div>
                                </td>
                                <td style="width: 25%;">
                                    <div class="dashboard-items" style="padding: 20px; margin: auto; width: 95%; display: flex;">
                                        <div>
                                            <div class="h1-dashboard">
                                                <?php echo $appointmentrow->num_rows ?>
                                            </div><br>
                                            <div class="h3-dashboard">
                                                New Booking
                                            </div>
                                        </div>
                                        <div class="btn-icon-back dashboard-icons" style="margin-left: 0px; background-image: url('../img/icons/book-hover.svg');"></div>
                                    </div>
                                </td>
                                <td style="width: 25%;">
                                    <div class="dashboard-items" style="padding: 20px; margin: auto; width: 95%; display: flex; padding-top: 26px; padding-bottom: 26px;">
                                        <div>
                                            <div class="h1-dashboard">
                                                <?php echo $schedulerow->num_rows ?>
                                            </div><br>
                                            <div class="h3-dashboard" style="font-size: 15px">
                                                Today Sessions
                                            </div>
                                        </div>
                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/session-iceblue.svg');"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table width="100%" border="0" class="dashbord-tables">
                            <tr>
                                <td>
                                    <p style="padding: 10px; padding-left: 48px; padding-bottom: 0; font-size: 23px; font-weight: 700; color: var(--primarycolor);">
                                        Upcoming Appointments until Next <?php echo date("l", strtotime("+1 week")); ?>
                                    </p>
                                    <p style="padding-bottom: 19px; padding-left: 50px; font-size: 15px; font-weight: 500; color: #212529e3; line-height: 20px;">
                                        Here's Quick access to Upcoming Appointments until 7 days<br>
                                        More details available in @Appointment section.
                                    </p>
                                </td>
                                <td>
                                    <p style="text-align: right; padding: 10px; padding-right: 48px; padding-bottom: 0; font-size: 23px; font-weight: 700; color: var(--primarycolor);">
                                        Upcoming Sessions until Next <?php echo date("l", strtotime("+1 week")); ?>
                                    </p>
                                    <p style="padding-bottom: 19px; text-align: right; padding-right: 50px; font-size: 15px; font-weight: 500; color: #212529e3; line-height: 20px;">
                                        Here's Quick access to Upcoming Sessions that Scheduled until 7 days<br>
                                        Add, Remove and Many features available in @Schedule section.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    <center>
                                        <div class="abc scroll" style="height: 200px;">
                                            <table width="85%" class="sub-table scrolldown" border="0">
                                                <thead>
                                                    <tr>
                                                        <th class="table-headin" style="font-size: 12px;">
                                                            Appointment number
                                                        </th>
                                                        <th class="table-headin">
                                                            Patient name
                                                        </th>
                                                        <th class="table-headin">
                                                            Doctor
                                                        </th>
                                                        <th class="table-headin">
                                                            Session
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $nextweek = date("Y-m-d", strtotime("+1 week"));
                                                    $sqlmain = "SELECT appointment.appoid, schedule.scheduleid, schedule.title, doctor.docname, patient.pname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate 
                                                                FROM schedule 
                                                                INNER JOIN appointment ON schedule.scheduleid = appointment.scheduleid 
                                                                INNER JOIN patient ON patient.pid = appointment.pid 
                                                                INNER JOIN doctor ON schedule.docid = doctor.docid  
                                                                WHERE schedule.scheduledate >= '$today' AND schedule.scheduledate <= '$nextweek' 
                                                                ORDER BY schedule.scheduledate DESC";
                                                    $result = $database->query($sqlmain);

                                                    if ($result->num_rows == 0) {
                                                        echo '<tr>
                                                                <td colspan="4" style="text-align: center;">
                                                                    <br><br><br><br>
                                                                    <img src="../img/notfound.svg" width="25%">
                                                                    <br>
                                                                    <p class="heading-main12" style="margin-left: 45px; font-size: 20px; color: rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                                                    <a class="non-style-link" href="appointment.php"><button class="login-btn btn-primary-soft btn" style="margin-left: 20px;">&nbsp; Show all Appointments &nbsp;</button></a>
                                                                    <br><br><br><br>
                                                                </td>
                                                            </tr>';
                                                    } else {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $apponum = $row["apponum"];
                                                            $pname = $row["pname"];
                                                            $docname = $row["docname"];
                                                            $title = $row["title"];
                                                            echo '<tr>
                                                                    <td style="text-align:center; font-size:23px; font-weight:500; color: var(--btnnicetext); padding: 20px;">' . $apponum . '</td>
                                                                    <td style="font-weight:600;">&nbsp;' . substr($pname, 0, 25) . '</td>
                                                                    <td style="font-weight:600;">&nbsp;' . substr($docname, 0, 25) . '</td>
                                                                    <td>' . substr($title, 0, 15) . '</td>
                                                                </tr>';
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </center>
                                </td>
                                <td width="50%" style="padding: 0;">
                                    <center>
                                        <div class="abc scroll" style="height: 200px; padding: 0; margin: 0;">
                                            <table width="85%" class="sub-table scrolldown" border="0">
                                                <thead>
                                                    <tr>
                                                        <th class="table-headin">
                                                            Session Title
                                                        </th>
                                                        <th class="table-headin">
                                                            Doctor
                                                        </th>
                                                        <th class="table-headin">
                                                            Scheduled Date & Time
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sqlmain = "SELECT schedule.scheduleid, schedule.title, doctor.docname, schedule.scheduledate, schedule.scheduletime 
                                                                FROM schedule 
                                                                INNER JOIN doctor ON schedule.docid = doctor.docid  
                                                                WHERE schedule.scheduledate >= '$today' AND schedule.scheduledate <= '$nextweek' 
                                                                ORDER BY schedule.scheduledate DESC"; 
                                                    $result = $database->query($sqlmain);

                                                    if ($result->num_rows == 0) {
                                                        echo '<tr>
                                                                <td colspan="3" style="text-align: center;">
                                                                    <br><br><br><br>
                                                                    <img src="../img/notfound.svg" width="25%">
                                                                    <br>
                                                                    <p class="heading-main12" style="margin-left: 45px; font-size: 20px; color: rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                                                    <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="margin-left: 20px;">&nbsp; Show all Sessions &nbsp;</button></a>
                                                                    <br><br><br><br>
                                                                </td>
                                                            </tr>';
                                                    } else {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $title = $row["title"];
                                                            $docname = $row["docname"];
                                                            $scheduledate = $row["scheduledate"];
                                                            $scheduletime = $row["scheduletime"];
                                                            echo '<tr>
                                                                    <td style="padding:20px;">&nbsp;' . substr($title, 0, 30) . '</td>
                                                                    <td>' . substr($docname, 0, 20) . '</td>
                                                                    <td style="text-align:center;">' . substr($scheduledate, 0, 10) . ' ' . substr($scheduletime, 0, 5) . '</td>
                                                                </tr>';
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <center>
                                        <a href="appointment.php" class="non-style-link"><button class="btn-primary btn" style="width:85%">Show all Appointments</button></a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="schedule.php" class="non-style-link"><button class="btn-primary btn" style="width:85%">Show all Sessions</button></a>
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script src="script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>
