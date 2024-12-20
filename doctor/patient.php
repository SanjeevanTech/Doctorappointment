<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Single Bootstrap CSS Link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <!-- <link rel="stylesheet" href="../css/admin.css"> -->
    <!-- <link rel="stylesheet" href="../css/navbar.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdWl3w58Bvv0RmZr1r4R6hh5C3m5eF3R/qJ3" crossorigin="anonymous">
    <title>Patients</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'd') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    include("../connection.php");
    $userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["docid"];
    $username = $userfetch["docname"];
    ?>
    <div class="container-fluid">
        <?php include("navbar.php"); ?>

        <?php
        $selecttype = "My";
        $current = "My patients Only";
        if ($_POST) {
            if (isset($_POST["search"])) {
                $keyword = $_POST["search12"];
                $sqlmain = "SELECT * FROM patient WHERE pemail='$keyword' OR pname='$keyword' OR pname LIKE '$keyword%' OR pname LIKE '%$keyword' OR pname LIKE '%$keyword%'";
                $selecttype = "my";
            }
            if (isset($_POST["filter"])) {
                if ($_POST["showonly"] == 'all') {
                    $sqlmain = "SELECT * FROM patient";
                    $selecttype = "All";
                    $current = "All patients";
                } else {
                    $sqlmain = "SELECT * FROM appointment INNER JOIN patient ON patient.pid=appointment.pid INNER JOIN schedule ON schedule.scheduleid=appointment.scheduleid WHERE schedule.docid=$userid;";
                    $selecttype = "My";
                    $current = "My patients Only";
                }
            }
        } else {
            $sqlmain = "SELECT * FROM appointment INNER JOIN patient ON patient.pid=appointment.pid INNER JOIN schedule ON schedule.scheduleid=appointment.scheduleid WHERE schedule.docid=$userid;";
            $selecttype = "My";
        }
        ?>
        <div class="dash-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a href="patient.php">
                                <button class="btn btn-primary-soft" style="width: 125px;">Back</button>
                            </a>
                        </th>
                        <th>
                            <form action="" method="post" class="header-search d-flex">
                                <input type="search" name="search12" class="form-control me-2" placeholder="Search Patient name or Email" list="patient">
                                <datalist id="patient">
                                    <?php
                                    $list11 = $database->query($sqlmain);
                                    for ($y = 0; $y < $list11->num_rows; $y++) {
                                        $row00 = $list11->fetch_assoc();
                                        echo "<option value='{$row00["pname"]}'>";
                                        echo "<option value='{$row00["pemail"]}'>";
                                    }
                                    ?>
                                </datalist>
                                <input type="submit" value="Search" name="search" class="btn btn-primary">
                            </form>
                        </th>
                        <th class="text-end">
                            <p style="font-size: 14px; color: rgb(119, 119, 119); margin: 0;">Today's Date</p>
                            <p class="heading-sub12" style="margin: 0;">
                                <?php
                                date_default_timezone_set('Asia/Kolkata');
                                echo date('Y-m-d');
                                ?>
                            </p>
                        </th>
                        <th>
                            <button class="btn btn-label">
                                <img src="../img/calendar.svg" width="100%">
                            </button>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-start">
                            <p class="heading-main12" style="font-size: 18px; color: rgb(49, 49, 49);">
                                <?php echo $selecttype . " Patients (" . $list11->num_rows . ")"; ?>
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4">
                            <form action="" method="post" class="d-flex justify-content-end">
                                <label class="me-2">Show Details About:</label>
                                <select name="showonly" class="form-select me-2">
                                    <option value="" disabled selected hidden><?php echo $current; ?></option>
                                    <option value="my">My Patients Only</option>
                                    <option value="all">All Patients</option>
                                </select>
                                <input type="submit" name="filter" value="Filter" class="btn btn-primary-soft">
                            </form>
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="table-responsive">
                <table class="table table-bordered sub-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Telephone</th>
                            <th>Email</th>
                            <th>Date of Birth</th>
                            <th>Events</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $database->query($sqlmain);
                        if ($result->num_rows == 0) {
                            echo '<tr>
                                    <td colspan="6">
                                        <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            <p class="heading-main12" style="font-size: 20px; color: rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                            <a class="non-style-link" href="patient.php">
                                                <button class="btn btn-primary-soft">Show all Patients</button>
                                            </a>
                                        </center>
                                    </td>
                                  </tr>';
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>
                                        <td>' . substr($row["pname"], 0, 35) . '</td>
                                        <td>' . substr($row["pnic"], 0, 12) . '</td>
                                        <td>' . substr($row["ptel"], 0, 10) . '</td>
                                        <td>' . substr($row["pemail"], 0, 20) . '</td>
                                        <td>' . substr($row["pdob"], 0, 10) . '</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="?action=view&id=' . $row["pid"] . '" class="non-style-link">
                                                    <button class="btn btn-primary-soft">View</button>
                                                </a>
                                            </div>
                                        </td>
                                      </tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        $sqlmain = "SELECT * FROM patient WHERE pid='$id'";
        $result = $database->query($sqlmain);
        $row = $result->fetch_assoc();
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <a class="close" href="patient.php">&times;</a>
                    <div class="content">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="font-size: 25px; font-weight: 500;">View Details.</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">Patient ID:</td>
                                <td>P-' . $id . '</td>
                            </tr>
                            <tr>
                                <td class="label-td">Name:</td>
                                <td>' . $row["pname"] . '</td>
                            </tr>
                            <tr>
                                <td class="label-td">NIC:</td>
                                <td>' . $row["pnic"] . '</td>
                            </tr>
                            <tr>
                                <td class="label-td">Email:</td>
                                <td>' . $row["pemail"] . '</td>
                            </tr>
                            <tr>
                                <td class="label-td">Telephone:</td>
                                <td>' . $row["ptel"] . '</td>
                            </tr>
                            <tr>
                                <td class="label-td">Date of Birth:</td>
                                <td>' . $row["pdob"] . '</td>
                            </tr>
                            <tr>
                                <td class="label-td">Allergies:</td>
                                <td>' . $row["pallergies"] . '</td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <button class="btn btn-primary-soft" onclick="history.go(-1)">Close</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </center>
            </div>
        </div>';
    }
    ?>
     <script src="script.js"></script>
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>
