<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Customer | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');


$_getC = $dbComObj->viewData($conn,"website_app_user", "*","1  order by id DESC");    
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
                <i class="gi gi-notes_2"></i>Manage Category List <br><small>Manage Category!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Category List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Category</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">

                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="name" data-sortable="true">Name</th>
                                        <th data-field="email" data-sortable="true">Email</th>
                                        <th data-field="contact_no" data-sortable="true">Contact No</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "website_app_user", "*", "1 order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_CustomerData = $dbComObj->fetch_object($result)) {
                                            $i++;
                                            if($_CustomerData->status == '1')
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
                                            <a href="'.ADMIN_URL.'eMasters/manageOrdDetails/?ord_id='.($_CustomerData->id).'" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="View Order Detail" data-original-title="View Order Detail"><i class="fa fa-eye mt-0"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-primary" onclick="return manageStatus('.$_CustomerData->id.','.$txtSType.');" data-toggle="tooltip" data-original-title="Enable / Disable  Category"><i class="fa fa-exclamation-triangle"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger" onclick="return deleteCategory('.$_CustomerData->id.','.$txtSType.');" data-toggle="tooltip" title="" data-original-title="Delete Category"><i class="fa fa-times"></i></a></div>';
                                            echo 

                                            '<tr><td>' . $i . '</td>
                                             <td>' . htmlentities($_CustomerData->name) . '</td>
                                             <td>' .$_CustomerData->email .'</td>
                                             <td>' .$_CustomerData->contact .'</td>
                                             <td>' . ($status) . '</td>
                                             <td>' . date("M d, Y", strtotime($_CustomerData->added_on)) . '</td>
                                             <td>' . $_action. '</td></tr>';





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
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageCustomer/?page='+page;
}

var _urlPage = "_website_customer_operations.php";

function manageStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Customer status has been updated.',e,f,btoa('manageStatus'),'9',_urlPage);
}

function deleteCustomer(e)
{
    alertBox("If you want to delete Customer! All related data and Customer will be removed!",'Yes, delete Customer!','Customer was removed from system.',e,'',btoa('deleteCustomer'),'9',_urlPage);
}



</script>    
<?php 
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>  