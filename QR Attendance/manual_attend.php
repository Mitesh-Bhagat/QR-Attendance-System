<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
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
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Take Manual Attendance</h6>
                
                <form class="row gy-2 gx-3 align-items-center border p-3 mb-4 rounded" style="border-color: rgba(255,255,255,0.1) !important;" action="manual_attend.php" method="get">
                    <div class="col-auto">
                        <p class="mb-0" style="color: var(--secondary); font-weight: bold;">SELECT CRITERIA:</p>
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
                        <select class="form-select" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
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
                        <button type="submit" class="btn btn-outline-info">Search Time Table</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['academic']) && isset($_GET['branch'])) {
                ?>

                    <h6 class="mb-4 text-center mt-4" style="color: var(--primary); letter-spacing: 1px;">
                        Time Table: <span class="text-white"><?php echo $_GET['academic']; ?></span> | 
                        Branch: <span class="text-white"><?php echo $_GET['branch']; ?></span> | 
                        Batch: <span class="text-white"><?php echo $_GET['batch']; ?></span> | 
                        Sem: <span class="text-white"><?php echo $_GET['semester']; ?></span>
                    </h6>

                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $batch = mysqli_escape_string($conn, $_GET['batch']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    $branch = mysqli_escape_string($conn, $_GET['branch']);
                    
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
                    $sqlslot = "SELECT DISTINCT `slot`,`slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
                    
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
                        echo "<p class='text-center text-muted'>No time table data found for this selection.</p>";
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
                                                    $subjectSQL = "SELECT `name` FROM `subjects` WHERE `subject_code`='$subjectCode'";
                                                    $subjectRes = mysqli_query($conn, $subjectSQL);
                                                    $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                    
                                                    echo '<span class="subject-name">' . $subjectRow['name'] . '</span>';
                                                    
                                                    echo '<a href="give_manual_attend.php?subject_code=' . $subjectCode . '&slot=' . $slot . '&batch=' . $batch . '&semester=' . $semester . '&branch=' . $branch . '&day=' . $day . '" title="Take Attendance" class="action-icon">
                                                            <i class="fas fa-clipboard-check ms-2"></i>
                                                          </a>';
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
                    } // End Else for empty slots
                } // End IF params set
                ?>
            </div>
        </div>
    </div>
</div>
<?php
require('footer.php');
?>