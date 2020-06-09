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

$table = "master_setting";

if ($operation == "addUpdateMasterSetting") {
    //echo  date('Y-m-d', strtotime($_POST['store_off_date'] . ' +1 day'));die;
    $condition = " 1 ";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {

        $_POSTAdd['store_status'] = ($_POST['store_status']);
        $_POSTAdd['store_off_date'] = $_POST['store_off_date'];
        $_POSTAdd['store_start_date'] = date('Y-m-d', strtotime($_POST['store_off_date'] . ' +1 day'));
        $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, $table, $_POSTAdd);
        
        $_POSTAddD['store_status'] = ($_POST['store_status']);
        $_POSTAddD['store_off_date'] = $_POST['store_off_date'];
        $_POSTAddD['store_start_date'] = date('Y-m-d', strtotime($_POST['store_off_date'] . ' +1 day'));
        $_POSTAddD['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAddD['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, "master_setting_detail", $_POSTAddD); 
        echo "Reload : Category details added successfully.";
    } else {
        
        $_POSTAdd['store_status'] = ($_POST['store_status']);
        $_POSTAdd['store_off_date'] = $_POST['store_off_date'];
        $_POSTAdd['store_start_date'] = date('Y-m-d', strtotime($_POST['store_off_date'] . ' +1 day'));
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd ,"1");
        
        $_POSTAddD['store_status'] = ($_POST['store_status']);
        $_POSTAddD['store_off_date'] = $_POST['store_off_date'];
        $_POSTAddD['store_start_date'] = date('Y-m-d', strtotime($_POST['store_off_date'] . ' +1 day'));
        $_POSTAddD['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAddD['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, "master_setting_detail", $_POSTAddD);
        echo "Reload : Category details added successfully.";
    }
}