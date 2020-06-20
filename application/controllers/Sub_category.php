<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Subcategory_model');
        $this->load->model('Category_model');
        if(!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if($this->session->userdata['user_type'] != 1){
            redirect(base_url('dashboard'));
        }
    }
    
    public function addsubcategory(){
        $template['page'] = 'sub_category/subcategoryadd_view';
        $template['pTitle'] = "Add New sub category";
        $template['pDescription'] = "Create New sub category";
        $template['menu'] = "Subcategory Management";
        $template['smenu'] = "Add sub category";
        $template['category_data'] = $this->Category_model->get_category();
        $this->load->view('template',$template);
    }
    
    public function listsubcat(){
        $template['page'] = 'sub_category/subcategory_view';
        $template['pTitle'] = "View subcategory";
        $template['pDescription'] = "View and Manage subcategory"; 
        $template['menu'] = "Subcategory Management";
        $template['smenu'] = "View subcategory";
        $template['scatData'] = $this->Subcategory_model->getsubcategory();
        $this->load->view('template',$template);
    }
    
    public function createsubcategory(){
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/addsubcategory'));
        }
        if($err == 0 && (!isset($_POST['subcategory_name']) || empty($_POST['subscategory_name']))){
            $err = 0;
            $errMsg = 'Provide a sub category name';
        }
        $_POST['image'] = '';
        if($err == 0){
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time()."_".$_FILES['image']['name'];
            $this->upload->initialize($config);
            if(!$this->upload->do_upload('image')){
                $err = 1;
                $errMsg = $this->upload->display_errors();
            }else{
                $upload_data = $this->upload->data();
                $_POST['image'] = $config['upload_path']."/".$upload_data['file_name'];
            }
        }
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/addsubcategory'));
        }
        $status = $this->Subcategory_model->createsubcategory($_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'sub category Created';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/listsubcat'));
        }else if($status == 2){
            $flashMsg['message'] = 'sub category already in use.';
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('category/addcategory'));
    }
    
    public function getsubcategorydata(){
        $return_arr = array('status'=>'0');
        if(!isset($_POST)||empty($_POST)||!isset($_POST['subcat_id'])||empty($_POST['subcat_id'])){
            echo json_encode($return_arr);exit;
        }
        $subcat_id = decode_param($_POST['subcat_id']);
        //$product_data = $this->Product_model->get_product_data(array('product_id'=>$product_id));
        $subcat_data = $this->Subcategory_model->get_subcategory($subcat_id);
        if(!empty($subcat_data)){
            $return_arr['status'] = 1;
            $return_arr['subcat_data'] = $subcat_data;
        }
        echo json_encode($return_arr);exit;
    }
    
    function changestatus($category_id = '',$status = '1'){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($category_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/listsubcat'));
        }
        $category_id = decode_param($category_id);
        $status = $this->Subcategory_model->changeStatus($category_id,$status);
        if(!$status){
            $this->session->set_flashdata('message',$flashMsg);
        }
        redirect(base_url('sub_category/listsubcat'));
    }
    
    public function editsubcategory($subcat_id){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($subcat_id) || !is_numeric($subcat_id = decode_param($subcat_id))){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/subcategoryadd_view'));
        }
        $template['page'] = 'sub_category/subcategoryadd_view';
        $template['menu'] = 'sub category Management';
        $template['smenu'] = 'Edit sub category';
        $template['pTitle'] = "Edit sub category";
        $template['pDescription'] = "Update subcategory Data";
        $template['subcat_id'] = encode_param($subcat_id);
        $template['subcategory_data'] = $this->Subcategory_model->get_subcategory($subcat_id);
        $this->load->view('template',$template);
    }
    
    function updatesubcategory($subcat_id = ''){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($subcat_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/listsubcat'));
        }
        $customerIdDec = decode_param($subcat_id);
        $err = 0;
        $errMsg = '';
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/addcategory'));
        }
        if($err == 0 && (!isset($_POST['subcategory_name']) || empty($_POST['subcategory_name']))){
            $err = 1;
            $errMsg = 'Provide a sub category name';
        }
        
        $config = set_upload_service("assets/uploads/services");
        $this->load->library('upload');
        $config['file_name'] = time()."_".$_FILES['image']['name'];
        $this->upload->initialize($config);
        if($this->upload->do_upload('image')){
            $upload_data = $this->upload->data();
            $_POST['image'] = $config['upload_path']."/".$upload_data['file_name'];
        }
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('category/editsubcategory/'.$category_id));
        }
        
        $status = $this->Subcategory_model->updatesubcategory($customerIdDec,$_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'sub category Details Updated';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('sub_category/listsubcat'));
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('sub_category/editsubcategory/'.$subcat_id));
    }
}
?>