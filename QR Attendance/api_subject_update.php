<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}
?>

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Subjects</li>
            <li class="breadcrumb-item">Details</li>
            <li class="breadcrumb-item active">Update</li>
        </ol>
    </nav>
</div>


<div class="container-fluid pt-4 px-4">
    <?php
    if (isset($_GET['enroll'])) {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);
        $sql = "SELECT * FROM subjects WHERE subject_code='$enroll'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    }
    ?>

    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Update Subject</h6>
                
                <form action="api_subject.php?type=update" method="post">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Subject Code</label>
                        <div class="col-sm-9">
                            <input type="number" name="subjectcode" value="<?php echo $row['subject_code']; ?>" readonly class="form-control" style="background: #1a1a1a; color: #888;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Description (Name)</label>
                        <div class="col-sm-9">
                            <input type="text" name="description" class="form-control" value="<?php echo $row['name']; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Abbreviation</label>
                        <div class="col-sm-9">
                            <input type="text" name="abbrevation" class="form-control" value="<?php echo $row['abbreviation']; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Semester</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="semester" required>
                                <option disabled>Select Semester</option>
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
                        <label class="col-sm-3 col-form-label">Branch</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="branch" required>
                                <option disabled>Select Branch</option>
                                <option value="Computer Engineering" <?php if ($row['branch'] == 'Computer Engineering') echo ' selected="selected"'; ?>>Computer Engineering</option>
                                <option value="Mechanical Engineering" <?php if ($row['branch'] == 'Mechanical Engineering') echo ' selected="selected"'; ?>>Mechanical Engineering</option>
                                <option value="Electrical Engineering" <?php if ($row['branch'] == 'Electrical Engineering') echo ' selected="selected"'; ?>>Electrical Engineering</option>
                                <option value="Civil Engineering" <?php if ($row['branch'] == 'Civil Engineering') echo ' selected="selected"'; ?>>Civil Engineering</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Taught By</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="teacherid" required>
                                <option disabled>Select Teacher</option>
                                <?php
                                $tsql = "SELECT * FROM teachers";
                                $tresult = mysqli_query($conn, $tsql);
                                while ($trow = mysqli_fetch_assoc($tresult)) {
                                ?>
                                    <option value="<?php echo $trow['id']; ?>" <?php if ($row['teacher_id'] == $trow['id']) echo ' selected="selected"'; ?>><?php echo $trow['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="text-end mt-4">
                        <a href="subject_details.php" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; color: #000; font-weight: bold; box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);">Update Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require('footer.php');
?>