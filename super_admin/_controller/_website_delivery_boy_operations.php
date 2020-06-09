<?php

include('../../page_fragment/define.php');
include ('../../page_fragment/dbConnect.php');
include ('../../page_fragment/dbGeneral.php');
include ('../../page_fragment/njGeneral.php');
include ('../../page_fragment/njFile.php');
include ('../../page_fragment/njImportAPI.php');

$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$njFileObj = new njFile();
$conn = $dbConObj->dbConnect();

$operation = "";
if (isset($_POST['todo'])) {
    $operation = base64_decode($_POST['todo']);
    unset($_POST['todo']);
} elseif (isset($_GET['todo'])) {
    $operation = base64_decode($_GET['todo']);
    unset($_GET['todo']);
}

if ($operation == "adddeliveryboy") {
    unset($_POST['id']);
    
    if($_POST['password'] != $_POST['conf_pass']){
        echo "Error : Delivery Boy Password and Confirm password are not match please try again. ";       
        die;
    }
    
    
    $condition = " `phone_no`='" . ($_POST['deli_phone']) . "'";
    $result = $dbComObj->viewData($conn, "delivery_boy", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
         $dates = date('Y-m-d-H-i-s');
         if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['deli_name']));
            $filename = $name . "" . $dates;
            $pathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/";
            $thumbPathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/thumb/";
            $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/" . $main_logo;
            $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $_POSTAdd['image'] = $main_logo;
        }
        if (isset($_FILES['id_proof_image']['name']) && !empty($_FILES['id_proof_image']['name'])) {
            $imageIDPR = $_FILES['id_proof_image'];
            $nameIDPR = preg_replace('/[^a-zA-Z0-9_]/', '-', uniqid());
            $filenameIDPR = $nameIDPR . "" . $dates;
            $pathToSaveIDPR = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/";
            $thumbPathToSaveIDPR = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/thumb/";
            $main_logoIDPR = $njFileObj->uploadImage($imageIDPR, $filenameIDPR, $pathToSaveIDPR);
            $image_sourceIDPR = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/" . $main_logoIDPR;
            $thumb_logoIDPR = $njFileObj->resizeImage($image_sourceIDPR, $filenameIDPR, $thumbPathToSaveIDPR);
            $_POSTAdd['id_proof_image'] = $main_logoIDPR;
        }
        //print_r($_POSTAdd);die;
        $_POSTAdd['name'] = ($_POST['deli_name']);
        $_POSTAdd['phone_no'] = $_POST['deli_phone'];
        $_POSTAdd['password'] = $_POST['password'];
        $_POSTAdd['encrypt_password'] = md5($_POST['password']);
        $_POSTAdd['address'] = $_POST['deli_address'];
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, "delivery_boy", $_POSTAdd);

        echo "Reload : Delivery Boy details added successfully.";
    } else {
        echo "Error : Delivery Boy details already exist in system. Please try again with diffrent Delivery Boy title.";
    }
} 

elseif ($operation == "editDeliveryboy") {
    $driver_id = ($_POST['id']);
    unset($_POST['id']);
    
    $condition = " `id`!=" . $driver_id . " and `phone_no`='" .  ($_POST['deli_phone']) . "'";
    $result = $dbComObj->viewData($conn, "delivery_boy", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');
        if(isset($_POST['password']) && $_POST['password']){
            if($_POST['password'] != $_POST['conf_pass']){
                echo "Error : Delivery Boy Password and Confirm password are not match please try again.";       
                die;
            }
            else{                
                $_POSTAdd['password'] = $_POST['password'];
                $_POSTAdd['encrypt_password'] = md5($_POST['password']);
            }
        }
        $dates = date('Y-m-d-H-i-s');
        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['deli_name']));
            $filename = $name . "" . $dates;
            $pathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/";
            $thumbPathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/thumb/";
            $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/" . $main_logo;
            $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $_POSTAdd['image'] = $main_logo;
        }
        if (isset($_FILES['id_proof_image']['name']) && !empty($_FILES['id_proof_image']['name'])) {
            $imageIDPR = $_FILES['id_proof_image'];
            $nameIDPR = preg_replace('/[^a-zA-Z0-9_]/', '-', uniqid());
            $filenameIDPR = $nameIDPR . "" . $dates;
            $pathToSaveIDPR = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/";
            $thumbPathToSaveIDPR = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/thumb/";
            $main_logoIDPR = $njFileObj->uploadImage($imageIDPR, $filenameIDPR, $pathToSaveIDPR);
            $image_sourceIDPR = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/DeliveryBoy/" . $main_logoIDPR;
            $thumb_logoIDPR = $njFileObj->resizeImage($image_sourceIDPR, $filenameIDPR, $thumbPathToSaveIDPR);
            $_POSTAdd['id_proof_image'] = $main_logoIDPR;
        }
        $_POSTAdd['name'] = ($_POST['deli_name']);
        $_POSTAdd['phone_no'] = $_POST['deli_phone'];
        $_POSTAdd['address'] = $_POST['deli_address'];
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, "delivery_boy", $_POSTAdd, "1 and `id`=" . $driver_id . "");
        echo "Redirect : Delivery Boy details updated successfully. URL " . ADMIN_URL . "eMasters/manageDeliveryBoy/";
    } else {
        echo "Error : Delivery Boy already exist in system. Please try again with diffrent phone number.";
    }
}


elseif ($operation == "deleteDeliveryBoy") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, "delivery_boy", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, "delivery_boy", $condition);
        echo "Msg : Delivery Boy removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} 

elseif ($operation == "manageStatus") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, "delivery_boy", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $_POSTAdd['status'] = $_POST['b'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, "delivery_boy", $_POSTAdd, $condition);
        echo "Msg : status updated.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
}


 
