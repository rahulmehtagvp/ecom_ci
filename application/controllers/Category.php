<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Category_model');
        $this->load->model('Stores_model');
        if(!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if($this->session->userdata['user_type'] != 1){
            redirect(base_url('dashboard'));
        }
    }
    
    public function addcategory(){
        $template['page'] = 'category/categoryadd_view';
        $template['pTitle'] = "Add New category";
        $template['pDescription'] = "Create New category";
        $template['menu'] = "Category Management";
        $template['smenu'] = "Add category";
        $template['store_data'] = $this->Stores_model->getStores();
        $this->load->view('template',$template);
    }
    
    public function listcategory(){
        $template['page'] = 'category/category_view';
        $template['pTitle'] = "View category";
        $template['pDescription'] = "View and Manage category"; 
        $template['menu'] = "Category Management";
        $template['smenu'] = "View category";
        $template['categoryData'] = $this->Category_model->get_category();
        $this->load->view('template',$template);
    }
    
    public function createcategory(){
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('Category/addcategory'));
        }
        if($err == 0 && (!isset($_POST['category_name']) || empty($_POST['category_name']))){
            $err = 0;
            $errMsg = 'Provide a category name';
        }
        
        $_POST['category_image'] = '';
        if($err == 0){
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time()."_".$_FILES['category_image']['name'];
            $this->upload->initialize($config);
            if(!$this->upload->do_upload('category_image')){
                $err = 1;
                $errMsg = $this->upload->display_errors();
            }else{
                $upload_data = $this->upload->data();
                $_POST['category_image'] = $config['upload_path']."/".$upload_data['file_name'];
            }
        }
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/addcategory'));
        }
        $status = $this->Category_model->createcategory($_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'category Created';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('Category/listcategory'));
        }else if($status == 2){
            $flashMsg['message'] = 'category already in use.';
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('category/addcategory'));
    }
    public function getcategorydata(){
        $return_arr = array('status'=>'0');
        if(!isset($_POST)||empty($_POST)||!isset($_POST['category_id'])||empty($_POST['category_id'])){
            echo json_encode($return_arr);exit;
        }
        $category_id = decode_param($_POST['category_id']);
        $category_data = $this->Category_model->get_category($category_id);
        if(!empty($category_data)){
            $return_arr['status'] = 1;
            $return_arr['category_data'] = $category_data;
        }
        echo json_encode($return_arr);exit;
    }
    
    function changestatus($category_id = '',$status = '1'){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($category_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/listcategory'));
        }
        $category_id = decode_param($category_id);
        $status = $this->Category_model->changeStatus($category_id,$status);
        if(!$status){
            $this->session->set_flashdata('message',$flashMsg);
        }
        redirect(base_url('category/listcategory'));
    }
    
    public function editcategory($category_id){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($category_id) || !is_numeric($category_id = decode_param($category_id))){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/categoryadd_view'));
        }
        $template['page'] = 'category/categoryadd_view';
        $template['menu'] = 'Category Management';
        $template['smenu'] = 'Edit category';
        $template['pTitle'] = "Edit category";
        $template['pDescription'] = "Update category Data";
        $template['category_id'] = encode_param($category_id);
        $template['category_data'] = $this->Category_model->get_category($category_id);
        $this->load->view('template',$template);
    }
    
    function updatecategory($category_id = ''){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($category_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/listcategory'));
        }
        $customerIdDec = decode_param($category_id);
        $err = 0;
        $errMsg = '';
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/addcategory'));
        }
        if($err == 0 && (!isset($_POST['category_name']) || empty($_POST['category_name']))){
            $err = 1;
            $errMsg = 'Provide a category name';
        }
        
        
        $config = set_upload_service("assets/uploads/services");
        $this->load->library('upload');
        $config['file_name'] = time()."_".$_FILES['category_image']['name'];
        $this->upload->initialize($config);
        if($this->upload->do_upload('category_image')){
            $upload_data = $this->upload->data();
            $_POST['category_image'] = $config['upload_path']."/".$upload_data['file_name'];
        }
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/editcategory/'.$category_id));
        }
        
        $status = $this->Category_model->updatecategory($customerIdDec,$_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'category Details Updated';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/listcategory'));
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('category/editcategory/'.$category_id));
    }
}
?>