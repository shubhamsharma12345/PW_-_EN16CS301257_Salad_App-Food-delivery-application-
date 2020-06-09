<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Products | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');


$_getC = $dbComObj->viewData($conn,"order_detail", "*","1 and status !='3' and status !='7' and status !='8' order by id DESC");    
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
                <i class="gi gi-notes_2"></i>Manage Order List <br><small>Order Details!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Order List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Order Item</h2>
                    <a onclick="return getAllotDrivers()" class="btn btn-info">Allot Drivers</a>
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
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered" >
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="order_update" data-sortable="true">Order Update To Driver</th>
                                        <th data-field="name" data-sortable="true">Delivery Boy Name</th>
                                        <th data-field="order_unique_id" data-sortable="true">Order id</th>
                                        <th data-field="cus_name" data-sortable="true">Customer Name</th>
                                        <th data-field="cus_address" data-sortable="true">Customer Address</th>
                                        <th data-field="cus_email" data-sortable="true">Customer Email </th>
                                        <th data-field="cus_phone" data-sortable="true">Customer Phone </th>
                                        <th data-field="prd_price" data-sortable="true">Order Price </th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "order_detail", "*", "1 and status !='3' and status !='7' and status !='8' order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                
                                    
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_orderdetailsData = $dbComObj->fetch_object($result)) {
                                            $i++;
                                           // echo $i;
                                           // echo $_orderdetailsData->product_id;
                                           //foreach($employee as $key => $element) {  
                                           // echo $key . ": " . $element . "<br>";  
                                       // }  

                                            
                                            $_prdNameData = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "*", "1 and id='" . $_orderdetailsData->product_id . "'"));
                                            $_prdCatData = $dbComObj->fetch_object($dbComObj->viewData($conn, "category", "name", "1 and id='" . $_orderdetailsData->category_id . "'"));
                                            $_Deliveryboy = 'NA';
                                            $_deliveryNameData = $dbComObj->viewData($conn, "delivery_boy", "name", "1 and id='" . $_orderdetailsData->allot_driver_id . "'");
                                            if($dbComObj->num_rows($_deliveryNameData) > 0){
                                               $_DelvData = $dbComObj->fetch_object($_deliveryNameData);
                                               $_Deliveryboy =$_DelvData->name;
                                               

                                            

                                            }
                                            
                                            
                                            $_orderUpBut = '';
                                            if($_orderdetailsData->order_type == 'NORMAL' && ($_orderdetailsData->status == '1' || $_orderdetailsData->status == '2' || $_orderdetailsData->status == '4')){
                                                $_orderUpBut = '<a href="'.ADMIN_URL.'eMasters/editOrder/?a='.$_orderdetailsData->id.'" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="Edit Order" data-original-title="Edit Order"><i class="fa fa-edit mt-0"></i> Edit Order</a> &nbsp;';
                                            }
                                            
                                           
                                            if($_orderdetailsData->status == '1')
                                            {
                                                $status = '<span class="label label-sm label-default">Created</span>';
                                            }
                                            else if($_orderdetailsData->status == '2')
                                            {
                                                $status = '<span class="label label-sm label-info">Accepted</span>';
                                            }
                                            else if($_orderdetailsData->status == '3')
                                            {
                                                $status = '<span class="label label-sm label-success">Completed</span>';
                                            }
                                            else if($_orderdetailsData->status == '4')
                                            {
                                                $status = '<span class="label label-sm label-warning">Ready</span>';
                                            }
                                            else if($_orderdetailsData->status == '5')
                                            {
                                                $status = '<span class="label label-sm label-warning">Dispatch</span>';
                                            }
                                            else if($_orderdetailsData->status == '7')
                                            {
                                                $status = '<span class="label label-sm label-danger">Cancel By Custmer</span>';
                                            }
                                            else if($_orderdetailsData->status == '8')
                                            {
                                                $status = '<span class="label label-sm label-danger">Cancel By Admin</span>';
                                            }
                                            $_orderAllot = '';
                                            if(  $_orderdetailsData->allot_driver_id ==0){
                                                $_orderAllot = '<input type="checkbox" id="order_id_'.$_orderdetailsData->id.'" name="_order_id[]" value="'.$_orderdetailsData->id.'"/>';                                                
                                            } 
                                            
                                            $_orderCancel = '';
                                            if($_orderdetailsData->status == '1'){
                                                $_orderCancel = '<a href="javascript:void(0)" class="btn btn-alt btn-sm btn-success" onclick="return managaeOrderCancel('.$_orderdetailsData->id.');" data-toggle="tooltip" title="" data-original-title="Cancel Order" style="color: white;background-color: red;"><i class="fa fa-times"></i></a>';                                                
                                            }
                                            
                                            //<td>' . $i . '</td>
                                            $_action = '<div id="manageBtnNj" class="block-options">
