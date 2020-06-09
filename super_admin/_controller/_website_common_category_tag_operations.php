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

if ($operation == "addCommonCategoryTag") {
    unset($_POST['id']);
    $condition = " `common_category_id`='" . ($_POST['common_category_id']) . "'";
    $result = $dbComObj->viewData($conn, "common_category_tag", "*", $condition); 
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {

        $_POSTAdd['common_category_id'] = ($_POST['common_category_id']);
        $_POSTAdd['description'] = $_POST['cat_desc'];
        $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, "common_category_tag", $_POSTAdd);
        //$dbComObj->editData($conn, "common_category_tag", $_POSTAdd, "1 and `id`=" . $type_id . "");
        echo "Reload :Common Category details added successfully.";
    } else {
        echo "Error :Common Category details already exist in system. Please try again with diffrent Common Category title.";
    }
}
 elseif ($operation == "editCommonCategoryTag") {
    $slider_id = ($_POST['id']);
    unset($_POST['id']);
    $condition = " `id`!=" . $slider_id . " and `name`='" . htmlentities($_POST['common_cat_name']) . "'";
    $result = $dbComObj->viewData($conn, "common_category_tag", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');

        $_POSTAdd['name'] = htmlentities($_POST['common_cat_name']);
        $_POSTAdd['description'] = $_POST['cat_desc'];
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, "common_category_tag", $_POSTAdd, "1 and `id`=" . $slider_id . "");
        // echo "Reload : Category details updated successfully.";
        echo "Redirect : Common Category details updated successfully. URL " . ADMIN_URL . "eMasters/manageCommonCategory/";
    } else {
        echo "Error : Common Category already exist in system. Please try again with diffrent offer title.";
    }
} elseif ($operation == "manageStatus") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, 'common_category_tag', "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $_POSTAdd['status'] = $_POST['b'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, 'common_category_tag', $_POSTAdd, $condition);
        echo "Msg : status updated.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
}

elseif ($operation == "deleteCommonCategoryTag") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, 'common_category_tag', "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, 'common_category_tag', $condition);
        echo "Msg : Common Category removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
}