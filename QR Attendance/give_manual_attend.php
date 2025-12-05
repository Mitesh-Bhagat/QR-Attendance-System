<?php
require('header.php');
require('conn.php');


if (isset($_GET['subject_code']) && isset($_GET['slot']) && isset($_GET['batch']) && isset($_GET['semester'])) {
    $subject_code = $_GET['subject_code'];
    $slot = $_GET['slot'];
    $batch = $_GET['batch'];
    // $currentDay = $_GET['day'];
    $semester = $_GET['semester'];
    $branch = $_GET['branch'];

    $currentDate = date('d-m-Y');
    $currentDay = $_GET['day'];
    $currentTime = date('h:i:s a', time());

    $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $subject_code;
    $fsresult = mysqli_query($conn, $fssql);
    $fsrow = mysqli_fetch_assoc($fsresult);

    $sql = "SELECT * FROM `timetable` WHERE `subject_code`='$subject_code' AND `semester`='$semester' AND `branch`='$branch' AND `day`='$currentDay' AND `slot`='$slot' AND `batch`='$batch'";

    $result = mysqli_query($conn, $sql);

    $currentTimestamp = time();
    if ($result->num_rows == 1) {
        $studSQl = "SELECT * FROM `students` WHERE `semester`='$semester' AND `branch`='$branch' AND `batch`='$batch'";
        $studRes = mysqli_query($conn, $studSQl);
    } else {
        echo '<script>
        alert(" Lecture Not Allocated On ' . $currentDay . '!.");
        window.location.href ="manual_attend.php";
    </script>';
    }
}
?>
<link href="css/theme of project.css" rel="stylesheet">
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item active">Manual Attendance</li>
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
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Manual Entry Authorization</h6>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-future">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Batch</th>
                                <th>Semester</th>
                                <th>Branch</th>
                                <th>Day</th>
                                <th>Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-white fw-bold"><?php echo $fsrow['name']; ?></td>
                                <td><?php echo $batch; ?></td>
                                <td><?php echo $semester; ?></td>
                                <td><?php echo $branch; ?></td>
                                <td style="color: var(--secondary);"><?php echo $currentDay; ?></td>
                                <td><?php echo $slot; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form action="api_manual_attend.php" method="post">
                    <input class="form-control" type="text" name="batch" value="<?php echo $batch; ?>" hidden>
                    <input class="form-control" type="text" name="semester" value="<?php echo $semester; ?>" hidden>
                    <input class="form-control" type="text" name="branch" value="<?php echo $branch; ?>" hidden>
                    <input class="form-control" type="text" name="slot" value="<?php echo $slot; ?>" hidden>
                    <input class="form-control" type="text" name="subject_code" value="<?php echo $subject_code; ?>" hidden>
                    <input class="form-control" type="text" id="currentDay" name="currentDay" value="<?php echo $currentDay; ?>" readonly hidden>
                    <input class="form-control" type="text" id="clientIp" name="clientIp" readonly hidden>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for=""><i class="far fa-calendar-alt me-2"></i>Select Date</label>
                            <input class="form-control" type="date" name="date" required>
                            <p class="text-warning-custom"><i class="fas fa-exclamation-triangle me-2"></i> Ensure the date matches the day (<?php echo $currentDay; ?>) above.</p>
                        </div>
                        <div class="col-md-6">
                            <label for=""><i class="far fa-clock me-2"></i>Select Time</label>
                            <input class="form-control" type="time" name="time" required>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3" style="color: var(--primary); border-bottom: 1px solid var(--secondary); display:inline-block;">Student List</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-future text-center">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 100px;">Mark</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Enrollment No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($studrow = mysqli_fetch_assoc($studRes)) {
                                ?>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" name="students[]" value="<?php echo $studrow['enrollment_no']; ?>">
                                        </td>
                                        <td class="text-start ps-5"><?php echo $studrow['name']; ?></td>
                                        <td><?php echo $studrow['enrollment_no']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-lg" style="background: var(--primary); color: #000; font-weight: bold; border: 1px solid #fff; box-shadow: 0 0 15px var(--primary);">
                            <i class="fas fa-check-circle me-2"></i> Submit Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
    let clientIp = document.getElementById("clientIp");

    // Fetch the client's IP address
    $.get('https://api.ipify.org?format=json', function(data) {
        clientIp.value = data.ip;
    });
</script>

<?php
require('footer.php');
?>