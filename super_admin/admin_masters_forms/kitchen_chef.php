<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Kitchen Chef | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');
$chef_name= '';
$chef_description = '';
$todo = base64_encode('addKitchenChef');
if (isset($_GET['a'])) {
    $required = '';
    $id = ($_GET['a']);
    $condition = " `id` = '" . $id . "'";
    $qry = $dbComObj->viewData($conn, "kitchen_chef_data", "*", $condition);
    $num = $dbComObj->num_rows($qry);
    if ($num) {
        $todo = base64_encode('editKitchenChef');
        $row = $dbComObj->fetch_assoc($qry);
        extract($row);
    }
}


$_getC = $dbComObj->viewData($conn,"kitchen_chef_data", "*","1  order by id DESC");    
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
                <i class="gi gi-notes_2"></i>Manage Kitchen Chef List <br><small>Manage Kitchen Chef!</small>
            </h1>
        </div>
        
        
    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Kitchen Chef List</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->
    
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Add Edit Kitchen Chef</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Kitchen Chef Name</label> 
                                        <input type="text" name="chef_name" id="chef_name" class="form-control input-sm" placeholder="Enter Kitchen Chef Name" required value="<?php echo $chef_name; ?>"/>
                                    </div>
                                </div>
                                 
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Kitchen Chef Image 1</label> 
                                        <input type="file" name="image1" id="image1" class="form-control input-sm"   />
                                    </div>
                                </div> 
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Kitchen Chef Image 2</label> 
                                        <input type="file" name="image2" id="image2" class="form-control input-sm" />
                                    </div>
                                </div> 
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Kitchen Chef Image 3</label> 
                                        <input type="file" name="image3" id="image3" class="form-control input-sm" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Kitchen Chef Description </label> 
                                        <textarea type="text" name="chef_description" id="chef_description" class="form-control input-sm" placeholder="Enter Kitchen Chef Description" required ><?php echo $chef_description; ?></textarea>
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_kitchen_chef_dataOperations.php')" class="btn btn-success srSubmitBtn">Submit</button>

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
                    <h2>Manage Kitchen Chef</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">

                        <!--<div id="result1"></div>-->
                        <div class="table-responsive">                            
                            <table id="countryTable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">#</th>
                                        <th data-field="name" data-sortable="true">Chef Name</th>
                                        <th data-field="desc" data-sortable="true">Chef Description</th>
                                        <th data-field="img1" data-sortable="true">Image 1</th>
                                        <th data-field="img2" data-sortable="true">Image 2</th>
                                        <th data-field="img3" data-sortable="true">Image 3</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="addedOn" data-sortable="true">Date</th>
                                        <th data-field="addedOn" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $dbComObj->viewData($conn, "kitchen_chef_data", "*", "1 order by id DESC " . $mainPagination);
                                    $num = $dbComObj->num_rows($result);
                                    if ($num > 0) {
                                        $i = 0;
                                        while ($_ChefData = $dbComObj->fetch_object($result)) {
                                            $i++;
                                            if($_ChefData->status == '1')
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
                                            $_img1= '';
                                            $_img2= '';
                                            $_img3= '';
                                            if(strlen($_ChefData->image_1) >8){
                                                $_img1 = '<a href="'.LOCAL_ROOT.'Chef/'.$_ChefData->image_1.'" target="_blank"><img src="'.LOCAL_ROOT.'Chef/'.$_ChefData->image_1.'" style="width :100px; height :80px;"/><a/>';
                                            }
                                            if(strlen($_ChefData->image_2) >8){
                                                $_img2 = '<a href="'.LOCAL_ROOT.'Chef/'.$_ChefData->image_2.'" target="_blank"><img src="'.LOCAL_ROOT.'Chef/'.$_ChefData->image_2.'" style="width :100px; height :80px;"/><a/>';
                                            }
                                            if(strlen($_ChefData->image_3) >8){
                                                $_img3 = '<a href="'.LOCAL_ROOT.'Chef/'.$_ChefData->image_3.'" target="_blank"><img src="'.LOCAL_ROOT.'Chef/'.$_ChefData->image_3.'" style="width :100px; height :80px;"/><a/>';
                                            }
                                            $_action = '<div id="manageBtnNj" class="block-options">
<a href="'.ADMIN_URL.'eMasters/manageMasterChef/?a='.($_ChefData->id).'#BlogData" class="btn btn-alt btn-sm btn-success" data-toggle="tooltip" title="Edit Chef" data-original-title="Edit Chef"><i class="fa fa-pencil mt-0"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-primary" onclick="return manageStatus('.$_ChefData->id.','.$txtSType.');" data-toggle="tooltip" data-original-title="Enable / Disable  Chef"><i class="fa fa-exclamation-triangle"></i></a><a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger" onclick="return deleteChef('.$_ChefData->id.','.$txtSType.');" data-toggle="tooltip" title="" data-original-title="Delete Chef"><i class="fa fa-times"></i></a></div>';
                                            echo '<tr><td>' . $i . '</td><td>' . htmlentities($_ChefData->chef_name) . '</td><td>' . ($_ChefData->chef_description) . '</td><td>' . $_img1 . '</td><td>' . $_img2 . '</td><td>' . $_img3 . '</td><td>' . ($status) . '</td><td>' . date("M d, Y", strtotime($_ChefData->added_on)) . '</td><td>' . $_action. '</td></tr>';
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
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageMasterChef/?page='+page;
}

var _urlPage = "_kitchen_chef_dataOperations.php";

function manageStatus(e,f)
{
    alertBox("You want to change status!",'Yes, change status!','Store status has been updated.',e,f,btoa('manageStatus'),'9',_urlPage);
}

function deleteChef(e)
{
    alertBox("If you want to delete Chef! All related data and Chef will be removed!",'Yes, delete Chef!','Chef was removed from system.',e,'',btoa('deleteChef'),'9',_urlPage);
}

function resetFilter()
{
    let _category_Id = $('#category_Id').val();
    window.location.href = '<?php echo ADMIN_URL;?>eMasters/manageMasterChef/';
} 

</script>    
<?php 
require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>  