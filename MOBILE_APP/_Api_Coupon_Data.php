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

    if ($method == 'All_Offers') {
        $_currentDate = date('Y-m-d');
        $result = $dbComObj->viewData($conn, "master_offer_coupons", "*", "1 and type='OFFER_ADMIN' and isActive='1' and valid_to>= '$_currentDate' and valid_from<='$_currentDate'   order by coupon_Id asc ");

        $num = $dbComObj->num_rows($result);
        if ($num > 0) {
            $i = 0;
            while ($dataOffer = $dbComObj->fetch_object($result)) {
                $_offerCategory = $dbComObj->fetch_object($dbComObj->viewData($conn, "master_offer_category", "*", "1 and offer_cat_Id='".$dataOffer->offer_cat_Id."'"));
                if($dataOffer->offer_cat_Id == 1){
                    $_getproductData =  $dbComObj->viewData($conn, "product", "id,name", "1 and id IN($dataOffer->product_id)");
                    if($dbComObj->num_rows($_getproductData) > 0){
                        $productName = array();
                        $productId = array();
                        while ($_resProductData = $dbComObj->fetch_object($_getproductData)){
                            $productName[] = $_resProductData->name;
                            $productId[] = $_resProductData->id;
                        }
                        $_finalPrdId = implode(",",$productId);
                        $_finalPrdName = implode(",",$productName);
                    }
                }else{
                    $_finalPrdId = '';
                    $_finalPrdName = '';
                }
                
                $c['coupon_id'] = $dataOffer->coupon_Id;
                $c['coupon_code'] = $dataOffer->coupon_code;
                $c['coupon_user_use_limt'] = $dataOffer->no_of_user;
                $c['offer_type'] = $_offerCategory->category_name;
                $c['discount'] = $dataOffer->discount;
                $c['offer_image'] = BASE_URL.'admin-assets/images/offerCoupon/thumb/'.$dataOffer->image;
                
                $c['product_name'] = $_finalPrdName;
                $c['product_id'] = $_finalPrdId;
                $c['remark'] = $dataOffer->remarks;
                
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