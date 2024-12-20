<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-user-md"></i> <?php echo htmlspecialchars($username); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointment.php">My Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule.php">My Sessions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patient.php">My Patients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Settings</a>
                </li>
            </ul>
            <a href="../logout.php" class="btn btn-primary ms-auto">Log out</a>
        </div>
    </div>
</nav>
