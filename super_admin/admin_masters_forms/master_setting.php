<?php
require_once('../../page_fragment/define.php');
require_once('../../page_fragment/topScript_folders.php');
$site_title = "Manage Seeting ON/OFF | SALAD ADMIN";

require_once('../../admin-assets/inc/config.php');
require_once('../../admin-assets/inc/template_start.php');
require_once('../../admin-assets/inc/page_head.php');
$store_status = '';
$store_off_date = date('m/d/Y');
$todo = base64_encode('addUpdateMasterSetting');


$condition = " 1";
$qry = $dbComObj->viewData($conn, "master_setting", "*", $condition);
$num = $dbComObj->num_rows($qry);
if ($num) {
    $row = $dbComObj->fetch_assoc($qry);
    extract($row);
}

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
                <i class="gi gi-notes_2"></i>Manage  Restaurant On OFF System <br><small>Manage  Restaurant On OFF System!</small>
            </h1>
        </div>


    </div>

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="<?php echo ADMIN_URL; ?>dashboard/">Dashboard</a></li>
        <li>View Restaurant On OFF System</li>
    </ul>
    <!-- END Forms General Header -->
    <!-- Form Example with Blocks in the Grid -->

    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-title">
                    <h2>Manage Add Edit Restaurant On OFF System</h2>
                </div>
                <div class="box-body">
                    <div class="x_content">
                        <form class="" id="form_employee" enctype="multipart/form-data" method="post" data-parsley-validate>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Restaurant On / Off</label> <br/>
                                        <input type="radio" name="store_status" id="store_status"  required value="1" <?php if($num ==0) {echo 'checked';}?> <?php if($store_status == 1){echo 'checked';}?>> &nbsp;&nbsp;ON&nbsp;&nbsp;
                                        <input type="radio" name="store_status" id="store_status_off" value="0" <?php if($store_status == 0){echo 'checked';}?>/> &nbsp;&nbsp;OFF&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Off Date </label> 
                                        <input type="text" name="store_off_date" id="example-datepicker" class="form-control form-control input-sm datepicker" value="<?php echo $store_off_date; ?>"/>
                                    </div>
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="hidden" name="todo" value="<?php echo $todo; ?>" />
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="button" id="empfrm" onclick="formSubmit('form_employee', 'result_employee', '<?php echo ADMIN_URL; ?>_controller/_website_setting_operations.php')" class="btn btn-success srSubmitBtn">Submit</button>

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


    <!-- END Form Example with Blocks in the Grid -->
</div>
<?php
require_once('../../admin-assets/inc/page_footer.php');

require_once('../../admin-assets/inc/template_scripts.php');
require_once('../../admin-assets/inc/template_end.php');
?>  
<script>
    $(function () {
        $('#example-datepicker').datepicker({
            startDate: '-0m'
                    //endDate: '+2d'
        }).on('changeDate', function (ev) {
        });

    })
</script>    