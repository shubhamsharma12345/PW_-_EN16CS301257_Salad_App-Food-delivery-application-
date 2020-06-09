<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript.php');
$site_title = "Manage New Orders List | Iris Eyewear";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');

$todo = base64_encode('createOrder');
$_categoryData = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : '1';
$_ptypeData = isset($_REQUEST['ptype']) ? $_REQUEST['ptype'] : '1';
if (isset($_GET['a'])) {
    $required = '';
    $id = ($_GET['a']);
    $condition = " `id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "order_detail", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $todo = base64_encode('edidAddOrder');
        $row = $dbComObj->fetch_assoc($qry);
        extract($row);
    }
}
?>
<style>
    .container {
        top: 10%;
        left: 10%;
        right: 0;
        bottom: 0;
    }
    .action {
        width: 400px;
        height: 30px;
        margin: 10px 0;
    }
    .cropped>img {
        margin-right: 10px;
    }
    .imageBox {
        position: relative;
        height: 400px;
        width: 400px;
        border:1px solid #aaa;
        background: #fff;
        overflow: hidden;
        background-repeat: no-repeat;
        cursor:move;
    }
    .imageBox .thumbBox {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        height: 200px;
        margin-top: -100px;
        margin-left: -100px;
        box-sizing: border-box;
        border: 1px solid rgb(102, 102, 102);
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
        background: none repeat scroll 0% 0% transparent;
    }
    .imageBox .spinner {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        text-align: center;
        line-height: 400px;
        background: rgba(0, 0, 0, 0.7);
    }
    img#loading {
        margin: 0 auto;
        text-align: center;
        position: fixed;
        top: 50%;
        left: 50%;
        z-index: 999999;
        position: fixed;
    }
    label.sr-msg-error {
        color: rgba(244, 67, 54, 0.92);
    }
    #search_product {
        color:#000;
        border:solid 1px #000;
        padding:10px;
        font-size:14px;
    }
    #search_product_1 {
        color:#000;
        border:solid 1px #000;
        padding:10px;
        font-size:14px;
    }
    #result {
        position:absolute;
        width:100%;
        padding:10px;
        display:none;
        margin-top:-1px;
        border-top:0px;
        overflow:hidden;
        border:1px #CCC solid;
        background-color: white;
    }
    .show {
        padding:5px;
        border-bottom:1px #999 dashed;
        font-size:15px;
    }
    .show:hover {
        background:#4c66a4;
        color:#FFF;
        cursor:pointer;
    }
    hr {
        margin: 5px 0 !important;
    }

    div#uniform-sameas {
        display: inline-block;
    }
    label.error {
        color: #F44336;
    }
    .input-sm.error {
        border: 1px solid #F44336;
    }
    .loading-overlay{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(183, 173, 173, 0.5) url(https://www.bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/loading.gif) no-repeat center center;
        /*background: rgba(0,0,0,0.5);*/
        display: none;
    }
    div#uniform-orderBy,div#uniform-orderByJ,div#uniform-orderByZ {
        /* margin: 0px !important; */
        width: 20px;
        float: right;
        margin-top: -13px;
    }    
    .showClass{
        display: block !important; 
    }
    .hideClass{
        display: none !important; 
    }
