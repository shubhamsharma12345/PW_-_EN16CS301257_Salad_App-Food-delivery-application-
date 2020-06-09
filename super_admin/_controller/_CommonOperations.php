<?php
include '../../page_fragment/define.php'; 
include '../../page_fragment/topScript.php';




$njFileObj = new njFile();
$operation = "";
if (isset($_POST['todo'])) {
    $operation = base64_decode($_POST['todo']);
    unset($_POST['todo']);
} elseif (isset($_GET['todo'])) {
    $operation = base64_decode($_GET['todo']);
    unset($_GET['todo']);
}
if($operation == 'getCategoryPower')
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_master_power', "*", "1 and `productType_Id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_powers = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_powers->power_Id.'">'.$_powers->power_Name.'</option>';
        }        
    }
}



else if($operation == 'getMastersGroupIDCode')
{
    $_ids = $_POST['ids'];
    $_CatId = $_POST['CatId'];    
    $_sendData =0;
    $result = $dbComObj->viewData($conn,'iz_admin_website_menuFilter', "*", "1 and `category_Id` = $_CatId and `group_Id` = $_ids");
    if ($dbComObj->num_rows($result) > 0)
    {
        $_frameGL = $dbComObj->fetch_object($result);
        $filterMenu_Id = $_frameGL->filterMenu_Id;
        $filterCode = $_frameGL->filterCode;
        $_sendData = $filterMenu_Id.'@'.$filterCode;
    }else{
        $_sendData = 0;
    }
    echo $_sendData;
}

else if($operation == 'getMenuLabel')
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_admin_website_menuLabel', "*", "1 and `menu_Id` in ($_ids) order by label_Id asc");
    $resultG = $dbComObj->viewData($conn,'iz_admin_website_menuLabel', "*", "1 and `genderM_Id` in ($_ids)  order by label_Id asc");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_subLabel = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_subLabel->label_Id.'">'.$_subLabel->labelText.'</option>';
        }        
    }
    else if ($dbComObj->num_rows($resultG) > 0)
    {
        while($_subLabel = $dbComObj->fetch_object($resultG))
        {
            echo '<option value="'.$_subLabel->label_Id.'">'.$_subLabel->labelText.'</option>';
        }        
    }
    else
    {
        echo '<option value="0" selected> -- NA -- </option>';
    }
}

elseif($operation == "getMenuCategoryGender")
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_admin_website_menuGender', "*", "1 and `menu_Id` in ($_ids) order by position");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_subMenuGen = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_subMenuGen->genderM_Id.'">'.$_subMenuGen->genderText.'</option>';
        }        
    }
    else
    {
        echo '<option value="0" selected> -- NA -- </option>';
    }
}

else if($operation == 'getMenuFilters')
{
    $_ids = $_POST['ids'];
    $language_Id = $_POST['lang'];
    $result = $dbComObj->viewData($conn,'iz_admin_website_menuFilter', "*", "1 and `language_Id` = '".$language_Id."' and `category_Id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_filterD = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_filterD->filterMenu_Id.'">'.$_filterD->filterText.' ('.$_filterD->filterCode.')</option>';
        }        
    }
}


else if($operation == 'getLensSubType')
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_master_cl_lensubtype', "*", "1 and `lensType_id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_subLens = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_subLens->lensSubType_Id.'">'.$_subLens->subLensType_Name.'</option>';
        }        
    }
}

else if($operation == 'getMastersTypes')
{
    $_table = 'iz_master_'.$_POST['ids'];

    $_ids = '0';
    $_txt = '0';
    $_pids = '';
    $_optonal = '';
    if($_POST['ids'] == 'brand')
    {
        $_ids = 'brand_Id';
        $_txt = 'brand_Name';
        $_pids = 'brand_Id';
        $_optonal = " and `brand_Type` = '0'";
    }
    elseif($_POST['ids'] == 'cl_brand')
    {
        $_table = 'iz_master_brand';
        $_ids = 'brand_Id';
        $_txt = 'brand_Name';
        $_pids = 'brand_Id';
        $_optonal = " and `brand_Type` = 'CL'";
    }
    elseif($_POST['ids'] == 'frameshape')
    {
        $_ids = 'shape_Id';
        $_txt = 'shape_Name';
        $_pids = 'frameShape_Id';
    }
    elseif($_POST['ids'] == 'frametype')
    {
        $_ids = 'frametype_Id';
        $_txt = 'frametype_Name';
        $_pids = 'frametype_Id';
    }
    elseif($_POST['ids'] == 'framematerial')
    {
        $_ids = 'material_id';
        $_txt = 'material_Name';
        $_pids = 'material_id';
    }

    elseif($_POST['ids'] == 'cl_disposability')
    {
        $_ids = 'disposabilityType_Id';
        $_txt = 'disposabilityType_Name';
        $_pids = 'disposabilityType_Id';
    }
    elseif($_POST['ids'] == 'cl_lenscolor')
    {
        $_ids = 'lensColor_id';
        $_txt = 'lensColor_Name';
        $_pids = 'lensColor_id';
    }
   
    $result = $dbComObj->viewData($conn,$_table, "*", "1 ".$_optonal." and `language_Id` = '".$_POST['lang']."'");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_datss = $dbComObj->fetch_assoc($result))
        {
            echo "1 and `".$_pids."` = '".$_datss[$_ids]."'<br/>";
            $_getCount = $dbComObj->viewData($conn,"iz_master_products", "*", "1 and `".$_pids."` = '".$_datss[$_ids]."'");
            echo '<option value="'.$_datss[$_ids].'">'.$_datss[$_txt].' ('.$dbComObj->num_rows($_getCount).')</option>';
        }        
    }
}

