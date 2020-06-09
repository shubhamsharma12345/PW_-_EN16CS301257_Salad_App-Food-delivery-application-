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

$table = "iz_master_producttype";


if ($operation == "addCategory")
{
    $_POST['productType_Name'] = htmlentities($_POST['productType_Name']);
    unset($_POST['id']);
    $condition = " `productType_Name`='" . $_POST['productType_Name'] . "' and `language_Id` = '".$_POST['language_Id']."'";
    $result = $dbComObj->viewData($conn,$table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0)
    {
        $dates = date("Y-m-d-H-i-s");
        if (isset($_FILES['productType_ImageUrl']['name']) && !empty($_FILES['productType_ImageUrl']['name']))
        {
            $image = $_FILES['productType_ImageUrl'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['productType_Name']);
            $filename = $name . "-" . $dates;
            $pathToSave = "../../admin-assets/images/category/";
            $thumbPathToSave = "../../admin-assets/images/category/thumb/";
            $main_logo = $srFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "../../admin-assets/images/category/" . $main_logo;
            $thumb_logo = $srFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            unset($_FILES['productType_ImageUrl'],$_POST['productType_ImageUrl']);
            $_POST['productType_ImageUrl'] = $main_logo;
        }
        
        $_POST['isActive'] = 0;
        $_POST['productType_Slug'] = 'IZCT-'.strtoupper(preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['productType_Name']));
        
        $dbComObj->addData($conn,$table, $_POST);
        echo "Redirect : Category details added successfully. URL ".ADMIN_URL."eCommerce/manageCategories/?languageid=".$_POST['language_Id'];
    } else {
        echo "Error : Category details already exist.";
    }
}


elseif ($operation == "editCategory")
{
    $_POST['productType_Name'] = htmlentities($_POST['productType_Name']);

    $condition = " `productType_Id`!=" . ($_POST['id']) . " and  `productType_Name`='" . $_POST['productType_Name'] . "' and `language_Id` = '".$_POST['language_Id']."'";
    $result = $dbComObj->viewData($conn,$table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0)
    {
        $dates = date("Y-m-d-H-i-s");
        if (isset($_FILES['productType_ImageUrl']['name']) && !empty($_FILES['productType_ImageUrl']['name']))
        {
            $image = $_FILES['productType_ImageUrl'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['productType_Name']);
            $filename = $name . "-" . $dates;
            $pathToSave = "../../admin-assets/images/category/";
            $thumbPathToSave = "../../admin-assets/images/category/thumb/";
            $main_logo = $srFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "../../admin-assets/images/category/" . $main_logo;
            $thumb_logo = $srFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            unset($_FILES['productType_ImageUrl'],$_POST['productType_ImageUrl']);
            $_POST['productType_ImageUrl'] = $main_logo;
        }

        $conditions = " `productType_Id`='" . ($_POST['id']) . "'";
        unset($_POST['id']);
        //$_POST['productType_Slug'] = 'IZCT-'.strtoupper(preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['productType_Name']));

        $_POST['updated_by'] = $_SESSION['IRIS_SESSION_IDS'];
        $_POST['updated_on'] = date("Y-m-d-H-i-s");
        $dbComObj->editData($conn,$table, $_POST, $conditions);
        echo "Redirect : Category details updated successfully. URL ".ADMIN_URL."eCommerce/manageCategories/?languageid=".$_POST['language_Id'];
    } else {
        echo "Error : Category details already exist.";
    }
} 

elseif ($operation == "managaeStatusCategory")
{
    $condition = " `productType_Id` = '".($_POST['a'])."'";
    $result = $dbComObj->viewData($conn,$table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $data['isActive'] = $_POST['b'];
        $_POST['updated_by'] = $_SESSION['IRIS_SESSION_IDS'];
        $_POST['updated_on'] = date("Y-m-d-H-i-s");
        $dbComObj->editData($conn,$table, $data, $condition);
        echo "Msg : Category status updated.";
    }
    else {
        echo "Error : Category details not found in system.";
    }
}

elseif ($operation == "deleteCategory")
{
    $condition = " `productType_Id` = '".($_POST['a'])."'";
    $result = $dbComObj->viewData($conn,$table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0)
    {
        //$dbComObj->deleteData($conn,$table, $condition);
        echo "Msg : Category deleted.";
    }
    else {
        echo "Error : Category details not found in system.";
    }
}