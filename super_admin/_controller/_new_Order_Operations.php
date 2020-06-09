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

$table = "product";


if ($operation == 'searchMenuItem') {
    $_itesmArray = array();
    $_cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] :  1;
    $_ptype = isset($_REQUEST['ptype']) ? $_REQUEST['ptype'] :  1;
    $_chkIts = $dbComObj->viewData($conn, "product", "*", "1 and status='1' and category_id='$_cat' and product_type_id='$_ptype' ");
    if ($dbComObj->num_rows($_chkIts) > 0) {
        while ($_gtIts = $dbComObj->fetch_object($_chkIts)) {
            $_is['name'] = ($_gtIts->name);
            $_is['id'] = ($_gtIts->id);
            $_is['item_full_price'] = ($_gtIts->price);

            $_itesmArray[] = $_is;
        }
    }

    $example = $_itesmArray;
    $searchword = $_GET['request']['term'];
    $matches = array();
    foreach ($example as $k => $v) {
        if (false !== stripos($v['name'], $searchword)) {
            $matches[$k] = $v;
        }
    }

    if (count($matches) > 0) {
        foreach ($matches as $val) {
            $json[] = array(
                'id' => $val['id'],
                'value' => $val['name'],
                'menu_id' => '0',
                'item_full_price' => $val['item_full_price'],
                'name' => $val['name']
            );
        }
    } else {
        $json[] = array('id' => '0', 'value' => 'Item not found.');
    }
    echo json_encode($json);
}

else if ($operation == 'searchMenuItemSubs') {
    $_itesmArray = array();
    $_cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] :  1;
    $_ptype = isset($_REQUEST['ptype']) ? $_REQUEST['ptype'] :  1;
    $_chkIts = $dbComObj->viewData($conn, "product", "*", "1 and is_subscription='1' and status='1' and category_id='$_cat' and product_type_id='$_ptype'");
    if ($dbComObj->num_rows($_chkIts) > 0) {
        while ($_gtIts = $dbComObj->fetch_object($_chkIts)) {
            $_is['name'] = ($_gtIts->name);
            $_is['id'] = ($_gtIts->id);
            $_is['item_full_price'] = ($_gtIts->price);

            $_itesmArray[] = $_is;
        }
    }

    $example = $_itesmArray;
    $searchword = $_GET['request']['term'];
    $matches = array();
    foreach ($example as $k => $v) {
        if (false !== stripos($v['name'], $searchword)) {
            $matches[$k] = $v;
        }
    }

    if (count($matches) > 0) {
        foreach ($matches as $val) {
            $json[] = array(
                'id' => $val['id'],
                'value' => $val['name'],
                'menu_id' => '0',
                'item_full_price' => $val['item_full_price'],
                'name' => $val['name']
            );
        }
    } else {
        $json[] = array('id' => '0', 'value' => 'Item not found.');
    }
    echo json_encode($json);
}


