<?php

include '../page_fragment/define.php';
include ('../page_fragment/dbConnect.php');
include ('../page_fragment/dbGeneral.php');
include ('../page_fragment/njGeneral.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$conn = $dbConObj->dbConnect();

date_default_timezone_set("Asia/Kolkata");
$data = array_merge($_POST, json_decode(file_get_contents('php://input'), true));
if (!empty($data)) {
    $method = $data['method'];
    if ($method == 'restaut_status') {
        $currentDate = date('m/d/y');
        $qry = $dbComObj->viewData($conn, "master_setting", "*", "1 and store_status = '0'");
        $num = $dbComObj->num_rows($qry);
        if ($num > 0) {
            $rowRestaurant = $dbComObj->fetch_object($qry);
            $thmsg = array("msg" => "Restaurant Not Open", "Open Date" => date('d/m/Y', strtotime($rowRestaurant->store_start_date)));
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        } else {
            $thmsg = array("msg" => "Restaurant Open");
            $msg['message'] = 'Success';
            $msg['result'][] = $thmsg;
            $msg['status'] = '200';
        }
        echo json_encode($msg);
    }
}