<a href="'.ADMIN_URL.'eMasters/manageOrderItemDetails/?ord_id='.($_orderdetailsData->id).'" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="View Order Detail" data-original-title="View Order Detail"><i class="fa fa-eye mt-0"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-success" onclick="return managaeOrderStatus('.$_orderdetailsData->id.');" data-toggle="tooltip" title="" data-original-title="Update Order Status"><i class="fa fa-exclamation-triangle"></i></a>'.$_orderCancel.$_orderUpBut.'</div>';
                                            echo '<tr class="parent" id="'.$_orderdetailsData->id.'">
                                            <td><button type="button" class="btn btn-sm btn-circle btn-outline-secondary enable-tooltip" data-toggle="dropdown" title="Show / Hide Product List"><i class="si si-info fa-2x" style="margin-top: -3px;"></i></button></td>
                                            <td>'.$_orderAllot.'</td>
                                            <td> <a href="'.ADMIN_URL.'eMasters/manageDriverDetails/?del_id='.$_orderdetailsData->allot_driver_id.' ">'.ucfirst($_Deliveryboy) .'</a> </td>
                                            <td>' . htmlentities(ucfirst($_orderdetailsData->order_unique_id)) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_name) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_address) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_email) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_phone) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->total_price) . '</td>
                                            <td>' . ($status) . '</td>
                                            <td>' . date("M d, Y", strtotime($_orderdetailsData->added_on)) . '</td>
                                            <td>' . $_action. '</td></tr>';
                                            $_ordId = $_orderdetailsData->id;
                                                $resultItemD = $dbComObj->viewData($conn, "order_item_detail", "*", "1  and order_id = '$_ordId' order by id DESC " . $mainPagination);
                                           
                                            
                                                if ($dbComObj->num_rows($resultItemD) > 0) {
                                                    $i = 0;
                                                    while ($_oderItemDetail = $dbComObj->fetch_object($resultItemD)) {
                                                        $i++;
                                                        $_prdCatData = $dbComObj->fetch_object($dbComObj->viewData($conn, "category", "name", "1 and id='" . $_oderItemDetail->category_id . "'"));
                                                        $_prdTypeData = $dbComObj->fetch_object($dbComObj->viewData($conn, "Product_Type", "type", "1 and id='" . $_oderItemDetail->product_type_id . "'"));
                                                        $_prdNameData = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "*", "1 and id='" . $_oderItemDetail->product_id . "'"));
                                                        $_deliveryNameData = $dbComObj->fetch_object($dbComObj->viewData($conn, "delivery_boy", "name", "1 and id='" . $_oderItemDetail->delivery_id . "'")); 


                                        


                                                
                                                        $_ordMainData = $dbComObj->fetch_object($dbComObj->viewData($conn, "order_detail", "*", "1 and id='" . $_ordId . "'"));



                                                        
                                                        echo'<tr class="child-'.$_orderdetailsData->id.'" id="childRows_'.$_orderdetailsData->id.'" style="display:none;font-weight: 600;border-bottom: 2px solid rgb(61, 87, 126);">
                                                        <td><i class="hi hi-hand-right"></i></td><td colspan = "6">Order ID - '.$_ordMainData->order_unique_id.' | Delivery Name - '.htmlentities(ucfirst( $_deliveryNameData->name)).'| Product Name - '.htmlentities(ucfirst($_prdNameData->name)).' | Quantity - '.$_oderItemDetail->quantity.' | Price - '.$_oderItemDetail->price * $_oderItemDetail->quantity .' | Category Name - '.htmlentities(ucfirst($_prdCatData->name)).' | Product Type - '.htmlentities(ucfirst($_prdTypeData->type)).'  </td><td class="block-options"><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-warning" onclick="return managaeStatusProduct('.$_oderItemDetail->product_id.',' . $_oderItemDetail->order_id . ');" data-toggle="tooltip" title="" data-original-title="Update Item Detail"><i class="fa fa-exclamation-triangle"></i></a></td><td></td>
                                                        </tr>';  
                                                        
                                                    }
                                                }
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
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>

<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style=" display: none;">Open Modal</button>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Single Item Update</h4>
            </div>

            <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Order / Item Status</label> 
                                <select type="text" name="order_status" id="_order_status_main" class="form-control input-sm" required>
                                    

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="order_id" name="order_id" value=""/>
                    <input type="hidden" id="order_item_id" name="order_item_id" value=""/>
                    <input type="hidden" id="todo" name="todo" value="<?php echo base64_encode('updatedOrder') ?>"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_new_Order_Operations.php')" class="btn btn-success srSubmitBtn">Update Status</button>
                </div>
            </form>
            <div id="result_employee"></div>
        </div>

    </div>
</div>

