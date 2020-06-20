<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Advertisement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Advertisement_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
    }

    public function index() {
        $template['page'] = 'Advertisement/Advertisement_list';
        $template['menu'] = "Adds";
        $template['smenu'] = "Change Adds Data";
        $template['pTitle'] = "Adds Data";
        $template['page_head'] = "Adds Data";
        $template['pDescription'] = "Change Adds Data";
        $template['notificationData'] = $this->Advertisement_model->get_Advertisement();
        //print_r($template['notificationData']);exit;
        $this->load->view('template', $template);
    }

    public function addadvertisement() {
        $template['page'] = 'Advertisement/Advertisement_view';
        $template['pTitle'] = "Add Advertisement Data";
        $template['pDescription'] = "Create Data";
        $template['menu'] = "Add Data";
        $template['smenu'] = "Add Data";
        $this->load->view('template', $template);
    }

    function editadvertisement($add_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($add_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement'));
        }
        $template['page'] = 'Advertisement/Advertisement_view';
        $template['pTitle'] = "Edit Advertisement Data";
        $template['pDescription'] = "Edit Data";
        $template['menu'] = "Edit Data";
        $template['smenu'] = "Edit Data";
        $template['add_id'] = $add_id;
        $add_id = decode_param($add_id);
        $template['notificationData'] = $this->Advertisement_model->get_Advertisement($add_id);
        $this->load->view('template', $template);
    }

    public function createadvertisement() {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement/addadvertisement'));
        }
        if ($err == 0 && (!isset($_POST['add_name']) || empty($_POST['add_name']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 0 && (!isset($_POST['starting_time']) || empty($_POST['starting_time']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 0 && (!isset($_FILES['image']) || empty($_FILES['image']))) {
            $err = 1;
            $errMsg = 'Provide necessary image';
        }
        $_POST['image'] = '';
        if ($err == 0) {
            $config = set_upload_service("assets/uploads/advertisement");
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
            redirect(base_url('advertisement/addadvertisement'));
        }
        $status = $this->Advertisement_model->createAdvertisement($_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Advertisement Created';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('advertisement/addadvertisement'));
    }

    function updateadvertisement($add_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($add_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement'));
        }
        $err = 0;
        $errMsg = '';
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement/editadvertisement/' . $id));
        }
        if ($err == 0 && (!isset($_POST['add_name']) || empty($_POST['add_name']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }

        if ($err == 0 && (!isset($_POST['starting_time']) || empty($_POST['starting_time']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 0 && (!isset($_POST['ending_time']) || empty($_POST['ending_time']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }
        $_POST['image'] = '';
        if ($err == 0) {
            $config = set_upload_service("assets/uploads/advertisement");
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
            redirect(base_url('advertisement/editadvertisement/' . $id));
        }
        $add_id = decode_param($add_id);
        $status = $this->Advertisement_model->updateNotif($add_id, $_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Advertisement Details Updated';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('advertisement/editadvertisement/' . $id));
    }

    function changestatus($add_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($add_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('advertisement'));
        }
        $add_id = decode_param($add_id);
        $status = $this->Advertisement_model->changeStatus($add_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('advertisement'));
    }

}

?>