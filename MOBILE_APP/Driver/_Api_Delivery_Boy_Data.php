<?php

include '../../page_fragment/define.php';
include ('../../page_fragment/dbConnect.php');
include ('../../page_fragment/dbGeneral.php');
include ('../../page_fragment/njGeneral.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$conn = $dbConObj->dbConnect();

date_default_timezone_set("Asia/Kolkata");
$data = array_merge($_POST, json_decode(file_get_contents('php://input'), true));
if (!empty($data)) {
    $method = $data['method'];
    /*
      api for driver login
      send parameter phone number password
     */
    if ($method == 'Delivery_Boy') {
        if (!empty($data)) {
            $phone_no = $data['phone_no'];
            $password = $data['password'];
            $result_delivery_boy = $dbComObj->viewData($conn, "delivery_boy", "*", "1 and phone_no='$phone_no' and password='$password'");

            if ($dbComObj->num_rows($result_delivery_boy) > 0) {
                $dataDeliveryBoy = $dbComObj->fetch_assoc($result_delivery_boy);
                if ($dataDeliveryBoy['status'] == 0) {

                    $thmsg = array("msg" => "login detail not approved please contact to admin");
                    $msg['message'] = 'Error';
                    $msg['result'][] = $thmsg;
                    $msg['status'] = '400';
                } else {
                    $c['driver_id'] = $dataDeliveryBoy['id'];
                    $c['driver_phone'] = $dataDeliveryBoy['phone_no'];
                    $c['driver_password'] = $dataDeliveryBoy['password'];
                    $thmsg[] = $c;
                    $msg['message'] = 'Success';
                    $msg['result'] = $thmsg;
                    $msg['status'] = '200';
                }
            } else {
                $thmsg = array("msg" => "InvalidPhone number or Password");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
}