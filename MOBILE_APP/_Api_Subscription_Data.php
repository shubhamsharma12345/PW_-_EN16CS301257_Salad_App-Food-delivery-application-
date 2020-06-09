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

    if ($method == 'Subscription_Details') {
        if (!empty($data)) {
            $result_subscription_detail = $dbComObj->viewData($conn, "product_subscription_price", "*", "1 and status='1' and product_id ='".$data['product_id']."'");
            //$result_product = $dbComObj->viewData($conn, "product", "*", "1 and status='1'");

             if ($dbComObj->num_rows($result_subscription_detail) > 0) {
                while ($dataSubscription = $dbComObj->fetch_assoc($result_subscription_detail)) {
                    
                    $dataMasterSubscriptionDays = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "master_subscription_days", "days", "1 and id='".$dataSubscription['subscription_id']."'"));

                    $c['subscription_id'] =  $dataSubscription['subscription_id'];
                    $c['subscription_days'] = $dataMasterSubscriptionDays['days'].' Days';
                    $c['subscription_price'] = $dataSubscription['product_subs_price'];
                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "Subscription Detail not available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
    
}