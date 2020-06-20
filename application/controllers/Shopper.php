<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shopper extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Shopper_model');
        $this->load->model('Stores_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if ($this->session->userdata['user_type'] != 1) {
            redirect(base_url('dashboard'));
        }
    }

    public function addshopper() {
        $this->load->model('Stores_model');
        $template['page'] = 'Shopper/addshopper';
        $template['menu'] = 'Shopper Management';
        $template['smenu'] = 'Add Shopper';
        $template['pTitle'] = "Add Shopper";
        $template['pDescription'] = "Create New Shopper";
        $template['shop_data'] = $this->Stores_model->getStores();
        //print_r($templates);exit;
        $this->load->view('template', $template);
    }

    public function viewshopper() {
        //$this->load->model('Store_model');
        $template['shop_data'] = $this->Stores_model->getStores('');
        $template['page'] = 'Shopper/viewshopper';
        $template['menu'] = 'Shopper Management';
        $template['smenu'] = 'View Shopper';
        $template['pTitle'] = "View Shopper";
        $template['pDescription'] = "View and Manage Shopper";
        $template['page_head'] = "Shopper Management";
        $template['user_data'] = $this->Shopper_model->getShopper('', 1);

        $this->load->view('template', $template);
    }

    function changestatus($shopper_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($shopper_id) || !is_numeric($shopper_id = decode_param($shopper_id))) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/viewshopper'));
        }
        $status = $this->Shopper_model->changeStatus($shopper_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('shopper/viewshopper'));
    }

    public function createshopper() {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/addshopper'));
        }
        if ($err == 0 && (!isset($_POST['display_name']) || empty($_POST['display_name']))) {
            $err = 1;
            $errMsg = 'Provide a  Name';
        } else if ($err == 0 && (!isset($_POST['password']) || empty($_POST['password']))) {
            $err = 1;
            $errMsg = 'Provide a Password';
        } else if ($err == 0 && (!isset($_POST['phone']) || empty($_POST['phone']))) {
            $err = 1;
            $errMsg = 'Provide a Phone Number';
        } else if ($err == 0 && (!isset($_POST['email_id']) || empty($_POST['email_id']))) {
            $err = 1;
            $errMsg = 'Provide an Email ID';
        } else if ($err == 0 && (!isset($_POST['display_name']) || empty($_POST['display_name']))) {
            $err = 1;
            $errMsg = 'Provide a Display Name';
        } else if ($err == 0 && (!isset($_FILES['profile_image']) || empty($_FILES['profile_image']))) {
            $err = 1;
            $errMsg = 'Provide a Profile Photo';
        }

        if ($err == 0) {
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time() . "_" . $_FILES['profile_image']['name'];
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('profile_image')) {
                $err = 1;
                $errMsg = $this->upload->display_errors();
            } else {
                $upload_data = $this->upload->data();
                $_POST['profile_image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
            }
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/addshopper'));
        }
        $_POST['password'] = md5($_POST['password']);
        $status = $this->Shopper_model->addShopper($_POST);
        if ($status == 1) {
            $flashMsg = array('message' => 'Successfully Updated User Details..!', 'class' => 'success');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/viewshopper'));
        } else if ($status == 2) {
            $flashMsg = array('message' => 'Email ID already exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/addshopper'));
        } else if ($status == 3) {
            $flashMsg = array('message' => 'Phone Number already exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/addshopper'));
        } else if ($status == 4) {
            $flashMsg = array('message' => 'User Name already exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/addshopper'));
        } else {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/addshopper'));
        }
    }

    public function getshopperdata() {
        $return_arr = array('status' => '0');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['shopper_id']) || empty($_POST['shopper_id'])) {
            echo json_encode($return_arr);
            exit;
        }
        $shopper_id = decode_param($_POST['shopper_id']);
        $shopper_data = $this->Shopper_model->getShopperData($shopper_id);
        if (!empty($shopper_data)) {
            $return_arr['status'] = 1;
            $return_arr['shopper_data'] = $shopper_data;
        }
        echo json_encode($return_arr);
        exit;
    }

    public function editshopper($shopper_id) {
        $this->load->model('Stores_model');
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($shopper_id) || !is_numeric($shopper_id = decode_param($shopper_id))) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/viewshopper'));
        }
        $template['page'] = 'Shopper/addshopper';
        $template['menu'] = 'Shopper Management';
        $template['smenu'] = 'Edit Shopper';
        $template['pTitle'] = "Edit Shopper";
        $template['pDescription'] = "Update Shopper Data";
        //print_r($shopper_id);exit;
        $template['user_data'] = $this->Shopper_model->getShopperData($shopper_id);
        $template['shopper_id'] = encode_param($shopper_id);
        $template['shop_data'] = $this->Stores_model->getStores();
        $this->load->view('template', $template);
    }

    public function updateshopper($shopper_id = '') {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($shopper_id) || !isset($_POST) || empty($_POST) || !is_numeric(decode_param($shopper_id))) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/viewshopper'));
        }
        if ($err == 0 && (!isset($_POST['display_name']) || empty($_POST['display_name']))) {
            $err = 1;
            $errMsg = 'Provide a display Name';
        } else if ($err == 0 && (!isset($_POST['phone']) || empty($_POST['phone']))) {
            $err = 1;
            $errMsg = 'Provide a Phone Number';
        } else if ($err == 0 && (!isset($_POST['email_id']) || empty($_POST['email_id']))) {
            $err = 1;
            $errMsg = 'Provide an Email ID';
        } else if ($err == 0 && (!isset($_FILES['profile_image']) || empty($_FILES['profile_image']))) {
            $err = 1;
            $errMsg = 'Provide a Profile Photo';
        }

        if ($err == 0) {
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time() . "_" . $_FILES['profile_image']['name'];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $_POST['profile_image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
            }
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/editshopper/' . $shopper_id));
        }
        $status = $this->Shopper_model->updateShopper(decode_param($shopper_id), $_POST);
        if ($status == 1) {
            $flashMsg = array('message' => 'Successfully Updated User Details..!', 'class' => 'success');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/viewshopper'));
        } else if ($status == 2) {
            $flashMsg = array('message' => 'Email ID alrady exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/editshopper/' . $shopper_id));
        } else if ($status == 3) {
            $flashMsg = array('message' => 'Phone Number alrady exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/editshopper/' . $shopper_id));
        } else if ($status == 4) {
            $flashMsg = array('message' => 'User Name alrady exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/editshopper/' . $shopper_id));
        } else {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('shopper/editshopper/' . $shopper_id));
        }
    }

}

?>