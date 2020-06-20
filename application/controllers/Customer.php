<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Customer_model');
        $this->load->model('City_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if ($this->session->userdata['user_type'] != 1) {
            redirect(base_url('dashboard'));
        }
    }

    public function addcustomeruser() {
        $template['page'] = 'Customer/customer_add';
        $template['pTitle'] = "Add New Customer";
        $template['pDescription'] = "Create New Customer";
        $template['menu'] = "Customer Management";
        $template['smenu'] = "Add Customer";
        $template['city_data'] = $this->City_model->getCities('', '');
        $this->load->view('template', $template);
    }

    public function listcustomerusers() {
        $template['page'] = 'Customer/customer_list';
        $template['pTitle'] = "View Customers";
        $template['pDescription'] = "View and Manage Customers";
        $template['menu'] = "Customer Management";
        $template['smenu'] = "View Customers";
        $template['customerData'] = $this->Customer_model->get_customer();
        $this->load->view('template', $template);
    }

    public function createcustomer() {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/addcustomeruser'));
        }
        if ($err == 0 && (!isset($_POST['fullname']) || empty($_POST['fullname']))) {
            $err = 1;
            $errMsg = 'Provide the Name';
        } else if ($err == 0 && (!isset($_POST['email']) || empty($_POST['email']))) {
            $err = 1;
            $errMsg = 'Provide an Email';
        } else if ($err == 0 && (!isset($_POST['phone_no']) || empty($_POST['phone_no']))) {
            $err = 1;
            $errMsg = 'Provide a Phone Number';
        } else if ($err == 0 && (!isset($_POST['district']) || empty($_POST['district']))) {
            $err = 1;
            $errMsg = 'Provide District';
        } else if ($err == 0 && (!isset($_POST['password']) || empty($_POST['password']))) {
            $err = 1;
            $errMsg = 'Provide password';
        } else if ($err == 0 && (!isset($_FILES['image']) || empty($_FILES['image']))) {
            $err = 1;
            $errMsg = 'Provide Profile Picture';
        }
        $_POST['password'] = md5($_POST['password']);
        $_POST['image'] = '';
        if ($err == 0) {
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time() . "_" . $_FILES['image']['name'];
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('image')) {
                $err = 1;
                $errMsg = $this->upload->display_errors();
            } else {
                $upload_data = $this->upload->data();
                $_POST['image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
            }
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/addcustomeruser'));
        }

        $status = $this->Customer_model->createCustomer($_POST);

        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'User Created';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/listcustomerusers'));
        } else if ($status == 2) {
            $flashMsg['message'] = 'Email ID already in use.';
        } else if ($status == 3) {
            $flashMsg['message'] = 'Phone Number already in use.';
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('customer/addcustomeruser'));
    }

    public function getcustomerdata() {
        $return_arr = array('status' => '0');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['customer_id']) || empty($_POST['customer_id'])) {
            echo json_encode($return_arr);
            exit;
        }
        $customer_id = decode_param($_POST['customer_id']);
        $customer_data = $this->Customer_model->get_customer(array('customer_id' => $customer_id));
        if (!empty($customer_data)) {
            $return_arr['status'] = 1;
            $return_arr['customer_data'] = $customer_data;
        }
        echo json_encode($return_arr);
        exit;
    }

    function editcustomer($customer_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($customer_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/listcustomerusers'));
        }
        $template['page'] = 'Customer/customer_add';
        $template['menu'] = "Customer Management";
        $template['smenu'] = "Edit Customer";
        $template['pDescription'] = "Edit Customer Details";
        $template['pTitle'] = "Edit Customer";
        $template['customer_id'] = $customer_id;
        $customer_id = decode_param($customer_id);
        $template['city_data'] = $this->City_model->getCities('', '');
        $template['customer_data'] = $this->Customer_model->get_customer(array('customer_id' => $customer_id));
        $this->load->view('template', $template);
    }

    function updatecustomer($customer_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($customer_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/listcustomerusers'));
        }
        $customerIdDec = decode_param($customer_id);
        $err = 0;
        $errMsg = '';
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/addcustomeruser'));
        }
        if ($err == 0 && (!isset($_POST['fullname']) || empty($_POST['fullname']))) {
            $err = 1;
            $errMsg = 'Provide a Full Name';
        } else if ($err == 0 && (!isset($_POST['email']) || empty($_POST['email']))) {
            $err = 1;
            $errMsg = 'Provide an Email ID';
        } else if ($err == 0 && (!isset($_POST['phone_no']) || empty($_POST['phone_no']))) {
            $err = 1;
            $errMsg = 'Provide a Phone Number';
        } else if ($err == 0 && (!isset($_POST['district']) || empty($_POST['district']))) {
            $err = 1;
            $errMsg = 'Provide a district';
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/editcustomer/' . $customer_id));
        }
        $config = set_upload_service("assets/uploads/services");
        $this->load->library('upload');
        $config['file_name'] = time() . "_" . $_FILES['image']['name'];
        $this->upload->initialize($config);
        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            $_POST['image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
        }

        $status = $this->Customer_model->updateCustomer($customerIdDec, $_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'User Details Updated';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/listcustomerusers'));
        } else if ($status == 2) {
            $flashMsg['message'] = 'Email ID already in use.';
        } else if ($status == 3) {
            $flashMsg['message'] = 'Phone Number already in use.';
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('customer/editcustomer/' . $customer_id));
    }

    function changeStatus($customer_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($customer_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('customer/listcustomerusers'));
        }
        $customer_id = decode_param($customer_id);
        $status = $this->Customer_model->changeStatus($customer_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('customer/listcustomerusers'));
    }

}

?>