<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cmsdata extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CmsData_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if ($this->session->userdata['user_type'] != 1) {
            $flashMsg = array('message' => 'Access Denied You don\'t have permission to access this Page',
                'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url());
        }
    }

    public function index() {
        $template['page'] = 'CmsData/CmsData_list';
        $template['menu'] = "CMS DATA";
        $template['smenu'] = "Change CMS DATA";
        $template['pTitle'] = "CMS DATA";
        $template['page_head'] = "CMS DATA";
        $template['pDescription'] = "Change CMS DATA";
        $template['notificationData'] = $this->CmsData_model->get_CmsData();
        //print_r($template['notificationData']);exit;
        $this->load->view('template', $template);
    }

    public function addcms() {
        $template['page'] = 'CmsData/CmsData_view';
        $template['pTitle'] = "Add Cms Data";
        $template['pDescription'] = "Create Data";
        $template['menu'] = "Add Data";
        $template['smenu'] = "Add Data";
        $this->load->view('template', $template);
    }

    function editcms($cms_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($cms_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata'));
        }
        $template['page'] = 'CmsData/CmsData_view';
        $template['pTitle'] = "Edit Cms Data";
        $template['pDescription'] = "Edit Data";
        $template['menu'] = "Edit Data";
        $template['smenu'] = "Edit Data";
        $template['cms_id'] = $cms_id;
        $cms_id = decode_param($cms_id);
        $template['notificationData'] = $this->CmsData_model->get_CmsData($cms_id);
        $this->load->view('template', $template);
    }

    public function createcms() {
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata/addcms'));
        }
        if ($err == 0 && (!isset($_POST['identifier']) || empty($_POST['identifier']))) {
            $err = 0;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 0 && (!isset($_POST['data']) || empty($_POST['data']))) {
            $err = 0;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata/addcms'));
        }
        //print_r($_POST);exit;
        $status = $this->CmsData_model->createCms($_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Cms Created';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('Cmsdata/addcms'));
    }

    function updatecms($cms_id = '') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($cms_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata'));
        }
        $err = 0;
        $errMsg = '';
        if (!isset($_POST) || empty($_POST)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata/editcms/' . $id));
        }
        if ($err == 0 && (!isset($_POST['identifier']) || empty($_POST['identifier']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 0 && (!isset($_POST['data']) || empty($_POST['data']))) {
            $err = 1;
            $errMsg = 'Provide necessary informations';
        }
        if ($err == 1) {
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata/editcms/' . $id));
        }
        $cms_id = decode_param($cms_id);
        $status = $this->CmsData_model->updateNotif($cms_id, $_POST);
        if ($status == 1) {
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Cms Details Updated';

            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata'));
        }
        $this->session->set_flashdata('message', $flashMsg);
        redirect(base_url('cmsdata/editcms/' . $id));
    }

    function changestatus($cms_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($cms_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('cmsdata'));
        }
        $cms_id = decode_param($cms_id);
        $status = $this->CmsData_model->changeStatus($cms_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('cmsdata'));
    }

}

?>