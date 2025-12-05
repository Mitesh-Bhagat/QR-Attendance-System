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
            <li class="breadcrumb-item">Time Table</li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </nav>
</div>


<div class="container-fluid pt-4 px-4">
    <div class="text-center w-100 mb-3">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <button type="button" class="btn btn-outline-info m-2" data-bs-toggle="modal" data-bs-target="#exampleModal" style="color: var(--primary); border-color: var(--primary);">
            <i class="fas fa-plus-circle me-2"></i>Set New Time Table
        </button>
        <button type="button" class="btn btn-outline-danger m-2" data-bs-toggle="modal" data-bs-target="#deleteModal" style="color: #ff4d4d; border-color: #ff4d4d;">
            <i class="fas fa-trash-alt me-2"></i>Delete Time Table
        </button>
    </div>

    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Time Table Management</h6>
                
                <form class="row gy-2 gx-3 align-items-center border p-3 mb-4 rounded" style="border-color: rgba(255,255,255,0.1) !important;" action="timetable.php" method="get">
                    <div class="col-auto">
                        <p class="mb-0" style="color: var(--secondary); font-weight: bold;">VIEW CRITERIA:</p>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="semester">
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
                        <select class="form-select" name="batch">
                            <option value="">Select Batch</option>
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="A3">A3</option>
                            <option value="A4">A4</option>
                            <option value="A5">A5</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="academic">
                            <option value="">Select Academic Year</option>
                            <option value="2022-23">2022-23</option>
                            <option value="2023-24">2023-24</option>
                            <option value="2024-25">2024-25</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['academic']) && isset($_GET['branch'])) {
                ?>

                    <h6 class="mb-3 text-center mt-3" style="color: var(--primary); letter-spacing: 1px;">
                        Configuration: <?php echo $_GET['academic']; ?> | <?php echo $_GET['branch']; ?> | Batch: <?php echo $_GET['batch']; ?> | Sem: <?php echo $_GET['semester']; ?>
                    </h6>

                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $batch = mysqli_escape_string($conn, $_GET['batch']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    $branch = mysqli_escape_string($conn, $_GET['branch']);
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
                    $sqlslot = "SELECT DISTINCT `slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
                    $result1 = mysqli_query($conn, $sqlslot);
                    $result2 = mysqli_query($conn, $sql);

                    $slots = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $slots[] = $row['slotlabel'];
                    }

                    $timetable = [];
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $timetable[$row['day']][] = $row['subject_code'];
                    }

                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-future text-center">
                            <thead>
                                <tr>
                                    <th scope="col" style="color: var(--secondary);">Day</th>
                                    <?php foreach ($slots as $slot) : ?>
                                        <th scope="col"><?php echo $slot; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                $slotCount = count($slots);

                                foreach ($daysOfWeek as $day) :
                                    $dayData = isset($timetable[$day]) ? $timetable[$day] : array_fill(0, $slotCount, '');
                                ?>
                                    <tr>
                                        <td style="font-weight: bold; color: var(--secondary);"><?php echo $day; ?></td>
                                        <?php for ($i = 0; $i < $slotCount; $i++) : ?>
                                            <td><?php
                                                $subjectCode =  isset($dayData[$i]) ? $dayData[$i] : '';
                                                if($subjectCode) {
                                                    $subjectSQL = "SELECT * FROM `subjects` WHERE `subject_code`=$subjectCode";
                                                    $subjectRes = mysqli_query($conn, $subjectSQL);
                                                    $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                    echo $subjectRow['name'];
                                                } else {
                                                    echo "-";
                                                }
                                                ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: var(--primary); font-family: 'Orbitron', sans-serif;">Set New Time Table</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <form action="api_timetable.php?type=add" method="post">
                        <div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Select Academic Year</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="academicyear" required>
                                        <option value="">Open this select menu</option>
                                        <option value="2022-23">2022-23</option>
                                        <option value="2023-24">2023-24</option>
                                        <option value="2024-25">2024-25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Select Branch</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="branch" required>
                                        <option value="">Open this select menu</option>
                                        <option value="Computer Engineering">Computer Engineering</option>
                                        <option value="Civil Engineering">Civil Engineering</option>
                                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                                        <option value="Electrical Engineering">Electrical Engineering</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Select Semester</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="semester" required>
                                        <option value="">Open this select menu</option>
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
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Select Batch</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="batch" required>
                                        <option value="">Open this select menu</option>
                                        <option value="A1">A1</option>
                                        <option value="A2">A2</option>
                                        <option value="A3">A3</option>
                                        <option value="A4">A4</option>
                                        <option value="A5">A5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-end align-items-center mt-4 mb-2 p-2" style="border-top: 1px solid #333;">
                            <label class="mb-0 text-white">Add New Slot:</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Slot Label (e.g. 10:00 - 11:00)" id="slotlabelinput" style="width: 200px;">
                            <button class="btn btn-outline-success btn-sm" onclick="addslot()" type="button"><i class="fas fa-plus"></i> Add</button>
                        </div>
                        <p class="text-danger small text-end">*Note: Ensure unique labels.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-future text-center">
                                <thead>
                                    <tr id="slotlabelbox">
                                        <th style="width: 150px;">Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="mondaySlotsBox">
                                        <td class="fw-bold" style="color:var(--secondary)">Monday</td>
                                    </tr>
                                    <tr id="tuesdaySlotsBox">
                                        <td class="fw-bold" style="color:var(--secondary)">Tuesday</td>
                                    </tr>
                                    <tr id="wednesdaySlotsBox">
                                        <td class="fw-bold" style="color:var(--secondary)">Wednesday</td>
                                    </tr>
                                    <tr id="thursdaySlotsBox">
                                        <td class="fw-bold" style="color:var(--secondary)">Thursday</td>
                                    </tr>
                                    <tr id="fridaySlotsBox">
                                        <td class="fw-bold" style="color:var(--secondary)">Friday</td>
                                    </tr>
                                    <tr id="saturdaySlotsBox">
                                        <td class="fw-bold" style="color:var(--secondary)">Saturday</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; color: #000;">Save Time Table</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: #ff4d4d; font-family: 'Orbitron', sans-serif;">Delete Time Table</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <form action="api_timetable.php?type=delete" method="post">
                        <div class="p-2 mb-3 rounded" style="background: rgba(255,0,0,0.1); border: 1px solid #ff4d4d;">
                             <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Warning: This action cannot be undone.</p>
                        </div>
                        <div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Select Branch</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="branch" required>
                                        <option value="">Open this select menu</option>
                                        <option value="Computer Engineering">Computer Engineering</option>
                                        <option value="Civil Engineering">Civil Engineering</option>
                                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                                        <option value="Electrical Engineering">Electrical Engineering</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Select Academic Year</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="academicyear" required>
                                        <option value="">Open this select menu</option>
                                        <option value="2022-23">2022-23</option>
                                        <option value="2023-24">2023-24</option>
                                        <option value="2024-25">2024-25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Select Semester</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="semester" required>
                                        <option value="">Open this select menu</option>
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
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Select Batch</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="batch" required>
                                        <option value="">Open this select menu</option>
                                        <option value="A1">A1</option>
                                        <option value="A2">A2</option>
                                        <option value="A3">A3</option>
                                        <option value="A4">A4</option>
                                        <option value="A5">A5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addslot() {
        let timelabel = $('#slotlabelinput').val();
        if (timelabel == "") {
            alert("Please enter a time label for the slot.");
        } else {
            $('#slotlabelbox').append(`<th scope="col" style="color:var(--primary);">${timelabel} <input type="text" readonly value="${timelabel}" name="slots[]" hidden></th>`);

            // I have added 'form-select' class here so the appended dropdowns inherit the dark theme immediately
            
            // MONDAY
            $('#mondaySlotsBox').append(`<td>
                <select class="form-select" name="Monday[]" required>
                    <option value="">Subject</option>
                    <?php
                    $tsql = "SELECT * FROM subjects";
                    $tresult = mysqli_query($conn, $tsql);
                    while ($trow = mysqli_fetch_assoc($tresult)) {
                    ?>
                        <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>`);

            // TUESDAY
            $('#tuesdaySlotsBox').append(`<td>
                <select class="form-select" name="Tuesday[]" required>
                    <option value="">Subject</option>
                    <?php
                    // Reset Pointer
                    mysqli_data_seek($tresult, 0); 
                    while ($trow = mysqli_fetch_assoc($tresult)) {
                    ?>
                        <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>`);

            // WEDNESDAY
            $('#wednesdaySlotsBox').append(`<td>
                <select class="form-select" name="Wednesday[]" required>
                    <option value="">Subject</option>
                    <?php
                    mysqli_data_seek($tresult, 0);
                    while ($trow = mysqli_fetch_assoc($tresult)) {
                    ?>
                        <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>`);

            // THURSDAY
            $('#thursdaySlotsBox').append(`<td>
                <select class="form-select" name="Thursday[]" required>
                    <option value="">Subject</option>
                    <?php
                    mysqli_data_seek($tresult, 0);
                    while ($trow = mysqli_fetch_assoc($tresult)) {
                    ?>
                        <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>`);

            // FRIDAY
            $('#fridaySlotsBox').append(`<td>
                <select class="form-select" name="Friday[]" required>
                    <option value="">Subject</option>
                    <?php
                    mysqli_data_seek($tresult, 0);
                    while ($trow = mysqli_fetch_assoc($tresult)) {
                    ?>
                        <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>`);

            // SATURDAY
            $('#saturdaySlotsBox').append(`<td>
                <select class="form-select" name="Saturday[]" required>
                    <option value="">Subject</option>
                    <?php
                    mysqli_data_seek($tresult, 0);
                    while ($trow = mysqli_fetch_assoc($tresult)) {
                    ?>
                        <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>`);

            $('#slotlabelinput').val('');
        }
    }
</script>

<?php
require('footer.php');
?>