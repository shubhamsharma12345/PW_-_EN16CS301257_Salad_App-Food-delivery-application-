<?php

include '../page_fragment/define.php';
include ('../page_fragment/dbConnect.php');
include ('../page_fragment/dbGeneral.php');
include ('../page_fragment/njGeneral.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$conn = $dbConObj->dbConnect();

date_default_timezone_set("Asia/Kolkata");
$data = array_merge($_POST, json_decode(file_get_contents('php://input'), true));
if (!empty($data)) {

    $method = $data['method'];
    if ($method == 'Web_User_Signup') {
        $name = $data['name'];
        $email = $data['email'];
        $mobile = $data['mobile'];
        $password = $data['password'];

        $customer_detail = $dbComObj->viewData($conn, "website_app_user", "*", "1 and contact='$mobile' and email='$email'");
        if ($dbComObj->num_rows($customer_detail) == 0) {

            $_POSTAdd['name'] = $name;
            $_POSTAdd['contact'] = $mobile;
            $_POSTAdd['email'] = $email;
            $_POSTAdd['password'] = $password;
            $_POSTAdd['password_encrypt'] = md5($password);
            $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
            $dbComObj->addData($conn, "website_app_user", $_POSTAdd);
            $customer_id = $dbComObj->insert_id($conn);

            $thmsg = array("msg" => "Register Successfully", "user_id" => $customer_id);
            $msg['message'] = 'Success';
            $msg['result'][] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "Email and contact number already exists");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    }
    if ($method == 'Web_User_Login') {
        $mobile = $data['mobile'];
        $password = $data['password'];

        $customer_detail = $dbComObj->viewData($conn, "website_app_user", "*", "1 and contact='$mobile'  and password='$password' ");
        if ($dbComObj->num_rows($customer_detail) > 0) {
            $dataUserD = $dbComObj->fetch_object($customer_detail);
            $_POSTAdd['name'] = $dataUserD->name;
            $_POSTAdd['contact'] = $dataUserD->contact;
            $_POSTAdd['email'] = $dataUserD->email;
            $_POSTAdd['user_id'] = $dataUserD->id;
            $thmsg[] = $_POSTAdd;

            $msg['message'] = 'Success';
            $msg['result'] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "Contact number not exists please try again");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    } else if ($method == 'Add_New_Address') {

        $name = $data['name'];
        $mobile = $data['mobile'];
        $user_id = $data['user_id'];
        $area = $data['area'];
        $location = $data['location'];
        $house_flat_no = $data['house_flat_no'];
        $landmark = $data['landmark'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $save_type = $data['save_type'];

        $customer_detail = $dbComObj->viewData($conn, "user_address", "*", "1 and location='$location' and user_id='$user_id' and delete_status='0'");
        if ($dbComObj->num_rows($customer_detail) == 0) {

            $_POSTAdd['name'] = $name;
            $_POSTAdd['contact'] = $mobile;
            $_POSTAdd['user_id'] = $user_id;
            $_POSTAdd['location'] = $location;
            $_POSTAdd['area'] = $area;
            $_POSTAdd['house_flat_no'] = $house_flat_no;
            $_POSTAdd['landmark'] = $landmark;
            $_POSTAdd['latitude'] = $latitude;
            $_POSTAdd['longitude'] = $longitude;
            $_POSTAdd['save_type'] = $save_type;
            $_POSTAdd['added_on'] = date("Y-m-d H:i:s");
            $dbComObj->addData($conn, "user_address", $_POSTAdd);
            $addCustomer_id = $dbComObj->insert_id($conn);

            $thmsg = array("msg" => "Address added Successfully", "address_id" => $addCustomer_id);
            $msg['message'] = 'Success';
            $msg['result'][] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "Already added this location name");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    } else if ($method == 'Edit_New_Address') {

        $address_id = $data['address_id'];
        $name = $data['name'];
        $mobile = $data['mobile'];
        $user_id = $data['user_id'];
        $area = $data['area'];
        $location = $data['location'];
        $house_flat_no = $data['house_flat_no'];
        $landmark = $data['landmark'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $save_type = $data['save_type'];

        $customer_detail = $dbComObj->viewData($conn, "user_address", "*", "1 and id='$address_id'");
        if ($dbComObj->num_rows($customer_detail) > 0) {

            $_POSTAdd['name'] = $name;
            $_POSTAdd['contact'] = $mobile;
            $_POSTAdd['user_id'] = $user_id;
            $_POSTAdd['location'] = $location;
            $_POSTAdd['area'] = $area;
            $_POSTAdd['house_flat_no'] = $house_flat_no;
            $_POSTAdd['landmark'] = $landmark;
            $_POSTAdd['latitude'] = $latitude;
            $_POSTAdd['longitude'] = $longitude;
            $_POSTAdd['save_type'] = $save_type;
            $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
            $_POSTAdd['updated_by'] = $user_id;
            $dbComObj->editData($conn, "user_address", $_POSTAdd, "1 and id='$address_id'");

            $thmsg = array("msg" => "Address updated Successfully", "address_id" => $address_id);
            $msg['message'] = 'Success';
            $msg['result'][] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "Already added this location name");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    } else if ($method == 'Delete_Address') {
        $address_id = $data['address_id'];
        $user_id = $data['user_id'];

        $customer_detail = $dbComObj->viewData($conn, "user_address", "*", "1 and id='$address_id'");
        if ($dbComObj->num_rows($customer_detail) > 0) {

            $_POSTAdd['delete_status'] = '1';
            $_POSTAdd['updated_on'] = date("Y-m-d H:i:s");
            $_POSTAdd['updated_by'] = $user_id;
            $dbComObj->editData($conn, "user_address", $_POSTAdd, "1 and id='$address_id'");
            $thmsg = array("msg" => "Address deleted successfully", "address_id" => $address_id);
            $msg['message'] = 'Success';
            $msg['result'][] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "Address not available for delete");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    } else if ($method == 'My_All_Address') {
        $user_id = $data['user_id'];

        $customerAddressDetail = $dbComObj->viewData($conn, "user_address", "*", "1 and user_id='$user_id' and delete_status='0'");
        if ($dbComObj->num_rows($customerAddressDetail) > 0) {
            while ($dataAddress = $dbComObj->fetch_assoc($customerAddressDetail)) {
                if ($dataAddress['save_type'] == 1) {
                    $save_type = 'Home';
                } elseif ($dataAddress['save_type'] == 2) {
                    $save_type = 'Work';
                } elseif ($dataAddress['save_type'] == 3) {
                    $save_type = 'Other';
                } else {
                    $save_type = '';
                }
                $c['address_id'] = $dataAddress['id'];
                $c['name'] = $dataAddress['name'];
                $c['contact'] = $dataAddress['contact'];
                $c['location'] = $dataAddress['location'];
                $c['area'] = $dataAddress['area'];
                $c['house_flat_no'] = $dataAddress['house_flat_no'];
                $c['landmark'] = $dataAddress['landmark'];
                $c['latitude'] = $dataAddress['latitude'];
                $c['longitude'] = $dataAddress['longitude'];
                $c['save_type'] = $save_type;
                $thmsg[] = $c;
            }

            $msg['message'] = 'My All Address List';
            $msg['result'] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "Address not available");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    }
    
    else if ($method == 'Forgot_Password') {
        $mobile_no = $data['mobile_no'];

        $customerAddressDetail = $dbComObj->viewData($conn, "website_app_user", "*", "1 and contact='$mobile_no'");
        if ($dbComObj->num_rows($customerAddressDetail) > 0) {
            $dataAddress = $dbComObj->fetch_assoc($customerAddressDetail);
                $_password = $dataAddress['password'];
                sendSMSVerification($mobile_no,"Your login passowrd for SALAD App is $_password ");
                $c['user_id'] = $dataAddress['id'];
                $c['password'] = $dataAddress['password'];
                $thmsg[] = $c;
           

            $msg['message'] = 'Password send successfully';
            $msg['result'] = $thmsg;
            $msg['status'] = '200';
        } else {

            $thmsg = array("msg" => "User mobile number not available");
            $msg['message'] = 'Error';
            $msg['result'][] = $thmsg;
            $msg['status'] = '400';
        }
        echo json_encode($msg);
    }
}