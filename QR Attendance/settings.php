<?php
require('header.php');
require('conn.php');
?>
<link href="css/theme of project.css" rel="stylesheet">

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-3 rounded-4 breadcrumb-container">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Settings</li>
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
                <h6 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: var(--primary);">System Configurations</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-future text-center">
                        <thead>
                            <tr>
                                <th scope="col">Campus Location</th>
                                <th scope="col">Latitude </th>
                                <th scope="col">Longitude</th>
                                <th scope="col">Coverage Area</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $settingsql = "SELECT * FROM `settings` WHERE `id`='1'";
                            $settingresult = mysqli_query($conn, $settingsql);
                            $settingrow = mysqli_fetch_assoc($settingresult);
                            ?>
                            <tr>
                                <td class="text-start ps-4 fw-bold"><?php echo $settingrow['location']; ?></td>
                                <td style="color: var(--secondary);"><?php echo $settingrow['lat']; ?></td>
                                <td style="color: var(--secondary);"><?php echo $settingrow['lon']; ?></td>
                                <td><?php echo $settingrow['covarage']; ?> KM</td>
                                <td>
                                    <button type="button" onclick="updateSetting()" class="btn btn-square btn-neon-action btn-sm" title="Edit Settings">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function updateSetting() {
        window.location = `api_settings_update.php`;
    }
</script>

<?php
require('footer.php');
?>