2<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Products | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');


$_getC = $dbComObj->viewData($conn,"order_detail", "*","1 and (status='7' OR status='8') order by id DESC");    
$dataAtOne = 10;
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
                <i class="gi gi-notes_2"></i>Manage Cancel Order List <br><small>Cancel Order Details!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Cancel Order List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Order Cancel</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">

                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
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
                                    $result = $dbComObj->viewData($conn, "order_detail", "*", "1 and (status='7' OR status='8') order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_orderdetailsData = $dbComObj->fetch_object($result)) {
                                            $i++;

                                            if ($_orderdetailsData->status == '7') {
                                                $status = '<span class="label label-sm label-danger">Cancel By Custmer</span>';
                                            } else if ($_orderdetailsData->status == '8') {
                                                $status = '<span class="label label-sm label-danger">Cancel By Admin</span>';
                                            }


                                            $_action = '<div id="manageBtnNj" class="block-options">
<a href="' . ADMIN_URL . 'eMasters/manageOrdDetails/?ord_id=' . ($_orderdetailsData->id) . '" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="View Order Detail" data-original-title="View Order Detail"><i class="fa fa-eye mt-0"></i></a>&nbsp;</div>';
                                            echo '<tr class="parent" id="'.$_orderdetailsData->id.'"><td><button type="button" class="btn btn-sm btn-circle btn-outline-secondary enable-tooltip" data-toggle="dropdown" title="Show / Hide Product List"><i class="si si-info fa-2x" style="margin-top: -3px;"></i></button></td>
                                            <td>' . htmlentities(ucfirst($_orderdetailsData->order_unique_id)) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_name) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_address) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_email) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->customer_phone) . '</td>
                                            <td>' . htmlentities($_orderdetailsData->total_price) . '</td>
                                            <td>' . ($status) . '</td>
                                            <td>' . date("M d, Y", strtotime($_orderdetailsData->added_on)) . '</td>
                                            <td>' . $_action . '</td>';

                                            $_ordId = $_orderdetailsData->id;
                                            $resultItemD = $dbComObj->viewData($conn, "order_item_detail", "*", "1  and order_id = '$_ordId' order by id DESC " . $mainPagination);

                                            if ($dbComObj->num_rows($resultItemD) > 0) {
                                                $i = 0;
                                                while ($_oderItemDetail = $dbComObj->fetch_object($resultItemD)) {
                                                    $i++;
                                                    $_prdCatData = $dbComObj->fetch_object($dbComObj->viewData($conn, "category", "name", "1 and id='" . $_oderItemDetail->category_id . "'"));
                                                    $_prdTypeData = $dbComObj->fetch_object($dbComObj->viewData($conn, "Product_Type", "type", "1 and id='" . $_oderItemDetail->product_type_id . "'"));
                                                    $_prdNameData = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "*", "1 and id='" . $_oderItemDetail->product_id . "'"));
                                                    $_ordMainData = $dbComObj->fetch_object($dbComObj->viewData($conn, "order_detail", "*", "1 and id='" . $_ordId . "'"));
                                                    echo'<tr class="child-' . $_orderdetailsData->id . '" id="childRows_' . $_orderdetailsData->id . '" style="display:none;font-weight: 600;border-bottom: 2px solid rgb(61, 87, 126);">
                                                        <td><i class="hi hi-hand-right"></i></td><td colspan = "6">Order ID - ' . $_ordMainData->order_unique_id . ' | Product Name - ' . htmlentities(ucfirst($_prdNameData->name)) . ' | Quantity - ' . $_oderItemDetail->quantity . ' | Price - ' . $_oderItemDetail->price * $_oderItemDetail->quantity . ' | Category Name - ' . htmlentities(ucfirst($_prdCatData->name)) . ' | Product Type - ' . htmlentities(ucfirst($_prdTypeData->type)) . '  </td><td></td>'
                                                    . '</tr>';
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

<script>
$(function() {
    $('tr.parent td button.btn')
        .on("click", function(){
        var idOfParent = $(this).parents('tr').attr('id');
        $('tr.child-'+idOfParent).toggle('slow');
    });
    $('tr[class^=child-]').hide().children('td');
});


</script>    