<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Products | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');

$_ordId = $_REQUEST['ord_id'];
$_getC = $dbComObj->viewData($conn, "order_item_detail", "*", "1 and order_id = '$_ordId' order by id DESC");
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
                <i class="gi gi-notes_2"></i>Manage Order Item List <br><small>Order Item Details!</small>
            </h1>
        </div>


    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Order Item List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->


    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Order Item</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">

                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="order_id" data-sortable="true">Order ID</th>
                                        <th data-field="category_id" data-sortable="true">Category Name</th>
                                        <th data-field="product_type_id" data-sortable="true">Product Type</th>
                                        <th data-field="product_id" data-sortable="true">Product Name </th>
                                        <th data-field="quantity" data-sortable="true">Quantity </th>
                                        <th data-field="price" data-sortable="true">Item Price </th>
                                        <th data-field="price" data-sortable="true">Item Status </th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "order_item_detail", "*", "1  and order_id = '$_ordId' order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_oderItemDetail = $dbComObj->fetch_object($result)) {
                                            $i++;
                                            $_prdCatData = $dbComObj->fetch_object($dbComObj->viewData($conn, "category", "name", "1 and id='" . $_oderItemDetail->category_id . "'"));
                                            $_prdTypeData = $dbComObj->fetch_object($dbComObj->viewData($conn, "Product_Type", "type", "1 and id='" . $_oderItemDetail->product_type_id . "'"));
                                            $_prdNameData = $dbComObj->fetch_object($dbComObj->viewData($conn, "product", "*", "1 and id='" . $_oderItemDetail->product_id . "'"));
                                            $_ordMainData = $dbComObj->fetch_object($dbComObj->viewData($conn, "order_detail", "*", "1 and id='" . $_ordId . "'"));

                                            if ($_oderItemDetail->status == '1') {
                                                $status = '<span class="label label-sm label-default">Created</span>';
                                            } else if ($_oderItemDetail->status == '2') {
                                                $status = '<span class="label label-sm label-info">Accepted</span>';
                                            } else if ($_oderItemDetail->status == '3') {
                                                $status = '<span class="label label-sm label-success">Completed</span>';
                                            } else if ($_oderItemDetail->status == '4') {
                                                $status = '<span class="label label-sm label-warning">Ready</span>';
                                            } else if ($_oderItemDetail->status == '5') {
                                                $status = '<span class="label label-sm label-warning">Dispatch</span>';
                                            } else if ($_oderItemDetail->status == '7') {
                                                $status = '<span class="label label-sm label-danger">Cancel By Custmer</span>';
                                            } else if ($_oderItemDetail->status == '8') {
                                                $status = '<span class="label label-sm label-danger">Cancel By Admin</span>';
                                            }
                                            if ($_oderItemDetail->status != 7 && $_oderItemDetail->status != 8) {
                                                $_action = '<div id="manageBtnNj" class="block-options">
                                                <a href="javascript:void(0)" onclick="return getOrderDetailUpdate(' . $_oderItemDetail->product_id . ',' . $_oderItemDetail->order_id . ')" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="Update Order" data-original-title="Update Order"><i class="fa fa-wrench mt-0"></i></a>&nbsp;</div>';
                                            } else {
                                                $_action = '';
                                            }

                                            echo '<tr><td>' . $i . '</td>
                                            <td>' . $_ordMainData->order_unique_id . '</td>
                                            <td>' . htmlentities(ucfirst($_prdCatData->name)) . '</td>
                                            <td>' . htmlentities(ucfirst($_prdTypeData->type)) . '</td>
                                            <td>' . htmlentities(ucfirst($_prdNameData->name)) . '</td>
                                            <td>' . $_oderItemDetail->quantity . '</td>
                                            <td>' . $_oderItemDetail->price * $_oderItemDetail->quantity . '</td>
                                            <td>' . $status . '</td>
                                            <td>' . date("M d, Y", strtotime($_oderItemDetail->added_on)) . '</td>
                                            </tr>';
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
                                    $pg->defaultUrl = ADMIN_URL . 'eMasters/manageCountries/';
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
    function getOrderDetailUpdate(a, b) {
        $('#myModal').modal('show');
        $("#order_item_id").val(a);
        $("#order_id").val(b);
    }
</script>     