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
            <li class="breadcrumb-item">Students</li>
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
            <i class="fas fa-plus me-2"></i>Add New Student
        </button>
        <button type="button" class="btn btn-outline-success m-2" data-bs-toggle="modal" data-bs-target="#excelstudModal">
            <i class="fas fa-file-excel me-2"></i>Add Bulk (Excel)
        </button>
    </div>

    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Student Registry</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-future text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Enrollment No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Roll No</th>
                                <th scope="col">Batch</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM students";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $row['enrollment_no']; ?></th>
                                    <td class="text-start ps-3"><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['semester']; ?></td>
                                    <td><?php echo $row['branch']; ?></td>
                                    <td><?php echo $row['roll_no']; ?></td>
                                    <td><?php echo $row['batch']; ?></td>
                                    <td>
                                        <button type="button" onclick="updatestud('<?php echo $row['enrollment_no']; ?>')" class="btn btn-square btn-neon-action btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="deletestud('<?php echo $row['enrollment_no']; ?>')" class="btn btn-square btn-neon-delete btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: var(--primary); font-family: 'Orbitron', sans-serif;">Add New Student</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <form action="api_stud.php?type=add" method="post">
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Enrollment No</label>
                            <div class="col-sm-8">
                                <input type="text" name="enrollmentno" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Student Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="studentname" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Semester</label>
                            <div class="col-sm-8">
                                <select class="form-select" name="semester" required>
                                    <option selected disabled>Select Semester</option>
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
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Branch</label>
                            <div class="col-sm-8">
                                <select class="form-select" name="branch" required>
                                    <option selected disabled>Select Branch</option>
                                    <option value="Computer Engineering">Computer Engineering</option>
                                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                                    <option value="Electrical Engineering">Electrical Engineering</option>
                                    <option value="Civil Engineering">Civil Engineering</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Roll No</label>
                            <div class="col-sm-8">
                                <input type="text" name="rollno" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Batch</label>
                            <div class="col-sm-8">
                                <select class="form-select" name="batch" required>
                                    <option selected disabled>Select Batch</option>
                                    <option value="A1">A1</option>
                                    <option value="A2">A2</option>
                                    <option value="A3">A3</option>
                                    <option value="A4">A4</option>
                                    <option value="A5">A5</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" style="color: #aaa;">Password</label>
                            <div class="col-sm-8">
                                <input type="text" name="password" class="form-control">
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; color: #000;">Save Student</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="excelstudModal" tabindex="-1" aria-labelledby="excelstudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="excelstudModalLabel" style="color: var(--primary); font-family: 'Orbitron', sans-serif;">Add Bulk Students</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div style="border: 1px dashed var(--secondary); padding: 10px; margin-bottom: 15px; background: rgba(0,0,0,0.5);">
                        <img src="./img/excelformat.PNG" alt="Excel Format" class="img-fluid" style="opacity: 0.9;">
                    </div>
                    <p class="text-danger mt-2" style="font-size: 0.9rem;">*Note: Please ensure the Excel file matches the format above exactly.</p>
                    
                    <form action="api_stud.php?type=bulk" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" style="color: #aaa;">Select Excel File</label>
                            <div class="col-sm-9">
                                <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Upload & Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function deletestud(enroll) {
        let isDelete = confirm('Are you sure you want to delete this student?');
        if (isDelete) {
            window.location = `api_stud.php?type=delete&enroll=${enroll}`;
        }
    }

    function updatestud(enroll) {
        window.location = `api_stud_update.php?enroll=${enroll}`;
    }
</script>

<?php
require('footer.php');
?>