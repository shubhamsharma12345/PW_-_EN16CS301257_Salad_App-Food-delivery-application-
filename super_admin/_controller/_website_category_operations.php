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

$table = "category";

if ($operation == "addCategory") {
    unset($_POST['id']);
    $condition = " `name`='" . htmlentities($_POST['cat_name']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {

        $_POSTAdd['name'] = htmlentities($_POST['cat_name']);
        $_POSTAdd['description'] = $_POST['cat_desc'];
        $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, $table, $_POSTAdd);
        echo "Reload : Category details added successfully.";
    } else {
        echo "Error : Category details already exist in system. Please try again with diffrent Category title.";
    }
} elseif ($operation == "editCategory") {
    $slider_id = ($_POST['id']);
    unset($_POST['id']);
    $condition = " `id`!=" . $slider_id . " and `name`='" . htmlentities($_POST['cat_name']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');

        $_POSTAdd['name'] = htmlentities($_POST['cat_name']);
        $_POSTAdd['description'] = $_POST['cat_desc'];
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd, "1 and `id`=" . $slider_id . "");
        // echo "Reload : Category details updated successfully.";
        echo "Redirect : Category details updated successfully. URL " . ADMIN_URL . "eMasters/manageCategory/";
    } else {
        echo "Error : Category already exist in system. Please try again with diffrent offer title.";
    }
} elseif ($operation == "manageStatus") {
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
} elseif ($operation == "deleteCategory") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, $table, $condition);
        echo "Msg : Category removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} else if ($operation == "addCategoryslots") {
    unset($_POST['id']);
    if ($_POST['start_time'] == $_POST['end_time']) {
        echo "Error : Start Time and End Time not same.";
    }

    $condition = " `category_id`='" . ($_POST['category_id']) . "' and  start_time >='" . $_POST['start_time'] . "' and  end_time <= '" . $_POST['end_time'] . "'";
    $result = $dbComObj->viewData($conn, "timing_slots", "*", $condition); //where start_time='start_time' or end_time='end' ;
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {

        $_POSTAdd['category_id'] = ($_POST['category_id']);
        $_POSTAdd['start_time'] = ($_POST['start_time']);
        $_POSTAdd['end_time'] = ($_POST['end_time']);
        $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, "timing_slots", $_POSTAdd);
        echo "Reload : Timing slots details added successfully.";
    } else {
        echo "Error :   Slot Time difference already available.";
    }
} else if ($operation == "deleteCategorySlots") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, "timing_slots", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {

        $dbComObj->deleteData($conn, "timing_slots", $condition);
        echo "Msg : addCategoryslots removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} elseif ($operation == "manageSlotsStatus") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, "timing_slots", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $_POSTAdd['status'] = $_POST['b'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, "timing_slots", $_POSTAdd, $condition);
        echo "Msg : status updated.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} elseif ($operation == "editCategoryslots") {
    $slot_id = ($_POST['id']);
    unset($_POST['id']);
    if ($_POST['start_time'] == $_POST['end_time']) {
        echo "Error : Start Time and End Time not same.";
    }
    $condition = " `id`!=" . $slot_id . " and `category_id`='" . ($_POST['category_id']) . "'and start_time >='" . $_POST['start_time'] . "' and  end_time <= '" . $_POST['end_time'] . "'";
    $result = $dbComObj->viewData($conn, "timing_slots", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');

        $_POSTAdd['category_id'] = ($_POST['category_id']);
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, "timing_slots", $_POSTAdd, "1 and `id`=" . $slot_id . "");
        // echo "Reload : Category details updated successfully.";
        echo "Redirect : Slot details updated successfully. URL " . ADMIN_URL . "eMasters/manageCategorySlots/";
    } else {
        echo "Error : Slot Time difference already available..";
    }
}  