<div id="myModalOrder" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Driver Order Allot</h4>
            </div>

            <form class="" id="form_employee_all" enctype="multipart/form-data" method="post" data-parsley-validate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select Delivery Boy</label> 
                                <select type="text" name="driver_id" id="driver_id" class="form-control input-sm" required>
                                    <option value=""> -- Select Select Delivery Boy --</option>
                                    <?php
                                    $resultDrivr = $dbComObj->viewData($conn, "delivery_boy", "*", "1 and status ='1' order by name ASC ");
                                    if ($dbComObj->num_rows($resultDrivr) > 0) {
                                        while ($_driverData = $dbComObj->fetch_object($resultDrivr)) {
                                            echo '<option value="'.$_driverData->id.'">'.$_driverData->name.'</option>';
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Order Status</label> 
                                <select type="text" name="all_order_status" id="_all_order_status_driver" class="form-control input-sm" required>
                                    <option value=""> -- Select Order Status --</option>
                                    <option value="2">Accepted</option>
                                    <option value="4">Ready</option>
                                    <option value="5">Dispatch</option>
                                    <option value="6">Delivered</option>
                                    <option value="3">Completed</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="all_order_id" name="all_order_id" value=""/>
                    <input type="hidden"  name="todo" value="<?php echo base64_encode('updateAllOrder') ?>"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="empfrm_all" onclick="formSubmit('form_employee_all', 'result_employee_all', '<?php echo ADMIN_URL; ?>_controller/_new_Order_Operations.php')" class="btn btn-success srSubmitBtn">Update Status</button>
                </div>
            </form>
            <div id="result_employee_all"></div>
        </div>

    </div>
</div>

<div id="myModalSignleOrder" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Full Order Update</h4>
            </div>

            <form class="" id="form_employee_ord" enctype="multipart/form-data" method="post" data-parsley-validate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select Delivery Boy</label> 
                                <select type="text" name="driver_id"  class="form-control input-sm" required>
                                    <option value=""> -- Select Select Delivery Boy --</option>
                                    <?php
                                    $resultDrivr = $dbComObj->viewData($conn, "delivery_boy", "*", "1 and status ='1' order by name ASC ");
                                    if ($dbComObj->num_rows($resultDrivr) > 0) {
                                        while ($_driverData = $dbComObj->fetch_object($resultDrivr)) {
                                            echo '<option value="'.$_driverData->id.'">'.$_driverData->name.'</option>';
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Order Status</label> 
                                <select type="text" name="all_order_status" id="_all_order_status" class="form-control input-sm" required>
                                    

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="order_id_single" name="order_id_single" value=""/>
                    <input type="hidden" id="todo" name="todo" value="<?php echo base64_encode('SingleOrderUpdated') ?>"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" onclick="formSubmit('form_employee_ord', 'result_employee_ord', '<?php echo ADMIN_URL; ?>_controller/_new_Order_Operations.php')" class="btn btn-success srSubmitBtn">Update Status</button> 
                </div>
            </form>
            <div id="result_employee_ord"></div>
        </div>

    </div>
</div>
<script>
function getDealsAjax(page)
{
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageOrderDetails/?page='+page+'&count='+<?=$dataAtOne?>;
}

var _urlPage = "_new_Order_Operations.php";

function manageStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Product status has been updated.',e,f,btoa('manageStatus'),'9',_urlPage);
}

function managaeOrderCancel(e)
{
    alertBox("If you want to delete Order! All related data and Product will be removed!",'Yes, delete Order!','Order was removed from system.',e,'',btoa('managaeOrderCancel'),'9',_urlPage);
}

function resetFilter()
{
    let _category_Id = $('#order_Id').val();
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageorderdetails/';
} 
function getPerPage(a){
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageOrderDetails/?page='+<?=$page?>+'&count='+a;
}
$(function() {
    $('tr.parent td button.btn')
        .on("click", function(){
        var idOfParent = $(this).parents('tr').attr('id');
        $('tr.child-'+idOfParent).toggle('slow');
    });
    $('tr[class^=child-]').hide().children('td');
});

function managaeStatusProduct(a, b) {    
    $.post('<?php echo ADMIN_URL;?>_controller/_new_Order_Operations.php', {order_id:b, todo: 'Z2V0T3JkZXJTdGF0dXNEZXRhaWw='}, function (data)  { 
        $("#_order_status_main").html(data);        
    });    
    $('#myModal').modal('show');
    $("#order_item_id").val(a);
    $("#order_id").val(b);
}

function getAllotDrivers(){  
    var checked, checkedValues = new Array();
    checked = $("input[type=checkbox]:checked");
    
    checkedValues = checked.map(function(i) { return $(this).val()}).get();
    if (checked.length) {
        var str = checkedValues.join();
        $("#all_order_id").val((str));
    }
    
    var all_order_id = $("#all_order_id").val();
    if(all_order_id  == ''){
        alert('Please select any checkbox');
        return false;
    }
      
    
    $('#myModalOrder').modal('show');
}
function managaeOrderStatus(a){
    $.post('<?php echo ADMIN_URL;?>_controller/_new_Order_Operations.php', {order_id:a, todo: 'Z2V0T3JkZXJTdGF0dXNEZXRhaWw='}, function (data)  { 
        $("#_all_order_status").html(data);        
    });    
    $('#myModalSignleOrder').modal('show');
    $("#order_id_single").val(a);
}

</script>    