else if ($operation == "getColorsCode")
{
    $_ids = '';
    foreach($_POST['ids'] as $val)
    {
        $_ids .= $val.',';        
    }
    $_ids = rtrim($_ids,',');
    $result = $dbComObj->viewData($conn,'iz_master_framecolors', "*", "1 and `colors_Id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        $_colorMxData = '';
        while($_colors = $dbComObj->fetch_object($result))
        {
            if($_colors->color_type == 0)
            {
                $_arrC = explode('),',$_colors->colors_Hexa);
                $_colorMxData = '<span class="colorIcons" id="colorHD_'.$_colors->colors_Id.'"><span class="colorIcons_colorTop" style="background: '.$_colors->colors_Hexa.';"><span class="colorIcons_colorBottom" style="background: '.$_colors->colors_Hexa.';"></span></span></span>';
                if(count($_arrC) == 2)
                {
                    $_colorMxData = '<span class="colorIcons" id="colorHD_'.$_colors->colors_Id.'"><span class="colorIcons_colorTop" style="background: '.$_arrC[0].');"><span class="colorIcons_colorBottom" style="background: '.$_arrC[1].';"></span></span></span>';
                }
            }
            else
            {
                $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background-image:url( '.BASE_URL.'admin-assets/images/color/'.$_colors->colors_Hexa.');"><span class="colorIcons_colorBottom" style="background-image:url( '.BASE_URL.'admin-assets/images/color/'.$_colors->colors_Hexa.');"></span></span></span>';
                $_colorSgData = '<img src="'.BASE_URL.'admin-assets/images/color/'.$_colors->colors_Hexa.'" style="width:15px;"/>';
            } 
            echo $_colorMxData;
        }        
    }
}

else if ($operation == "getColorsCodeSingle")
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_master_framecolors', "*", "1 and `colors_Id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        $_colorMxData = '';
        while($_colors = $dbComObj->fetch_object($result))
        {
            if($_colors->color_type == 0)
            {
                $_arrC = explode('),',$_colors->colors_Hexa);
                $_colorMxData = '<span class="colorIcons" id="colorHD_'.$_colors->colors_Id.'"><span class="colorIcons_colorTop" style="background: '.$_colors->colors_Hexa.';"><span class="colorIcons_colorBottom" style="background: '.$_colors->colors_Hexa.';"></span></span></span>';
                if(count($_arrC) == 2)
                {
                    $_colorMxData = '<span class="colorIcons" id="colorHD_'.$_colors->colors_Id.'"><span class="colorIcons_colorTop" style="background: '.$_arrC[0].');"><span class="colorIcons_colorBottom" style="background: '.$_arrC[1].';"></span></span></span>';
                }
            }
            else
            {
                $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background-image:url( '.BASE_URL.'admin-assets/images/color/'.$_colors->colors_Hexa.');"><span class="colorIcons_colorBottom" style="background-image:url( '.BASE_URL.'admin-assets/images/color/'.$_colors->colors_Hexa.');"></span></span></span>';
                $_colorSgData = '<img src="'.BASE_URL.'admin-assets/images/color/'.$_colors->colors_Hexa.'" style="width:15px;"/>';
            } 
            echo $_colorMxData;
        }        
    }
}


else if($operation == 'getCategoryType')
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_master_productsubtype', "*", "1 and `productType_Id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        $_colorMxData = '';
        while($_frameCT = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_frameCT->productSubType_Id.'" '.$_sel.'>'.$_frameCT->subType_Name.'</option>';
        }        
    }
}

else if($operation == 'getFrameSizeMeasure')
{
    $_ids = $_POST['ids'];
    $result = $dbComObj->viewData($conn,'iz_master_framesizemeasure', "*", "1 and `frameSize_id` in ($_ids)");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_frameFMT = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_frameFMT->frameMeasure_id.'" '.$_sel.'>'.$_frameFMT->frameMeasure_Name.'</option>';
        }        
    }
}

