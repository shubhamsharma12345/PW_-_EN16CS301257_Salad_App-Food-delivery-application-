<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Product Subscription | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');
$category_id = '';
$product_type_id= '';
$name= '';
$description = '';
$quantity= '';
$price = '';
$todo = base64_encode('addProductSubsdays');
if (isset($_GET['a'])) {
    $required = '';
    $id = ($_GET['a']);
    $condition = " `id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "product_subscription_price", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $todo = base64_encode('editProductSubsdays');
        $row = $dbComObj->fetch_assoc($qry);
        extract($row);
    }
}


$_getC = $dbComObj->viewData($conn,"product", "*","1  order by id DESC");    
$dataAtOne = isset($_REQUEST['count']) ? $_REQUEST['count'] : 10;
$mainPagination = "";
$data = $_REQUEST;
$page = isset($data['page']) ? $data['page'] : "1";
if ($page != 1) {
    $mainPagination = "LIMIT " . (($page - 1) * $dataAtOne) . "," . $dataAtOne; // (($dataAtOne * $page) - $dataAtOne) + 1
} else {
    $mainPagination = "LIMIT 0," . $dataAtOne;
}
$mainNum = $dbComObj->num_rows($_getC);
/* End Pagination Code */

?>
<style>
    #gmaps-canvas {
        backgorund: pink;
        height: 200px;        
    }
</style>
<!-- Page content -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"/>
<div id="page-content">
    <!-- Forms General Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-notes_2"></i>Manage Product Subscription Price List <br><small>Manage Product Subscription Price!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Product Subscription Price List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Add Edit Product Subscription Price</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Product Name</label> 
                                        <select  name="product_id" id="product_id" class="form-control input-sm" required>
                                            <option value="">Select Product Name</option> 
                                            <?php
                                            $_getSelProduct = $dbComObj->viewData($conn, "product", "*", "1 and status='1'");
                                            if ($dbComObj->num_rows($_getSelProduct) > 0) {
                                                while ($_rowProductData = $dbComObj->fetch_assoc($_getSelProduct)) {
                                                    if ($category_id == $_rowProductData['id']) {
                                                        $_selProduct = 'selected="selected"';
                                                    } else {
                                                        $_selProduct = '';
                                                    }
                                                    echo "<option value='" . $_rowProductData['id'] . "' " . $_selProduct . ">" . ucfirst($_rowProductData['name']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php 
                                     $_getSubsDays = $dbComObj->viewData($conn, "master_subscription_days", "*", "1 and status='1'");
                                            if ($dbComObj->num_rows($_getSubsDays) > 0) {
                                                while ($_rowSubsDays = $dbComObj->fetch_object($_getSubsDays)) {
                                ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $_rowSubsDays->days;?> Days Price</label> 
                                        <input type="hidden" name="product_subs_days[]"  class="form-control input-sm" value="<?php echo $_rowSubsDays->id; ?>"/>
                                        <input type="text" name="product_subs_price[]"  class="form-control input-sm" placeholder="Enter Product Subscription Price" required />
                                    </div>
                                </div>
                                <?php } }?>
                                
                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_website_Product_Price_Subscription_Operations.php')" class="btn btn-success srSubmitBtn">Submit</button>

                                </div>
                            </div>
                            <div id="result_employee"></div>
                            <hr/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Product Subscription Price</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        
                        <div class="pull-right">
                            <select name="perPage" id="perPage" onchange="return getPerPage(this.value)">
                                <option value="10" <?php if($dataAtOne ==10){echo 'selected';}?>>10</option>
                                <option value="25" <?php if($dataAtOne ==25){echo 'selected';}?>>25</option>
                                <option value="50" <?php if($dataAtOne ==50){echo 'selected';}?>>50</option>
                                <option value="100" <?php if($dataAtOne ==100){echo 'selected';}?>>100</option>
                                <option value="200" <?php if($dataAtOne ==200){echo 'selected';}?>>200</option>
                            </select>
                        </div>
                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="name" data-sortable="true">Product Name</th>
                                        <th data-field="subs_days" data-sortable="true">Subscription Days</th>
                                        <th data-field="prd_type" data-sortable="true">Subscription Price</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "product_subscription_price", "*", "1 order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_productSubsData = $dbComObj->fetch_object($result)) {
                                            $i++;
                                            $_productData = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "name", "1 and id='".$_productSubsData->product_id."'"));
                                            $_prdSubsData = $dbComObj->fetch_object($dbComObj->viewData($conn, "master_subscription_days", "days", "1 and id='".$_productSubsData->subscription_id."'"));
                                            if($_productSubsData->status == '1')
                                            {
                                                $txtSType = '0';
                                                $txtStatus = 'Disable';
                                                $status = '<span class="label label-sm label-success">Enable</span>';
                                            }
                                            else
                                            {
                                                $txtStatus = 'Enable';
                                                $txtSType = '1';
                                                $status = '<span class="label label-sm label-danger">Disable</span>';
                                            }
                                            $_action = '<div id="manageBtnNj" class="block-options"><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-primary" onclick="return manageStatus('.$_productSubsData->id.','.$txtSType.');" data-toggle="tooltip" data-original-title=""><i class="fa fa-exclamation-triangle"></i></a>&nbsp;<a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger" onclick="return deleteSubsProduct('.$_productSubsData->id.','.$txtSType.');" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-times"></i></a></div>';
                                            echo '<tr><td>' . $i . '</td><td>' . htmlentities(ucfirst($_productData->name)) . '</td><td>' . htmlentities($_prdSubsData->days) . ' Days</td><td>' . $_productSubsData->product_subs_price . ' RS.</td><td>' . ($status) . '</td><td>' . date("M d, Y", strtotime($_productSubsData->added_on)) . '</td><td>' . $_action. '</td></tr>';
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
                        $pg->defaultUrl = ADMIN_URL.'eMasters/manageCountries/';
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
?>

<script>
function getDealsAjax(page)
{
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageProductSubscriptionPrice/?page='+page+'&count='+<?=$dataAtOne?>;
}

function getPerPage(a){
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageProductSubscriptionPrice/?page='+<?=$page?>+'&count='+a;
}
var _urlPage = "_website_Product_Price_Subscription_Operations.php";

function manageStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Product Subscription Price status has been updated.',e,f,btoa('manageStatus'),'9',_urlPage);
}

function deleteSubsProduct(e)
{
    alertBox("If you want to delete Product Days Subscription! All related data and Product will be removed!",'Yes, delete Product Subscription!','Product Subscription was removed from system.',e,'',btoa('deleteSubsProduct'),'9',_urlPage);
}

function resetFilter()
{
    let _category_Id = $('#category_Id').val();
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageProduct/';
} 

</script>    
<?php 
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>  