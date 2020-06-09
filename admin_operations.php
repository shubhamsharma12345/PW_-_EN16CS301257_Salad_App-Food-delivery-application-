<?php
require_once './page_fragment/define.php';
include('./page_fragment/dbConnect.php');
include('./page_fragment/dbGeneral.php');
include('./page_fragment/njGeneral.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$conn     = $dbConObj->dbConnect();
$mode     = "";

if (isset($_POST['mode'])) {
    $mode = base64_decode($_POST['mode']);
    unset($_POST['mode']);
} elseif (isset($_GET['mode'])) {
    $mode = base64_decode($_GET['mode']);
    unset($_GET['mode']);
}

if($mode == 'administrator-login')
{
    $username  = $_POST['login-email'];
    $password  = md5($_POST['login-password']);
    $conditionA = " `admin_Email` = '" . $username . "'";
    
    $njTImage = '';
    $resultA = $dbComObj->viewData($conn,"admin_user", "*", $conditionA);
    $numA = $dbComObj->num_rows($resultA);
    if ($numA > 0)
    {
        $row = $dbComObj->fetch_object($resultA);
        $pwd = $row->admin_Encrypt;
        if ($password == $pwd)
        {
            if($row->isActive > 0)
            {
                $_getRole = $dbComObj->fetch_object($dbComObj->viewData($conn,"admin_roles","role_Slug","1 and `role_id` = '".$row->role_id."'"));
                
                $_SESSION['SALAD_SESSION_IDS'] = $row->admin_id;
                $_SESSION['SALAD_SESSION_NAME'] = $row->admin_Name;
                $_SESSION['SALAD_SESSION_TYPE'] = $_getRole->role_Slug;
                    
                if($_getRole->role_Slug == 'SALAD-SA')
                {
                    echo "Redirect : Logged in successfully. URL " . ADMIN_URL ."dashboard/";
                } 
                else
                {
                	session_destroy();
                	unset($_SESSION);
                    echo "Error : You are not a authorised person. Please contact your administrator.";
                }
            }
            else
            {
                echo "Error : You are not a authorised person. Please contact your administrator.";
            }            
        } 
        else
        {
            echo "Error : Password is incorrect.";
        }
    }
    else
    {
        echo "Error : User not registered.";
        die;
    }
}

elseif ($mode == "lockScreen")
{
    $password = md5($_POST['pwd']);    
    if(isset($_POST['token']))
    {
        $_venID = base64_decode($_POST['token']);
        $_getUser = $dbComObj->fetch_object($dbComObj->viewData($conn, "admin_user", "*","1 and `id` = '".$_venID."'"));
        $_getRole = $dbComObj->fetch_object($dbComObj->viewData($conn,"admin_roles","role_Slug","1 and `role_id` = '".$_getUser->role_id."'"));

        $njT = 'Administrator';
        $_SESSION['SALAD_SESSION_IDS'] = $_getUser->admin_id;
        $_SESSION['SALAD_SESSION_NAME'] = $_getUser->admin_Name;
        $_SESSION['SALAD_SESSION_TYPE'] = $_getRole->role_Slug;

        $page = base64_decode($_POST['page']);
        $pwd = $_getUser->admin_Encrypt;
        if ($password == $pwd)
        {
            echo "Redirect : Logged in successfully. URL ".$page;
        }
        else
        {
            echo "Error : Password is incorrect.";
        }
    }    
}

elseif ($mode == "logout")
{
    unset($_SESSION['SALAD_SESSION_IDS']);
    unset($_SESSION['SALAD_SESSION_NAME']);
    unset($_SESSION['SALAD_SESSION_TYPE']);
    session_destroy();
    header('Location:' . BASE_URL);
}
?>
