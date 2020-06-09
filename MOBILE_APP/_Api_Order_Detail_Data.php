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

    if ($method == 'All_Orders') {
        if (!empty($data)) {
            $result_order_detail = $dbComObj->viewData($conn, "order_detail", "*", "1 and status='1'");

            if ($dbComObj->num_rows($result_order_detail) > 0) {
                while ($dataOrder_Detail = $dbComObj->fetch_assoc($result_order_detail)) {
                    $c['order_id'] = $dataOrder_Detail['id'];
                    $c['order_unique_id'] = $dataOrder_Detail['order_unique_id'];
                    $c['customer_name'] = $dataOrder_Detail['customer_name'];
                    $c['customer_address'] = $dataOrder_Detail['customer_address'];
                    $c['customer_email'] = $dataOrder_Detail['customer_email'];
                    $c['customer_phone'] = $dataOrder_Detail['customer_phone'];
                    //$c['subscription_id'] = $dataOrder_Detail['subscription_id'];
                    $c['total_price'] = $dataOrder_Detail['total_price'];

                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "No order available  ");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    } 
    
    else if ($method == 'Create_Order') {
        if (!empty($data)) {
            $customer_name = $data['customer_name'];
            $customer_address = $data['customer_address'];
            $customer_email = $data['customer_email'];
            $subscription_id = $data['subscription_id'];
            $customer_phone = $data['customer_phone'];
            $customer_id = $data['customer_id'];
            $total_price = $data['total_price'];


            $randNo = mt_rand(1000, 9999);
            $uniqueOrderId = 'SALADORD-' . $randNo;

            $_POSTAdd['order_unique_id'] = $uniqueOrderId;
            $_POSTAdd['customer_name'] = $customer_name;
            $_POSTAdd['customer_address'] = $customer_address;
            $_POSTAdd['customer_email'] = $customer_email;
            $_POSTAdd['customer_phone'] = $customer_phone;
            $_POSTAdd['total_price'] = $total_price;
            $_POSTAdd['subscription_available'] = isset($subscription_id) ? $subscription_id : 0;
            $_POSTAdd['added_by'] = $customer_id;
            $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
            $_POSTAdd['customer_id'] = $customer_id;
            $dbComObj->addData($conn, "order_detail", $_POSTAdd);
            $_orderId = $dbComObj->insert_id($conn);


            $size = sizeof($data['orderDetails']);

            for ($i=0; $i < $size; $i++) {
                $category_id = $data['orderDetails'][$i]['category_id'];
                $product_type_id = $data['orderDetails'][$i]['product_type_id'];
                $product_id = $data['orderDetails'][$i]['product_id'];
                $price = $data['orderDetails'][$i]['price'];
                $quantity = $data['orderDetails'][$i]['quantity']; 

                $_POSTAddItem['category_id'] = $category_id;
                $_POSTAddItem['order_id'] = $_orderId;
                $_POSTAddItem['product_type_id'] = $product_type_id;
                $_POSTAddItem['quantity'] = $quantity;
                $_POSTAddItem['price'] = $price;
                $_POSTAddItem['product_id'] = $product_id;
                $_POSTAddItem['added_by'] = $customer_id;
                $_POSTAddItem['added_on'] = date("Y-m-d H:i:s");
                $dbComObj->addData($conn, "order_item_detail", $_POSTAddItem);
            }
            $c['order_id'] = $uniqueOrderId;
            $thmsg[] = $c;
            $msg['message'] = 'Success';
            $msg['result'] = $thmsg;
            $msg['status'] = '200';
            echo json_encode($msg);
        }
    }
}