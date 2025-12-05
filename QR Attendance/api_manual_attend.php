<?php
require "conn.php";
session_start();

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}


if (isset($_POST['batch']) && isset($_POST['semester']) && isset($_POST['branch'])) {
    $batch = mysqli_escape_string($conn, $_POST['batch']);
    $semester = mysqli_escape_string($conn, $_POST['semester']);
    $branch = mysqli_escape_string($conn, $_POST['branch']);
    $ip_address = mysqli_escape_string($conn, $_POST['clientIp']);
    $subject_code = mysqli_escape_string($conn, $_POST['subject_code']);
    $slot = mysqli_escape_string($conn, $_POST['slot']);
    $date = mysqli_escape_string($conn, $_POST['date']);
    $time = date('h:i:s a', strtotime(mysqli_escape_string($conn, $_POST['time'])));
    $students = $_POST['students'];
    $currentDay = date('l', strtotime($date));

    $success = array();
    $failed = array();



    foreach ($students as $key => $value) {

        $sql = "SELECT * FROM `students` WHERE `batch`='$batch' AND `enrollment_no`=$value AND `semester`='$semester'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows == 1) {


            $csql = "SELECT * FROM `attendance` WHERE `date`='$date' AND `enrollment_no`=$value AND `subject_code`=$subject_code AND `slot`=$slot AND `batch`='$batch'";
            $cres = mysqli_query($conn, $csql);
            if ($cres->num_rows == 0) {
                $fsql = "INSERT INTO `attendance`(`enrollment_no`, `date`, `day`, `subject_code`, `slot`, `batch`, `branch`,`semester`,`time`,`ip_address`) VALUES ('$value','$date','$currentDay', '$subject_code','$slot','$batch','$branch','$semester','$time','$ip_address')";
                $fres = mysqli_query($conn, $fsql);
                if ($fres) {
                    $success[$value] = "Attendance Marked.";
                } else {
                    $failed[$value] = "Database Error.";
                }
            } else {
                $failed[$value] = "Attendance Already Marked.";
            }
        } else {
            $failed[$value] = "Batch/Semester Mismatch.";
        }
    }

    $msg = '<h6 class="text-white">Attendance Summary</h6>';
    
    if(!empty($success)) {
        $msg .= '<div class="text-success mb-2"><strong><i class="fas fa-check"></i> Successful:</strong><br>';
        foreach ($success as $key => $value) {
            $msg .= '<span>' . $key . ' </span> ';
        }
        $msg .= '</div>';
    }

    if(!empty($failed)) {
        $msg .= '<div class="text-danger"><strong><i class="fas fa-times"></i> Failed:</strong><ul>';
        foreach ($failed as $key => $value) {
            $msg .= '<li>' . $key . ': ' . $value . '</li>';
        }
        $msg .= '</ul></div>';
    }

    $_SESSION['msg'] = '<div class="alert alert-info glass-alert text-start mb-2" role="alert">' . $msg . '</div>';
    header("location: manual_attend.php");
    exit();
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-exclamation-triangle me-2"></i> Missing Details. Please try again.</div>';
    header("location: manual_attend.php");
    exit();
}
?>