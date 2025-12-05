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
            <li class="breadcrumb-item active">Allocation Map</li>
        </ol>
    </nav>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Teacher-Student Allocation Matrix</h6>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-future text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">ID</th>
                                <th scope="col" style="width: 20%;">Teacher Name</th>
                                <th scope="col" style="width: 15%;">Branch</th>
                                <th scope="col" style="width: 10%;">Count</th>
                                <th scope="col">Allocated Students (Active Semesters)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 1. Get all Teachers
                            $t_sql = "SELECT * FROM teachers";
                            $t_res = mysqli_query($conn, $t_sql);

                            while ($teacher = mysqli_fetch_assoc($t_res)) {
                                $tid = $teacher['id'];
                                $branch = $teacher['branch'];

                                // 2. Find which Semesters this Teacher teaches based on Subjects
                                // We use DISTINCT because a teacher might have 2 subjects in the same semester
                                $sub_sql = "SELECT DISTINCT semester FROM subjects WHERE teacher_id = '$tid'";
                                $sub_res = mysqli_query($conn, $sub_sql);
                                
                                $semesters = [];
                                while($s = mysqli_fetch_assoc($sub_res)){
                                    $semesters[] = $s['semester'];
                                }

                                // 3. If teacher has subjects, find students in those semesters & branch
                                $student_list_html = "";
                                $student_count = 0;

                                if (!empty($semesters)) {
                                    $sem_list = implode(",", $semesters); // e.g., "3,5,7"
                                    
                                    $stud_sql = "SELECT name, enrollment_no, semester FROM students 
                                                 WHERE branch = '$branch' AND semester IN ($sem_list) 
                                                 ORDER BY semester ASC, name ASC";
                                    $stud_res = mysqli_query($conn, $stud_sql);
                                    $student_count = mysqli_num_rows($stud_res);

                                    if($student_count > 0){
                                        $student_list_html = '<div class="student-scroll-box text-start">';
                                        while($stud = mysqli_fetch_assoc($stud_res)){
                                            $student_list_html .= '<div class="student-item">
                                                <span class="badge bg-secondary me-2">Sem '.$stud['semester'].'</span>
                                                <span style="color: #fff;">'.$stud['name'].'</span> 
                                                <small class="text-muted ms-1">('.$stud['enrollment_no'].')</small>
                                            </div>';
                                        }
                                        $student_list_html .= '</div>';
                                    } else {
                                        $student_list_html = '<span class="text-muted">No students found in assigned semesters.</span>';
                                    }
                                } else {
                                    $student_list_html = '<span class="text-danger">No Subjects Assigned yet.</span>';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $teacher['id']; ?></td>
                                    <td class="fw-bold" style="color: var(--primary); text-align: left;"><?php echo $teacher['name']; ?></td>
                                    <td><?php echo $teacher['branch']; ?></td>
                                    <td><span class="badge" style="background: var(--primary); color: #fff;"><?php echo $student_count; ?></span></td>
                                    <td class="p-2"><?php echo $student_list_html; ?></td>
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
<style>
    /* Specific styles for the scrollable student list */
    .student-scroll-box {
        max-height: 150px;
        overflow-y: auto;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid #333;
        padding: 5px 10px;
        border-radius: 4px;
    }
    
    /* Custom Scrollbar for the box */
    .student-scroll-box::-webkit-scrollbar {
        width: 5px;
    }
    .student-scroll-box::-webkit-scrollbar-track {
        background: #000;
    }
    .student-scroll-box::-webkit-scrollbar-thumb {
        background: var(--secondary);
        border-radius: 10px;
    }

    .student-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 4px 0;
        font-size: 0.9rem;
    }
    .student-item:last-child {
        border-bottom: none;
    }
</style>

<?php
require('footer.php');
?>