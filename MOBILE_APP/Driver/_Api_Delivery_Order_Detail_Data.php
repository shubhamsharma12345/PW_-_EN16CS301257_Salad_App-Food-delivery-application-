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
      api for ddelivery order detail
      send parameter alloted driver
     */
    if ($method == 'running_orders') {
        if (!empty($data)) {
            $allotedDriver = $data['driver_id'];

            $result_delivery_order_details = $dbComObj->viewData($conn, "order_detail", "*", "1 and allot_driver_id='$allotedDriver' and (status='0' or status='1' or status='2' or status= '4' or status= '5')");
            $itemData = array();
            if ($dbComObj->num_rows($result_delivery_order_details) > 0) {
                while ($dataDeliveryOrderDetails = $dbComObj->fetch_object($result_delivery_order_details)) {
                    $dataOrderItemDetails = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $dataDeliveryOrderDetails->id . "'");
                    if ($dbComObj->num_rows($dataOrderItemDetails) > 0) {
                        while ($rowItemData = $dbComObj->fetch_object($dataOrderItemDetails)) {
                            $dataProduct = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "*", "1 and id='" . $rowItemData->product_id . "'"));
                            $cc['item_name'] = $dataProduct->name;
                            $cc['quantity'] = $rowItemData->quantity;
                            $itemData[] = $cc;
                        }
                    }



                    if ($dataDeliveryOrderDetails->status == '1') {
                        $status = 'Created';
                    } else if ($dataDeliveryOrderDetails->status == '2') {
                        $status = 'Accepted';
                    } else if ($dataDeliveryOrderDetails->status == '4') {
                        $status = 'Ready';
                    } else if ($dataDeliveryOrderDetails->status == '5') {
                        $status = 'Dispatch';
                    }


                    $c['order_id'] = $dataDeliveryOrderDetails->id;
                    $c['order_unique_id'] = $dataDeliveryOrderDetails->order_unique_id;
                    $c['customer_name'] = $dataDeliveryOrderDetails->customer_name;
                    $c['customer_address'] = $dataDeliveryOrderDetails->customer_address;
                    $c['customer_phone'] = $dataDeliveryOrderDetails->customer_phone;
                    $c['total_price'] = $dataDeliveryOrderDetails->total_price;
                    $c['payment_type'] = $dataDeliveryOrderDetails->payment_type;
                    $c['order_status'] = $status;
                    $c['itemData'] = $itemData;


                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "No order available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    } 
    
    else if ($method == 'completed_orders') {
        if (!empty($data)) {
            $allotedDriver = $data['driver_id'];
            $result_delivery_order_details = $dbComObj->viewData($conn, "order_detail", "*", "1 and allot_driver_id='$allotedDriver' and (status='6' or status='3' )");
            $itemData = array();
            if ($dbComObj->num_rows($result_delivery_order_details) > 0) {
                while ($dataDeliveryOrderDetails = $dbComObj->fetch_object($result_delivery_order_details)) {
                    $dataOrderItemDetails = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $dataDeliveryOrderDetails->id . "'");
                    if ($dbComObj->num_rows($dataOrderItemDetails) > 0) {
                        while ($rowItemData = $dbComObj->fetch_object($dataOrderItemDetails)) {
                            $dataProduct = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "*", "1 and id='" . $rowItemData->product_id . "'"));
                            $cc['item_name'] = $dataProduct->name;
                            $cc['quantity'] = $rowItemData->quantity;
                            $itemData[] = $cc;
                        }
                    }



                    if ($dataDeliveryOrderDetails->status == '6') {
                        $status = 'Delivered';
                    } else if ($dataDeliveryOrderDetails->status == '3') {
                        $status = 'Completed';
                    }


                    $c['order_id'] = $dataDeliveryOrderDetails->id;
                    $c['order_unique_id'] = $dataDeliveryOrderDetails->order_unique_id;
                    $c['customer_name'] = $dataDeliveryOrderDetails->customer_name;
                    $c['customer_address'] = $dataDeliveryOrderDetails->customer_address;
                    $c['customer_phone'] = $dataDeliveryOrderDetails->customer_phone;
                    $c['total_price'] = $dataDeliveryOrderDetails->total_price;
                    $c['payment_type'] = $dataDeliveryOrderDetails->payment_type;
                    $c['order_status'] = $status;
                    $c['itemData'] = $itemData;


                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "No order available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
    
    
    else if ($method == 'order_picked') {
        if (!empty($data)) {
            $driver_id = $data['driver_id'];
            $order_id = $data['order_id'];   
            $result_delivery_order_details = $dbComObj->viewData($conn, "order_detail", "*", "1 and allot_driver_id='$driver_id' and id='$order_id' ");

            if ($dbComObj->num_rows($result_delivery_order_details) > 0) {


                $_POSTORDER['status'] = 5;
                $_POSTORDER['updated_by'] = $driver_id;
                $_POSTORDER['updated_on'] = date("Y-m-d H:i:s");
                $_POSTORDER['updated_user'] = 'Driver';
                $dbComObj->editData($conn, "order_detail", $_POSTORDER, "1 and id='" . $order_id . "'");

                $_getOrderDetail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $order_id . "' ");
                if ($dbComObj->num_rows($_getOrderDetail) > 0) {
                    while ($_orderdetailsItemData = $dbComObj->fetch_object($_getOrderDetail)) {

                        $_POSTITEM['status'] = 5;
                        $_POSTITEM['updated_by'] = $driver_id;
                        $_POSTITEM['updated_on'] = date("Y-m-d H:i:s");
                        $_POSTITEM['updated_user'] = 'Driver';
                        $dbComObj->editData($conn, "order_item_detail", $_POSTITEM, "1 and product_id='" . $_orderdetailsItemData->product_id . "' and order_id='" . $order_id . "'");

                        $_POSTORDERPRO['status'] = 'Dispatch';
                        $_POSTORDERPRO['order_id'] = $order_id;
                        $_POSTORDERPRO['order_item_id'] = $_orderdetailsItemData->product_id;
                        $_POSTORDERPRO['added_by'] = $driver_id;
                        $_POSTORDERPRO['added_on'] = date("Y-m-d H:i:s");
                        $_POSTORDERPRO['updated_user'] = 'Driver';
                        $dbComObj->addData($conn, "order_process_detail", $_POSTORDERPRO);
                    } 
                }
                $thmsg = array("msg" => "order picked successfully");
                $msg['message'] = 'Success';
                $msg['result'][] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "order not available for picked");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
    
    
    else if ($method == 'order_delivered') {
        if (!empty($data)) {
            $driver_id = $data['driver_id'];
            $order_id = $data['order_id'];   
            $result_delivery_order_details = $dbComObj->viewData($conn, "order_detail", "*", "1 and allot_driver_id='$driver_id' and id='$order_id' ");

            if ($dbComObj->num_rows($result_delivery_order_details) > 0) {


                $_POSTORDER['status'] = 6;
                $_POSTORDER['updated_by'] = $driver_id;
                $_POSTORDER['updated_on'] = date("Y-m-d H:i:s");
                $_POSTORDER['updated_user'] = 'Driver';
                $dbComObj->editData($conn, "order_detail", $_POSTORDER, "1 and id='" . $order_id . "'");

                $_getOrderDetail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $order_id . "' ");
                if ($dbComObj->num_rows($_getOrderDetail) > 0) {
                    while ($_orderdetailsItemData = $dbComObj->fetch_object($_getOrderDetail)) {

                        $_POSTITEM['status'] = 6;
                        $_POSTITEM['updated_by'] = $driver_id;
                        $_POSTITEM['updated_on'] = date("Y-m-d H:i:s");
                        $_POSTITEM['updated_user'] = 'Driver';
                        $dbComObj->editData($conn, "order_item_detail", $_POSTITEM, "1 and product_id='" . $_orderdetailsItemData->product_id . "' and order_id='" . $order_id . "'");

                        $_POSTORDERPRO['status'] = 'Delivered';
                        $_POSTORDERPRO['order_id'] = $order_id;
                        $_POSTORDERPRO['order_item_id'] = $_orderdetailsItemData->product_id;
                        $_POSTORDERPRO['added_by'] = $driver_id;
                        $_POSTORDERPRO['added_on'] = date("Y-m-d H:i:s");
                        $_POSTORDERPRO['updated_user'] = 'Driver';
                        $dbComObj->addData($conn, "order_process_detail", $_POSTORDERPRO);
                    } 
                }
                $thmsg = array("msg" => "order delivered successfully");
                $msg['message'] = 'Success';
                $msg['result'][] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "order not available for delivered");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
}