else if ($operation == 'createOrder') {

    $customer_id = 0;
    $randNo = mt_rand(1000, 9999);
    $uniqueOrderId = 'SALADORD-' . $randNo;
    $subscription_id = 0;
    if ($_POST['normal_days'] == 1) {
        $itemTotal = $_POST['itemTotal'];
        $allItemsArray = $_POST['allItemsArray'];
        $itemCountt = explode(',', $allItemsArray);
        $subscription_id = 0;
        $_itemTotal = $_POST['itemTotal'];
    }

    if ($_POST['subscription_days'] == 1) {
        $itemTotal = $_POST['itemTotalS'];
        $allItemsArray = $_POST['allItemsArrayS'];
        $itemCountt = explode(',', $allItemsArray);
        $subscription_id = 1;
        $_itemTotal = $_POST['itemTotalS'];
    }
        
    $_POSTAdd['order_unique_id'] = $uniqueOrderId;
    $_POSTAdd['customer_name'] = $_POST['customer_name'];
    $_POSTAdd['customer_address'] = $_POST['customer_address'];
    $_POSTAdd['customer_email'] = $_POST['customer_email'];
    $_POSTAdd['customer_phone'] = $_POST['customer_phone'];
    $_POSTAdd['status'] = $_POST['order_status'];
    $_POSTAdd['total_price'] = $_itemTotal;
    $_POSTAdd['subscription_available'] = $subscription_id;
    $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
    $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
    $dbComObj->addData($conn, "order_detail", $_POSTAdd);
    $_orderId = $dbComObj->insert_id($conn);
    if ($_POST['normal_days'] == 1) { 
        for ($i = 0; $i < count($itemCountt); $i++) {
            $keyC = 'product_code_' . $itemCountt[$i];
            $keyI = 'menu_id_' . $itemCountt[$i];
            $keyMK = 'multi_key_' . $itemCountt[$i];
            $keyN = 'product_name_' . $itemCountt[$i];
            $keyQ = 'product_qty_' . $itemCountt[$i];
            $keyP = 'product_price_' . $itemCountt[$i];
            if ($_POST[$keyI] == '0') {
                $or_de['product_id'] = $_POST[$keyC];
            } else {
                $or_de['product_id'] = $_POST[$keyI];
            }
            $or_de['order_id'] = $_orderId;
            $or_de['category_id'] = $_POST['category_id'];
            $or_de['product_type_id'] = $_POST['product_type_id'];
            $or_de['quantity'] = $_POST[$keyQ];
            $or_de['price'] = $_POST[$keyP] * $_POST[$keyQ];
            $or_de['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
            $or_de['added_on'] = date("Y-m-d H:i:s");

            $subtotal += $or_de['quantity'] * $or_de['price'];
            $dbComObj->addData($conn, 'order_item_detail', $or_de);
        }
    }
    
    if ($_POST['subscription_days'] == 1) {
        for ($i = 0; $i < count($itemCountt); $i++) {
            $keyC = 'product_code_s_' . $itemCountt[$i];
            $keyI = 'menu_id_s_' . $itemCountt[$i];
            $keyMK = 'multi_key_s_' . $itemCountt[$i];
            $keyN = 'product_name_s_' . $itemCountt[$i];
            $keyQ = 'product_qty_s_' . $itemCountt[$i];
            $keyP = 'product_price_s_' . $itemCountt[$i];
            $keySub = 'product_subs_id_'. $itemCountt[$i];

            if ($_POST[$keyI] == '0') {
                $or_de['product_id'] = $_POST[$keyC];
            } else {
                $or_de['product_id'] = $_POST[$keyI];
            }
            $or_de['order_id'] = $_orderId;
            $or_de['category_id'] = $_POST['category_id'];
            $or_de['product_type_id'] = $_POST['product_type_id'];
            $or_de['subscription_id'] = $_POST[$keySub];
            $or_de['quantity'] = $_POST[$keyQ];
            $or_de['price'] = $_POST[$keyP] * $_POST[$keyQ];
            $or_de['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
            $or_de['added_on'] = date("Y-m-d H:i:s");

            $subtotal += $or_de['quantity'] * $or_de['price'];
            $dbComObj->addData($conn, 'order_item_detail', $or_de);
        }
    }
    
    $aUpdate['total_price'] = $subtotal;
    $_ordInsert = $dbComObj->editData($conn, "order_detail", $aUpdate,"1 and id='$_orderId'");
    //echo "Reload : Order Created Successfully.";
    echo "Redirect : Order Created successfully. URL " . ADMIN_URL . "eOrder/addNewOrder/";
}

else if($operation == 'getSubSDays'){
    $_prodcuId = $_POST['productId'];
    $_getProdcuSub =  $dbComObj->viewData($conn, "product_subscription_price", "*", "1 and status='1' and product_id='$_prodcuId' ");
    $htmlData='<select name="product_subs_id_'.$_prodcuId.'" id="product_subs_id_'.$_prodcuId.'" required class="form-control input-sm" onchange="return getSusbDaysAmt(this.value);">';
    if ($dbComObj->num_rows($_getProdcuSub) > 0) {
        $htmlData .='<option value="">Select Subscription Days</option>';
        while ($_rowPrds = $dbComObj->fetch_object($_getProdcuSub)) {
            $_rowDataDays = $dbComObj->fetch_object($dbComObj->viewData($conn, "master_subscription_days", "*", "1 and status='1' and id='".$_rowPrds->subscription_id."' "));
            $htmlData .='<option value="'.$_rowPrds->id.'">'.$_rowDataDays->days.' Days</option>';
        }
    }else{
        $htmlData .='<option value="">No Subscription Days</option>';
    }
    $htmlData .='</select>';
    echo $htmlData; 
}

else if ($operation == 'getSusbDaysAmt') {

    $_subsId = $_POST['productSubsId'];
    $_getProdcuSub = $dbComObj->viewData($conn, "product_subscription_price", "*", "1 and id='$_subsId' ");
    if ($dbComObj->num_rows($_getProdcuSub) > 0) {
        $_rowPrds = $dbComObj->fetch_object($_getProdcuSub);
        $_product_subs_price = $_rowPrds->product_subs_price;
        echo $_product_subs_price.'||'.$_rowPrds->product_id;
    }
}


else if ($operation == 'updatedOrder') {
    $_getOrderDetail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and product_id='" . $_POST['order_item_id'] . "' and order_id='" . $_POST['order_id'] . "'");
    if ($dbComObj->num_rows($_getOrderDetail) > 0) {
        if($_POST['order_status'] == 2){
            $_order_status = 'Accepted';
        }
        else if($_POST['order_status'] == 3){
            $_order_status = 'Completed';
        }
        else if($_POST['order_status'] == 4){
            $_order_status = 'Ready';
        }
        else if($_POST['order_status'] == 5){
            $_order_status = 'Dispatch';
        }
        else if($_POST['order_status'] == 6){
            $_order_status = 'Delivered';
        }
        else if($_POST['order_status'] == 8){
            $_order_status = 'Cancel By Admin';
        }
        
        
        
        $_POSTITEM['status'] = $_POST['order_status'];
        $_POSTITEM['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTITEM['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn,"order_item_detail", $_POSTITEM, "1 and product_id='" . $_POST['order_item_id'] . "' and order_id='" . $_POST['order_id'] . "'");
        
        $_POSTORDER['status'] = $_POST['order_status'];
        $_POSTORDER['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTORDER['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn,"order_detail", $_POSTORDER, "1 and id='" . $_POST['order_id'] . "'");
        
        
        
        $_POSTORDERPRO['status'] = $_order_status;
        $_POSTORDERPRO['order_id'] = $_POST['order_id'];
        $_POSTORDERPRO['order_item_id'] = $_POST['order_item_id'];
        $_POSTORDERPRO['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTORDERPRO['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn,"order_process_detail", $_POSTORDERPRO);
        
        //echo "Redirect : Order Updated Successfully. URL " . ADMIN_URL . "eOrder/manageOrderDetails/";
        echo "Reload : Order Updated Successfully";
    } else {

        echo "Error : Order Not Updated";
    }
}

else if ($operation == 'updateAllOrder') {
   $_orderID = $_POST['all_order_id'];
   $_driverId = $_POST['driver_id'];
   $_all_order_status = $_POST['all_order_status'];

   if ($_POST['all_order_status'] == 2) {
       $_order_status = 'Accepted';
   } else if ($_POST['all_order_status'] == 3) {
       $_order_status = 'Completed';
   } else if ($_POST['all_order_status'] == 4) {
       $_order_status = 'Ready';
   } else if ($_POST['all_order_status'] == 5) {
       $_order_status = 'Dispatch';
   } else if ($_POST['all_order_status'] == 6) {
       $_order_status = 'Delivered';
   } else if ($_POST['all_order_status'] == 8) {
       $_order_status = 'Cancel By Admin';
   }

   $result = $dbComObj->viewData($conn, "order_detail", "*", "1 and  id IN($_orderID) ");
   if ($dbComObj->num_rows($result) > 0) {
       while ($_orderdetailsData = $dbComObj->fetch_object($result)) {

           $_POSTORDER['status'] = $_POST['all_order_status'];
           $_POSTORDER['allot_driver_id'] = $_driverId;
           $_POSTORDER['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
           $_POSTORDER['updated_on'] = date("Y-m-d H:i:s");
           $dbComObj->editData($conn, "order_detail", $_POSTORDER, "1 and id='" . $_orderdetailsData->id . "'");

           $_getOrderDetail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $_orderdetailsData->id . "' ");
           if ($dbComObj->num_rows($_getOrderDetail) > 0) {
               while ($_orderdetailsItemData = $dbComObj->fetch_object($_getOrderDetail)) {

                   $_POSTITEM['status'] = $_POST['all_order_status'];
                   $_POSTITEM['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
                   $_POSTITEM['updated_on'] = date("Y-m-d H:i:s");
                   $dbComObj->editData($conn, "order_item_detail", $_POSTITEM, "1 and product_id='" . $_orderdetailsItemData->product_id . "' and order_id='" . $_orderdetailsData->id . "'");

                   $_POSTORDERPRO['status'] = $_order_status;
                   $_POSTORDERPRO['order_id'] = $_orderdetailsData->id;
                   $_POSTORDERPRO['order_item_id'] = $_orderdetailsItemData->product_id;
                   $_POSTORDERPRO['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
                   $_POSTORDERPRO['added_on'] = date("Y-m-d H:i:s");
                   $dbComObj->addData($conn, "order_process_detail", $_POSTORDERPRO);
               }
           }
       } 

       echo "Reload : Order Updated Successfully";
   } else {
       echo "Error : Order Not Updated";
   }
}

else if ($operation == 'SingleOrderUpdated') {
    $_orderID = $_POST['order_id_single'];
    $_driverId = $_POST['driver_id'];
    $_all_order_status = $_POST['all_order_status'];
    
    if ($_POST['all_order_status'] == 2) {
        $_order_status = 'Accepted';
    } else if ($_POST['all_order_status'] == 3) {
        $_order_status = 'Completed';
    } else if ($_POST['all_order_status'] == 4) {
        $_order_status = 'Ready';
    } else if ($_POST['all_order_status'] == 5) {
        $_order_status = 'Dispatch';
    } else if ($_POST['all_order_status'] == 6) {
        $_order_status = 'Delivered';
    } else if ($_POST['all_order_status'] == 8) {
        $_order_status = 'Cancel By Admin';
    }
    
    //print_r($_POST);die;
    $result = $dbComObj->viewData($conn, "order_detail", "*", "1 and  id ='$_orderID' ");
    if ($dbComObj->num_rows($result) > 0) {
        //while ($_orderdetailsData = $dbComObj->fetch_object($result)) {

            $_POSTORDER['status'] = $_POST['all_order_status'];
            $_POSTORDER['allot_driver_id'] = $_driverId;
            $_POSTORDER['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
            $_POSTORDER['updated_on'] = date("Y-m-d H:i:s");
            $dbComObj->editData($conn, "order_detail", $_POSTORDER, "1 and id='" . $_orderID . "'");

            $_getOrderDetail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $_orderID . "' ");
            if ($dbComObj->num_rows($_getOrderDetail) > 0) {
                while ($_orderdetailsItemData = $dbComObj->fetch_object($_getOrderDetail)) {

                    $_POSTITEM['status'] = $_POST['all_order_status'];
                    $_POSTITEM['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
                    $_POSTITEM['updated_on'] = date("Y-m-d H:i:s");
                    $dbComObj->editData($conn, "order_item_detail", $_POSTITEM, "1 and product_id='" . $_orderdetailsItemData->product_id . "' and order_id='" . $_orderID . "'");

                    $_POSTORDERPRO['status'] = $_order_status;
                    $_POSTORDERPRO['order_id'] = $_orderID;
                    $_POSTORDERPRO['order_item_id'] = $_orderdetailsItemData->product_id;
                    $_POSTORDERPRO['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
                    $_POSTORDERPRO['added_on'] = date("Y-m-d H:i:s");
                    $dbComObj->addData($conn, "order_process_detail", $_POSTORDERPRO);
                }
            }
        //} 

        echo "Reload : Order Updated Successfully";
    } else {
        echo "Error : Order Not Updated";
    }
}

elseif ($operation == "managaeOrderCancel") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, "order_detail", "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $_POSTORDER['status'] = '8';
        $_POSTORDER['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTORDER['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, "order_detail", $_POSTORDER, "1 and id='" . $_POST['a'] . "'");
        
        $_getOrderDetail = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id='" . $_POST['a'] . "' ");
        if ($dbComObj->num_rows($_getOrderDetail) > 0) {
            while ($_orderdetailsItemData = $dbComObj->fetch_object($_getOrderDetail)) {

                $_POSTITEM['status'] = 8;
                $_POSTITEM['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
                $_POSTITEM['updated_on'] = date("Y-m-d H:i:s");
                $dbComObj->editData($conn, "order_item_detail", $_POSTITEM, "1 and product_id='" . $_orderdetailsItemData->product_id . "' and order_id='" . $_POST['a'] . "'");

                $_POSTORDERPRO['status'] = 'Cancel By Admin';
                $_POSTORDERPRO['order_id'] = $_POST['a'];
                $_POSTORDERPRO['order_item_id'] = $_orderdetailsItemData->product_id;
                $_POSTORDERPRO['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
                $_POSTORDERPRO['added_on'] = date("Y-m-d H:i:s");
                $dbComObj->addData($conn, "order_process_detail", $_POSTORDERPRO);
            }
        }
        echo "Msg : Order cancel successfully.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
}



elseif ($operation == "getOrderStatusDetail") {
    $_getOrderDetaiil = $dbComObj->viewData($conn, "order_detail", "*", "1 and id='" . $_POST['order_id'] . "' ");
    if ($dbComObj->num_rows($_getOrderDetaiil) > 0) {
        $_orderStatusData = $dbComObj->fetch_object($_getOrderDetaiil);
        
        if($_orderStatusData->status == 1){
            $_html = '<option value=""> -- Select Order Status --</option><option value="2">Accepted</option><option value="4">Ready</option><option value="5">Dispatch</option><option value="6">Delivered</option><option value="3">Completed</option><option value="8">Cancel</option>';
        }
        else if($_orderStatusData->status == 2){
            $_html = '<option value=""> -- Select Order Status --</option><option value="4">Ready</option><option value="5">Dispatch</option><option value="6">Delivered</option><option value="3">Completed</option>';
        }
        else if($_orderStatusData->status == 4){
            $_html = '<option value=""> -- Select Order Status --</option><option value="5">Dispatch</option><option value="6">Delivered</option><option value="3">Completed</option>';
        }
        else if($_orderStatusData->status == 5){
            $_html = '<option value=""> -- Select Order Status --</option><option value="6">Delivered</option>';
        }
        else{
            $_html = '';
        }
        echo $_html;
    }
}