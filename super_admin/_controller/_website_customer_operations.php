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
$table = "website_app_user";
if ($operation == "manageStatus") {
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
} elseif ($operation == "deleteCustomer") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, $table, $condition);
        echo "Msg : Customer removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
}