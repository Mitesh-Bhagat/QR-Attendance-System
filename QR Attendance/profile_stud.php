<?php
require('header.php');
require('conn.php');

if($_SESSION['usertype'] != 'STUDENT'){
    session_destroy();
    header("location: login.php");
    exit();
}

$enrollment_no = $_SESSION['enrollment_no'];
$sql = "SELECT * FROM `students` WHERE `enrollment_no`= $enrollment_no";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Profile</li>
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
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">My Profile</h6>
                
                <form action="api_self_stud_update.php" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Profile Picture</label>
                        <div id="piceditbox" class="col-sm-9" hidden>
                            <input type="file" name="pic" class="form-control">
                        </div>
                        <div id="picshow" class="col-sm-9">
                            <img src="./img/profile/<?php echo $row['pic']; ?>" alt="No image upload" width="150px" height="150px" class="rounded-circle" style="border: 2px solid var(--primary); box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Enrollment No</label>
                        <div class="col-sm-9">
                            <input type="text" name="enrollmentno" class="form-control" value="<?php echo $enrollment_no; ?>" id="inputEmail3" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Student Name</label>
                        <div class="col-sm-9">
                            <input type="text" id="studentname" name="studentname" value="<?php echo $row['name']; ?>" class="form-control" id="inputPassword3" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Semester</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="semester" name="semester" value="<?php echo $row['semester']; ?>" required disabled>
                                <option selected="">Open this select menu</option>
                                <option value="1" <?php if ($row['semester'] == '1') echo ' selected="selected"'; ?>>First</option>
                                <option value="2" <?php if ($row['semester'] == '2') echo ' selected="selected"'; ?>>Second</option>
                                <option value="3" <?php if ($row['semester'] == '3') echo ' selected="selected"'; ?>>Third</option>
                                <option value="4" <?php if ($row['semester'] == '4') echo ' selected="selected"'; ?>>Fourth</option>
                                <option value="5" <?php if ($row['semester'] == '5') echo ' selected="selected"'; ?>>Fifth</option>
                                <option value="6" <?php if ($row['semester'] == '6') echo ' selected="selected"'; ?>>Sixth</option>
                                <option value="7" <?php if ($row['semester'] == '7') echo ' selected="selected"'; ?>>Seven</option>
                                <option value="8" <?php if ($row['semester'] == '8') echo ' selected="selected"'; ?>>Eight</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Branch</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="branch" name="branch" value="<?php echo $row['branch']; ?>" required disabled>
                                <option selected="">Open this select menu</option>
                                <option value="Computer Engineering" <?php if ($row['branch'] == 'Computer Engineering') echo ' selected="selected"'; ?>>Computer Engineering</option>
                                <option value="Mechanical Engineering" <?php if ($row['branch'] == 'Mechanical Engineering') echo ' selected="selected"'; ?>>Mechanical Engineering</option>
                                <option value="Electrical Engineering" <?php if ($row['branch'] == 'Electrical Engineering') echo ' selected="selected"'; ?>>Electrical Engineering</option>
                                <option value="Civil Engineering" <?php if ($row['branch'] == 'Civil Engineering') echo ' selected="selected"'; ?>>Civil Engineering</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Roll No</label>
                        <div class="col-sm-9">
                            <input type="text" name="rollno" class="form-control" value="<?php echo $row['roll_no']; ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Batch</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="batch" value="<?php echo $row['batch']; ?>" required disabled>
                                <option selected="">Open this select menu</option>
                                <option value="A1" <?php if ($row['batch'] == 'A1') echo ' selected="selected"'; ?>>A1</option>
                                <option value="A2" <?php if ($row['batch'] == 'A2') echo ' selected="selected"'; ?>>A2</option>
                                <option value="A3" <?php if ($row['batch'] == 'A3') echo ' selected="selected"'; ?>>A3</option>
                                <option value="A4" <?php if ($row['batch'] == 'A4') echo ' selected="selected"'; ?>>A4</option>
                                <option value="A5" <?php if ($row['batch'] == 'A5') echo ' selected="selected"'; ?>>A5</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-info w-25 m-2" onclick="edit()" style="border-color: var(--primary); color: var(--primary);">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </button>
                        <button type="submit" class="btn btn-primary w-25 m-2" id="formbtn" hidden style="background: var(--primary); border: none; color: #000; font-weight: bold;">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function edit() {
        $('#piceditbox').removeAttr("hidden");
        $('#studentname').removeAttr("readonly");
        $('#picshow').attr("hidden", true);
        $('#formbtn').removeAttr("hidden");
        // Also allow changing semester/batch if that logic is desired, currently kept disabled in PHP logic
    }
</script>


<?php
require('footer.php');
?>