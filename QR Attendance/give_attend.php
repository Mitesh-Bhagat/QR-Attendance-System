<?php
require('header.php');
require('conn.php');


if ($_SESSION['usertype'] != 'STUDENT') {
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
            <li class="breadcrumb-item active">Mark Attendance</li>
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
                <h6 class="mb-2" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">SCANNER INTERFACE</h6>
                <p class="mb-4 text-muted"><i class="fas fa-info-circle me-2"></i>Align the QR Code within the frame to mark attendance.</p>
                
                <div class="alert alert-custom-danger fw-bold" id="locationWarnAlert" role="alert">
                    <i class="fas fa-map-marker-alt me-2"></i> Please Allow Location Permission To Give Attendance.
                </div>
                
                <div class="text-center py-3">
                    <div class="scanner-container">
                        <video id="preview" class="img-thumbnail"></video>
                    </div>
                </div>
                
                <div class="btn-group btn-group-toggle mb-5 text-center w-100 mt-4" data-toggle="buttons">
                    <label class="btn btn-neon-toggle active">
                        <input type="radio" name="options" value="1" autocomplete="off" checked> 
                        <i class="fas fa-camera me-2"></i> Front Cam
                    </label>
                    <label class="btn btn-neon-toggle">
                        <input type="radio" name="options" value="2" autocomplete="off"> 
                        <i class="fas fa-camera-retro me-2"></i> Back Cam
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    getLocation()

    function showPosition(position) {
        let locationWarnAlert = document.getElementById("locationWarnAlert");
        locationWarnAlert.style.display = "none";

        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        let clientIp = '';

        // Fetch the client's IP address
        $.get('https://api.ipify.org?format=json', function(data) {
            clientIp = data.ip;
        });


        var scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: false
        });
        scanner.addListener('scan', function(content) {
            // alert(content);
            window.location.href = `api_give_attend.php?data=${content}&lat=${lat}&lon=${lon}&ip_address=${clientIp}`;
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                $('[name="options"]').on('change', function() {
                    // Update Active Class for styling
                    $('.btn-neon-toggle').removeClass('active');
                    $(this).parent().addClass('active');

                    if ($(this).val() == 1) {
                        if (cameras[0] != "") {
                            scanner.start(cameras[0]);
                        } else {
                            alert('No Front camera found!');
                        }
                    } else if ($(this).val() == 2) {
                        if (cameras[1] != "") {
                            scanner.start(cameras[1]);
                        } else {
                            alert('No Back camera found!');
                        }
                    }
                });
            } else {
                console.error('No cameras found.');
                alert('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
            alert(e);
        });
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById("locationWarnAlert").innerHTML = "<i class='fas fa-exclamation-triangle me-2'></i> Please Allow Location Permission To Give Attendance.";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById("locationWarnAlert").innerHTML = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                document.getElementById("locationWarnAlert").innerHTML = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById("locationWarnAlert").innerHTML = "An unknown error occurred.";
                break;
        }
    }
</script>
<?php
require('footer.php');
?>