<?php
require('header.php');
require('conn.php');


if ($_SESSION['usertype'] != 'TEACHER') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_GET['subject_code']) && isset($_GET['slot']) && isset($_GET['batch']) && isset($_GET['day'])) {
    $subject_code = mysqli_escape_string($conn, $_GET['subject_code']);
    $slot = mysqli_escape_string($conn, $_GET['slot']);
    $batch = mysqli_escape_string($conn, $_GET['batch']);
    $day = mysqli_escape_string($conn, $_GET['day']);
    $semester = mysqli_escape_string($conn, $_GET['semester']);
    $branch = mysqli_escape_string($conn, $_GET['branch']);
    $slotlabel = mysqli_escape_string($conn, $_GET['slotlabel']);

    $currentDate = date('d-m-Y');
    $currentDay = date('l');
    $currentTime = date('h:i:s a', time());

    $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $subject_code;
    $fsresult = mysqli_query($conn, $fssql);
    $fsrow = mysqli_fetch_assoc($fsresult);

    $sql = "SELECT * FROM `timetable` WHERE `subject_code`='$subject_code' AND `day`='$day' AND `slot`='$slot' AND `batch`='$batch'";
    $result = mysqli_query($conn, $sql);

    $currentTimestamp = time();
    if ($result->num_rows == 1) {
        $qrdata = array("subject_code" => $subject_code, "day" => $day, "slot" => $slot, "batch" => $batch, "currentDate" => $currentDate, "currentDay" => $currentDay, "qrgentime" => $currentTimestamp, "semester" => $semester, "branch" => $fsrow['branch']);
        $encryptQR = base64_encode(json_encode($qrdata));
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
        Class Not Available!.
    </div>';
        header("location: take_attendance.php");
        exit();
    }
}
?>
<link href="css/theme of project.css" rel="stylesheet">

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item">Take Attendance</li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $fsrow['name']; ?></li>
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
                
                <div class="row">
                    <div class="col-md-7">
                        <h6 class="mb-3" style="font-family: 'Orbitron', sans-serif; color: var(--secondary);">
                            <i class="fas fa-terminal me-2"></i> Real Time Attendance Logs
                        </h6>
                        <ul id="notifications" class="rounded mb-4"></ul>

                        <h6 class="mb-3" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Class Details</h6>
                        <table class="table table-bordered table-future">
                            <tbody>
                                <tr>
                                    <td class="label-col">Subject</td>
                                    <td><?php echo $fsrow['name']; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-col">Batch</td>
                                    <td><?php echo $batch; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-col">Semester</td>
                                    <td><?php echo $semester; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-col">Date / Day</td>
                                    <td><?php echo $currentDate . " (" . $currentDay . ")"; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-col">Time Slot</td>
                                    <td><?php echo $slotlabel; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <h5 class="mb-3" style="color: #fff; text-shadow: 0 0 10px #fff;">SCAN TO MARK ATTENDANCE</h5>
                        <div class="qr-frame rounded">
                            <img class="img-fluid" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?php echo $encryptQR; ?>" title="QR Generated" />
                        </div>
                        <p class="mt-3 text-muted">
                            <small>QR Code is generated securely.</small>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    function fetchNotifications() {
        $.ajax({
            url: 'api_noti.php',
            type: 'GET',
            dataType: 'json',
            data: {
                branch: "<?php echo $branch; ?>",
                batch: "<?php echo $batch; ?>",
                semester: "<?php echo $semester; ?>",
                slot: "<?php echo $slot; ?>"
            },
            success: function(data) {
                // We do NOT empty the list here so logs can accumulate or we can empty if we want only fresh snapshot. 
                // Original code emptied it. Let's stick to original logic but ensure style is applied.
                $('#notifications').empty();

                data.forEach(function(notification) {
                    // Added timestamp for terminal feel
                    var logTime = new Date().toLocaleTimeString();
                    var notificationElement = $(`<li class="notification">
                        <span style="color: #888;">[${logTime}]</span> 
                        <strong>${notification.name}</strong> 
                        <span style="color: var(--secondary); float:right;">[${notification.ip_address}]</span>
                    </li>`);
                    
                    $('#notifications').prepend(notificationElement);

                    // Remove the notification after 4 seconds (as per original logic logic)
                    setTimeout(function() {
                        notificationElement.fadeOut(1000, function() {
                            $(this).remove();
                        });
                    }, 4000); 

                });
            }
        });
    }

    setInterval(fetchNotifications, 2000); // Poll every 2 seconds
</script>

<?php
require('footer.php');
?>