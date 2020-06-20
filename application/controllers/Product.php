<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Product_model');
        $this->load->model('Stores_model');
        $this->load->model('Category_model');
        if(!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        
    }
    
    public function addproduct(){
        $template['page'] = 'Product/addproduct';
        $template['pTitle'] = "Add Product";
        $template['pDescription'] = "Add Product"; 
        $template['menu'] = "Product Management";
        $template['smenu'] = "View Product";
        $template['category_data'] = $this->Category_model->get_category();
        $shopper_id = ($this->session->userdata['user_type']==2)?$this->session->userdata['id']:''; 
        //print_r($shopper_id);exit;
        $template['store_data'] = $this->Stores_model->getStores($shopper_id);
        $this->load->view('template',$template);
    }
    
    public function viewproducts(){
        $template['page'] = 'Product/viewProduct';
        $template['menu'] = 'Product Management';
        $template['smenu'] = 'View Products';
        $template['pTitle'] = "View Products";
        $template['pDescription'] = "View and Manage Product";
        $shopper_id = ($this->session->userdata['user_type']==2)?$this->session->userdata['id']:'';
        //print_r($shopper_id);exit;
        $template['product_data'] = $this->Product_model->getProduct($shopper_id);
        $this->load->view('template',$template);
    }
    
    function changestatus($product_id = '', $status = '1'){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($product_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/viewproducts'));
        }
        $status = $this->Product_model->changeStatus($product_id, $status);
        if(!$status){
            $this->session->set_flashdata('message',$flashMsg);
        }
        redirect(base_url('product/viewproducts'));
    }
    
    public function createproduct(){
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('Product/addproduct'));
        }else if($err == 0 && (!isset($_POST['product_name']) || empty($_POST['product_name']))){
            $err = 1;
            $errMsg = 'Provide a Product Name';
        }else if($err == 0 && (!isset($_POST['product_price']) || empty($_POST['product_price']))){
            $err = 1;
            $errMsg = 'Provide a Amount';
        } else if($err == 0 && (!isset($_FILES['product_image']) || empty($_FILES['product_image']))){
            $err = 1;
            $errMsg = 'Provide Product Picture';
        }
        $_POST['product_image'] = '';
        if($err == 0){
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time()."_".$_FILES['product_image']['name'];
            $this->upload->initialize($config);
            if(!$this->upload->do_upload('product_image')){
                $err = 1;
                $errMsg = $this->upload->display_errors();
            }
            else{
                $upload_data = $this->upload->data();
                $_POST['product_image'] = $config['upload_path']."/".$upload_data['file_name'];
            }
        }
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/addproduct'));
        }
        
        $status = $this->Product_model->createProduct($_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Product Created';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/viewproducts'));
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('product/addproduct'));
    }
    
    public function editproduct($product_id){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($product_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/viewproducts'));
        }
        $template['page'] = 'Product/addproduct';
        $template['menu'] = "ProductProduct Management";
        $template['smenu'] = "Edit Product";
        $template['pDescription'] = "Edit Product Details";
        $template['pTitle'] = "Edit Product";
        $template['product_id'] = $product_id;
        $product_id = decode_param($product_id);
        //print_r($product_id);exit;
        $template['product_data'] = $this->Product_model->getProduct($shopper_id='',$product_id);
        //print_r($template1);exit;
        $this->load->view('template',$template);
    }
    
    
    public function updateproduct($product_id = ''){
        
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($product_id) || !isset($_POST) || empty($_POST) || !is_numeric(decode_param($product_id))){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/viewproducts'));
        }else if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/addproduct'));
        }else if($err == 0 && (!isset($_POST['product_name']) || empty($_POST['product_name']))){
            $err = 1;
            $errMsg = 'Provide a Product Name';
        }else if($err == 0 && (!isset($_POST['product_price']) || empty($_POST['product_price']))){
            $err = 1;
            $errMsg = 'Provide a Amount';
        } else if($err == 0 && (!isset($_FILES['product_image']['name']) || empty($_FILES['product_image']['name']))){
            $err = 1;
            $errMsg = 'Provide Product Picture';
        }
        
        $_POST['product_image'] = '';
        if($err == 0){
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $config['file_name'] = time()."_".$_FILES['product_image']['name'];
            $this->upload->initialize($config);
            if(!$this->upload->do_upload('product_image')){
                $err = 1;
                $errMsg = $this->upload->display_errors();
            }else{
                $upload_data = $this->upload->data();
                $_POST['product_image'] = $config['upload_path']."/".$upload_data['file_name'];
            }
        }
       
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/editproduct/'.$product_id));
            
        }
        
        $status = $this->Product_model->updateProduct(decode_param($product_id),$_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'Product Updated';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('product/viewproducts'));
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('product/addproduct'));
    }
    public function getproductdata(){
        $return_arr = array('status'=>'0');
        if(!isset($_POST)||empty($_POST)||!isset($_POST['product_id'])||empty($_POST['product_id'])){
            echo json_encode($return_arr);exit;
        }
        $product_id = decode_param($_POST['product_id']);
        $product_data = $this->Product_model->get_product_data(array('product_id'=>$product_id));
        if(!empty($product_data)){
            $return_arr['status'] = 1;
            $return_arr['product_data'] = $product_data;
        }
        echo json_encode($return_arr);exit;
    }

    
}
?>
