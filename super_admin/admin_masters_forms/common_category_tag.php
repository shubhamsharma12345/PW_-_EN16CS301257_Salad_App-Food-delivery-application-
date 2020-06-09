<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Category | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');
$common_category_id = "";
$description = '';

$todo = base64_encode('addCommonCategoryTag');
if (isset($_GET['a'])) {
    $required = '';
    $id = ($_GET['a']);
    $condition = " `id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "common_category_tag", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $todo = base64_encode('editCommonCategoryTag');
        $row = $dbComObj->fetch_assoc($qry);
        extract($row);
    }
}


$_getC = $dbComObj->viewData($conn,"common_category_tag", "*","1  order by id DESC");    
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
                <i class="gi gi-notes_2"></i>Manage Common Category Tag List <br><small>Manage Common Category Tag!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Common Category Tag List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Add Edit Common Category Tag</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <label>Common Category Tag</label>
                                        <select  name="common_category_id" id="common_category_id" class="form-control input-sm" required>
                                            <option value="">Select Common Category Name</option>
                                            <?php
                                            $_getSelcat = $dbComObj->viewData($conn, "common_category", "*", "1 and status='1'");
                                            if ($dbComObj->num_rows($_getSelcat) > 0) {
                                                while ($_rowCatData = $dbComObj->fetch_assoc($_getSelcat)) {
                                                    if($common_category_id == $_rowCatData['id']){$_selCat = 'selected="selected"';}else{$_selCat='';}
                                                    echo "<option value='".$_rowCatData['id']."' ".$_selCat.">".$_rowCatData['name']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                     </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Common Category Tag Description </label> 
                                        <input type="text" name="cat_desc" id="cat_desc" class="form-control input-sm" placeholder="Enter Catgory Description" required value="<?php echo $description; ?>"/>
                                    </div>
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_website_common_category_tag_operations.php')" class="btn btn-success srSubmitBtn">Submit</button>

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
                    <h2>Manage Common Category Tag</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">

                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="common_category_name" data-sortable="true">Common Category Name</th>
                                        <th data-field="description" data-sortable="true">Description</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "common_category_tag", "*", "1 order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_common_category_tag_Data = $dbComObj->fetch_object($result)) {
                                            $i++;
                                           $_ComCatData = $dbComObj->fetch_object($dbComObj->viewData($conn, "common_category", "name", "1 and id='".$_common_category_tag_Data->common_category_id."'"));
                                            
                                           if($_common_category_tag_Data->status == '1')
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
<a href="'.ADMIN_URL.'eMasters/manageCommonCategoryTag/?a='.($_common_category_tag_Data->id).'#BlogData" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="Edit Common Category Tag" data-original-title="Edit Common Category Tag"><i class="fa fa-pencil mt-0"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-primary" onclick="return manageStatus('.$_common_category_tag_Data->id.','.$txtSType.');" data-toggle="tooltip" data-original-title="Enable / Disable  Category"><i class="fa fa-exclamation-triangle"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger" onclick="return deleteCommonCategoryTag('.$_common_category_tag_Data->id.','.$txtSType.');" data-toggle="tooltip" title="" data-original-title="Delete Category"><i class="fa fa-times"></i></a></div>';
                                            echo '<tr><td>' . $i . '</td><td>' . htmlentities($_ComCatData->name) . '</td><td>' . ($_common_category_tag_Data->description) . '</td><td>' . ($status) . '</td><td>' . date("M d, Y", strtotime($_common_category_tag_Data->added_on)) . '</td><td>' . $_action. '</td></tr>';
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
<?php 
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?> 
<script>
function getDealsAjax(page)
{
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageCommonCategoryTag/?page='+page;
}

var _urlPage = "_website_common_category_tag_operations.php";

function manageStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Store status has been updated.',e,f,btoa('manageStatus'),'9',_urlPage);
}

function deleteCommonCategoryTag(e)
{
    alertBox("If you want to delete Common Category Tag! All related data and Common Category Tag will be removed!",'Yes, delete Common Category Tag!','Common Category Tag was removed from system.',e,'',btoa('deleteCommonCategoryTag'),'9',_urlPage);
}

function resetFilter()
{
    let _category_Id = $('#category_Id').val();
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageCommonCategoryTag/';
} 

</script>    
 