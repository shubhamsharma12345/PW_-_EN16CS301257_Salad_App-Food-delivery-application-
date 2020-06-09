<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Products | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');
$category_id = '';
$product_type_id= '';
$name= '';
$description = '';
$quantity= '';
$price = '';
$chef_id = '';
$todo = base64_encode('addProduct');
if (isset($_GET['a'])) {
    $required = '';
    $id = ($_GET['a']);
    $condition = " `id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "product", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $todo = base64_encode('editProduct');
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
                <i class="gi gi-notes_2"></i>Manage Products List <br><small>Manage Products!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Products List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Add Edit Product</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Category Name</label> 
                                        <select  name="category_id" id="category_id" class="form-control input-sm" required>
                                            <option value="">Select Category Name</option>
                                            <?php
                                            $_getSelcat = $dbComObj->viewData($conn, "category", "*", "1 and status='1'");
                                            if ($dbComObj->num_rows($_getSelcat) > 0) {
                                                while ($_rowCatData = $dbComObj->fetch_assoc($_getSelcat)) {
                                                    if($category_id == $_rowCatData['id']){$_selCat = 'selected="selected"';}else{$_selCat='';}
                                                    echo "<option value='".$_rowCatData['id']."' ".$_selCat.">".$_rowCatData['name']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Type </label> 
                                        <select  name="product_type_id" id="product_type_id" class="form-control input-sm" required>
                                            <option value="">Select Product Type Name</option>
                                            <?php
                                            $_getPrdType = $dbComObj->viewData($conn, "Product_Type", "*", "1 and status='1'");
                                            if ($dbComObj->num_rows($_getPrdType) > 0) {
                                                while ($_rowPrdTypeData = $dbComObj->fetch_assoc($_getPrdType)) {
                                                    if($product_type_id == $_rowPrdTypeData['id']){$_selPrdT = 'selected="selected"';}else{$_selPrdT='';}
                                                    echo "<option value='".$_rowPrdTypeData['id']."' ".$_selPrdT.">".$_rowPrdTypeData['type']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>  
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Select Chef </label> 
                                        <select  name="chef_id" id="chef_id" class="form-control input-sm" required>
                                            <option value="">Select Chef Name</option>
                                            <?php
                                            $_getChef = $dbComObj->viewData($conn, "kitchen_chef_data", "*", "1 and status='1'");
                                            if ($dbComObj->num_rows($_getChef) > 0) {
                                                while ($_rowChef = $dbComObj->fetch_assoc($_getChef)) {
                                                    if($chef_id == $_rowChef['id']){$_selchef_id = 'selected="selected"';}else{$_selchef_id='';}
                                                    echo "<option value='".$_rowChef['id']."' ".$_selchef_id.">".$_rowChef['chef_name']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Name</label> 
                                        <input type="text" name="name" id="name" class="form-control input-sm" placeholder="Enter Product Name" required value="<?php echo $name; ?>"/>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Quantity</label> 
                                        <input type="number" name="quantity" id="quantity" class="form-control input-sm" placeholder="Enter Product Quantity" required value="<?php echo $quantity; ?>"/>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Price</label> 
                                        <input type="number" name="price" id="price" class="form-control input-sm" placeholder="Enter Product Price" required value="<?php echo $price; ?>" />
                                    </div>
                                </div>                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Product Description</label>                                         
                                        <textarea name="description" id="description" class="form-control input-sm" required placeholder="Enter Product Description"><?php echo $description?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Image 1</label> 
                                        <input type="file" name="image1" id="image1" class="form-control input-sm" />
                                    </div>
                                </div> 
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Image 2</label> 
                                        <input type="file" name="image2" id="image2" class="form-control input-sm" />
                                    </div>
                                </div> 
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product Image 3</label> 
                                        <input type="file" name="image3" id="image3" class="form-control input-sm" />
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_website_Product_Operations.php')" class="btn btn-success srSubmitBtn">Submit</button>

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
                    <h2>Manage Category</h2>
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
                                        <th data-field="cat_name" data-sortable="true">Category Name</th>
                                        <th data-field="prd_type" data-sortable="true">Product Type </th>
                                        <th data-field="prd_Quan" data-sortable="true">Product Quantity </th>
                                        <th data-field="prd_price" data-sortable="true">Product Price </th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "product", "*", "1 order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_productData = $dbComObj->fetch_object($result)) {
                                            $i++;
                                            $_prdCatData = $dbComObj->fetch_object($dbComObj->viewData($conn, "category", "name", "1 and id='".$_productData->category_id."'"));
                                            $_prdTypeData = $dbComObj->fetch_object($dbComObj->viewData($conn, "Product_Type", "type", "1 and id='".$_productData->product_type_id."'"));
                                            if($_productData->status == '1')
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
                                            $_action = '<div id="manageBtnNj" class="block-options">
<a href="'.ADMIN_URL.'eMasters/manageProduct/?a='.($_productData->id).'#BlogData" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="Edit Product" data-original-title="Edit Product"><i class="fa fa-pencil mt-0"></i></a>&nbsp;<a href="javascript:void(0)" class="btn btn-alt btn-sm btn-primary" onclick="return manageStatus('.$_productData->id.','.$txtSType.');" data-toggle="tooltip" data-original-title="Enable / Disable  Product"><i class="fa fa-exclamation-triangle"></i></a>&nbsp;<a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger" onclick="return deleteProduct('.$_productData->id.','.$txtSType.');" data-toggle="tooltip" title="" data-original-title="Delete Product"><i class="fa fa-times"></i></a></div>';
                                            echo '<tr><td>' . $i . '</td><td>' . htmlentities(ucfirst($_productData->name)) . '</td><td>' . htmlentities($_prdCatData->name) . '</td><td>' . htmlentities($_prdTypeData->type) . '</td><td>' . $_productData->quantity . '</td><td>' . $_productData->price . ' RS</td><td>' . ($status) . '</td><td>' . date("M d, Y", strtotime($_productData->added_on)) . '</td><td>' . $_action. '</td></tr>';
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
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageProduct/?page='+page+'&count='+<?=$dataAtOne?>;
}

function getPerPage(a){
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageProduct/?page='+<?=$page?>+'&count='+a;
}
var _urlPage = "_website_Product_Operations.php";

function manageStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Product status has been updated.',e,f,btoa('manageStatus'),'9',_urlPage);
}

function deleteProduct(e)
{
    alertBox("If you want to delete Product! All related data and Product will be removed!",'Yes, delete Product!','Product was removed from system.',e,'',btoa('deleteProduct'),'9',_urlPage);
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