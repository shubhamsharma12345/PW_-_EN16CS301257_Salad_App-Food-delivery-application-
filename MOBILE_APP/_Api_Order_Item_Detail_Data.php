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

    if ($method == 'Order_Details') {
        if (!empty($data)) {
            $result_order_item_detail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and status='1'");

            if ($dbComObj->num_rows($result_order_item_detail) > 0) {
                while ($dataOrder_Item_Detail = $dbComObj->fetch_assoc($result_order_item_detail)) {
                    $c['item_id'] = $dataOrder_Item_Detail['id'];
                    $c['item_order_id'] = $dataOrder_Item_Detail['order_id'];
                    $c['item_category_id']=$dataOrder_Item_Detail['category_id'];
                    $c['product_type_id'] = $dataOrder_Item_Detail['product_type_id'];
                    $c['item_product_id'] = $dataOrder_Item_Detail['product_id'];
                    $c['item_quantity'] = $dataOrder_Item_Detail['quantity'];
                    $c['item_price'] = $dataOrder_Item_Detail['price'];
                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "Orders_Detail not available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
    
}