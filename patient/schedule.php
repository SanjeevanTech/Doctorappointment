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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdWl3w58Bvv0RmZr1r4R6hh5C3m5eF3R/qJ3" crossorigin="anonymous">
        
    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        @media (max-width: 576px) {
            .header-search {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-search input[type="search"] {
                width: 100%;
                margin-bottom: 10px;
            }
            .header-search input[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    // Import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    ?>
    <div class="container_body">
        <?php include("Patient_navbar.php"); ?>
        <?php
        $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' order by schedule.scheduledate asc";
        $insertkey = "Book Doctor";
        $searchtype = "All";

        if ($_POST) {
            if (!empty($_POST["search"])) {
                $keyword = $_POST["search"];
                $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' and (doctor.docname='$keyword' or doctor.docname like '$keyword%' or doctor.docname like '%$keyword' or doctor.docname like '%$keyword%' or schedule.title='$keyword' or schedule.title like '$keyword%' or schedule.title like '%$keyword' or schedule.title like '%$keyword%' or schedule.scheduledate like '$keyword%' or schedule.scheduledate like '$keyword' or schedule.scheduledate like '%$keyword%' or schedule.scheduledate='$keyword' ) order by schedule.scheduledate asc";
                $insertkey = $keyword;
                $searchtype = "Search Result : ";
            }
        }

        $result = $database->query($sqlmain);
        ?>
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="schedule.php">
                            <button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:120px">
                                <font class="tn-in-text">Back</font>
                            </button>
                        </a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search d-flex">
                            <input type="search" name="search" class="input-text header-searchbar form-control " placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors" value="<?php echo $insertkey; ?>" style="margin-left: 10px;">
                            &nbsp;&nbsp;
                            <input type="submit" value="Search" class="login-btn btn-primary btn" style="padding: 10px 25px;">
                        </form>
                    </td>
                    <td width="15%" class="text-end">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;">Today's Date</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;"><?php echo $today; ?></p>
                    </td>
                    <td width="10%" class="text-center">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;">
                            <img src="../img/calendar.svg" width="100%">
                        </button>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                            <?php echo $searchtype . " Sessions(" . $result->num_rows . ")"; ?>
                        </p>
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                            <?php echo $insertkey; ?>
                        </p>
                    </td>
                </tr>

                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                            <tbody>
                            <?php
                                if ($result->num_rows == 0) {
                                    echo '<tr>
                                    <td colspan="4">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                    <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Sessions &nbsp;</button></a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                } else {
                                    for ($x = 0; $x < ($result->num_rows); $x++) {
                                        echo "<tr>";
                                        for ($q = 0; $q < 3; $q++) {
                                            $row = $result->fetch_assoc();
                                            if (!isset($row)) {
                                                break;
                                            }
                                            $scheduleid = $row["scheduleid"];
                                            $title = $row["title"];
                                            $docname = $row["docname"];
                                            $scheduledate = $row["scheduledate"];
                                            $scheduletime = $row["scheduletime"];

                                            if ($scheduleid == "") {
                                                break;
                                            }

                                            echo '
                                            <td style="width: 25%;">
                                                <div class="dashboard-items search-items">
                                                    <div style="width:100%">
                                                        <div class="h1-search">' . substr($title, 0, 21) . '</div><br>
                                                        <div class="h3-search">' . substr($docname, 0, 30) . '</div>
                                                        <div class="h4-search">' . $scheduledate . '<br>Starts: <b>@' . substr($scheduletime, 0, 5) . '</b> (24h)</div>
                                                        <br>
                                                        <a href="booking.php?id=' . $scheduleid . '"><button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Book Now</font></button></a>
                                                    </div>
                                                </div>
                                            </td>';
                                        }
                                        echo "</tr>";
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>
