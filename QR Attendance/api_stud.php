<?php
// Include PHPExcel
require 'PHPExcel/PHPExcel.php';
require 'PHPExcel/PHPExcel/IOFactory.php';
require "conn.php";
session_start();

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {

    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $enrollmentno = mysqli_escape_string($conn, $_POST['enrollmentno']);
        $studentname = mysqli_escape_string($conn, $_POST['studentname']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $rollno = mysqli_escape_string($conn, $_POST['rollno']);
        $batch = mysqli_escape_string($conn, $_POST['batch']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "INSERT INTO `students`(`enrollment_no`, `name`, `semester`, `branch`, `roll_no`, `batch`, `password`) VALUES ('$enrollmentno','$studentname','$semester','$branch','$rollno','$batch','$password')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success glass-alert mb-2" role="alert"><i class="fas fa-check-circle me-2"></i> Student Added Successfully.</div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-times-circle me-2"></i> Something went wrong!.</div>';
            header("location: stud_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);

        $sql = "DELETE FROM `students` WHERE enrollment_no='$enroll'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success glass-alert mb-2" role="alert"><i class="fas fa-trash-alt me-2"></i> Student Deleted.</div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-times-circle me-2"></i> Something went wrong!.</div>';
            header("location: stud_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $enrollmentno = mysqli_escape_string($conn, $_POST['enrollmentno']);
        $studentname = mysqli_escape_string($conn, $_POST['studentname']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        // $shift = mysqli_escape_string($conn, $_POST['shift']); // Optional if not in DB
        $rollno = mysqli_escape_string($conn, $_POST['rollno']);
        $batch = mysqli_escape_string($conn, $_POST['batch']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        // Removed 'shift' from query for safety if column doesn't exist, uncomment if needed
        $sql = "UPDATE `students` SET `name`='$studentname',`semester`='$semester',`branch`='$branch',`roll_no`='$rollno',`batch`='$batch',`password`='$password' WHERE `enrollment_no`='$enrollmentno'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success glass-alert mb-2" role="alert"><i class="fas fa-check-circle me-2"></i> Student Details Updated.</div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-times-circle me-2"></i> Something went wrong!.</div>';
            header("location: stud_details.php");
            exit();
        }
    }


    if (isset($_GET['type']) && $_GET['type'] == "bulk" && isset($_FILES['file'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['xls', 'xlsx'])) {
            $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-exclamation-triangle me-2"></i> Invalid file type. Please upload an Excel file.</div>';
            header("location: stud_details.php");
            exit();
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $expectedHeaders = ['enrollment', 'name', 'semester', 'branch', 'roll no', 'batch', 'password'];

            try {
                $objPHPExcel = PHPExcel_IOFactory::load($uploadFile);
                $worksheet = $objPHPExcel->getSheet(0);
                $headerRow = $worksheet->getRowIterator(1)->current();
                $cellIterator = $headerRow->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $headers = [];
                foreach ($cellIterator as $cell) {
                    $headers[] = trim(strtolower($cell->getValue()));
                }

                if ($headers !== $expectedHeaders) {
                    $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-exclamation-circle me-2"></i> Invalid file structure. Check header columns.</div>';
                    header("location: stud_details.php");
                    exit();
                }

                $stmt = $conn->prepare("INSERT INTO students (enrollment_no, name, semester, branch, roll_no, batch, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isisiss", $enrollment, $name, $semester, $branch, $rollno, $batch, $password);

                $firstRow = true;
                foreach ($worksheet->getRowIterator() as $row) {
                    if ($firstRow) { $firstRow = false; continue; }

                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $rowData = [];
                    foreach ($cellIterator as $cell) { $rowData[] = $cell->getValue(); }

                    $enrollment = $rowData[0];
                    $name = $rowData[1];
                    $semester = $rowData[2];
                    $branch = $rowData[3];
                    $rollno = $rowData[4];
                    $batch = $rowData[5];
                    $password = $rowData[6];

                    $stmt->execute();
                }

                $stmt->close();
                $conn->close();

                $_SESSION['msg'] = '<div class="alert alert-success glass-alert mb-2" role="alert"><i class="fas fa-check-circle me-2"></i> Bulk Data Inserted Successfully.</div>';
                header("location: stud_details.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-bug me-2"></i> Error loading file.</div>';
                header("location: stud_details.php");
                exit();
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-upload me-2"></i> File upload failed.</div>';
            header("location: stud_details.php");
            exit();
        }
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger glass-alert mb-2" role="alert"><i class="fas fa-times-circle me-2"></i> File Not Selected.</div>';
        header("location: stud_details.php");
        exit();
    }
} else {
    header("location: login.php");
    exit();
}
?>