<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Promocode extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Promocode_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if ($this->session->userdata['user_type'] != 1) {
            redirect(base_url('dashboard'));
        }
    }

    public function addpromocode() {
        $template['page'] = 'promocode/promocodeadd_view';
        $template['pTitle'] = "Add New promocode";
        $template['pDescription'] = "Create New promocode";
        $template['menu'] = "promocode Management";
        $template['smenu'] = "Add promocode";
        $this->load->view('template', $template);
    }

    public function listpromocode() {
        $template['page'] = 'promocode/promocode_view';
        $template['pTitle'] = "View promocode";
        $template['pDescription'] = "View and Manage promocode";
        $template['menu'] = "promocode Management";
        $template['smenu'] = "View promocode";
        $template['customerData'] = $this->Promocode_model->getpromocode();
        $this->load->view('template', $template);
    }

    public function createpromocode() {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/addpromocode'));
        }
        if ($err == 0 && (!isset($_POST['promo_code']) || empty($_POST['promo_code']))) {
            $err = 0;
            $errMsg = 'Provide a promocode name';
        } else if ($err == 0 && (!isset($_POST['starting_date']) || empty($_POST['starting_date']))) {
            $err = 1;
            $errMsg = 'Provide a starting date';
        } else if ($err == 0 && (!isset($_POST['ending_date']) || empty($_POST['ending_date']))) {
            $err = 1;
            $errMsg = 'Provide an ending date';
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/addpromocode'));
        }
        $status = $this->Promocode_model->createpromocode($_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Promocode Created';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/listpromocode'));
        } else if ($status == 2) {
            $flashMsg['message'] = 'promocode already in use.';
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('promocode/addpromocode'));
    }

    public function getpromodata() {
        $return_arr = array('status' => '0');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['promo_id']) || empty($_POST['promo_id'])) {
            echo json_encode($return_arr);
            exit;
        }
        $promo_id = decode_param($_POST['promo_id']);
        $promo_data = $this->Promocode_model->get_promoData($promo_id);
        if (!empty($promo_data)) {
            $return_arr['status'] = 1;
            $return_arr['promo_data'] = $promo_data;
        }
        echo json_encode($return_arr);
        exit;
    }

    function changestatus($promo_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($promo_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/listpromocode'));
        }
        $promo_id = decode_param($promo_id);
        $status = $this->Promocode_model->changeStatus($promo_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('promocode/listpromocode'));
    }

    public function editpromocode($promo_id) {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($promo_id) || !is_numeric($promo_id = decode_param($promo_id))) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/promocodeadd_view'));
        }
        $template['page'] = 'promocode/promocodeadd_view';
        $template['menu'] = 'Promocode Management';
        $template['smenu'] = 'Edit promocode';
        $template['pTitle'] = "Edit promocode";
        $template['pDescription'] = "Update promocode Data";
        $template['promo_id'] = encode_param($promo_id);
        $template['promo_data'] = $this->Promocode_model->get_promoData($promo_id);
        $this->load->view('template', $template);
    }

    function updatepromocode($promo_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($promo_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/listpromocode'));
        }
        $customerIdDec = decode_param($promo_id);
        $err = 0;
        $errMsg = '';
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/addpromocode'));
        }
        if ($err == 0 && (!isset($_POST['promo_code']) || empty($_POST['promo_code']))) {
            $err = 1;
            $errMsg = 'Provide a promocode name';
        } else if ($err == 0 && (!isset($_POST['starting_date']) || empty($_POST['starting_date']))) {
            $err = 1;
            $errMsg = 'Provide a starting date';
        } else if ($err == 0 && (!isset($_POST['ending_date']) || empty($_POST['ending_date']))) {
            $err = 1;
            $errMsg = 'Provide an ending date';
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/editpromocode/' . $promo_id));
        }

        $status = $this->Promocode_model->updatepromocode($customerIdDec, $_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'User Details Updated';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('promocode/listpromocode'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('promocode/editpromocode/' . $promo_id));
    }

}

?>