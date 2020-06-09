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

$site_title = "Admin Lock Screen | My Local 99";
$vendorPageTitle = 'Admin Lock Screen | MyLocal99';
include './inc/config.php';
include './inc/template_start.php';

$_venID = '0';
$_guestName = 'Guest';
$imgUser = BASE_URL . 'img/profile_small.jpg';

if ($njGenObj->isNotLoggedIn())
{
    ?><script>window.location.href= "<?php echo BASE_URL.'admin_operations.php?mode='.base64_encode("logout");?>";</script><?php
}

if(!isset($_SESSION['ML9_session_id']))
{
    ?><script>window.location.href= "<?php echo BASE_URL.'admin_operations.php?mode='.base64_encode("logout");?>";</script><?php
}
else
{
    $_venID = $_SESSION['ML9_session_id'];
    $_venData = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "tblm_ml9_users", "id,email_id,name,image,title_image","1 and `id` = '".$_venID."'"));

    $_guestName = $_venData['name'];
    if ($_venData['image'] != '0')
    {
        $imgUser = BASE_URL . 'images/user/' . $_venData['image'];
    }
    else if ($_venData['title_image'] != '0')
    {
        $imgUser = BASE_URL . 'images/user-icon/' . $_venData['title_image'];
    }
}

?>
<link rel="shortcut icon" href="<?php echo BASE_URL;?>img/favicon.png">
<style type="text/css">
html, body {
    height: 0% !important;
}

</style>
<link href="<?php echo BASE_URL;?>css/lockscreen_css.css" rel="stylesheet" media="screen">
<body class="gray-bg" id="njBack" onload="startTime()">
    <img src="<?php echo BASE_URL;?>img/lock-screen.jpg" alt="Login Full Background" class="full-bg animation-pulseSlow">
    <div id="login-container" class="animation-fadeIn">
        <div class="lock-wrapper"  style="margin-top: 10px;">
        <div id="time"></div>
        <div id="lockResult"></div>
        <div class="lock-box text-center">
            <div class="lock-name"><?php echo ucfirst($_guestName);?></div>
            <img src="<?php echo $imgUser; ?>" alt="lock avatar"/>
            <div class="lock-pwd">
                <form role="form" id="lockscreen" class="form-inline" method="post" role="form" action="" onsubmit="return loginDetails();">
                    <div class="form-group">
                        <input type="password" required placeholder="Password" name="pwd" id="exampleInputPassword2" class="form-control lock-input">
                        <input type="hidden" name="mode" value="<?php echo base64_encode("lockScreen"); ?>" />
                        <input type="hidden" name="page" value="<?php echo ($_GET['session']); ?>" />
                        <input type="hidden" name="token" value="<?php echo base64_encode($_venID); ?>" />
                        <button onclick="formSubmit('lockscreen', 'lockResult', '<?php echo BASE_URL; ?>admin_operations.php')" style="margin:0 !important" class="btn btn-lock" type="button">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
<script src="//code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js" type="text/javascript"></script>    
<script src="<?php echo BASE_URL;?>js/nj_form.js"></script>
<script src="<?php echo BASE_URL; ?>js/jquery.validate.js" type="text/javascript"></script>
<script>
        function startTime()
        {
            var today=new Date();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            // add a zero in front of numbers<10
            m=checkTime(m);
            s=checkTime(s);
            document.getElementById('time').innerHTML=h+":"+m+":"+s;
            t=setTimeout(function(){startTime()},500);
        }
        function checkTime(i)
        {
            if (i<10)
            {
                i="0" + i;
            }
            return i;
        }
    </script>
</body>
</html>
<script>
    $(document).ready(function() {
        window.history.pushState(null, "", window.location.href);        
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    });
</script>
<script type="text/javascript">
function loginDetails(e)
{
    formSubmit('lockscreen', 'lockResult', '<?php echo BASE_URL; ?>admin_operations.php');
    return false;
}
</script>