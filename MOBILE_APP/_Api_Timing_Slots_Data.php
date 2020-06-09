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

    if ($method == 'Timing_Slots') {
        if (!empty($data)) {
            $categoryId = $data['category_id'];
            $result_timing_slots = $dbComObj->viewData($conn, "timing_slots", "*", "1 and status='1' and category_id='$categoryId'");

            if ($dbComObj->num_rows($result_timing_slots) > 0) {
                while ($dataTimingSlots = $dbComObj->fetch_assoc($result_timing_slots)) {
                    $dataCategory = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "category", "name", "1 and id='" . $dataTimingSlots['category_id'] . "'"));
                    $c['timing_slots_id'] = $dataTimingSlots['id'];
                    $c['category_name'] = $dataCategory['name'];
                    $c['category_id'] = $dataTimingSlots['category_id'];
                    $c['timing_start_time'] = $dataTimingSlots['start_time'];
                    $c['timing_end_time'] = $dataTimingSlots['end_time'];
                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "Timing Slots not available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
}