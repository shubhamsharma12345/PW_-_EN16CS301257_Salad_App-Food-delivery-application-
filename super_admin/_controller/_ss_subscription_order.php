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


else if ($operation == 'searchMenuItemEdit') {
    $_itesmArray = array();
    $_cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] :  1;
    $_ptype = isset($_REQUEST['ptype']) ? $_REQUEST['ptype'] :  1;
    $allPrdId = isset($_REQUEST['addedPrd']) ? $_REQUEST['addedPrd'] :  1;
    //echo "1 and status='1' and category_id='$_cat' and product_type_id='$_ptype' and id NOT IN($allPrdId) ";
    $_chkIts = $dbComObj->viewData($conn, "product", "*", "1 and status='1' and category_id='$_cat' and product_type_id='$_ptype' and id NOT IN($allPrdId) ");
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
    $_chkIts = $dbComObj->viewData($conn, "product", "*", "1 and is_subscription='1' and status='1'");
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
    
    $aUpdate['total_price'] = $subtotal;
    $_ordInsert = $dbComObj->editData($conn, "order_detail", $aUpdate,"1 and id='$_orderId'");
    //echo "Reload : Order Created Successfully.";
    echo "Redirect : Order Created successfully. URL " . ADMIN_URL . "eOrder/addNewOrder/";
}


else if ($operation == 'edidAddOrder') {
    $_orderId = $_POST['order_id'];
    $customer_id = 0;
    $_getOrderD = $dbComObj->viewData($conn, "order_detail", "*", "1 and id='$_orderId' ");
    if ($dbComObj->num_rows($_getOrderD) > 0) {
        $_rowOrderD = $dbComObj->fetch_object($_getOrderD);
        if ($_POST['normal_days'] == 1) {
            $itemTotal = $_POST['itemTotal'];
            $allItemsArray = $_POST['allItemsArray'];
            $itemCountt = explode(',', $allItemsArray);
            $_itemTotal = $_POST['itemTotal'];
        }
        $oldAmt = $_rowOrderD->total_price;

        //$_POSTAdd['total_price'] = $_itemTotal;
        //$_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        //$_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        //$dbComObj->addData($conn, "order_detail", $_POSTAdd);
        //$_orderId = $dbComObj->insert_id($conn);
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
                $or_de['category_id'] = 1;//$_POST['category_id'];
                $or_de['product_type_id'] = 1;//$_POST['product_type_id'];
                $or_de['quantity'] = $_POST[$keyQ];
                $or_de['price'] = $_POST[$keyP] * $_POST[$keyQ];
                $or_de['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
                $or_de['added_on'] = date("Y-m-d H:i:s");

                $subtotal += $or_de['quantity'] * $or_de['price'];
                $dbComObj->addData($conn, 'order_item_detail', $or_de);
            }
        }


        $aUpdate['total_price'] = $subtotal+$oldAmt;
        $_ordInsert = $dbComObj->editData($conn, "order_detail", $aUpdate, "1 and id='$_orderId'");
        //echo "Reload : Order Created Successfully.";
        echo "Redirect : Order Updated successfully. URL " . ADMIN_URL . "eMasters/manageOrderDetails/";
    }
    else{
        echo "Error : Order Not Uppdated.";        
    }
}

