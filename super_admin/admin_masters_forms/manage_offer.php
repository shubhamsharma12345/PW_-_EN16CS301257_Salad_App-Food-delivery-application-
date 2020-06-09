<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Offer | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');

$todo = "addNewCoupon";
$timestamp = time()-86400;
$dateater = strtotime("+15 day", $timestamp);
$After7Day =  date('m/d/Y', $dateater);   

$banner_Name = '';
$banner_Desc = ''; 
$banner_Image = '';
$country_Id = '';
$offer_cat_Id ='';
$productType_Id ='';
$productSubType_Id ='';
$frametype_Id ='';
$brand_Id ='';
$coupon_code ='';
$product_id='';
$product_free_id='';
$valid_from= date('m/d/Y');
$valid_to=date('m/d/Y');

$valid_from_main = date("m/d/Y", strtotime(date("Y-m-d")));
$valid_to_main = $After7Day;

$no_of_user = '';
$discount = '';
$remarks = '';
$id = '0';

$_bannerLogo = BASE_URL.'admin-assets/images/noImage.png';

if (isset($_GET['a'])) {
    $id = base64_decode($_GET['a']);
    $condition = " `coupon_Id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "master_offer_coupons", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $row = $dbComObj->fetch_assoc($qry);
        $todo = "editHomeBanner";
        extract($row);
        if(strlen($banner_Image) > 5)
        {
            $_bannerLogo = BASE_URL.'admin-assets/images/offerCoupon/thumb/'.$banner_Image;    
        }
    } else {
        header('Location:' . ADMIN_URL . 'eWebsite/manage-offer/');
    }
}

/* Pagination Code */
$_getOfferCoupons = $dbComObj->viewData($conn,"master_offer_coupons", "*","1 and type='OFFER_ADMIN' order by coupon_Id desc");    
$dataAtOne = 10;
$mainPagination = "";
$data = $_REQUEST;
$page = isset($data['page']) ? $data['page'] : "1";
if ($page != 1) {
    $mainPagination = "LIMIT " . (($page - 1) * $dataAtOne) . "," . $dataAtOne; 
} else {
    $mainPagination = "LIMIT 0," . $dataAtOne;
}
$mainNum = $dbComObj->num_rows($_getOfferCoupons);
/* End Pagination Code */

?>
<style>
    .hideClass{
        display: none !important; 
    }
    .showClass{
        display: block !important;        
    }
