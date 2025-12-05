<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'TEACHER') {
    session_destroy();
    header("location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$teacher_sql = "SELECT * FROM teachers WHERE `id`=$teacher_id";
$teacher_res = mysqli_query($conn, $teacher_sql);
$teacher_row = mysqli_fetch_assoc($teacher_res);
?>
<link href="css/theme of project.css" rel="stylesheet">

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item active">Take Attendance</li>
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
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Select Class to Take Attendance</h6>
                
                <form class="row gy-2 gx-3 align-items-center border p-3 mb-4 rounded" style="border-color: rgba(255,255,255,0.1) !important;" action="take_attendance.php" method="get">
                    <div class="col-auto">
                        <p class="mb-0" style="color: var(--secondary); font-weight: bold;">CRITERIA:</p>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                            <option value="3">Third</option>
                            <option value="4">Fourth</option>
                            <option value="5">Fifth</option>
                            <option value="6">Sixth</option>
                            <option value="7">Seven</option>
                            <option value="8">Eight</option>
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
                        <select class="form-select" name="academic" required>
                            <option value="">Select Academic Year</option>
                            <option value="2022-23">2022-23</option>
                            <option value="2023-24">2023-24</option>
                            <option value="2024-25">2024-25</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-info">Search Time Table</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['academic'])) {
                ?>

                    <h6 class="mb-4 text-center mt-3" style="color: var(--primary); letter-spacing: 1px;">
                        Time Table: <span class="text-white"><?php echo $_GET['academic']; ?></span> | 
                        Batch: <span class="text-white"><?php echo $_GET['batch']; ?></span> | 
                        Sem: <span class="text-white"><?php echo $_GET['semester']; ?></span>
                    </h6>

                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $batch = mysqli_escape_string($conn, $_GET['batch']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='" . $teacher_row['branch'] . "' AND `semester`='$semester' AND `batch`='$batch'";
                    $sqlslot = "SELECT DISTINCT `slot`,`slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='" . $teacher_row['branch'] . "' AND `semester`='$semester' AND `batch`='$batch'";
                    
                    $result1 = mysqli_query($conn, $sqlslot);
                    $result2 = mysqli_query($conn, $sql);

                    $slots = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $slots[$row['slot']] = $row['slotlabel'];
                    }

                    $timetable = [];
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $day = $row['day'];
                        $slot = $row['slot'];
                        if (!isset($timetable[$day])) {
                            $timetable[$day] = [];
                        }
                        $timetable[$day][$slot] = $row['subject_code'];
                    }
                    
                    if(empty($slots)) {
                        echo "<p class='text-center text-muted'>No schedule found for this selection.</p>";
                    } else {
                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-future text-center">
                            <thead>
                                <tr>
                                    <th scope="col" style="color: var(--secondary);">Day</th>
                                    <?php foreach ($slots as $slot => $slotLabel) : ?>
                                        <th scope="col"><?php echo $slotLabel; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                foreach ($daysOfWeek as $day) :
                                    $dayData = isset($timetable[$day]) ? $timetable[$day] : [];
                                ?>
                                    <tr>
                                        <td style="font-weight: bold; color: var(--secondary);"><?php echo $day; ?></td>
                                        <?php foreach ($slots as $slot => $slotLabel) : ?>
                                            <td>
                                                <?php
                                                $subjectCode = isset($dayData[$slot]) ? $dayData[$slot] : '';
                                                if ($subjectCode) {
                                                    // Fetch full subject details to check teacher_id
                                                    $subjectSQL = "SELECT * FROM `subjects` WHERE `subject_code`='$subjectCode'";
                                                    $subjectRes = mysqli_query($conn, $subjectSQL);
                                                    $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                    
                                                    echo '<span class="subject-name">' . $subjectRow['name'] . '</span>';

                                                    // Show button ONLY if: 
                                                    // 1. Current user is the teacher assigned to this subject
                                                    // 2. The Day matches Today
                                                    if ($subjectRow['teacher_id'] == $_SESSION['teacher_id'] && $day == date('l')) {
                                                        echo '<a href="take_attend.php?subject_code=' . $subjectCode . '&slot=' . $slot . '&batch=' . $batch . '&day=' . $day . '&semester=' . $semester . '&branch='. $teacher_row['branch'].'&slotlabel='. $slotLabel.'" title="Start Attendance" class="action-icon">
                                                                <i class="fas fa-qrcode"></i>
                                                              </a>';
                                                    }
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php
                    } // End Else empty slots
                } // End IF params set
                ?>
            </div>
        </div>
    </div>
</div>
<?php
require('footer.php');
?>