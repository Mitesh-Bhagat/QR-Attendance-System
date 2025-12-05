<?php
session_start();
require_once 'conn.php'; 

// Authentication Check
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header("location: login.php");
    exit();
}

// Profile Picture Logic
$userPic = "img/dp.jpg"; 
if ($_SESSION['usertype'] == "STUDENT" && isset($_SESSION['enrollment_no'])) {
    $eid_header = $_SESSION['enrollment_no'];
    $pic_sql = "SELECT pic FROM students WHERE enrollment_no = '$eid_header'";
    $pic_res = mysqli_query($conn, $pic_sql);
    if ($pic_res && mysqli_num_rows($pic_res) > 0) {
        $pic_row = mysqli_fetch_assoc($pic_res);
        if (!empty($pic_row['pic']) && file_exists("img/profile/" . $pic_row['pic'])) {
            $userPic = "img/profile/" . $pic_row['pic'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>QR Code Attendance System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.colVis.min.js"></script>
    
    <link href="css/themes of project.css" rel="stylesheet">

    <script>
        $(document).ready(function () {
            // Spinner
            var spinner = function () {
                setTimeout(function () {
                    if ($('#spinner').length > 0) {
                        $('#spinner').removeClass('show');
                    }
                }, 1);
            };
            spinner();

            // Sidebar Toggler - Toggles the 'open' class
            $('.sidebar-toggler').click(function () {
                $('.sidebar, .content').toggleClass("open");
                return false;
            });
        });
    </script>
</head>

<body>
    <div class="container-xxl position-relative d-flex p-0">
        <div id="spinner" class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <?php
        if ($_SESSION['usertype'] == "ADMIN") {
        ?>
            <div class="sidebar pe-4 pb-3">
                <nav class="navbar navbar-dark">
                    <a href="./" class="navbar-brand mx-4 mb-3">
                        <h3 class="text-primary"><i class="fas fa-qrcode"></i> QR-AS</h3>
                    </a>
                    <div class="d-flex align-items-center ms-4 mb-4">
                        <div class="position-relative">
                            <img class="rounded-circle" src="<?php echo $userPic; ?>" alt="" style="width: 40px; height: 40px;">
                            <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white"><?php echo $_SESSION['username']; ?></h6>
                            <span class="text-muted" style="font-size: 0.8rem;"><?php echo $_SESSION['usertype']; ?></span>
                        </div>
                    </div>
                    <div class="navbar-nav w-100">
                        <a href="./" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                        <a href="view_admin_attend.php" class="nav-item nav-link"><i class="fas fa-clipboard-check me-2"></i>View Attendance</a>
                        <a href="manual_attend.php" class="nav-item nav-link"><i class="fas fa-clipboard-check me-2"></i>Attendance</a>
                        <a href="stud_details.php" class="nav-item nav-link"><i class="fas fa-user-graduate me-2"></i>Students Details</a>
                        <a href="teacher_details.php" class="nav-item nav-link"><i class="fas fa-chalkboard-teacher me-2"></i>Teachers Details</a>
                        <a href="subject_details.php" class="nav-item nav-link"><i class="fas fa-book me-2"></i>Subjects Details</a>
                        <a href="timetable.php" class="nav-item nav-link"><i class="fas fa-table me-2"></i>Set Time Table</a>
                        <a href="settings.php" class="nav-item nav-link"><i class="fas fa-cog me-2"></i>Settings</a>
                        <a href="allocation.php" class="nav-item nav-link"><i class="fas fa-project-diagram me-2"></i>Allocation Map</a>
                    </div>
                </nav>
            </div>
        <?php
        } else if ($_SESSION['usertype'] == "STUDENT") {
        ?>
            <div class="sidebar pe-4 pb-3">
                <nav class="navbar navbar-dark">
                    <a href="./" class="navbar-brand mx-4 mb-3">
                        <h3 class="text-primary"><i class="fas fa-qrcode"></i> QR-AS</h3>
                    </a>
                    <div class="d-flex align-items-center ms-4 mb-4">
                        <div class="position-relative">
                            <img class="rounded-circle" src="<?php echo $userPic; ?>" alt="" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white"><?php echo $_SESSION['username']; ?></h6>
                            <span class="text-muted" style="font-size: 0.8rem;"><?php echo $_SESSION['usertype']; ?></span>
                        </div>
                    </div>
                    <div class="navbar-nav w-100">
                        <a href="./" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                        <a href="give_attend.php" class="nav-item nav-link"><i class="far fa-calendar-check me-2"></i>Mark Attendance</a>
                        <a href="view_stud_attend.php" class="nav-item nav-link"><i class="fas fa-clipboard-check me-2"></i>View Attendance</a>
                        <a href="profile_stud.php" class="nav-item nav-link"><i class="fas fa-user me-2"></i>Profile</a>
                    </div>
                </nav>
            </div>
        <?php
        } else {
        ?>
            <div class="sidebar pe-4 pb-3">
                <nav class="navbar navbar-dark">
                    <a href="./" class="navbar-brand mx-4 mb-3">
                        <h3 class="text-primary"><i class="fas fa-qrcode"></i> QR-AS</h3>
                    </a>
                    <div class="d-flex align-items-center ms-4 mb-4">
                        <div class="position-relative">
                            <img class="rounded-circle" src="<?php echo $userPic; ?>" alt="" style="width: 40px; height: 40px;">
                            <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white"><?php echo $_SESSION['username']; ?></h6>
                            <span class="text-muted" style="font-size: 0.8rem;"><?php echo $_SESSION['usertype']; ?></span>
                        </div>
                    </div>
                    <div class="navbar-nav w-100">
                        <a href="./" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                        <a href="take_attendance.php" class="nav-item nav-link"><i class="far fa-calendar-check me-2"></i>Take Attendance</a>
                        <a href="view_teacher_attend.php" class="nav-item nav-link"><i class="fas fa-clipboard-check me-2"></i>View Attendance</a>
                    </div>
                </nav>
            </div>
        <?php
        }
        ?>

        <div class="content">
            <nav class="navbar navbar-expand navbar-dark sticky-top px-4 py-0">
                <a href="./" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fas fa-qrcode"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars text-primary"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="<?php echo $userPic; ?>" alt="" style="width: 40px; height: 40px; object-fit: cover;">
                            <span class="d-none d-lg-inline-flex text-white"><?php echo $_SESSION['username']; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end border-0 rounded-0 rounded-bottom m-0">
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
