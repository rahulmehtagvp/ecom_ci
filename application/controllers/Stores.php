<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Stores_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if ($this->session->userdata['user_type'] != 1) {
            redirect(base_url('dashboard'));
        }
    }

    public function addnewstore() {
        $this->load->model('City_model');
        $template['page'] = 'Stores/store_add';
        $template['pTitle'] = "Add New Store";
        $template['pDescription'] = "Create New Store";
        $template['menu'] = "Store Management";
        $template['smenu'] = "Add Store";
        $city_id = 5;
        $template['city_data'] = $this->City_model->getCities('', $city_id);
        $this->load->view('template', $template);
    }

    public function liststores($store_id = '') {
        //print_r($store_id);exit;
        if (!empty($store_id)) {
            $store_id = (!is_numeric($store_id)) ? decode_param($store_id) : $store_id;
        }
        $store_data = '';
        if ($this->session->userdata('user_type') == 1) {
            $this->load->model('Store_model');
            $store_data = $this->Stores_model->getStores('');
            //print_r($store_data);exit;
            if (isset($_POST['store_id']) && !empty($_POST['store_id'])) {
                $store_id = $_POST['store_id'];
                $store_id = (!is_numeric($store_id)) ? decode_param($store_id) : $store_id;
            }
        }
        $template['page'] = 'Stores/store_list';
        $template['pTitle'] = "View Stores";
        $template['pDescription'] = "View and Manage Stores";
        $template['menu'] = "Store Management";
        $template['smenu'] = "View Stores";
        $template['store_id'] = ($this->session->userdata('user_type') == 1 &&
                empty($store_id)) ? '' : $store_id;
        $template['storedata'] = $store_data;
        $template['store_data'] = $this->Stores_model->getStores($store_id);
        $this->load->view('template', $template);
    }

    public function createstore() {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/addnewstore'));
        }
        if ($err == 0 && (!isset($_POST['store_name']) || empty($_POST['store_name']))) {
            $err = 1;
            $errMsg = 'Provide a Store Name';
        } else if ($err == 0 && (!isset($_POST['description']) || empty($_POST['description']))) {
            $err = 1;
            $errMsg = 'Provide a Description';
        } else if ($err == 0 && (!isset($_FILES['store_image']) || empty($_FILES['store_image']))) {
            $err = 1;
            $errMsg = 'Provide Store Picture';
        }
        $_POST['store_image'] = '';
        if ($err == 0) {
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time() . "_" . $_FILES['store_image']['name'];
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('store_image')) {
                $err = 1;
                $errMsg = $this->upload->display_errors();
            } else {
                $upload_data = $this->upload->data();
                $_POST['store_image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
            }
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/addnewstore'));
        }

        $status = $this->Stores_model->createStore($_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Store Created';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/liststores'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('stores/addnewstore'));
    }

    function changestatus($store_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($store_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/liststores'));
        }
        $store_id = decode_param($store_id);
        $status = $this->Stores_model->changeStatus($store_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('stores/liststores'));
    }

    function editstore($store_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($store_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/liststores'));
        }
        $template['page'] = 'Stores/store_add';
        $template['menu'] = "Store Management";
        $template['smenu'] = "Edit Store";
        $template['pDescription'] = "Edit Store Details";
        $template['pTitle'] = "Edit Store";
        $template['store_id'] = $store_id;
        $store_id = decode_param($store_id);
        //print_r($store_id);exit;
        $template['store_data'] = $this->Stores_model->get_Stores(array('store_id' => $store_id));
        $this->load->view('template', $template);
    }

    function updatestore($store_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($store_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/liststores'));
        }
        $customerIdDec = decode_param($store_id);
        $err = 0;
        $errMsg = '';
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/editstore/' . $store_id));
        }
        if ($err == 0 && (!isset($_POST['store_name']) || empty($_POST['store_name']))) {
            $err = 1;
            $errMsg = 'Provide a store name';
        } else if ($err == 0 && (!isset($_POST['description']) || empty($_POST['description']))) {
            $err = 1;
            $errMsg = 'Provide description';
        }
        $config = set_upload_service("assets/uploads/services");
        $this->load->library('upload');
        $config['file_name'] = time() . "_" . $_FILES['store_image']['name'];
        $this->upload->initialize($config);
        if ($this->upload->do_upload('store_image')) {
            $upload_data = $this->upload->data();
            $_POST['store_image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/editstore/' . $store_id));
        }

        $status = $this->Stores_model->updatestore($customerIdDec, $_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Store Details Updated';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('stores/liststores'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('stores/editstore/' . $store_id));
    }

    public function getstoresdata() {
        $return_arr = array('status' => '0');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['store_id']) || empty($_POST['store_id'])) {
            echo json_encode($return_arr);
            exit;
        }
        $store_id = decode_param($_POST['store_id']);
        $store_data = $this->Stores_model->get_stores(array('store_id' => $store_id));
        if (!empty($store_data)) {
            $return_arr['status'] = 1;
            $return_arr['store_data'] = $store_data;
        }
        echo json_encode($return_arr);
        exit;
    }

}
?>