</style>
<!-- Page content -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"/>
<div id="page-content">
    <!-- Forms General Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-notes_2"></i>Manage Add Order <br><small>Manage  Add Order!</small>
            </h1>
        </div>


    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>

    </ul>
    <!-- END Forms General Header -->

    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Add Edit Order </h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                            <div class="row">
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> Select Category  </label>
                                        <select type="text" name="category_id" id="category_id" class="form-control input-sm" required onchange="return getCatData(this.value);">
                                            <?php 
                                              $resultcatData = $dbComObj->viewData($conn, "category", "*", "1 and status='1'");
                                                if ($dbComObj->num_rows($resultcatData) > 0) {
                                                    while ($dataCategory = $dbComObj->fetch_object($resultcatData)) {
                                                        if($_categoryData == $dataCategory->id){$selCat ='selected';}else{$selCat='';}
                                                        echo '<option value="'.$dataCategory->id.'" '.$selCat.'>'.$dataCategory->name.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> Select Product Type </label>
                                        <select type="text" name="product_type_id" id="product_type_id" class="form-control input-sm" required  onchange="return getPTypeData(this.value);">
                                            <?php 
                                              $resultPrdData = $dbComObj->viewData($conn, "Product_Type", "*", "1 and status='1'");
                                                if ($dbComObj->num_rows($resultPrdData) > 0) {
                                                    while ($dataprdType = $dbComObj->fetch_object($resultPrdData)) {
                                                        if($_ptypeData == $dataprdType->id){$selPtype ='selected';}else{$selPtype='';}
                                                        echo '<option value="'.$dataprdType->id.'" '.$selPtype.'>'.$dataprdType->type.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Order Status</label> 
                                        <select type="text" name="order_status" id="order_status" class="form-control input-sm" required>
                                            <option value=""> -- Select Order Status --</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Accepted</option>
                                            <option value="2">Ready</option>
                                            <option value="7">Dispatched</option>
                                            <option value="3">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> Order Type </label> 
                                        <select  name="order_type" id="order_type" class="form-control input-sm" required onchange="return getItems(this.value)">
                                            <option value="Normal">Normal</option>
                                            <option value="Subscription">Subscription</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> Payment Type </label>
                                        <select type="text" name="payment_type" id="payment_type" class="form-control input-sm" required>
                                            <option value=""> -- Payment Type--</option>
                                            <option value="COD">COD</option>
                                            <option value="Online">Online</option>

                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6" i>
                                    <div class="form-group">
                                        <label> Customer Name </label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control input-sm" placeholder="Enter Customer Name" value="" required=""/>
                                    </div>
                                </div>
                                <div class="col-sm-6" i>
                                    <div class="form-group">
                                        <label> Customer Email </label>
                                        <input type="email" name="customer_email" id="customer_email" class="form-control input-sm" placeholder="Enter Customer Email" value="" required=""/>
                                    </div>
                                </div>
                                <div class="col-sm-6" i>
                                    <div class="form-group">
                                        <label> Customer Mobile </label>
                                        <input type="number" name="customer_phone" id="customer_phone" class="form-control input-sm" placeholder="Enter Customer Phone" value="" required=""/>
                                    </div>
                                </div>  

                                <div class="col-sm-6" i>
                                    <div class="form-group">
                                        <label> Customer Address </label>
                                        <input type="text" name="customer_address" id="customer_address" class="form-control input-sm" placeholder="Enter Customer Address" value="" required=""/>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12 showClass" id="normal_div">
                                    <div class="form-group">
                                        <label> Add Menu </label>
                                        <input type="text" name="search_product" id="search_product" class="form-control input-sm ui-autocomplete-input" placeholder="Enter Search Normal Product Here" value="" />

                                        <div class="autocomplete-suggestions"><ul></ul></div>
                                    </div>
                                </div>


                                <div class="col-sm-12 hideClass" id="subs_div">
                                    <div class="form-group">
                                        <label> Add Subscription Menu </label>
                                        <input type="text" name="search_product_1" id="search_product_1" class="form-control input-sm ui-autocomplete-input" placeholder="Enter Search Subscription Product Here" value="" />

                                        <div class="autocomplete-suggestion_1"><ul></ul></div>
                                    </div>
                                </div>

                                

                                <div class="col-xs-12">
                                    <div class="table-responsive text-muted well well-sm no-shadow" >
                                        <div class="col-xs-4">
                                            <h4>Payment Details:</h4>
                                            <textarea rows="4" name="description" id="description" class="form-control input-sm" placeholder="Enter Discription Here"></textarea>
                                        </div>
                                        <div class="col-xs-4" style="float: right;">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:50%">Subtotal:</th>
                                                        <td><i class="fa fa-inr" aria-hidden="true"></i><label id="subtotal_amt">00.00</label></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <td><i class="fa fa-inr" aria-hidden="true"></i><label id="total_amt">00.00</label></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12" id="tableItems" style="display:none;">
                                    <div class="input_fields_wrap">
                                        <table id="status_table" class="table">
                                            <thead name="MyTable">
                                            <th>SNo</th>
                                            <th>Product Name</th>
                                            <th>Product Quantity</th>
                                            <th>Product Full Price</th>
                                            <th>Total</th>
                                            <th>#</th>
                                            </thead>
                                            <tbody name="MyTable" id="addProduct">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12" id="tableItemsubs" style="display:none;">
                                    <div class="input_fields_wrap">
                                        <table id="status_table_s" class="table">
                                            <thead name="MyTable">
                                            <th>SNo</th>
                                            <th>Product Name (Subs)</th>
                                            <th>Subscription Days</th>

                                            <!--<th>Subscription Days</th>-->
                                            <th>Product Quantity</th>
                                            <th>Product Full Price</th>
                                            <th>Total</th>
                                            <th>#</th>
                                            </thead>
                                            <tbody name="MyTable" id="addProduct_s">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" name="allItemsArray" id="allItemsArray" value="0" />
                                    <input type="hidden" name="allItemsArrayS" id="allItemsArrayS" value="0" />
                                    <input type="hidden" name="normal_days" id="normal_days" value="1" />
                                    <input type="hidden" name="subscription_days" id="subscription_days" value="0" />
                                    <input type="hidden" name="itemTotal" id="itemTotal" value="0" />
                                    <input type="hidden" name="itemTotalS" id="itemTotalS" value="0" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_ss_subscription_order.php')" class="btn btn-success srSubmitBtn">Submit</button>

                                </div>
                            </div>
                        </form>
                        <div id="result_employee"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
require_once('../../admin-assets/inc/page_footer.php');
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>  

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyA7Bf-jC-8m0jWBDESWu1qqvtN7nCBiOps"></script>
<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
<script>   
function getCatData(a){
    var pType = $("#product_type_id").val();
    var catId = $("#category_id").val();
    window.location.href = '<?php echo ADMIN_URL.'eOrder/addNewOrder/?cat='?>'+catId+'&ptype='+pType;
} 
function getPTypeData(a){
    var pType = $("#product_type_id").val();
    var catId = $("#category_id").val();
    window.location.href = '<?php echo ADMIN_URL.'eOrder/addNewOrder/?cat='?>'+catId+'&ptype='+pType;
}
var allItemsArray = [];
var allItemsArrayS = [];
var ckbox = $('#sameas');
var rest_id = 0;
var max_fields = 10; //maximum input boxes allowed
var wrapper = $("#addProduct"); //Fields wrapper
var wrapper_s = $("#addProduct_s");
var add_button = $(".add_field_button"); //Add button ID
var x = 1; //initlal text box count

/* Cart Total Details Here*/
var subtotal_amt = '00.00';
var tax_amt = '00.00';
var shipping_amt = '00.00';
var total_amt = '00.00';

$("#search_product").autocomplete({
    source: function (request, response) {
        $.getJSON("<?php echo ADMIN_URL; ?>_controller/_ss_subscription_order.php?todo=<?php echo base64_encode("searchMenuItem"); ?>&cat=<?=$_categoryData;?>&ptype=<?=$_ptypeData;?>", {request: request},
        response);
    },
    open: function () {
        jQuery(this).autocomplete("widget")
        .appendTo(".autocomplete-suggestions")
        .css("position", "static");
    },
    select: function (event, ui)
    {
        if (ui.item.id > 0)
        {
            maanageOrders(ui.item.id, ui.item.menu_id, ui.item.item_full_price, ui.item.name);
        }
        ui.item.value = "";
    },
        position: {my: "left bottom", at: "left top", of: ".ui-autocomplete-input"},
        minLength: 1
});


$("#search_product_1").autocomplete({
    source: function (request, response) {
        $.getJSON("<?php echo ADMIN_URL; ?>_controller/_ss_subscription_order.php?todo=<?php echo base64_encode("searchMenuItemSubs"); ?>&cat=<?=$_categoryData;?>&ptype=<?=$_ptypeData;?>", {request: request},response);
    },
    open: function () {
        jQuery(this).autocomplete("widget")
        .appendTo(".autocomplete-suggestion_1")
        .css("position", "static");
    },
    select: function (event, ui)
    {
        if (ui.item.id > 0)
        {
            maanageOrders_subs(ui.item.id, ui.item.menu_id, ui.item.item_full_price, ui.item.name);
        }
        ui.item.value = "";
        //$('#coupan').prop('selectedIndex', 0);
    },
    position: {my: "left bottom", at: "left top", of: ".ui-autocomplete-input"},
    minLength: 1
});

    function getItems(a) {
        //alert(a);
        if (a == 'Normal') {
            $("#tableItems").css("display", "block");
            $("#tableItemsubs").css("display", "none"); 
            $("#normal_div").removeClass('hideClass');
            $("#normal_div").addClass('showClass');
            $("#subs_div").removeClass('showClass');
            $("#subs_div").addClass('hideClass');
            $("#normal_days").val('1');
            $("#subscription_days").val('0');
        }
        else {            
            $("#tableItems").css("display", "none");
            $("#tableItemsubs").css("display", "block");
            $("#normal_div").removeClass('showClass');
            $("#normal_div").addClass('hideClass');
            $("#subs_div").removeClass('hideClass');
            $("#subs_div").addClass('showClass');
            $("#normal_days").val('0');
            $("#subscription_days").val('1');
        }
    }

    //--------------
    //for subscription days on subscription click
   /* $(document).ready(function(){

    	$('#AddSubscriptionMenu').on('change' , function(){
            var subsID = $(this).val();
            if (subsID){
                $.ajax({

                    type:'POST',
                    url:'ajaxData.php'<?php echo ADMIN_URL; ?>controller/resturant_order_operations.php',',
                    data:'days=' +subsID,
                    success:function(html){
                        $('#SubscriptionDays').html(html);
                    }
                });
            }else {
                $('#SubscriptionDays').html('<option value=""> Selection subscription Days</option>');
            }


        });
    });*/
    //-----------------------
    // For Manage Product add in ROWs
    function maanageOrders(a, b, c, d)
    {
        // a='id'|b='idm'|c='p'|d='n'
        $("#tableItemsubs").css("display", "none");
        $("#tableItems").css("display", "block");
        $('#discount_amt').val($('#discountAmt').val());
        var addrow = '';
        var tableItems = '0';
        var qtyItem = '0';

        $('#itemTotal').val(parseFloat($('#itemTotal').val()) + parseFloat(1));
        tableItems = $('#itemTotal').val();
        if (tableItems > 0)
        {          
            $('#tableItemsubs').hide();
            $('#tableItems').show();  
            $('#orderItemsButton').show();
        }
        if ($('#product_code_' + a).length > 0) {
            qtyItem = $('#product_qty_' + a).val();
            $('#product_qty_' + a).val(parseFloat(qtyItem) + parseFloat(1));
            $('#itemTotal').val(parseFloat($('#itemTotal').val()) - parseFloat(1));
            $('#total_' + a).val(parseFloat($('#product_qty_' + a).val()) * parseFloat($('#product_price_' + a).val()));
            manageTotalAmount(a, 'qty', qtyItem, '1');
            return true;
        }
        else
        {

            allItemsArray.push(a);
            $('#allItemsArray').val(allItemsArray);
            addrow = '<tr name="MyTable" id="item_' + a + '">';
            addrow += '<td>RM00' + a + '<input type="hidden" name="product_code_' + a + '" id="product_code_' + a + '" value="' + a + '" class="form-control input-sm" readonly/><input type="hidden" name="menu_id_' + a + '" id="menu_id_' + a + '" value="' + b + '" class="form-control input-sm" readonly/><input type="hidden" name="multi_key_' + a + '" id="multi_key_' + a + '" value="' + c + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><input type="text" name="product_name_' + a + '" id="product_name_' + a + '" value="' + d + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><input type="number" min="1" pattern="^[0-9]+$" min="1" name="product_qty_' + a + '" id="product_qty_' + a + '" onfocus="this.oldvalue = this.value;" oninput="return manageQtyItems(this.oldvalue,' + a + ',this.value);this.oldvalue = this.value;" value="1" class="QtyItems form-control input-sm"/></td>';
            addrow += '<td><input type="text" name="product_price_' + a + '" id="product_price_' + a + '" value="' + c + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><input type="text" name="total_' + a + '" id="total_' + a + '" value="' + c + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><a href="#" class="remove_field" id="remove_' + a + '" onclick="return removeItem(' + a + ');"><img src="<?php echo ADMIN_URL; ?>cross.png"/></a></td></tr>';
            //console.log(addrow);
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append(addrow); //add input box
                $('#tableItemsubs').css("display", "none");
                $("#tableItems").css("display", "block");
                manageTotalAmount(a, 'add', '0', '0');
            }
        }
        return false;
    }
    //----------------------------
    // manage subscription order
    
    // For Manage Product add in ROWs
    function maanageOrders_subs(a, b, c, d)
    {
        //alert('aaa');   
        $("#tableItems").css("display", "none");
        $("#tableItemsubs").css("display", "block");
        $('#tableItemsubs').show();
        // a='id'|b='idm'|c='p'|d='n'
        $('#discount_amt').val($('#discountAmt').val());
        var addrow = '';
        var tableItems = '0';
        var qtyItem = '0';

        $('#itemTotalS').val(parseFloat($('#itemTotalS').val()) + parseFloat(1));
        tableItems = $('#itemTotalS').val();
        if (tableItems > 0)
        {
            $('#tableItem').hide();
            $('#tableItemsubs').show();
            $('#orderItemsButton').show();
        }
        if ($('#product_code_s_' + a).length > 0) {
            qtyItem = $('#product_qty_s_' + a).val();
            $('#product_qty_s_' + a).val(parseFloat(qtyItem) + parseFloat(1));
            $('#itemTotalS').val(parseFloat($('#itemTotalS').val()) - parseFloat(1));
            $('#total_s_' + a).val(parseFloat($('#product_qty_s_' + a).val()) * parseFloat($('#product_price_s_' + a).val()));
            manageTotalAmountS(a, 'qty', qtyItem, '1');
            return true;
        }
        else
        {

            allItemsArrayS.push(a);
            $('#allItemsArrayS').val(allItemsArrayS);
            addrow = '<tr name="MyTable" id="item_s_' + a + '">';
            addrow += '<td>RM00' + a + '<input type="hidden" name="product_code_s_' + a + '" id="product_code_s_' + a + '" value="' + a + '" class="form-control input-sm" readonly/><input type="hidden" name="menu_id_s_' + a + '" id="menu_id_s_' + a + '" value="' + b + '" class="form-control input-sm" readonly/><input type="hidden" name="multi_key_s_' + a + '" id="multi_key_s_' + a + '" value="' + c + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><input type="text" name="product_name_s_' + a + '" id="product_name_s_' + a + '" value="' + d + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><select name="product_qty_s_' + a + '" id="product_qty_s_' + a + '" onfocus="this.oldvalue = this.value;" oninput="return manageQtyItems_S(this.oldvalue,' + a + ',this.value);this.oldvalue = this.value;" class="QtyItems form-control input-sm"><option value="1">1</option><option value="3">3</option><option value="7">7</option><option value="30">30</option></select></td>';
            addrow += '<td><input type="text" name="product_price_s_' + a + '" id="product_price_s_' + a + '" value="' + c + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><input type="text" name="total_s_' + a + '" id="total_s_' + a + '" value="' + c + '" class="form-control input-sm" readonly/></td>';
            addrow += '<td><a href="#" class="remove_field" id="remove_s_' + a + '" onclick="return removeItem_s(' + a + ');"><img src="<?php echo ADMIN_URL; ?>cross.png"/></a></td></tr>';
            //console.log(addrow);
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper_s).append(addrow); //add input box
                $("#tableItems").css("display", "none");
                $("#tableItemsubs").css("display", "block");
                manageTotalAmountS(a, 'add', '0', '0');
            }
        }
        return false;
    }
    //End Subscription order
    
    
    // For Manage Product delete in ROWs
    function removeItem(a)
    {
        allItemsArray = jQuery.grep(allItemsArray, function (value) {
            return value != a;
        });
        $('#allItemsArray').val(allItemsArray);
        // a='id'|b='idm'|c='p'|d='n'
        alert('remove item from row.');
        var iId = a;
        var menu_id = $('#menu_id_' + a).val();
        var qtyItem = $('#product_qty_' + a).val();
        var iName = $('#product_name_' + a).val();
        manageRemoveTotalAmount(iId, 'qty', qtyItem, iName);

        $('#itemTotal').val(parseFloat($('#itemTotal').val()) - parseFloat(1)); // Manage row count
        //alert($('#itemTotal').val());
        // tableItems = $('#itemTotal').val();
        $('#item_' + a).remove();
        x--;
        return false;
    }
    function removeItem_s(a)
    {
        allItemsArrayS = jQuery.grep(allItemsArrayS, function (value) {
            return value != a;
        });
        $('#allItemsArrayS').val(allItemsArrayS);
        // a='id'|b='idm'|c='p'|d='n'
        alert('remove item from row.');
        var iId = a;
        var menu_id = $('#menu_id_s_' + a).val();
        var qtyItem = $('#product_qty_s_' + a).val();
        var iName = $('#product_name_s_' + a).val();
        manageRemoveTotalAmount(iId, 'qty', qtyItem, iName);

        $('#itemTotalS').val(parseFloat($('#itemTotalS').val()) - parseFloat(1)); // Manage row count
        //alert($('#itemTotalS').val());
        // tableItems = $('#itemTotalS').val();
        $('#item_s_' + a).remove();
        x--;
        return false;
    }

    
    function manageTotalAmountS(a, b, qtyItem, d)
    {
        var Amtt = '0.0';
        var gstAmt = '0.0';
        var rowQ = $('#product_qty_s_' + a).val();
        var rowP = $('#product_price_s_' + a).val();
        var gst = $('#gstTaxAmt').val();
        var oldAmt = '0.0';
        var disAmt = $('#discountAmt').val();
        var subtotal_amt = $('#subtotal_amt').html();
        //tax_amt = $('#tax_amt').html();
        //shipping_amt = $('#shipping_amt').html();
        var total_amt = $('#total_amt').html();
        var fake = '0.0';
        if (b == 'add')
        {
            Amtt = parseFloat(rowQ) * parseFloat(rowP);
            fake = parseFloat(subtotal_amt) - parseFloat('0');
            disAmt = parseFloat(disAmt); // Discount Amount
            gstAmt = (parseFloat(Amtt) + parseFloat(subtotal_amt)) * parseFloat(gst) / parseFloat(100); // Gst Amount

        }
        else if (b == 'qty')
        {
            oldAmt = parseFloat(qtyItem) * parseFloat(rowP);
            Amtt = parseFloat(rowQ) * parseFloat(rowP);
            fake = parseFloat(subtotal_amt) - parseFloat(oldAmt);
            disAmt = parseFloat(disAmt); // Discount Amount
            gstAmt = (parseFloat(Amtt) + parseFloat(fake)) * parseFloat(gst) / parseFloat(100); // Gst Amount
        }

        // shipping_amt = cartCalculationShiiping((parseFloat(Amtt) + parseFloat(fake)));
        //shipping_amt = shipping_amt.toFixed(2);
        $('#subtotal_amt').html((parseFloat(Amtt) + parseFloat(fake)).toFixed(2)); // Manae Sub total
        // $('#tax_amt').html((gstAmt).toFixed(2)); // Manae GST total
        //$('#discount_amt').html((disAmt).toFixed(2)); // Manae Discount total
        //$('#shipping_amt').html(shipping_amt); // Manae Sub total
        $('#total_amt').html((parseFloat(Amtt) + parseFloat(fake)).toFixed(2)); // Manae Sub total

        //reCalaculateCoupan($('#coupan').val()); // manage Coupan calculation
        return true;
    }


//----------------------------
// For Manage All amount into add in ROWs
    function manageTotalAmount(a, b, qtyItem, d)
    {
        var Amtt = '0.0';
        var gstAmt = '0.0';
        var rowQ = $('#product_qty_' + a).val();
        var rowP = $('#product_price_' + a).val();
        var gst = $('#gstTaxAmt').val();
        var oldAmt = '0.0';
        var disAmt = $('#discountAmt').val();
        var subtotal_amt = $('#subtotal_amt').html();
        //tax_amt = $('#tax_amt').html();
        //shipping_amt = $('#shipping_amt').html();
        var total_amt = $('#total_amt').html();
        var fake = '0.0';
        if (b == 'add')
        {
            Amtt = parseFloat(rowQ) * parseFloat(rowP);
            fake = parseFloat(subtotal_amt) - parseFloat('0');
            disAmt = parseFloat(disAmt); // Discount Amount
            gstAmt = (parseFloat(Amtt) + parseFloat(subtotal_amt)) * parseFloat(gst) / parseFloat(100); // Gst Amount

        }
        else if (b == 'qty')
        {
            oldAmt = parseFloat(qtyItem) * parseFloat(rowP);
            Amtt = parseFloat(rowQ) * parseFloat(rowP);
            fake = parseFloat(subtotal_amt) - parseFloat(oldAmt);
            disAmt = parseFloat(disAmt); // Discount Amount
            gstAmt = (parseFloat(Amtt) + parseFloat(fake)) * parseFloat(gst) / parseFloat(100); // Gst Amount
        }

        // shipping_amt = cartCalculationShiiping((parseFloat(Amtt) + parseFloat(fake)));
        //shipping_amt = shipping_amt.toFixed(2);
        $('#subtotal_amt').html((parseFloat(Amtt) + parseFloat(fake)).toFixed(2)); // Manae Sub total
        // $('#tax_amt').html((gstAmt).toFixed(2)); // Manae GST total
        //$('#discount_amt').html((disAmt).toFixed(2)); // Manae Discount total
        //$('#shipping_amt').html(shipping_amt); // Manae Sub total
        $('#total_amt').html((parseFloat(Amtt) + parseFloat(fake)).toFixed(2)); // Manae Sub total

        //reCalaculateCoupan($('#coupan').val()); // manage Coupan calculation
        return true;
    }
 
    //----------------------------
    // For Manage Item Quantity
    function manageQtyItems(a, b, c)
    {


        //alert(c+'---'+c.length);
        if (c.length == 0)
        {
            $('#product_qty_' + b).val(a);
            return false;
        }
        //alert('Item quantity updated.');
        var oldQ = a;
        var Amtt = '0.0';
        var gstAmt = '0.0';

        var rowQ = c;
        var rowP = $('#product_price_' + b).val();
        var gst = $('#gstTaxAmt').val();
        var oldAmt = '0.0';
        var disAmt = $('#discountAmt').val();

        var subtotal_amt = $('#subtotal_amt').html();
        //tax_amt = $('#tax_amt').html();
        //shipping_amt = $('#shipping_amt').html();
        var total_amt = $('#total_amt').html();

        var fake = '0.0';
        oldAmt = parseFloat(oldQ) * parseFloat(rowP);
        Amtt = parseFloat(rowQ) * parseFloat(rowP);
        fake = parseFloat(subtotal_amt) - parseFloat(oldAmt);
        disAmt = parseFloat(disAmt); // Discount Amount
        gstAmt = (parseFloat(Amtt) + parseFloat(fake)) * parseFloat(gst) / parseFloat(100); // Gst Amount
        var shipping_amt = cartCalculationShiiping((parseFloat(Amtt) + parseFloat(fake))); // toFixed(2)
        $('#total_' + b).val(parseFloat($('#product_qty_' + b).val()) * parseFloat($('#product_price_' + b).val()));

        $('#subtotal_amt').html((parseFloat(Amtt) + parseFloat(fake)).toFixed(2)); // Manae Sub total
        $('#total_amt').html((parseFloat(Amtt) + parseFloat(fake))); // Manae Sub total
        return true;
    }
    
    
    function manageQtyItems_S(a, b, c){
        if (c.length == 0)
        {
            $('#product_qty_s_' + b).val(a);
            return false;
        }
        var oldQ = a;
        var Amtt = '0.0';
        var gstAmt = '0.0';

        var rowQ = c;
        var rowP = $('#product_price_s_' + b).val();
        var gst = $('#gstTaxAmt').val();
        var oldAmt = '0.0';
        var disAmt = $('#discountAmt').val();

        var subtotal_amt = $('#subtotal_amt').html();
        //tax_amt = $('#tax_amt').html();
        //shipping_amt = $('#shipping_amt').html();
        var total_amt = $('#total_amt').html();

        var fake = '0.0';
        oldAmt = parseFloat(oldQ) * parseFloat(rowP);
        Amtt = parseFloat(rowQ) * parseFloat(rowP);
        fake = parseFloat(subtotal_amt) - parseFloat(oldAmt);
        disAmt = parseFloat(disAmt); // Discount Amount
        gstAmt = (parseFloat(Amtt) + parseFloat(fake)) * parseFloat(gst) / parseFloat(100); // Gst Amount
        var shipping_amt = cartCalculationShiiping((parseFloat(Amtt) + parseFloat(fake))); // toFixed(2)
        $('#total_s_' + b).val(parseFloat($('#product_qty_s_' + b).val()) * parseFloat($('#product_price_s_' + b).val()));

        $('#subtotal_amt').html((parseFloat(Amtt) + parseFloat(fake)).toFixed(2)); // Manae Sub total
        $('#total_amt').html((parseFloat(Amtt) + parseFloat(fake))); // Manae Sub total
        return true; 
    }
    
    //---------------------------------------
    // For Manage All amount Remove in ROWs
    function manageRemoveTotalAmount(a, b, qtyItem, d)
    {
        var Amtt = '0.0';
        var gstAmt = '0.0';
        var rowP = parseFloat($('#product_price_' + a).val()) * parseFloat(qtyItem);
        var gst = $('#gstTaxAmt').val();
        var oldAmt = '0.0';
        var subtotal_amt = $('#subtotal_amt').html();
        //var tax_amt = $('#tax_amt').html();
        //var shipping_amt = $('#shipping_amt').html();
        var total_amt = $('#total_amt').html();
        var fake = '0.0';
        //var disAmt = $('#discountAmt').val();

        Amtt = (parseFloat(subtotal_amt) - parseFloat(rowP));
        //gstAmt = parseFloat(Amtt) * parseFloat(gst) / parseFloat(100); // Gst Amount
        //disAmt = parseFloat(disAmt); // Discount Amount

        if (Amtt > 0)
        {
            //shipping_amt = cartCalculationShiiping(Amtt); //.toFixed(2)
        }
        else
        {
            //shipping_amt = '0.00';
        }

        $('#subtotal_amt').html((Amtt).toFixed(2)); // Manae Sub total
        //$('#tax_amt').html((gstAmt).toFixed(2)); // Manae Sub total
        //$('#discount_amt').html((disAmt).toFixed(2)); // Manae Discount total
        //$('#shipping_amt').html(shipping_amt); // Manae Sub total
        //$('#total_amt').html(((parseFloat(Amtt)) + parseFloat(gstAmt) - parseFloat(disAmt) + parseFloat(shipping_amt)).toFixed(2)); // Manae Sub total
        $('#total_amt').html((parseFloat(Amtt))); // Manae Sub total

        //reCalaculateCoupan($('#coupan').val()); // manage Coupan calculation

        return true;
    }
    //-----------------------------
    // Loader Js
    function load_bar(x)
    {
        if (x == 0)
        {
            $(document.body).css({"cursor": "default"});
            $("body").css({"cursor": "default"});
        }

        else if (x == 1)
        {
            $(document.body).css({"cursor": "wait"});
            $("body").css({"cursor": "wait"});
        }
        else
        {
            return alert("Wrong argument!");
        }
    }
//-----------------------------------
// Manage Complete Form
    function saveFormData()
    {
        // load_bar(1); //DISABLE clicks and show load_bar
        //$('#loading-overlay').show();
        formSubmit('categoryForm', 'errorMessage', '<?php echo ADMIN_URL; ?>_controller/resturant_order_operations.php');
    <?php echo "string"; ?> // $('#loading-overlay').hide();
        // load_bar(0); //ENABLE clicks and hide load_bar
        return false;
    }

    //--------------------------------------
    // For Manage Coupan Amount


    function reCalaculateCoupan(a)
    {
        //222 $restL->id.'|nj|'.$restL->coupon_discount.'|nj|'.$restL->DisType
        if (a == 0) {
            // clearCouponData();
        }
        else
        {
            var arr = a.split('|nj|');

            var copAmt = arr[1];
            var copType = arr[2];
            copAmt = parseFloat(copAmt);
            $('#coupanAmt').val(copAmt);
            $('#coupanType').val(copType);
            var gstAmt = $('#tax_amt').html(); // Manae Sub total
            var disAmt = $('#discount_amt').html(); // Manae Sub total
            var subAmt = $('#subtotal_amt').html();
            var shipping_amt = cartCalculationShiiping(subAmt);
            if (copType == 'A')
            {
                copAmt = parseFloat(copAmt); // Discount Amount
            }
            else
            {
                copAmt = (parseFloat(subAmt)) * parseFloat(copAmt) / parseFloat(100); // Discount Percentage
            }
            $('#coupan_amt').html((copAmt).toFixed(2)); // Manae Discount total
            //$('#total_amt').html(((parseFloat(subAmt)) - parseFloat(disAmt) + parseFloat(gstAmt) - parseFloat(copAmt) + parseFloat(shipping_amt)).toFixed(2)); // Manae Sub total
            return true;
        }
    }
//--------------------------------------
// For Manage Discount Amount
    $("#calculateDiscount").click(function () {
    //$("#discountAmt").bind('keyup mouseup', function () {
        //console.log($('#discountAmt').val());  
        manageAmounts($('#discountAmt').val());
    });

function manageAmounts(a)
{
    //alert(a);
    var InputDis = $("#discountAmt").val();
    if (a == '') {
        $("#discountAmt").val('0');
    }
    var gstAmt = $('#tax_amt').html(); // Manae Sub total
    var subAmt = $('#subtotal_amt').html();
    if (a != '') {
        var disAmt = parseFloat(a); // Discount Amount
    }
    var shipping_amt = cartCalculationShiiping(subAmt);
    //console.log(shipping_amt);
    $('#discount_amt').html((disAmt).toFixed(2)); // Manage Discount total
    if (a != '') {
        //$('#total_amt').html(((parseFloat(subAmt)) - parseFloat(disAmt) + parseFloat(gstAmt) + parseFloat(shipping_amt)).toFixed(2)); // Manae Sub total
        $('#total_amt').html((parseFloat(subAmt))); // Manae Sub total
    }
    //reCalaculateCoupan($('#coupan').val()); // manage Coupan calculation

    return true;
}
function cartCalculationShiiping(subtotal_amt) {
    subtotal_amt = parseFloat(subtotal_amt, 10);
    subtotal = '00.00';
    if (subtotal_amt < '99' && subtotal_amt > '0')
    {
        subtotal = 40;
    }
    else if (subtotal_amt < '149' && subtotal_amt > '99')
    {
        //console.log(subtotal_amt+'--nj');
        subtotal = 30;
    }
    else if (subtotal_amt > '149')
    {
        subtotal = 0;
    }
    return subtotal;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode
    console.log(keyCode);
    var InputDis = $("#discountAmt").val();

    if (InputDis == '') {
        $("#discountAmt").val('0');
    }
    var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode == 46 && keyCode != 0) || specialKeys.indexOf(keyCode) != -1);


    // var ret = ((inputValue >= 65 && inputValue <= 122 && inputValue != 94  && inputValue != 91  && inputValue != 93 && inputValue != 96 && inputValue != 95 && inputValue != 92) || (inputValue == 32 && inputValue != 0) || (inputValue == 46 && inputValue != 0 ) );
    var inputId = $(e.target).attr("id");
    if (inputId == 'discountAmt') {
        document.getElementById("error").style.display = ret ? "none" : "inline";
    }
    return ret;
}
</script> 