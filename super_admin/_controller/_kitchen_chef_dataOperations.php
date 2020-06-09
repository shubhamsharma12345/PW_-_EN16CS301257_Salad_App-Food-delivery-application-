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

$table = "kitchen_chef_data";

if ($operation == "addKitchenChef") {
    unset($_POST['id']);
    $condition = " `chef_name`='" . htmlentities($_POST['chef_name']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');
         if (isset($_FILES['image1']['name']) && !empty($_FILES['image1']['name'])) {
            $image = $_FILES['image1'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['chef_name']));
            $filename = $name . "-1-" . $dates;
            $pathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/";
            $thumbPathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/thumb/";
            $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/" . $main_logo;
            $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $_POSTAdd['image_1'] = $main_logo;
        }
        if (isset($_FILES['image2']['name']) && !empty($_FILES['image2']['name'])) {
            $image2 = $_FILES['image2'];
            $name2 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['chef_name']));
            $filename2 = $name2 . "-2-" . $dates;
            $pathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/";
            $thumbPathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/thumb/";
            $main_logo2 = $njFileObj->uploadImage($image2, $filename2, $pathToSave2);
            $image_source2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/" . $main_logo2;
            $thumb_logo2 = $njFileObj->resizeImage($image_source2, $filename2, $thumbPathToSave2);
            $_POSTAdd['image_2'] = $main_logo2;
        }
        if (isset($_FILES['image3']['name']) && !empty($_FILES['image3']['name'])) {
            $image3 = $_FILES['image3'];
            $name3 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['chef_name']));
            $filename3 = $name3 . "-3-" . $dates;
            $pathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/";
            $thumbPathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/thumb/";
            $main_logo3 = $njFileObj->uploadImage($image3, $filename3, $pathToSave3);
            $image_source3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/" . $main_logo3;
            $thumb_logo3 = $njFileObj->resizeImage($image_source3, $filename3, $thumbPathToSave3);
            $_POSTAdd['image_3'] = $main_logo3;
        }
        $_POSTAdd['chef_name'] = htmlentities($_POST['chef_name']);
        $_POSTAdd['chef_description'] = htmlentities($_POST['chef_description']);
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, $table, $_POSTAdd);
        echo "Reload : Kitchen Chef details added successfully.";
    } else {
        echo "Error : Kitchen Chef details already exist in system. Please try again with diffrent Kitchen Chef name.";
    }
} 

 elseif ($operation == "editKitchenChef") {
    $slider_id = ($_POST['id']);
    unset($_POST['id']);
    $condition = " `id`!=" . $slider_id . " and `chef_name`='" . htmlentities($_POST['chef_name']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');

        if (isset($_FILES['image1']['name']) && !empty($_FILES['image1']['name'])) {
            $image = $_FILES['image1'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['chef_name']));
            $filename = $name . "-1-" . $dates;
            $pathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/";
            $thumbPathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/thumb/";
            $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/" . $main_logo;
            $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $_POSTAdd['image_1'] = $main_logo;
        }
        if (isset($_FILES['image2']['name']) && !empty($_FILES['image2']['name'])) {
            $image2 = $_FILES['image2'];
            $name2 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['chef_name']));
            $filename2 = $name2 . "-2-" . $dates;
            $pathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/";
            $thumbPathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/thumb/";
            $main_logo2 = $njFileObj->uploadImage($image2, $filename2, $pathToSave2);
            $image_source2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/" . $main_logo2;
            $thumb_logo2 = $njFileObj->resizeImage($image_source2, $filename2, $thumbPathToSave2);
            $_POSTAdd['image_2'] = $main_logo2;
        }
        if (isset($_FILES['image3']['name']) && !empty($_FILES['image3']['name'])) {
            $image3 = $_FILES['image3'];
            $name3 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['chef_name']));
            $filename3 = $name3 . "-3-" . $dates;
            $pathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/";
            $thumbPathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/thumb/";
            $main_logo3 = $njFileObj->uploadImage($image3, $filename3, $pathToSave3);
            $image_source3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Chef/" . $main_logo3;
            $thumb_logo3 = $njFileObj->resizeImage($image_source3, $filename3, $thumbPathToSave3);
            $_POSTAdd['image_3'] = $main_logo;
        }
        $_POSTAdd['chef_name'] = htmlentities($_POST['chef_name']);
        $_POSTAdd['chef_description'] = htmlentities($_POST['chef_description']);
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd, "1 and `id`=" . $slider_id . "");
        echo "Redirect : Kitchen Chef  details updated successfully. URL " . ADMIN_URL . "eMasters/manageMasterChef/";
    } else {
        echo "Error : Kitchen Chef name already exist in system. Please try again with diffrent offer title.";
    }
} 

elseif ($operation == "manageStatus") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $_POSTAdd['status'] = $_POST['b'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd, $condition);
        echo "Msg : status updated.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} elseif ($operation == "deleteChef") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, $table, $condition);
        echo "Msg : Chef removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
}