else if($operation == 'getGlassPower')
{
    $_ids = $_POST['ids'];
    $_countyids = $_POST['cids'];
    $result = $dbComObj->viewData($conn,'iz_master_lens', "*", "1 and `country_Id` in ($_countyids) and `power_Id` in ($_ids) and `isActive` = '1'");
    if ($dbComObj->num_rows($result) > 0)
    {
        while($_frameGL = $dbComObj->fetch_object($result))
        {
            echo '<option value="'.$_frameGL->lens_Id.'" '.$_sel.'>'.$_frameGL->lens_Name.'</option>';
        }        
    }
}

else if($operation == 'getColorContainerEyeGlass')
{
    $_x = $_POST['x'];
    $_g = $_POST['g'];
    $_id = $_POST['e'];

    $_colorsD = $dbComObj->fetch_object($dbComObj->viewData($conn,'iz_master_framecolors', "colors_Name,colors_Hexa,colors_Id", "1 and `colors_Id` = '".$_id."'"));
    $_arrC = explode('),',$_colorsD->colors_Hexa);
    $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background: '.$_colorsD->colors_Hexa.';"><span class="colorIcons_colorBottom" style="background: '.$_colorsD->colors_Hexa.';"></span></span></span>';
    if(count($_arrC) == 2)
    {
        $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background: '.$_arrC[0].');"><span class="colorIcons_colorBottom" style="background: '.$_arrC[1].';"></span></span></span>';
    }

    echo '<div class="block" id="tags_'.$_g.'_'.$_id.'">
            <div class="block-title">
                <h2>Product Details <b>(<span id="colorName_'.$_g.'_'.$_x.'">'.$_colorsD->colors_Name.'</span> '.$_colorMxData.')</b> <div class="col-md-2"><div onclick="return remove_Tag('.$_x.','.$_g.','.$_id.')" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></div></div></h2>
            </div>
            <div class="box box-default">
                <div class="box-body">
                    <fieldset style="margin-top: -35px;">
                        <legend><i class="fa fa-angle-right"></i> Product Available Details</legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product Virual Try On <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNAvailable['.$_x.']" id="product_TNAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> Try on Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNAvailable['.$_x.']"  id="product_TNAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> Try on Available
                            </label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product Try at Home <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNHAvailable['.$_x.']" id="product_TNHAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> Try on Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNHAvailable['.$_x.']"  id="product_TNHAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> Try on Available
                            </label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product COD <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_CODAvailable['.$_x.']" id="product_CODAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> COD Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_CODAvailable['.$_x.']"  id="product_CODAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> COD Available
                            </label>
                            </div>
                        </div>
                    </div>
                    
                    <fieldset>
                        <legend style="padding: 0px !important;"><i class="fa fa-angle-right"></i> Product Setting</legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="material-text2">180 View</label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-checkbox">
                                <input type="checkbox" class="css-control-input" name="product_male180[]" checked="checked" id="product_male180_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> 180 View Male
                            </label>
                            <label class="css-control css-control-secondary css-checkbox">
                                <input type="checkbox" class="css-control-input" name="product_female180[]" checked="checked" id="product_female180_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> 180 View Female
                            </label>
                            </div>
                        </div>
                        <input type="hidden" id="_currentColor_'.$_x.'" name="_currentColor_'.$_x.'" value="'.$_id.'" />
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>               
        </div>';
}

else if($operation == 'getCountryTabs')
{
    $_Ids = $_POST['ids'];
    $_countryD = $dbComObj->fetch_object($dbComObj->viewData($conn,"iz_master_country","*","1 and `country_Id` = '".$_Ids."'"));
        echo '<div id="countrySpan_'.$_Ids.'" class="col-md-12" style="margin-top: -20px;">
            <fieldset>
                <legend><i class="fa fa-angle-right"></i> ('.$_countryD->country_Name.') Product Price Details</legend>
            </fieldset>
            <div class="row">    
                <div class="col-md-3 form-group">
                    <div class="form-material floating open">                                        
                        <input type="number" id="lens_marketPrice_'.$_Ids.'" name="lens_marketPrice_'.$_Ids.'" required="required" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="5" min="1" class="form-control" value="0"/>
                        <label for="lens_marketPrice">Glass Lens Market Price <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 form-group">
                    <div class="form-material floating open">                                        
                        <input type="number" id="lens_izPrice_'.$_Ids.'" name="lens_izPrice_'.$_Ids.'" oninput="return manageDiscount('.$_Ids.')" required="required" class="form-control" onkeyup="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" min="1" maxlength="5" value="0"/>
                        <label for="lens_izPrice">Glass Lens Iris Price <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 form-group">
                    <div class="form-material floating open">                                        
                        <input type="number" id="lens_discount_'.$_Ids.'" oninput="return manageDiscount('.$_Ids.')" name="lens_discount_'.$_Ids.'" required="required" class="form-control" min="0" max="100" value="0"/>
                        <label for="lens_discount">Discount Percentage (%)</label>
                    </div>
                </div>
                <div class="col-md-3 form-group">
                    <div class="form-material floating open">                                        
                        <input type="text" id="lens_discountPrice_'.$_Ids.'" readonly name="lens_discountPrice_'.$_Ids.'" class="form-control" value="0"/>
                        <label for="lens_discountPrice">Discount Price (%)</label>                        
                    </div>
                </div><div class="clearfix"></div>
            </div></div>';
}


