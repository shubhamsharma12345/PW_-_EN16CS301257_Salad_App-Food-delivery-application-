<?php
include '../../page_fragment/define.php'; 
include '../../page_fragment/topScript.php';



$njFileObj = new njFile();
$operation = "";
if (isset($_POST['todo'])) {
    $operation = base64_decode($_POST['todo']);
    unset($_POST['todo']);
} elseif (isset($_GET['todo'])) {
    $operation = base64_decode($_GET['todo']);
    unset($_GET['todo']);
}
if($operation == 'searchMenuItem')
{
     
 
    {
    $_itesmArray = array();
    $_chkIts = $dbComObj->viewData($conn, "product", "*","1");    
    if ($dbComObj->num_rows($_chkIts) > 0)
    {
        while($_gtIts = $dbComObj->fetch_object($_chkIts))
        {
            $_is['name'] =($_gtIts->name);
            $_is['id'] = ($_gtIts->id);
            $_is['item_full_price'] = ($_gtIts->price);
           
            $_itesmArray[] = $_is;
        }
        
    }

    $example = $_itesmArray;
    $searchword = $_GET['request']['term'];
    $matches = array();
    foreach($example as $k=>$v)
    {
        if (false !== stripos($v['name'], $searchword))
        {
            $matches[$k] = $v;
        }        
    }
    
    if(count($matches) > 0)
    {
        foreach($matches as $val)
        {
            $json[] = array(
                'id' => $val['id'],
                'value' => $val['name'],
                'menu_id' => '0',
                'item_full_price' => $val['item_full_price'],
                'name' => $val['name']
                );
        }
    }
    else
    {
        $json[] = array('id' => '0', 'value' => 'Item not found.');
    }
    echo json_encode($json);    
}



}

else if ($operation == 'addNewAdminOrder') {
    //print_r($_POST);
    //die;
    $_uniqueId = rand(1111, 9999);
    $itemTotal = $_POST['itemTotal'];
    $allItemsArray = $_POST['allItemsArray'];
    $itemCountt = explode(',', $allItemsArray);
    
    $resultOrd = $dbComObj->viewData($conn, "order_detail", "*", "1");
    $_numOrd = $dbComObj->num_rows($resultOrd);
    $_finaleUId = "ORDDCNPL".$_order_unique_id.($_numOrd+1);

    $a['order_id'] = $_finaleUId;
    $a['user_id'] = $_POST['id'];
    $a['name'] = $_POST['customer_name'];
    $a['email'] = $_POST['customer_email'];
    $a['mobile'] = $_POST['customer_phone'];
    $a['total_amount'] = $_POST['total_price']; //$total_amount;
    $a['payment_type'] = $_POST['payment_type'];
    $a['added_by'] = $_SESSION['added_by'];
    $a['added_on'] = date("Y-m-d H:i:s");
    $a['order_status'] = $_POST['status'];
    $a['description'] = $_POST['description'];
    //print_r($a);
    $_ordInsert = $dbComObj->addData($conn, "order_detail", $a);
    $lastOrdId = $dbComObj->insert_id($conn);
   
    for ($i = 0; $i < count($itemCountt); $i++) {
            //[product_code_12] => 12 [menu_id_12] => 0 [multi_key_12] => 100 [product_name_12] => Pizza Main [product_qty_12] => 1 [product_price_12] 
            $keyC = 'product_code_' . $itemCountt[$i];
            $keyI = 'menu_id_' . $itemCountt[$i];
            $keyMK = 'multi_key_' . $itemCountt[$i];
            $keyN = 'product_name_' . $itemCountt[$i];
            $keyQ = 'product_qty_' . $itemCountt[$i];
            $keyP = 'product_price_' . $itemCountt[$i];
            if ($_POST[$keyI] == '0') {

                //$or_de['product_code'] = '0';
                $or_de['item_id'] = $_POST[$keyC];
            } else {
                //$or_de['product_code'] = $_POST[$keyC];
                $or_de['item_id'] = $_POST[$keyI];
            }
            $or_de['order_id']= $lastOrdId;
            $or_de['quantity'] = 1;
            $or_de['itemQuantity'] = $_POST[$keyQ];
            $or_de['price'] = $_POST[$keyP]*$_POST[$keyQ];
            $or_de['added_by'] = $_SESSION['DGV_administrator_id'];
            $or_de['added_on'] = date("Y-m-d H:i:s");
            
            $subtotal += $or_de['itemQuantity'] * $or_de['price'];
            $dbComObj->addData($conn, 'order_detail', $or_de);
            
        }
        $aUpdate['total_amount'] = $subtotal;
        $aUpdate['sub_total_amount'] = $subtotal;
        $_ordInsert = $dbComObj->editData($conn, "order_detail", $aUpdate,"1 and id='$lastOrdId'");
        if ($lastOrdId > 0) {
            echo "Reload : Order Created Successfully.";
        } else {
            echo "Error : Some error occuored. Please try again.";
        }
}



