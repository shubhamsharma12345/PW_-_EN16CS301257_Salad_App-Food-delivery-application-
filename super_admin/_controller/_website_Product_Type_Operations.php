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

$table = "Product_Type";

if ($operation == "addProductType") {
    unset($_POST['id']);
    $condition = " `type`='" .  htmlentities($_POST['type']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {

        $_POSTAdd['type'] =  htmlentities($_POST['type']);
        $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, $table, $_POSTAdd);
        echo "Reload : Product type name details added successfully.";
    } else {
        echo "Error : Product type name details already exist in system. Please try again with diffrent Category title.";
    }
} elseif ($operation == "editProductType") {
    $type_id = ($_POST['id']);
    unset($_POST['id']);
    $condition = " `id`!=" . $type_id . " and `type`='" .  htmlentities($_POST['type']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');
        
        $_POSTAdd['type'] = htmlentities($_POST['type']);
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd, "1 and `id`=" . $type_id . "");
        echo "Redirect : Product type name details updated successfully. URL " . ADMIN_URL . "eMasters/manageProductType/";
    } else {
        echo "Error : Product type name already exist in system. Please try again with diffrent name";
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
} elseif ($operation == "deletePrdType") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, $table, $condition);
        echo "Msg : Product type name removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} 