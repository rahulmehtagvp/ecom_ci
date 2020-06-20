<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Store_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
    }

    public function listStores($store_id = '') {
        //print_r($store_id);exit;
        if (!empty($store_id)) {
            $store_id = (!is_numeric($store_id)) ? decode_param($store_id) : $store_id;
        }
        $store_data = '';
        if ($this->session->userdata('user_type') == 1) {
            $this->load->model('Stores_model');
            $store_data = $this->Stores_model->getStores('');
            //print_r($store_data);exit;
            if (isset($_POST['store_id']) && !empty($_POST['store_id'])) {
                $store_id = $_POST['store_id'];
                $store_id = (!is_numeric($store_id)) ? decode_param($store_id) : $store_id;
            }
        }
        $template['page'] = 'store/store_view';
        $template['pTitle'] = "View store";
        $template['pDescription'] = "View and Manage store";
        $template['menu'] = "store Management";
        $template['smenu'] = "View store";
        $template['store_id'] = ($this->session->userdata('user_type') == 1 &&
                empty($store_id)) ? '' : $store_id;
        $template['storedata'] = $store_data;
        $template['storeData'] = $this->Store_model->getstore($store_id);
        //print_r($template['storeData']);exit;
        $this->load->view('template', $template);
    }

    public function editstore($store_id) {
        //print_r(decode_param($store_id));exit;
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($store_id) || !is_numeric($store_id = decode_param($store_id))) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('Store/storeadd_view'));
        }
        $template['page'] = 'store/storeadd_view';
        $template['menu'] = 'Store Management';
        $template['smenu'] = 'Edit Product';
        $template['pTitle'] = "Edit Product";
        $template['pDescription'] = "Update Store Data";
        $template['store_id'] = encode_param($store_id);
        $template['store_data'] = $this->Store_model->getproduct();
        //print_r($template['store_data']);exit;
        $this->load->view('template', $template);
    }

    public function mapstore() {
        $store_id = array(
            'store_id' => $this->input->post("store_id"),
            'product_id' => $this->input->post('product_id')
        );
        //print_r($store_id);exit;
        $this->Store_model->insertstore_product($store_id);
        redirect('Store/liststores');
    }

    public function deletestore($store_id) {
        //print_r(decode_param($store_id));exit;
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($store_id) || !is_numeric($store_id = decode_param($store_id))) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('Store/storeadd_view'));
        }
        $template['page'] = 'Store/storedelete_view';
        $template['menu'] = 'Store Management';
        $template['smenu'] = 'Delete Product';
        $template['pTitle'] = "Delete Product";
        $template['pDescription'] = "Update Store Data";
        $template['store_id'] = encode_param($store_id);
        $template['store_data'] = $this->Store_model->getproduct($store_id, 1);
        //print_r($template['store_data']);exit;
        $this->load->view('template', $template);
    }

    public function deletestore_product() {
        $store_id = array(
            'store_id' => $this->input->post("store_id"),
            'product_id' => $this->input->post('product_id')
        );
        $this->Store_model->deletestore_product($store_id);
        redirect('Store/liststores');
    }

    function changeStatus($store_id = '', $status = '1') {
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($store_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('Store/listStores'));
        }
        $store_id = decode_param($store_id);
        $status = $this->Store_model->changeStatus($store_id, $status);
        if (!$status) {
            $this->session->set_flashdata('message', $flashMsg);
        }
        redirect(base_url('Store/listStores'));
    }

    public function getStoreData() {
        $return_arr = array('status' => '0');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['store_id']) || empty($_POST['store_id'])) {
            echo json_encode($return_arr);
            exit;
        }
        $store_id = decode_param($_POST['store_id']);
        $store_data = $this->Store_model->get_storeData(array('store_id' => $store_id));
        if (!empty($store_data)) {
            $return_arr['status'] = 1;
            $return_arr['store_data'] = $store_data;
        }
        echo json_encode($return_arr);
        exit;
    }

}

?>