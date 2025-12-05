<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

$sql = "SELECT * FROM `attendance` ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

?>
<link href="css/theme of project.css" rel="stylesheet">

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item active">View Attendance</li>
        </ol>
    </nav>
</div>


<div class="container-fluid pt-4 px-4">
    <div class="text-center w-100">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
    </div>

    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">View Attendance Records</h6>
                
                <form class="row gy-2 gx-3 align-items-center border p-3 mb-4 rounded" style="border-color: rgba(255,255,255,0.1) !important;" action="view_admin_attend.php" method="get">
                    <div class="col-auto d-flex align-items-center">
                        <label>Start</label>
                        <input type="date" name="startdate" date_format="yyyy-mm-dd" class="form-control" required>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <label>End</label>
                        <input type="date" name="enddate" class="form-control" required>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="subject" required>
                            <option value="">Select Subject</option>
                            <?php
                            $sssql = "SELECT * FROM `subjects`";
                            $ssresult = mysqli_query($conn, $sssql);
                            while ($erow = mysqli_fetch_array($ssresult)) {
                                echo '<option value="' . $erow['subject_code'] . '">' . $erow['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="batch" required>
                            <option value="">Select Batch</option>
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="A3">A3</option>
                            <option value="A4">A4</option>
                            <option value="A5">A5</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-info">Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                <?php
                if (isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['subject']) && isset($_GET['batch']) && isset($_GET['branch'])) {
                    $sdateString = $_GET['startdate']; 
                    $edateString = $_GET['enddate']; 
                    $subject_code = $_GET['subject'];
                    $batch = $_GET['batch'];
                    $branch = $_GET['branch'];

                    $sql = "SELECT * FROM attendance WHERE STR_TO_DATE(`date`, '%Y-%m-%d') BETWEEN STR_TO_DATE('$sdateString', '%Y-%m-%d') AND STR_TO_DATE('$edateString', '%Y-%m-%d') AND `subject_code`=$subject_code AND `batch`='$batch' AND `branch`='$branch' ORDER BY id DESC";
            
                    $result = mysqli_query($conn, $sql);
                ?>
                    <h6 class="text-muted mb-3">Filtered : Start= <span class="text-white"><?php echo $_GET['startdate']; ?></span> , End= <span class="text-white"><?php echo $_GET['enddate']; ?></span></h6>
                    
                    <table class="table table-bordered table-striped table-future text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Enrollment No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Day</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Batch</th>
                                <th scope="col">QR Scan Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $sr; ?></th>
                                    <td><?php echo $row['enrollment_no']; ?></td>
                                    <td><?php
                                        $efssql = "SELECT * FROM `students` WHERE `enrollment_no`=" . $row['enrollment_no'];
                                        $efsresult = mysqli_query($conn, $efssql);
                                        $efsrow = mysqli_fetch_assoc($efsresult);
                                        echo $efsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['day']; ?></td>
                                    <td><?php
                                        $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $row['subject_code'];
                                        $fsresult = mysqli_query($conn, $fssql);
                                        $fsrow = mysqli_fetch_assoc($fsresult);
                                        echo $fsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['slot']; ?></td>
                                    <td><?php echo $row['batch']; ?></td>
                                    <td><?php echo $row['time']; ?></td>
                                </tr>
                            <?php
                                $sr++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else {
                ?>
                    <table class="table table-bordered table-striped table-future text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">Enrollment No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Day</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Batch</th>
                                <th scope="col">QR Scan Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $sr; ?></th>
                                    <td><?php echo $row['enrollment_no']; ?></td>
                                    <td><?php
                                        $efssql = "SELECT * FROM `students` WHERE `enrollment_no`=" . $row['enrollment_no'];
                                        $efsresult = mysqli_query($conn, $efssql);
                                        $efsrow = mysqli_fetch_assoc($efsresult);
                                        echo $efsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['day']; ?></td>
                                    <td><?php
                                        $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $row['subject_code'];
                                        $fsresult = mysqli_query($conn, $fssql);
                                        $fsrow = mysqli_fetch_assoc($fsresult);
                                        echo $fsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['slot']; ?></td>
                                    <td><?php echo $row['batch']; ?></td>
                                    <td><?php echo $row['time']; ?></td>
                                </tr>
                            <?php
                                $sr++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                }
                ?>
                </div> </div>
        </div>
    </div>
</div>
<?php
require('footer.php');
?>