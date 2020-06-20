<?php

ob_start();
defined('BASEPATH')OR exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

class Webservices extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Web_model');
    }

    public function register() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($postdata = json_decode($postdata, true)) || !isset($postdata['fullname']) || empty($postdata['fullname']) || !isset($postdata['email']) || empty($postdata['email']) || !isset($postdata['district']) || empty($postdata['district']) || !isset($postdata['phone_no']) || empty($postdata['phone_no']) || !isset($postdata['password']) || empty($postdata['password'])) {
            $result = array('status' => 'error', 'message' => 'no data', 'error' => 'no data');
            print_r(json_encode($result));
            exit;
        }
        $phone_no = $this->Web_model->phoneno_select($postdata);
        $email_id = $this->Web_model->email_select($postdata);
        if (!empty($phone_no)) {
            $result = array('status' => 'error', 'message' => 'Phone number already exist', 'error' => '501');
            print_r(json_encode($result));
            exit;
        }
        if (!empty($email_id)) {
            $result = array('status' => 'error', 'message' => 'Email already exist', 'error' => '501');
            print_r(json_encode($result));
            exit;
        }
        $result = $this->Web_model->user_signup($postdata);
        if (empty($result)) {
            $result = array('status' => 'error', 'message' => 'Try Again', 'error' => '502');
        }
        print_r(json_encode($result));
        exit;
    }

    public function forgot_password() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($request = json_decode($postdata, true)) || !isset($request['phone_no']) || empty($request['phone_no']) ||
                !isset($request['password']) || empty($request['password'])) {
            $result = array('status' => 'error', 'message' => 'no data', 'error' => '503');
            print_r(json_encode($result));
            exit;
        }
        $phone_no = $this->Web_model->phoneno_select($request);
        //print_r($phone_no);exit;
        if (empty($phone_no)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '504');
            print_r(json_encode($result));
            exit;
        }
        $result = $this->Web_model->user_forgot($request);
        $result = array('status' => "success");
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function advertisement() {
        $result = $this->Web_model->advertisement();
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function search_all_categories() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '505');
            print_r(json_encode($result));
            exit;
        }
        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        //print_r($user_id);exit;
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '506');
            print_r(json_encode($result));
            exit;
        }
        if (isset($request['store_id']) && !empty($request['store_id'])) {
            $result = $this->Web_model->search_all_categories($request);
            if (empty($result)) {
                $result = array('status' => 'error');
            }
            print_r(json_encode($result));
            exit;
        }
        $result = $this->Web_model->search_all_categories();
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function search_category_store() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '505');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '506');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($postdata = json_decode($postdata, true)) ||
                !isset($postdata['keyword']) || empty($postdata['keyword'])) {
            $result = array('status' => 'error', 'message' => 'keyword missing', 'error' => '507');
            print_r(json_encode($result));
            exit;
        }

        $data = $this->Web_model->get_user($user_id);
        $result = $this->Web_model->search_category_store($postdata, $data);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function add_cart() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '505');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '506');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($postdata = json_decode($postdata, true)) ||
                !isset($postdata['product_id']) || empty($postdata['product_id']) || !isset($postdata['quantity']) || empty($postdata['quantity'])) {
            $result = array('status' => 'error', 'message' => 'no data', 'error' => '507');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->cart_add($postdata, $user_id);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function update_cart() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '509');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '510');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($postdata = json_decode($postdata, true)) || !isset($postdata['product_id']) || empty($postdata['product_id']) || !isset($postdata['quantity']) || empty($postdata['quantity'])) {
            $result = array('status' => 'error', 'message' => 'no data', 'error' => '511');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->cart_update($user_id, $postdata);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function get_cart() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '512');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '513');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->select_cart($user_id);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function deleteitem_cart() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '514');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '515');
            print_r(json_encode($result));
            exit;
        }
        if (empty($postdata) ||
                empty($data = json_decode($postdata, true)) ||
                !isset($data['product_id']) ||
                empty($data['product_id'])
        ) {
            $result = array('status' => 'error', 'message' => 'missing some data please check', 'error' => '516');
            print_r(json_encode($result));
            exit;
        }

        $productid = $this->Web_model->pdid_select($data, $user_id);
        if (empty($productid)) {
            $result = array('status' => 'error', 'message' => 'product not exist');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->item_delete($user_id, $productid);
        if (empty($result)) {
            $result = array('status' => 'error');
            print_r(json_encode($result));
            exit;
        }
        print_r(json_encode($result));
        exit;
    }

    public function delete_address() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '517');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '518');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($data = json_decode($postdata, true)) || !isset($data['address_id']) || empty($data['address_id'])) {
            $result = array('status' => 'error', 'message' => 'missing address id', 'error' => '522');
            print_r(json_encode($result));
            exit;
        }

        $addressid = $this->Web_model->addressid_select($user_id, $data);
        if (empty($addressid)) {
            $result = array('status' => 'error', 'message' => 'address not found');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->addressDelete($user_id, $addressid);
        if ($result) {
            $result = array('status' => "success");
        }
        print_r(json_encode($result));
        exit;
    }

    public function order_history() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '519');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '520');
            print_r(json_encode($result));
            exit;
        }

        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->user_order($user_id, 0, 0);
        $orderHistoryList = $this->Web_model->user_order($user_id, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($orderHistoryList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $orderHistoryList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function get_location() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '519');
            print_r(json_encode($result));
            exit;
        }

        $user_id = $this->Web_model->EncryptedPatientKey_select($auth);
        if (empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '520');
            print_r(json_encode($result));
            exit;
        }

        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($data = json_decode($postdata, true)) || !isset($data['zip_code']) || empty($data['zip_code'])) {
            $result = array('status' => 'error', 'message' => 'missing zip_code', 'error' => '522');
            print_r(json_encode($result));
            exit;
        }

        $zipcode = $this->Web_model->zipcode_select($data);
        if (empty($zipcode)) {
            $result = array('status' => 'error', 'message' => 'location not found');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->find_location($zipcode, $user_id);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function get_product() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($data = json_decode($postdata, true)) || !isset($data['store_id']) || empty($data['store_id']) ||
                !isset($data['category_id']) || empty($data['category_id'])) {
            $result = array('status' => 'error', 'message' => 'missing store_id or category_id', 'error' => '523');
            print_r(json_encode($result));
            exit;
        }

        $store_id = $this->Web_model->storeid_select($data);
        if (empty($store_id)) {
            $result = array('status' => 'error', 'message' => 'store not found');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->find_product($data);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function get_city() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($data = json_decode($postdata, true)) || !isset($data['city_id']) || empty($data['city_id'])) {
            $result = array('status' => 'error', 'message' => 'missing city_id', 'error' => '524');
            print_r(json_encode($result));
            exit;
        }

        $city_id = $this->Web_model->cityid_select($data);
        if (empty($city_id)) {
            $result = array('status' => 'error', 'message' => 'city not found');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->find_city($city_id);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function add_address() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }

        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        if (!isset($user_id) || empty($user_id)) {
            $result = array('status' => "error", 'message' => "user not exsist", 'error' => "501");
            print_r(json_encode($result));
            exit;
        }

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['street']) || empty($request['street']) || !isset($request['landmark']) || empty($request['landmark']) || !isset($request['zipcode']) || empty($request['zipcode'])) {
            $result = array('status' => "error", 'message' => "Data is missing", 'error' => "502");
            print_r(json_encode($result));
            exit;
        }

        $data = $this->Web_model->addAddress($user_id, $request);
        if (empty($data)) {
            $result = array('status' => 'error');
            print_r(json_encode($result));
            exit;
        }
        print_r(json_encode($data));
        exit;
    }

    public function select_address() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }

        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        if (!isset($user_id) || empty($user_id)) {
            $result = array('status' => "error", 'message' => "user not exsist", 'error' => "501");
            print_r(json_encode($result));
            exit;
        }

        if (!isset($request['address_id']) || empty('address_id')) {
            $result = array('status' => "error", 'message' => "address id is missing", 'error' => "502");
            print_r(json_encode($result));
            exit;
        }

        $data = $this->Web_model->selectAddress($user_id, $request);
        if (empty($data)) {
            $result = array('status' => "error");
            print_r(json_encode($result));
            exit;
        }
        print_r(json_encode($data));
        exit;
    }

    public function showAllAddress() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }

        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        if (!isset($user_id) || empty($user_id)) {
            $result = array('status' => "error", 'message' => "user not exsist", 'error' => "502");
            print_r(json_encode($result));
            exit;
        }

        $allAddress = $this->Web_model->showAllAddress($user_id);
        if (empty($allAddress)) {
            $result = array('status' => "success", 'message' => "No address is found for this user", 'error' => "503");
            print_r(json_encode($result));
            exit;
        }
        print_r(json_encode($allAddress));
        exit;
    }

    public function edit_profile() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }

        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        if (!isset($user_id) || empty($user_id)) {
            $result = array('status' => "error", 'message' => "user not found", 'error' => "501");
            print_r(json_encode($result));
            exit;
        }

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['fullname']) || empty($request['fullname']) || !isset($request['email']) || empty($request['email']) || !isset($request['phone_no']) || empty($request['phone_no'])) {
            $result = array('status' => "error", 'message' => "data is not sufficient", 'error' => "502");
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->editProfile($user_id, $request);
        if (empty($result['status'] == 'success')) {
            $respArr = array('status' => 'success', 'message' => "Your profile has updated", 'data' => $result['data']);
        }
        print_r(json_encode($result));
        exit;
    }

    public function change_password() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        if (!isset($user_id) || empty($user_id)) {
            $result = array('status' => "error", 'message' => "user not found");
            print_r(json_encode($result));
            exit;
        }

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['phone_no']) || empty($request['phone_no']) || !isset($request['password']) || empty($request['password']) || !isset($request['npassword']) || empty($request['npassword'])) {
            $result = array('status' => "error", 'message' => "valid fields are missing");
            print_r(json_encode($result));
            exit;
        }
        if ($request['password'] != $request['npassword']) {
            $result = array('status' => "error", 'message' => "password mismatches");
            print_r(json_encode($result));
            exit;
        }

        $value = $this->Web_model->check_user($user_id, $request);
        if (empty($value)) {
            $value = array('status' => "error", 'message' => "Please enter correct phone number");
            print_r(json_encode($value));
            exit;
        }

        $result = $this->Web_model->changePassword($user_id, $request);
        if ($result) {
            $result = array('status' => "success", 'message' => "Your Password has been changed");
        }
        print_r(json_encode($result));
        exit;
    }

    public function user_image() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($auth = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'missing auth', 'error' => '5021');
            print_r(json_encode($result));
            exit;
        }
        $user_id = $this->Web_model->get_userid($auth);
        if (!isset($user_id) || empty($user_id)) {
            $result = array('status' => 'error', 'message' => 'user not exist', 'error' => '5022');
            print_r(json_encode($result));
            exit;
        }

        if (isset($_FILES['file']) && !empty($_FILES['file'])) {
            $uploadfile = 'assets/uploads/upload_files/IMG' . time();
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $image = $this->Web_model->userImage($user_id, $uploadfile);
                print_r(json_encode($image));
                exit;
            }
        } else {
            $result = array('status' => 'error', 'message' => 'enter image', 'error' => '5022');
            print_r(json_encode($result));
            exit;
        }
    }

    public function stores_search() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        $data = $this->Web_model->get_user($user_id);
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($request = json_decode($postdata, true)) || !isset($request['store_name']) || empty($request['store_name'])) {
            $result = array('status' => 'error', 'message' => 'store_name missing');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->searchStore($request, $data, 0, 0);
        $storeList = $this->Web_model->searchStore($request, $data, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($storeList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $storeList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function product_search() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        $data = $this->Web_model->get_user($user_id);
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['product_name']) || empty($request['product_name'])) {
            $result = array('status' => "error", 'message' => "Product name is missing");
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->searchProduct($request, $data, 0, 0);
        $productList = $this->Web_model->searchProduct($request, $data, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($productList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $productList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function category_search() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        $data = $this->Web_model->get_user($user_id);
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['category_name']) || empty($request['category_name'])) {
            $result = array('status' => "error", 'message' => "Category name is missing");
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->searchCategory($request, $data, 0, 0);
        $category_list = $this->Web_model->searchCategory($request, $data, $page_limit, $per_page);

        if (empty($result)) {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => 1,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
            print_r(json_encode($respArr));
            exit;
        }

        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($category_list['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $category_list['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function productsByCategory() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['category_id']) || empty($request['category_id']) || !isset($request['store_id']) || empty($request['store_id'])) {
            $result = array('status' => 'error', 'message' => 'missing feilds', 'error' => 'no data');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->productsByCategory($request, 0, 0);
        // print_r($result);exit;
        $productsByCategoryList = $this->Web_model->productsByCategory($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($productsByCategoryList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $productsByCategoryList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function searchProductOrStore() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if ((!isset($request['product_name']) || empty($request['product_name'])) && (!isset($request['store_name']) || empty($request['store_name']))) {
            $result = array('status' => 'error', 'message' => 'missing feild', 'error' => 'no data');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->searchProductOrStore($request, 0, 0);
        // print_r($result);exit;
        $ProductOrStoreList = $this->Web_model->searchProductOrStore($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($ProductOrStoreList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $ProductOrStoreList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function storeProductSearch() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['store_id']) || empty($request['store_id']) || !isset($request['product_name']) || empty($request['product_name'])) {
            $result = array('status' => 'error', 'message' => 'missing feilds', 'error' => 'no data');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->storeProductSearch($request, 0, 0);
        $productList = $this->Web_model->storeProductSearch($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($productList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $productList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function peopleFavoriteList() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['city_id']) || empty($request['city_id'])) {
            $result = array('status' => 'error', 'message' => 'missing city_id', 'error' => 'no data');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->peopleFavoriteList($request, 0, 0);
        // print_r($result);exit;
        $people_favourite_list = $this->Web_model->peopleFavoriteList($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($people_favourite_list['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $people_favourite_list['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function peopleFavoriteStoresByCategory() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['city_id']) || empty($request['city_id']) || !isset($request['category_id']) || empty($request['category_id'])) {
            $result = array('status' => 'error', 'message' => 'missing data', 'error' => 'no data');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->peopleFavoriteStoresByCategory($request, 0, 0);
        $people_favourite_categorylist = $this->Web_model->peopleFavoriteStoresByCategory($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($people_favourite_categorylist['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $people_favourite_categorylist['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function myFavoriteStores() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        $request = $_GET;
        $user_id = $this->Web_model->get_userid($auth_token);
        $data = $this->Web_model->get_user($user_id);
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->myFavoriteStores($request, $data, 0, 0);
        $my_favourite_storelist = $this->Web_model->myFavoriteStores($request, $data, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($my_favourite_storelist['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $my_favourite_storelist['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function myFavoriteStoresByCategory() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        if (!isset($request['category_id']) || empty($request['category_id'])) {
            $result = array('status' => "category_id is missing");
            print_r(json_encode($result));
            exit;
        }
        $user_id = $this->Web_model->get_userid($auth_token);
        $data = $this->Web_model->get_user($user_id);
        //print_r($data);exit;
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->myFavoriteStoresByCategory($request, $data, 0, 0);
        $my_favourite_storeCategory = $this->Web_model->myFavoriteStoresByCategory($request, $data, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($my_favourite_storeCategory['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $my_favourite_storeCategory['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function do_login() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($request = json_decode($postdata, true)) || !isset($request['email']) || empty($request['email']) || !isset($request['password']) || empty($request['password'])) {
            $result = array('status' => 'error', 'message' => 'Valid Fields Missing', 'error' => '302');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->login($request);
        if (empty($result) || !isset($result['status']) || $result['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'Unknown Credential! Try Again', 'error' => '5023');
        }
        print_r(json_encode($result));
        exit;
    }

    public function user_details() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '102');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '202');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->get_userdata($output);
        if (empty($result) || !isset($result['status']) || $result['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'Unknown Credential! Try Again', 'error' => '5024');
        }
        print_r(json_encode($result));
        exit;
    }

    public function store_details() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($request = json_decode($postdata, true)) || !isset($request['city_id']) || empty($request['city_id'])) {
            $result = array('status' => 'error', 'message' => 'Valid Fields Missing', 'error' => '302');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->get_storelist($request, 0, 0);
        $store_list = $this->Web_model->get_storelist($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($store_list['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $store_list['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function category_details() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        $result = $this->Web_model->get_categorylist($request);
        if (empty($result) || !isset($result['status']) || $result['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'Unknown Credential! Try Again', 'error' => '5026');
        }
        print_r(json_encode($result));
        exit;
    }

    public function pastorder_details() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '1022');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '2022');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->pastorder_list($output);
        if (empty($result)) {
            $result = array('status' => 'error', 'message' => 'Try again', 'error' => '5027');
        }
        print_r(json_encode($result));
        exit;
    }

    public function order_details() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '5028');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '5029');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($order = json_decode($postdata, true)) || !isset($order['order_id']) || empty($order['order_id'])) {
            $result = array('status' => 'error', 'message' => 'Valid Fields Missing', 'error' => '50210');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->get_orderdetails($output, $order);
        print_r(json_encode($result));
        exit;
    }

    public function promocode() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '50211');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '50212');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($pcode = json_decode($postdata, true)) || !isset($pcode['promo_code']) || empty($pcode['promo_code']) || !isset($pcode['amount']) || empty($pcode['amount'])) {
            $result = array('status' => 'error', 'message' => 'Valid Fields Missing', 'error' => '50213');
            print_r(json_encode($result));
            exit;
        }
        $result = $this->Web_model->get_promocode($output, $pcode);
        print_r(json_encode($result));
        exit;
    }

    public function people_favorite_stores() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '50214');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '50215');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->get_favouritestore($output);
        print_r(json_encode($result));
        exit;
    }

    public function wallet_addmoney() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '50216');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '50217');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($amount = json_decode($postdata, true)) || !isset($amount['money']) || empty($amount['money'])) {
            $result = array('status' => 'error', 'message' => 'Valid Fields Missing', 'error' => '50218');
            print_r(json_encode($result));
            exit;
        }
        $result = $this->Web_model->addmoney($output, $amount);
        print_r(json_encode($result));
        exit;
    }

    public function wallet_updatemoney() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '50219');
            print_r(json_encode($result));
            exit;
        }

        $output = $this->Web_model->obtain_userid($request);
        if (empty($output) && $output['status'] != 'success') {
            $result = array('status' => 'error', 'message' => 'UserId Missing! Try Again', 'error' => '5020');
            print_r(json_encode($result));
            exit;
        }

        if (empty($postdata) || empty($amount = json_decode($postdata, true)) || !isset($amount['money']) || empty($amount['money']) || !isset($amount['action'])) {
            $result = array('status' => 'error', 'message' => 'Valid Fields Missing', 'error' => '50200');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->updatewallet($output, $amount);
        print_r(json_encode($result));
        exit;
    }

    public function allStoresByCategory() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['category_id']) || empty($request['category_id']) || !isset($request['city_id']) || empty($request['city_id'])) {
            $result = array('status' => "valid Fields are missing");
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->allStoresByCategory($request, 0, 0);
        $allStoresBy_Category = $this->Web_model->allStoresByCategory($request, $page_limit, $per_page);
        // print_r($result);exit;
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($allStoresBy_Category['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $allStoresBy_Category['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No data',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function help() {
        header('Content-type: application/json');
        $request = $_GET;
        $result = $this->Web_model->help();
        if (empty($result['status'] == 'success')) {
            $respArr = array('status' => 'success',
                'data' => $result['data']);
        } else {
            $respArr = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function reportAProblem() {
        header('Content-type: application/json');
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '50219');
            print_r(json_encode($result));
            exit;
        }
        $auth_token = $request['auth'];
        $user_id = $this->Web_model->get_userid($auth_token);
        $data = $this->Web_model->get_user($user_id);
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        if (!isset($request['prblm_desc']) || empty($request['prblm_desc'])) {
            $result = array('status' => 'error', 'message' => 'describe the problem', 'error' => '500');
            print_r(json_encode($result));
            exit;
        }

        $result = $this->Web_model->reportAProblem($data, $request, $user_id);
        if (empty($result)) {
            $result = array('status' => 'error');
        }
        print_r(json_encode($result));
        exit;
    }

    public function searchPlace() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (empty($postdata) || empty($request = json_decode($postdata, true)) || !isset($request['location']) || empty($request['location'])) {
            $result = array('status' => 'error', 'message' => 'place name is missing');
            print_r(json_encode($result));
            exit;
        }
        $total = 0;
        $per_page = 10;
        $page = (isset($request['page']) && $request['page'] != 1) ? $request['page'] : '1';
        $page_limit = ($page - 1) * $per_page;
        $result = $this->Web_model->searchPlace($request, 0, 0);
        $placeList = $this->Web_model->searchPlace($request, $page_limit, $per_page);
        if ($result['status'] == 'success') {
            $total = count($result['data']);
        }
        if ($total >= $per_page) {
            $totalPages = (int) ($total % $per_page == 0 ? $total / $per_page : ($total / $per_page) + 1);
        } else {
            $totalPages = 1;
        }
        if ($placeList['status'] == 'success') {
            $respArr = array(
                'status' => 'success',
                'message' => 'success',
                'data' => $placeList['data'],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        } else {
            $respArr = array(
                'status' => 'error',
                'message' => 'No Places are available',
                'data' => [],
                'meta' => array(
                    'total_pages' => $totalPages,
                    'total' => $total,
                    'current_page' => ($page == 0) ? 1 : $page,
                    'per_page' => $per_page
                )
            );
        }
        print_r(json_encode($respArr));
        exit;
    }

    public function logout() {
        header('Content-type: application/json');
        $postdata = file_get_contents("php://input");
        if (!isset(apache_request_headers()['Auth']) || empty($request['auth'] = apache_request_headers()['Auth'])) {
            $result = array('status' => 'error', 'message' => 'AuthToken Missing! Try Again', 'error' => '50222');
            print_r(json_encode($result));
            exit;
        }
        $result = $this->Web_model->user_logout($request);
        print_r(json_encode($result));
        exit;
    }

}

?>