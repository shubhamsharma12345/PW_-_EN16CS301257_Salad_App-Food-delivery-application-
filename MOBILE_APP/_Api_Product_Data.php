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

    if ($method == 'All_Products') {
        if (!empty($data)) {
            $result_product = $dbComObj->viewData($conn, "product", "*", "1 and status='1'");
            $thmsSubs = array();
            if ($dbComObj->num_rows($result_product) > 0) {
                while ($dataProduct = $dbComObj->fetch_assoc($result_product)) {
                    $dataProductType = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "Product_Type", "type", "1 and id='".$dataProduct['product_type_id']."'"));
                    $dataCategory = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "category", "name", "1 and id='".$dataProduct['category_id']."'"));
                    
                    $result_subscription_detail = $dbComObj->viewData($conn, "product_subscription_price", "*", "1 and status='1' and product_id ='" . $dataProduct['id'] . "'");
                    if ($dbComObj->num_rows($result_subscription_detail) > 0) {
                        $thmsSubs = array();
                        while ($dataSubscription = $dbComObj->fetch_assoc($result_subscription_detail)) {
                            $dataMasterSubscriptionDays = $dbComObj->fetch_assoc($dbComObj->viewData($conn, "master_subscription_days", "days", "1 and id='".$dataSubscription['subscription_id']."'"));

                            $cc['subscription_id'] =  $dataSubscription['subscription_id'];
                            $cc['subscription_days'] = $dataMasterSubscriptionDays['days'];
                            $cc['subscription_price'] = $dataSubscription['product_subs_price'];
                            $thmsSubs[] =$cc;
                        }
                    }
                    
                    $result_chef_detail = $dbComObj->viewData($conn, "kitchen_chef_data", "*", "1  and id ='" . $dataProduct['kitchen_chef_id'] . "'");
                    if ($dbComObj->num_rows($result_chef_detail) > 0) {
                        $thmsChef = array();
                        $_img1 = '';
                        $_img2 = '';
                        $_img3 = '';
                        while ($_ChefData = $dbComObj->fetch_object($result_chef_detail)) {
                            if (strlen($_ChefData->image_1) > 8) {
                                $_img1 =  LOCAL_ROOT . 'Chef/' . $_ChefData->image_1 . '" target="_blank"><img src="' . LOCAL_ROOT . 'Chef/' . $_ChefData->image_1 . '" style="width :100px; height :80px"';
                            }
                            if (strlen($_ChefData->image_2) > 8) {
                                $_img2 =  LOCAL_ROOT . 'Chef/' . $_ChefData->image_2 . '" target="_blank"><img src="' . LOCAL_ROOT . 'Chef/' . $_ChefData->image_2 . '" style="width :100px; height :80px"';
                            }
                            if (strlen($_ChefData->image_3) > 8) {
                                $_img3 =  LOCAL_ROOT . 'Chef/' . $_ChefData->image_3 . '" target="_blank"><img src="' . LOCAL_ROOT . 'Chef/' . $_ChefData->image_3 . '" style="width :100px; height :80px"';
                            }

                            $ccc['chef_id'] = $_ChefData->id;
                            $ccc['chef_name'] = $_ChefData->chef_name;
                            $ccc['chef_description'] = $_ChefData->chef_description;
                            $ccc['image1'] = $_img1;
                            $ccc['image2'] = $_img2;
                            $ccc['image3'] = $_img3;


                            $thmsChef[] = $ccc;
                        }
                    }


                    $c['product_id'] = $dataProduct['id'];
                    $c['product_category_id'] = $dataProduct['category_id'];
                    $c['product_category_name'] = $dataCategory['name'];
                    $c['product_type_id']      = $dataProduct['product_type_id'];   
                    $c['product_type_name']      = $dataProductType['type'];   
                    $c['product_name']        = $dataProduct['name'];
                    $c['product_quantity']    = $dataProduct['quantity'];
                    $c['product_price']       = $dataProduct['price'];
                    $c['product_description']= $dataProduct['description'];
                    if(strlen($dataProduct['image1']) > 5){
                        $c['product_image1']     = LOCAL_ROOT.'Product/'.$dataProduct['image1'];                        
                    }
                    if(strlen($dataProduct['image2']) > 5){
                        $c['product_image2']     = LOCAL_ROOT.'Product/'.$dataProduct['image2'];                        
                    }
                    if(strlen($dataProduct['image3']) > 5){
                        $c['product_image3']     = LOCAL_ROOT.'Product/'.$dataProduct['image3'];                        
                    }
                   
                    $c['product_subscription_data']= $thmsSubs; 
                    $c['product_chef_data']= $thmsChef;   


                    $thmsg[] = $c;
                }
                $msg['message'] = 'Success';
                $msg['result'] = $thmsg;
                $msg['status'] = '200';
            } else {
                $thmsg = array("msg" => "Product not available");
                $msg['message'] = 'Error';
                $msg['result'][] = $thmsg;
                $msg['status'] = '400';
            }
            echo json_encode($msg);
        }
    }
}