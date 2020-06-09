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

    if ($method == 'Product_Types') {
        if (!empty($data)) {
            $result_veg_type = $dbComObj->viewData($conn, "Product_Type", "*", "1 and status='1'");

            if ($dbComObj->num_rows($result_veg_type) > 0) {
                while ($dataVeg_Type = $dbComObj->fetch_assoc($result_veg_type)) {
                    $dataProductType = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "Product_Type", "type", "1 and id='".$dataProduct['product_type_id']."'"));
                    $dataCategory = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "category", "name", "1 and id='".$dataProduct['category_id']."'"));
                    $c['product_type_id'] = $dataVeg_Type['id'];
                    $c['product_type'] = $dataVeg_Type['type'];
                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "Veg_Type not available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
}