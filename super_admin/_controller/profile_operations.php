<?php
include '../../page_fragment/define.php'; 
include '../../page_fragment/topScript.php';

$operation = "";

if (isset($_POST['todo'])) {
    $operation = base64_decode($_POST['todo']);
    unset($_POST['todo']);
} elseif (isset($_GET['todo'])) {
    $operation = base64_decode($_GET['todo']);
    unset($_GET['todo']);
}

$table = "admin_user";

if ($operation == "changepass")
{
    $condition = " `admin_id`='" . $_POST['id'] . "'";
    $result = $dbComObj->viewData($conn,$table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $row = $dbComObj->fetch_assoc($result);
        if (md5($_POST['old_password']) != $row['admin_Encrypt'])
        {
            echo "Error : You entered an incorrect password";
        }
        else if ($_POST['newpassword'] == $_POST['confirmnewpassword'])
        {
            $data = array();
            $dates = date("Y-m-d-H-i-s");
            $data['admin_Encrypt'] = md5($_POST['confirmnewpassword']);
            $data['admin_Password'] = $_POST['confirmnewpassword'];
            $conditions = " `admin_id`='" . $_POST['id'] . "'";
            unset($data['id']);
            $dbComObj->editData($conn,$table, $data, $conditions);
            echo "Redirect : Password changed successfully. URL ".ADMIN_URL."dashboard/";
        }
    } else {
        echo "Error : Password doesn't Changed.";
    }
}

elseif ($operation == "editprofile")
{    
    $condition = " `admin_id`= '" . $_POST['id'] . "'";
    $result = $dbComObj->viewData($conn,$table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $data = array();
        $dates = date("Y-m-d-H-i-s");
        if (isset($_FILES['admin_Image']['name']) && !empty($_FILES['admin_Image']['name'])) {
            $image = $_FILES['admin_Image'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['admin_Name']);
            $filename = $name . "-" . $dates;
            $pathToSave = "../../admin-assets/images/user/";
            $thumbPathToSave = "../../admin-assets/images/user/thumb/";
            $main_logo = $srFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "../../admin-assets/images/user/" . $main_logo;
            $thumb_logo = $srFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $data['admin_Image'] = $main_logo;
        }
        $data['admin_Name'] = $_POST['admin_Name'];
        $data['admin_Email'] = $_POST['admin_Email'];
        $data['admin_Contact'] = $_POST['admin_Contact'];
        $data['admin_id'] = $_SESSION['FSGLOBAL_SESSION_IDS'];
        $conditions = " `admin_id`='" . $_POST['id'] . "'";
        unset($_POST['id']);
        $dbComObj->editData($conn,$table, $data, $conditions);
        echo "Reload : Profile updated successfully.";
    } else {
        echo "Error : Profile doesn't updated .";
    }
}
