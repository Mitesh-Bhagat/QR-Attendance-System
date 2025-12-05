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
            <li class="breadcrumb-item">Teachers</li>
            <li class="breadcrumb-item">Details</li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>
</div>


<div class="container-fluid pt-4 px-4">
    <?php
    if (isset($_GET['enroll'])) {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);
        $sql = "SELECT * FROM teachers WHERE id='$enroll'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    }
    ?>

    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Update Teacher Profile</h6>
                
                <form action="api_teacher.php?type=update" method="post">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Teacher ID</label>
                        <div class="col-sm-9">
                            <input type="text" name="id" value="<?php echo $row['id']; ?>" readonly class="form-control" style="background-color: #1a1a1a; color: #888;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Teacher Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="teachername" class="form-control" value="<?php echo $row['name']; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Education</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="education" required>
                                <option disabled>Select Education</option>
                                <option value="B.E/B.Tech" <?php if ($row['education'] == 'B.E/B.Tech') echo ' selected="selected"'; ?>>B.E/B.Tech</option>
                                <option value="M.E/M.Tech" <?php if ($row['education'] == 'M.E/M.Tech') echo ' selected="selected"'; ?>>M.E/M.Tech</option>
                                <option value="Ph.d" <?php if ($row['education'] == 'Ph.d') echo ' selected="selected"'; ?>>Ph.d</option>
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
                        <label class="col-sm-3 col-form-label">Designation</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="designation" required>
                                <option disabled>Select Designation</option>
                                <option value="Instructor" <?php if ($row['designation'] == 'Instructor') echo ' selected="selected"'; ?>>Instructor</option>
                                <option value="Assistant Professor" <?php if ($row['designation'] == 'Assistant Professor') echo ' selected="selected"'; ?>>Assistant Professor</option>
                                <option value="Associate Professor" <?php if ($row['designation'] == 'Associate Professor') echo ' selected="selected"'; ?>>Associate Professor</option>
                                <option value="Professor" <?php if ($row['designation'] == 'Professor') echo ' selected="selected"'; ?>>Professor</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="text" name="password" class="form-control" value="<?php echo $row['password']; ?>">
                        </div>
                    </div>
                    
                    <div class="text-end mt-4">
                        <a href="teacher_details.php" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; color: #000; font-weight: bold; box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);">Update Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require('footer.php');
?>