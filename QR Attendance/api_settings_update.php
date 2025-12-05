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
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">Update Configuration</li>
        </ol>
    </nav>
</div>


<div class="container-fluid pt-4 px-4">
    <?php
    $sql = "SELECT * FROM settings WHERE id='1'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>

    <div class="row mx-0">
        <div class="col-12">
            <div class="glass-box rounded h-100 p-4">
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">Update System Parameters</h6>
                
                <form action="api_settings.php?type=update" method="post">
                    <div class="row mb-3">
                        <label for="location" class="col-sm-3 col-form-label">Campus Location Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="location" id="location" class="form-control" value="<?php echo $row['location']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="lat" class="col-sm-3 col-form-label">Latitude</label>
                        <div class="col-sm-9">
                            <input type="text" name="lat" id="lat" class="form-control" value="<?php echo $row['lat']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="lon" class="col-sm-3 col-form-label">Longitude</label>
                        <div class="col-sm-9">
                            <input type="text" name="lon" id="lon" class="form-control" value="<?php echo $row['lon']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="covarage" class="col-sm-3 col-form-label">Coverage Radius (KM)</label>
                        <div class="col-sm-9">
                            <input type="text" name="covarage" id="covarage" class="form-control" value="<?php echo $row['covarage']; ?>" required>
                            
                            <div class="mt-3 p-3 rounded" style="background: rgba(0, 255, 0, 0.05); border: 1px dashed var(--secondary);">
                                <small class="d-block" style="color: #00ff00; margin-bottom: 5px;">
                                    <i class="fas fa-info-circle me-2"></i><strong>Note:</strong> Defines the allowable geofence area for attendance.
                                </small>
                                <small class="d-block" style="color: #aaa; margin-bottom: 5px;">
                                    <i class="fas fa-check me-2"></i>Examples: 0.5 (for 500m), 1.0 (for 1km), 5.0
                                </small>
                                <small class="d-block text-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Enter numeric values only. Do not add 'KM'.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="settings.php" class="btn btn-outline-secondary me-2" style="border-color: #666; color: #aaa;">Cancel</a>
                        <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; color: #000; font-weight: bold; box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);">
                            <i class="fas fa-save me-2"></i> Update Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require('footer.php');
?>