<?php 
require_once './page_fragment/define.php';
include('./page_fragment/dbConnect.php');
include('./page_fragment/dbGeneral.php');
include('./page_fragment/njGeneral.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$conn = $dbConObj->dbConnect();
$njGenObj = new njGeneral();

if ($njGenObj->isLoggedIn()) 
{
    if($_SESSION['SALAD_SESSION_TYPE'] == 'SALAD-SA')
    {$folderA = ADMIN_URL;}    
    header("Location:".$folderA."dashboard/");
}
$site_title = "Salad Admin Console Login | Salad Admin App";
?>
<?php require_once('./admin-assets/inc/config.php'); ?>
<?php require_once('./admin-assets/inc/template_start.php'); ?>
<img src="<?=BASE_URL;?>admin-assets/img/placeholders/backgrounds/background.jpg" alt="Login Full Background" class="full-bg animation-pulseSlow">
<div id="login-container" class="animation-fadeIn">
    <div class="login-title text-center">
        <h1 style="margin-top: 0px;"><small>Welcome <strong>SALAD APP ADMIN</strong> - <strong>Console Login</strong></small></h1>
    </div>
    <div class="block push-bit">
        <div id="errorMessageLog"></div>
        <form method="post" id="form-login" class="form-horizontal form-bordered form-control-borderless" onsubmit="return loginDetails('<?php echo base64_encode('UserLogin') ?>');">
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                        <input type="text" id="login-email" name="login-email" class="form-control input-lg" placeholder="Enter email address" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                        <input type="password" id="login-password" name="login-password" class="form-control input-lg" placeholder="Enter Password" required>
                    </div>
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-xs-12 text-center">
                <input type="hidden" name="mode" value="<?php echo base64_encode("administrator-login"); ?>" />
                <button type="submit" onclick="formSubmit('form-login', 'errorMessageLog', '<?php echo BASE_URL; ?>admin_operations.php')" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Login to Dashboard</button>
                </div>
            </div>
        </form>
        <!-- END Login Form -->        
    </div>
    <!-- END Login Block -->
    <!-- Footer -->
    <footer class="text-muted text-center">
        <small><span id=""><?php echo date('Y');?></span> &copy; <a href="#" target="_blank">Term & Conditions - SALAD ADMIN.</a></small>
    </footer>
   <!-- END Footer -->
</div>
<!-- END Login Container -->
<?php require_once('./admin-assets/inc/template_scripts.php'); ?>
<!-- Load and execute javascript code used only in this page -->
<!--script src="<?php echo BASE_URL;?>js/pages/login.js"></script -->
<?php require_once('./admin-assets/inc/template_end.php'); ?>
<script src="<?php echo CONTENT_BASE_URL;?>js/pages/login.js"></script>
<script>
$(function(){ Login.init(); });
function loginDetails(e)
	{
		formSubmit('form-login', 'errorMessageLog', '<?php echo BASE_URL; ?>admin_operations.php');
		return false;
	}
</script>