</style>
<!-- Page content -->
<div id="page-content">
    <!-- Forms General Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-notes_2"></i>Manage Master Coupon List <br>
            </h1>
        </div>
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Master Coupon List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    <div class="row">
    <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Add / Edit Master Coupon Section</h2>
                </div>
                <form action="" method="post" id="manageCategory" class="js-validation-material" onsubmit="return false;" enctype="multipart/form-data" >
                    
                    <div class="box box-default">
                        <div class="box-header with-border">
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset style="margin-top: -25px;">
                                        <legend><i class="fa fa-angle-right"></i> Offer Promo Code Type</legend>
                                    </fieldset>
                                    <div class="col-md-12 form-group">
                                        <label for="material-text2">Offer Promo Code Applied <span class="text-danger">*</span></label>
                                        <div class="floating open">                                        
                                        <label class="css-control css-control-secondary css-radio">
                                            <input type="radio" class="css-control-input" name="offerApplyOn" id="offerApplyOn_coupon" checked="checked" value="0">
                                            <span class="css-control-indicator"></span> <b>Offer Apply As Coupon / Promo Code for All Products</b> 
                                        </label>
                                        <label class="css-control css-control-secondary css-radio">
                                            <input type="radio" class="css-control-input" name="offerApplyOn" id="offerApplyOn_all" value="1">
                                            <span class="css-control-indicator"></span> <b>Offer Apply On Selected Products</b>
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <fieldset style="margin-top: -25px;">
                                        <legend><i class="fa fa-angle-right"></i> Offer Promo Code Details</legend>
                                    </fieldset>
                                    
                                    <div class="col-md-3 form-group">
                                        <div class="form-material floating open">
                                            <select name="offer_cat_Id" id="offer_cat_Id" class="form-control col-md-5 col-xs-12" required="required" onchange="manageOffersType(this.value)">
                                            <option value="">Select Offer Type</option>
                                            <?php
                                            $_getAllOffCat = $dbComObj->viewData($conn, "master_offer_category", "*", "1 and isActive=1");
                                            if ($dbComObj->num_rows($_getAllOffCat) > 0) {
                                                while ($rowAllCatData = $dbComObj->fetch_assoc($_getAllOffCat)) { 
                                                    echo '<option value="' . $rowAllCatData['offer_cat_Id'] . '" >' . $rowAllCatData['category_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>                                        
                                            <label for="material-text2">Select Offer Type <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 form-group">
                                        <div class="form-material floating">                                        
                                            <input type="text" id="coupon_code" name="coupon_code" required="required" pattern="^[a-zA-Z]{3,}$" class="form-control" value="<?php echo $coupon_code; ?>"/>
                                            <label for="material-text2">Master Coupon Code <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <div class="form-material floating open">                                        
                                            <input type="file" required id="banner_Image" name="banner_Image" class="form-control"/>
                                            <label for="material-text2">Image </label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-2 form-group" id="disCoutType">
                                        <div class="form-material floating">                                        
                                            <input type="number" id="discount" name="discount" pattern="^[a-zA-Z]{3,}$" class="form-control" required value="<?php echo $discount; ?>"/>
                                            <label for="material-text2">Discount </label>
                                        </div>
                                    </div>
                                    <div id="frmtodate" style="diplay:block">
                                        <div class="col-md-4 form-group">
                                            <div class="input-group input-daterange" data-date-format="mm/dd/yyyy">
                                                <label for="material-text2">Offer Date From - To </label>
                                                <input type="text" id="config-demo" class="form-control">                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="form-material floating">                                        
                                            <input type="text" id="remarks" name="remarks" required="required" pattern="^[a-zA-Z]{3,}$" class="form-control" value="<?php echo $remarks; ?>"/>
                                            <label for="material-text2">Remarks<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="offerApply_products" style="display:none;">
                                    <fieldset style="margin-top: -25px;">
                                        <legend><i class="fa fa-angle-right"></i> Offers On Products</legend>
                                    </fieldset>
                                    

                                    <div class="col-md-12 form-group">
                                        <div class="form-material floating open">                                        
                                            <select id="product_ids" name="product_ids"  class="form-control col-md-5 col-xs-12 select-chosen" multiple="multiple" >
                                                <?php 
                                                $resultAllProduct = $dbComObj->viewData($conn, "product",'*', "1 and status='1' order by id desc ");
                                                $numAllProduct = $dbComObj->num_rows($resultAllProduct);
                                                if ($numAllProduct > 0)
                                                {                                                    
                                                    while ($_productData = $dbComObj->fetch_object($resultAllProduct)){
                                                        echo  '<option value="' . $_productData->id . '">' . $_productData->name . '</option>';
                                                    }
                                                }
                                                ?>
                                                
                                            </select>
                                            <label for="material-text2">Select Products <span class="text-danger">(Offer Will be applied on selected Product)</span></label>
                                        </div>
                                    </div>                                                      
                                </div>
                                
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-material floating">
                                        <input type="hidden" id="product_id" value="0" name="product_id" />
                                        <input type="hidden" id="product_free_id" value="0" name="product_free_id" />
                                        <input type="hidden" id="txt_DateTo" value="<?=$valid_to;?>" name="valid_to" />
                                        <input type="hidden" id="txt_DateFrom" value="<?=$valid_from;?>" name="valid_from"/>    
                                        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                        <input type="hidden" name="todo" value="<?php echo base64_encode($todo); ?>" />
                                        <button type="button" class="btn btn-alt btn-sm btn-info" href="javascript:;" onclick="formSubmit('manageCategory', 'category_result', '<?php echo ADMIN_URL; ?>_controller/website_offerData_operations.php')">Add Coupon</button>
                                    </div>
                                </div>
                            </div>
                            <div class="mr-10">&nbsp;</div>
                        </div>
                    </div>
                <div id="category_result"></div> 
                </form>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage All Master Coupon </h2>
                </div>
                <div class="box-body">
                    <div class="x_content"><div id="result1"></div>
                        <div class="table-responsive">                            
                            <table id="categoryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="coupon_code" data-sortable="true">Coupon Code</th>                                   
                                        <th data-field="remark" data-sortable="true">Remark</th>
                                        <th data-field="banner_Image" data-sortable="true">Offer Image</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="coupon_user_use_limt" data-sortable="true">Coupon User Limit</th>
                                        <th data-field="offer_type" data-sortable="true">Coupon Type</th>
                                        <th data-field="discount" data-sortable="true">Discount</th>
                                        <th data-field="product_name" data-sortable="true">Products Name</th>     
                                        <th data-field="action" data-events="actionEvents">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $result = $dbComObj->viewData($conn,"master_offer_coupons", "*","1 and type='OFFER_ADMIN' order by coupon_Id asc ".$mainPagination);
                                        $num = $dbComObj->num_rows($result);
                                        if ($num > 0)
                                        {
                                            $i = 0;
                                            while ($_secData = $dbComObj->fetch_object($result))
                                            {
                                                $i++;
                                                
                                                $_offerCategory = $dbComObj->fetch_object($dbComObj->viewData($conn, "master_offer_category", "*", "1 and offer_cat_Id='".$_secData->offer_cat_Id."'"));
                                                if($_secData->offer_cat_Id == 1){
                                                    $_getproductData =  $dbComObj->viewData($conn, "product", "id,name", "1 and id IN($_secData->product_id)");
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
                                                
                                                $_txtStatus = '';
                                                $txtSType = '0';
                                                if($_secData->isActive == '1')
                                                {
                                                    $txtSType = '0';
                                                    $_txtStatus = 'Deactivated';
                                                    $status = '<span class="label label-sm label-success">Active</span>';
                                                }
                                                else
                                                {
                                                    $txtSType = '1';
                                                    $_txtStatus = 'Active';
                                                    $status = '<span class="label label-sm label-danger">Deactivated</span>';
                                                }

                                                
                                                $_img = 'NA';
                                                if(strlen($_secData->image) > 6)
                                                {
                                                    $_img = '<img src="'.BASE_URL.'admin-assets/images/offerCoupon/thumb/'.$_secData->image.'" style="width:50px;"/>';
                                                }
                                                //<a href="'.ADMIN_URL.'eWebsite/manageMasterOfferCoupons/?languageid='.$language_Id.'&a='.base64_encode($_secData->coupon_Id).'"><i class="si si-pencil fa-1x pull-right"></i>Edit</a>
                                                $_action = '<div class="btn-group btn-group-sm"><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-info dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Options"><span class="caret"></span></a><ul class="dropdown-menuAction text-left"><li>                                                
                                                <a href="javascript:void(0)" onclick="return managaeCoupon('.$_secData->coupon_Id.','.$txtSType.');"><i class="si si-ban fa-1x pull-right"></i>'.$_txtStatus.'</a>
                                                <a href="javascript:void(0)" onclick="return deleteCoupon('.$_secData->coupon_Id.');"><i class="si si-close fa-1x pull-right"></i>Delete</a>
                                                </li></ul></div>';                                                
                                                echo '<tr><td>'.$i.'</td><td>'.ucfirst(html_entity_decode($_secData->coupon_code)).'</td><td>'.ucfirst(($_secData->remarks)).'</td><td>'.$_img.'</td><td>'.$status.'</td><td>'.date("M d, Y", strtotime($_secData->added_on)).'</td><td>'.$_secData->no_of_user.'</td><td>'.$_offerCategory->category_name.'</td><td>'.$_secData->discount.'</td><td>'.$_finalPrdName.'</td><td>'.$_action.'</td></tr>';
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>    
                        </div>
                    </div>
                    <div class="fixed-table-pagination">
                    <?php
                        echo '<div class="shop-breadcrumb text-right">';
                        $pg->pagenumber = $page;
                        $pg->pagesize = $dataAtOne;
                        $pg->totalrecords = $mainNum;
                        $pg->showfirst = true;
                        $pg->showlast = true;
                        $pg->paginationcss = "pagination-normal";
                        $pg->paginationstyle = 0; // 1: advance advance pagination, 0: normal pagination
                        $pg->defaultUrl = ADMIN_URL.'eWebsite/manageHomeSectionTop/?languageid='.$language_Id;
                        $pg->paginationUrl = "javascript:;";
                        echo $pg->process();
                        echo '</div>';
                    ?>
                    </div>
                </div>
                <!-- END Example Form Content -->
            </div>
            <!-- END Example Form Block -->
        </div>
    </div>
    <!-- END Form Example with Blocks in the Grid -->
</div>
<?php 
require_once('../../admin-assets/inc/page_footer.php');
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>

<script type="text/javascript" src="<?php echo BASE_URL.'admin-assets/js/'?>daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL.'admin-assets/js/'?>daterangepicker.css" />
<script>

//normalOffs
$('input[type=radio][name=offerApplyOn]').change(function() {
    var _offerType = $('#offer_cat_Id').val();

    if(_offerType == 1 && this.value == 1){
        //alert('Bogo offer apply as coupon / promo code. Please select Offer Apply As Coupon / Promo Code for All Products.');
        $("#offer_cat_Id").val('');
        $('#bogoOff').hide();
        $("#discount").prop('required',true);
        $('#offerApply_products').hide();
    }

    if (this.value == '0') {
        $('#normalOffs').hide();
        $('#offerApply_products').hide();
        $('#frmtodate').show();
    }
    else if (this.value == '1') {
        $('#normalOffs').show();
        $('#offerApply_products').show();
        $('#frmtodate').hide();
        $("#brand_Ids").prop('required',true);
        $("#productType_Ids").prop('required',true); 
    }
    $('#product_ids').val('');

    $('#product_id').val('0');
    

    $('#product_ids option:selected').removeAttr('selected');
    $('#product_ids').trigger('chosen:updated');
});

function manageOffersType(e){
    
    var offerOption = $("input:radio[name=offerApplyOn]:checked").val();
    
    if(e == 1 && offerOption == 1){
        //alert('Bogo offer apply as coupon / promo code. Please select Offer Apply As Coupon / Promo Code for All Products.');
        $("#offer_cat_Id").val('');
        $('#bogoOff').hide();
        $("#discount").prop('required',true);
        $('#offerApply_products').hide();
        getProductData();
    }
    else if(e == 1 && offerOption == 0){
        $('#bogoOff').show();
        $("#discount").prop('required',false); 
        $("#disCoutType").hide();
        $('#offerApply_products').show();
        $('#frmtodate').show();
    }
    else if((offerOption == 1 && e == 3) || (offerOption == 1 && e == 2)){
        $('#frmtodate').hide();
        $('#offerApply_products').show();
        $('#bogoOff').hide();
        $("#discount").prop('required',true);      
    }
    else{
        $('#frmtodate').show();
        $('#offerApply_products').hide();
        $('#bogoOff').hide();        
        $("#discount").prop('required',true);
        $("#disCoutType").show();
    }
}

$(function() {
    $('#config-demo').daterangepicker({
        opens: 'right',
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showOn: "button",
        maxDate: 60,
        startDate : '<?php echo date('m/d/Y');?>',
        minDate: '<?php echo date('m/d/Y');?>',
        inline: true
    }, function(start, end, label)
    {      
        $('#txt_DateFrom').val(start.format('YYYY-MM-DD'));
        $('#txt_DateTo').val(end.format('YYYY-MM-DD'));
    });
});

function getDealsAjax(page)
{
    window.location.href = '<?php echo ADMIN_URL;?>eWebsite/manageHomeSectionTop/?languageid=<?=$language_Id;?>&page='+page;
}

var _urlPage = "website_offerData_operations.php";

function managaeCoupon(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Coupon status has been updated.',e,f,btoa('managaeCoupon'),'9',_urlPage);
}
function deleteCoupon(e)
{
    alertBox("If you want to delete Coupon! All related data will be removed!",'Yes, delete Coupon!','Coupon was removed from system.',e,'',btoa('deleteCoupon'),'9',_urlPage);
}

$('select#product_ids').on('change', function () {
    $('#product_id').val($('#product_ids').val());
});
 
function getProductData(){   
   
    $.post('<?php echo ADMIN_URL; ?>_controller/website_offerData_operations.php', {todo: '<?php echo base64_encode('getProductData'); ?>'}, function (data) {
        $("#product_ids").html(data);
        $('#product_ids option:selected').removeAttr('selected');
        $('#product_ids').trigger('chosen:updated');
        
        $("#product_free_ids").html(data);
        $('#product_free_ids option:selected').removeAttr('selected');
        $('#product_free_ids').trigger('chosen:updated');
    });
}
</script>    