else if($operation == 'getColorContainerSunGlass')
{
    $_x = $_POST['x'];
    $_g = $_POST['g'];
    $_id = $_POST['e'];

    $_colorsD = $dbComObj->fetch_object($dbComObj->viewData($conn,'iz_master_framecolors', "colors_Name,colors_Hexa,colors_Id", "1 and `colors_Id` = '".$_id."'"));
    
    if($_colorsD->color_type == 0)
    {
        $_arrC = explode('),',$_colorsD->colors_Hexa);
        $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background: '.$_colorsD->colors_Hexa.';"><span class="colorIcons_colorBottom" style="background: '.$_colorsD->colors_Hexa.';"></span></span></span>';
        if(count($_arrC) == 2)
        {
            $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background: '.$_arrC[0].');"><span class="colorIcons_colorBottom" style="background: '.$_arrC[1].';"></span></span></span>';
        }
    }
    else
    {
        $_colorMxData = '<span class="colorIcons"><span class="colorIcons_colorTop" style="background-image:url( '.BASE_URL.'admin-assets/images/color/'.$_colorsD->colors_Hexa.');"><span class="colorIcons_colorBottom" style="background-image:url( '.BASE_URL.'admin-assets/images/color/'.$_colorsD->colors_Hexa.');"></span></span></span>';
        $_colorSgData = '<img src="'.BASE_URL.'admin-assets/images/color/'.$_colorsD->colors_Hexa.'" style="width:15px;"/>';
    }

    echo '<div class="block" id="tags_'.$_g.'_'.$_id.'">
            <div class="block-title">
                <h2>Product Details <b>(<span id="colorName_'.$_g.'_'.$_x.'">'.$_colorsD->colors_Name.'</span> '.$_colorMxData.')</b> <div class="col-md-2"><div onclick="return remove_Tag('.$_x.','.$_g.','.$_id.')" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></div></div></h2>
            </div>
            <div class="box box-default">
                <div class="box-body">
                    <fieldset style="margin-top: -35px;">
                        <legend><i class="fa fa-angle-right"></i> Product Available Details</legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product Virual Try On <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNAvailable['.$_x.']" id="product_TNAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> Try on Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNAvailable['.$_x.']"  id="product_TNAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> Try on Available
                            </label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product Try at Home <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNHAvailable['.$_x.']" id="product_TNHAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> Try on Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_TNHAvailable['.$_x.']"  id="product_TNHAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> Try on Available
                            </label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product COD <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_CODAvailable['.$_x.']" id="product_CODAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> COD Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="product_CODAvailable['.$_x.']"  id="product_CODAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> COD Available
                            </label>
                            </div>
                        </div>
                    </div>
                    
                    <fieldset>
                        <legend style="padding: 0px !important;"><i class="fa fa-angle-right"></i> Product Setting</legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="material-text2">180 View</label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-checkbox">
                                <input type="checkbox" class="css-control-input" name="product_male180[]" checked="checked" id="product_male180_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> 180 View Male
                            </label>
                            <label class="css-control css-control-secondary css-checkbox">
                                <input type="checkbox" class="css-control-input" name="product_female180[]" checked="checked"  id="product_female180_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> 180 View Female
                            </label>
                            </div>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="material-text2">Product Power Sunglass <span class="text-danger">*</span></label>
                            <div class="floating open">                                        
                            <label class="css-control css-control-secondary css-radio">
                            <input type="radio" class="css-control-input" name="product_PGAvailable['.$_x.']"  id="product_PGAvailable_No_'.$_id.'" checked="checked" value="0">
                                <span class="css-control-indicator"></span> Power Sunglass Not Available
                            </label>
                            <label class="css-control css-control-secondary css-radio">
                            <input type="radio" class="css-control-input" name="product_PGAvailable['.$_x.']"  id="product_PGAvailable_Yes_'.$_id.'" value="1">
                                <span class="css-control-indicator"></span> Power Sunglass Available
                            </label>
                            </div>
                        </div>
                        <input type="hidden" id="_currentColor_'.$_x.'" name="_currentColor_'.$_x.'" value="'.$_id.'" />
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>               
        </div>';
}
