<?php
require('header.php');
require "conn.php";
?>
<link href="css/theme of project.css" rel="stylesheet">

<div class="container pt-4 px-4 m-0">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item active" aria-current="page">Home / Dashboard</li>
        </ol>
    </nav>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row vh-90 align-items-center justify-content-center mx-0">
        <div class="col-md-8 text-center">
            
            <div class="welcome-box rounded p-5">
                <h3 class="mb-4" style="color: var(--primary); text-transform: uppercase;">
                    <i class="fas fa-qrcode me-2"></i> Welcome To QR Code Attendance System
                </h3>

                <?php
                // TEACHER PROFILE
                if (isset($_SESSION['teacher_id'])) {
                    $teacher_id = $_SESSION['teacher_id'];
                    $teacher_sql = "SELECT * FROM teachers WHERE `id`=$teacher_id";
                    $teacher_res = mysqli_query($conn, $teacher_sql);
                    $teacher_row = mysqli_fetch_assoc($teacher_res);
                ?>
                    <div class="table-responsive">
                        <table class="table table-future table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="col" width="30%">Teacher ID</th>
                                    <td><?php echo $teacher_row['id']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Name</th>
                                    <td><?php echo $teacher_row['name']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Education</th>
                                    <td><?php echo $teacher_row['education']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Designation</th>
                                    <td><?php echo $teacher_row['designation']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Branch</th>
                                    <td><?php echo $teacher_row['branch']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                <?php
                // STUDENT PROFILE
                } else if (isset($_SESSION['enrollment_no'])) {
                    $student_id = $_SESSION['enrollment_no'];
                    $student_sql = "SELECT * FROM students WHERE `enrollment_no`=$student_id";
                    $student_res = mysqli_query($conn, $student_sql);
                    $student_row = mysqli_fetch_assoc($student_res);
                ?>
                    <div class="table-responsive">
                        <table class="table table-future table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="col" width="30%">Enrollment No</th>
                                    <td><?php echo $student_row['enrollment_no']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Name</th>
                                    <td><?php echo $student_row['name']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Semester</th>
                                    <td><?php echo $student_row['semester']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Branch</th>
                                    <td><?php echo $student_row['branch']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Roll No</th>
                                    <td><?php echo $student_row['roll_no']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Batch</th>
                                    <td><?php echo $student_row['batch']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                // ADMIN OR OTHER
                } else {
                    echo "<p class='text-muted'>Select an option from the sidebar to begin.</p>";
                }
                ?>
            </div>
            
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var s = document.getElementById('spinner');
        if(s) { s.style.display = 'none'; }
    });
</script>

<?php
require('footer.php');
?>