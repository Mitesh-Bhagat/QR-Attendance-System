<?php
session_start();
require "conn.php";

if (isset($_GET['type']) && $_GET['type'] == "admin") {
    $username = mysqli_escape_string($conn, $_POST['username']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if ($result->num_rows == 1) {
        if (password_verify($password, $row['password'])) {
            if ($row['status'] == 1) {
                if ($row['user_type'] == "ADMIN") {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['usertype'] = $row['user_type'];
                    $_SESSION['logged'] = true;
                    header("location: ./");
                    exit();
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
                 Invalid login credentials!.
            </div>';
                    header("location: login.php");
                    exit();
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
            Invalid login credentials!.
        </div>';
                header("location: login.php");
                exit();
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
            Invalid login credentials!.
        </div>';
            header("location: login.php");
            exit();
        }
    }
} else if (isset($_GET['type']) && $_GET['type'] == "teacher") {
    $teacherid = mysqli_escape_string($conn, $_POST['teacherid']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM teachers WHERE id='$teacherid' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);


    if ($result->num_rows == 1) {
        $_SESSION['username'] = $row['name'];
        $_SESSION['teacher_id'] = $row['id'];
        $_SESSION['usertype'] = "TEACHER";
        $_SESSION['logged'] = true;
        header("location: ./");
        exit();;
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
                Invalid login credentials!.
            </div>';
        header("location: login.php");
        exit();
    }
} else if (isset($_GET['type']) && $_GET['type'] == "student") {
    $enroll = mysqli_escape_string($conn, $_POST['enroll']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM students WHERE enrollment_no=$enroll AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);


    if ($result->num_rows == 1) {
        $_SESSION['username'] = $row['name'];
        $_SESSION['enrollment_no'] = $row['enrollment_no'];
        $_SESSION['usertype'] = "STUDENT";
        $_SESSION['logged'] = true;
        header("location: ./");
        exit();
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
            Invalid login credentials!.
        </div>';
        header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYSTEM LOGIN // QR-AS</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
    <link href="css/theme of project.css" rel="stylesheet">
</head>
<body>

    <div class="login-container">
        <h2>System Access</h2>

        <div class="role-switch">
            <div class="role-option">
                <input type="radio" name="role" id="r_admin" value="admin" checked onchange="toggleForms()">
                <label for="r_admin">ADMIN</label>
            </div>
            <div class="role-option">
                <input type="radio" name="role" id="r_teacher" value="teacher" onchange="toggleForms()">
                <label for="r_teacher">TEACHER</label>
            </div>
            <div class="role-option">
                <input type="radio" name="role" id="r_student" value="student" onchange="toggleForms()">
                <label for="r_student">STUDENT</label>
            </div>
        </div>

        <form id="form_admin" action="login.php?type=admin" method="post">
            <div class="input-group">
                <label>COMMANDER ID</label>
                <input type="text" name="username" class="input-field" placeholder="Admin Username">
            </div>
            <div class="input-group">
                <label>ACCESS CODE</label>
                <input type="password" name="password" class="input-field" placeholder="••••••">
            </div>
            <button type="submit" class="btn-future">INITIALIZE</button>
        </form>

        <form id="form_teacher" action="login.php?type=teacher" method="post" style="display:none;">
            <div class="input-group">
                <label>FACULTY ID</label>
                <input type="text" name="teacherid" class="input-field" placeholder="Teacher ID">
            </div>
            <div class="input-group">
                <label>ACCESS CODE</label>
                <input type="password" name="password" class="input-field" placeholder="••••••">
            </div>
            <button type="submit" class="btn-future">AUTHENTICATE</button>
        </form>

        <form id="form_student" action="login.php?type=student" method="post" style="display:none;">
            <div class="input-group">
                <label>ENROLLMENT SEQUENCE</label>
                <input type="text" name="enroll" class="input-field" placeholder="Enrollment No">
            </div>
            <div class="input-group">
                <label>ACCESS CODE</label>
                <input type="password" name="password" class="input-field" placeholder="••••••">
            </div>
            <button type="submit" class="btn-future">CONNECT</button>
        </form>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert-msg">
                <?php echo strip_tags($_SESSION['msg']); unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

    </div>

    <script>
        function toggleForms() {
            // Hide all forms
            document.getElementById('form_admin').style.display = 'none';
            document.getElementById('form_teacher').style.display = 'none';
            document.getElementById('form_student').style.display = 'none';

            // Show selected form
            if (document.getElementById('r_admin').checked) {
                document.getElementById('form_admin').style.display = 'block';
            } else if (document.getElementById('r_teacher').checked) {
                document.getElementById('form_teacher').style.display = 'block';
            } else if (document.getElementById('r_student').checked) {
                document.getElementById('form_student').style.display = 'block';
            }
        }
    </script>
</body>
</html>