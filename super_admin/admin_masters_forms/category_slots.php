<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Category Slots| SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');
$start_time = '';
$end_time = '';
$category_id = "";
$todo = base64_encode('addCategoryslots');
if (isset($_GET['a'])) {
    $required = '';   
    
    $id = ($_GET['a']);
    echo $condition = " `id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "timing_slots", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $todo = base64_encode('editCategoryslots');
        $row = $dbComObj->fetch_assoc($qry);
        extract($row);
    }
}   

$_getC = $dbComObj->viewData($conn,"timing_slots", "*","1  order by id DESC");    
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
                    <h2>Manage Add Edit Category</h2>
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
                                <div class="col-md-4">
                                    <label>Start Timing </label> 
                                    <div class="input-group bootstrap-timepicker">                                        
                                        <input type="text" id="example-timepicker-start" name="start_time" class="form-control input-timepicker" value="<?php if($start_time !=''){echo $start_time;}?>">
                                        <span class="input-group-btn">
                                            <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-clock-o"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>End Timing </label> 
                                    <div class="input-group bootstrap-timepicker">                                        
                                        <input type="text" id="example-timepicker-end" name="end_time" class="form-control input-timepicker" value="<?php if($end_time !=''){echo $end_time;}?>">
                                        <span class="input-group-btn">
                                            <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-clock-o"></i></a>
                                        </span>
                                    </div>
                                </div>
                                         
                                 
                            
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_website_category_operations.php')" class="btn btn-success srSubmitBtn">Submit</button>

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

                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th> 
                                        <th data-field="category_name" data-sortable="true">Category</th>
                                        <th data-field="start_time" data-sortable="true">Start Time</th>
                                        <th data-field="end_time" data-sortable="true">End Time</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>

                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "timing_slots", "*", "1 order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_categoryslotsData = $dbComObj->fetch_object($result)) {
                                            $i++;
                                             $_CategoryData = $dbComObj->fetch_object($dbComObj->viewData($conn, "category", "*", "1 and id='".$_categoryslotsData->category_id."'"));
                                             
                                            if($_categoryslotsData->status == '1')
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
<a href="'.ADMIN_URL.'eMasters/manageCategorySlots/?a='.($_categoryslotsData->id).'" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="Edit manageCategorySlots" data-original-title="Edit CategorySlots"><i class="fa fa-pencil mt-0"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-primary" onclick="return manageSlotsStatus('.$_categoryslotsData->id.','.$txtSType.');" data-toggle="tooltip" data-original-title="Enable / Disable  manageCategorySlots"><i class="fa fa-exclamation-triangle"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger" onclick="return deleteCategorySlots('.$_categoryslotsData->id.','.$txtSType.');" data-toggle="tooltip" title="" data-original-title="Delete manageCategorySlots"><i class="fa fa-times"></i></a></div>';
                                            echo '<tr><td>' . $i . '</td><td>' . ($_CategoryData->name) . '</td><td>' . ($_categoryslotsData->start_time) . '</td><td>' . ($_categoryslotsData->end_time) . '</td><td>' . date("M d, Y", strtotime($_categoryslotsData->added_on)) . '</td><td>' . ($status) . '</td><td>' . $_action. '</td></tr>';
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

    
   
<?php 
require_once('../../admin-assets/inc/page_footer.php');
require_once('../../admin-assets/inc/template_scripts.php');
?>

<script>
function getDealsAjax(page)
{
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageCategorySlots/?page='+page;
}

var _urlPage = "_website_category_operations.php";

function manageSlotsStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Store status has been updated.',e,f,btoa('manageSlotsStatus'),'9',_urlPage);
}

function deleteCategorySlots(e)
{
    alertBox("If you want to delete Category! All related data and Category will be removed!",'Yes, delete Category!','Category was removed from system.',e,'',btoa('deleteCategorySlots'),'9',_urlPage);
}

function resetFilter()
{
    let _category_Id = $('#category_Id').val();
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageCategorySlots/';
} 
$(document).ready(function(){
    $('input.input-timepicker').timepicker({
        timeFormat: 'hh:mm',
    });
}); 

</script>    
<?php 
require_once('../../admin-assets/inc/template_end.php');
?>  