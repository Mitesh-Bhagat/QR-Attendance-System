<?php
require "conn.php";
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true && $_SESSION['usertype'] == 'STUDENT') {


    $enrollmentno = $_SESSION['enrollment_no'];
    $studentname = mysqli_escape_string($conn, $_POST['studentname']);
    $filename = $_FILES['pic']['name'];

    if ($_FILES['pic']['error'] != UPLOAD_ERR_NO_FILE) {

        // File upload configuration
        $uploadDir = 'img/profile/';
        
        // --- FIX: Check if folder exists, if not, create it ---
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        // -----------------------------------------------------

        $uploadFile = $uploadDir . $filename;

        // Check file extension
        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['jpg', 'png', 'jpeg'])) {
            die('Invalid file type. Please upload jpg,png,jpeg.');
        }

        $filename = time() . "." . $fileExtension;
        $uploadFile = $uploadDir . $filename;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {

            $sql = "UPDATE `students` SET `name`='$studentname',`pic`='$filename' WHERE `enrollment_no`='$enrollmentno'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $_SESSION['msg'] = '<div class="alert alert-success glass-alert mb-2" role="alert"><i class="fas fa-check-circle me-2"></i> Student Details Updated.</div>';
                header("location: profile_stud.php");
                exit();
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-times-circle me-2"></i> Something went wrong!.</div>';
                header("location: profile_stud.php");
                exit();
            }
        } else {
            // Provide a clear error if movement still fails
            die('File upload failed. Ensure the folder "img/profile/" exists and has write permissions.');
        }
    } else {

        $sql = "UPDATE `students` SET `name`='$studentname' WHERE `enrollment_no`='$enrollmentno'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success glass-alert mb-2" role="alert"><i class="fas fa-check-circle me-2"></i> Student Details Updated.</div>';
            header("location: profile_stud.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-times-circle me-2"></i> Something went wrong!.</div>';
            header("location: profile_stud.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
?>