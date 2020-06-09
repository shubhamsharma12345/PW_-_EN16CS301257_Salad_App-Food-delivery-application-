<?php

include('../../page_fragment/define.php');
include ('../../page_fragment/dbConnect.php');
include ('../../page_fragment/dbGeneral.php');
include ('../../page_fragment/njGeneral.php');
include ('../../page_fragment/njFile.php');
include ('../../page_fragment/njImportAPI.php');

$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$njFileObj = new njFile();
$conn = $dbConObj->dbConnect();

$operation = "";
if (isset($_POST['todo'])) {
    $operation = base64_decode($_POST['todo']);
    unset($_POST['todo']);
} elseif (isset($_GET['todo'])) {
    $operation = base64_decode($_GET['todo']);
    unset($_GET['todo']);
}

$table = "product";

if ($operation == "addProduct") {
    $condition = " `name`='" . htmlentities($_POST['name']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    $dates = date('Y-m-d-H-i-s');
    if ($num == 0) {
        if (isset($_FILES['image1']['name']) && !empty($_FILES['image1']['name'])) {
            $image = $_FILES['image1'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['name']));
            $filename = $name . "-1-" . $dates;
            $pathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/";
            $thumbPathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/thumb/";
            $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/" . $main_logo;
            $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $_POSTAdd['image1'] = $main_logo;
        }
        if (isset($_FILES['image2']['name']) && !empty($_FILES['image2']['name'])) {
            $image2 = $_FILES['image2'];
            $name2 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['name']));
            $filename2 = $name2 . "-2-" . $dates;
            $pathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/";
            $thumbPathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/thumb/";
            $main_logo2 = $njFileObj->uploadImage($image2, $filename2, $pathToSave2);
            $image_source2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/" . $main_logo2;
            $thumb_logo2 = $njFileObj->resizeImage($image_source2, $filename2, $thumbPathToSave2);
            $_POSTAdd['image2'] = $main_logo2;
        }
        if (isset($_FILES['image3']['name']) && !empty($_FILES['image3']['name'])) {
            $image3 = $_FILES['image3'];
            $name3 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['name']));
            $filename3 = $name3 . "-3-" . $dates;
            $pathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/";
            $thumbPathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/thumb/";
            $main_logo3 = $njFileObj->uploadImage($image3, $filename3, $pathToSave3);
            $image_source3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/" . $main_logo3;
            $thumb_logo3 = $njFileObj->resizeImage($image_source3, $filename3, $thumbPathToSave3);
            $_POSTAdd['image3'] = $main_logo;
        }
        $_POSTAdd['name'] = htmlentities($_POST['name']);
        $_POSTAdd['category_id'] = $_POST['category_id'];
        $_POSTAdd['chef_id'] = $_POST['chef_id'];
        $_POSTAdd['product_type_id'] = $_POST['product_type_id'];
        $_POSTAdd['quantity'] = $_POST['quantity'];
        $_POSTAdd['price'] = $_POST['price'];
        $_POSTAdd['description'] = htmlentities($_POST['description']);
        $_POSTAdd['added_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
        $dbComObj->addData($conn, $table, $_POSTAdd);
        echo "Reload : Product details added successfully.";
    } else {
        echo "Error : Product name already exist in system. Please try again with diffrent Product name .";
    }
} elseif ($operation == "editProduct") {
    $type_id = ($_POST['id']);
    unset($_POST['id']);
    $condition = " `id`!=" . $type_id . " and `name`='" . htmlentities($_POST['name']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num == 0) {
        $dates = date('Y-m-d-H-i-s');

        if (isset($_FILES['image1']['name']) && !empty($_FILES['image1']['name'])) {
            $image = $_FILES['image1'];
            $name = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['name']));
            $filename = $name . "-1-" . $dates;
            $pathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/";
            $thumbPathToSave = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/thumb/";
            $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
            $image_source = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/" . $main_logo;
            $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
            $_POSTAdd['image1'] = $main_logo;
        }
        if (isset($_FILES['image2']['name']) && !empty($_FILES['image2']['name'])) {
            $image2 = $_FILES['image2'];
            $name2 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['name']));
            $filename2 = $name2 . "-2-" . $dates;
            $pathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/";
            $thumbPathToSave2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/thumb/";
            $main_logo2 = $njFileObj->uploadImage($image2, $filename2, $pathToSave2);
            $image_source2 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/" . $main_logo2;
            $thumb_logo2 = $njFileObj->resizeImage($image_source2, $filename2, $thumbPathToSave2);
            $_POSTAdd['image2'] = $main_logo2;
        }
        if (isset($_FILES['image3']['name']) && !empty($_FILES['image3']['name'])) {
            $image3 = $_FILES['image3'];
            $name3 = preg_replace('/[^a-zA-Z0-9_]/', '-', htmlentities($_POST['name']));
            $filename3 = $name3 . "-3-" . $dates;
            $pathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/";
            $thumbPathToSave3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/thumb/";
            $main_logo3 = $njFileObj->uploadImage($image3, $filename3, $pathToSave3);
            $image_source3 = "/var/www/html/bookshippingtrucks.com/Projects-Works/SALAD-APP/admin-assets/images/Product/" . $main_logo3;
            $thumb_logo3 = $njFileObj->resizeImage($image_source3, $filename3, $thumbPathToSave3);
            $_POSTAdd['image3'] = $main_logo;
        }
        $_POSTAdd['name'] = htmlentities($_POST['name']);
        $_POSTAdd['category_id'] = $_POST['category_id'];
        $_POSTAdd['chef_id'] = $_POST['chef_id'];
        $_POSTAdd['product_type_id'] = $_POST['product_type_id'];
        $_POSTAdd['quantity'] = $_POST['quantity'];
        $_POSTAdd['price'] = $_POST['price'];
        $_POSTAdd['description'] = htmlentities($_POST['description']);
        $_POSTAdd['updated_by'] = $_SESSION['SALAD_SESSION_IDS'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd, "1 and `id`=" . $type_id . "");
        echo "Redirect : Product etails updated successfully. URL " . ADMIN_URL . "eMasters/manageProduct/";
    } else {
        echo "Error : Product name already exist in system. Please try again with diffrent name";
    }
} elseif ($operation == "manageStatus") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $_POSTAdd['status'] = $_POST['b'];
        $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
        $dbComObj->editData($conn, $table, $_POSTAdd, $condition);
        echo "Msg : status updated.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} elseif ($operation == "deleteProduct") {
    $condition = " `id` = '" . ($_POST['a']) . "'";
    $result = $dbComObj->viewData($conn, $table, "*", $condition);
    $num = $dbComObj->num_rows($result);
    if ($num > 0) {
        $dbComObj->deleteData($conn, $table, $condition);
        echo "Msg : Product name removed from system.";
    } else {
        echo "Msg : Some error occuored. Please try again.";
    }
} 
