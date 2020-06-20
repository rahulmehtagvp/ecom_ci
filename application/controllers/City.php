<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class City extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('City_model');
        if(!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
        if($this->session->userdata['user_type'] != 1){
            redirect(base_url('dashboard'));
        }
    }
    
    public function addnewcity(){
        $template['page'] = 'City/addcityview';
        $template['pTitle'] = "Add New City";
        $template['pDescription'] = "Create New City";
        $template['menu'] = "City Management";
        $template['smenu'] = "Add City";
        $this->load->view('template',$template);
    }
    
    public function listcities(){
        $template['page'] = 'City/city_list';
        $template['pTitle'] = "View Cities";
        $template['pDescription'] = "View and Manage Cities"; 
        $template['menu'] = "Cities Management";
        $template['smenu'] = "View Cities";
        $template['city_data'] = $this->City_model->getCities();
        $this->load->view('template',$template);
    }
    
    public function createcity(){
        $err = 0;
        $errMsg = '';
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/addnewcity'));
        }
        if($err == 0 && (!isset($_POST['zip_code']) || empty($_POST['zip_code']))){
            $err = 1;
            $errMsg = 'Provide a zip code';
        }else if($err == 0 && (!isset($_POST['location']) || empty($_POST['location']))){
            $err = 1;
            $errMsg = 'Provide a location';
        }
        if($err == 1){
            $flashMsg['message'] = $errMsg;
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/addnewcity'));
        }
        
        $status = $this->City_model->createCity($_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'City Created';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/listcities'));
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('city/addnewcity'));
    }
    
    public function getcitydata(){
        $return_arr = array('status'=>'0');
        if(!isset($_POST)||empty($_POST)||!isset($_POST['city_id'])||empty($_POST['city_id'])){
            echo json_encode($return_arr);exit;
        }
        $city_id = decode_param($_POST['city_id']);
        $city_data = $this->City_model->getCities($city_id);
        if(!empty($city_data)){
            $return_arr['status'] = 1;
            $return_arr['city_data'] = $city_data;
        }
        echo json_encode($return_arr);exit;
    }
    public function editcity($city_id){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($city_id) || !is_numeric($city_id = decode_param($city_id))){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/listcities'));
        }
        $template['page'] = 'City/addcityview';
        $template['menu'] = 'City Management';
        $template['smenu'] = 'Edit city';
        $template['pTitle'] = "Edit city";
        $template['pDescription'] = "Update city Data";
        $template['city_id'] = encode_param($city_id);
        //print_r($template1);exit;
        $template['city_data'] = $this->City_model->getCities($city_id);
        //print_r($template1);exit;
        $this->load->view('template',$template);
    }
    
    function updatecity($city_id = ''){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($city_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/listcities'));
        }
        $city_id = decode_param($city_id);
        $err = 0;
        $errMsg = '';
        if(!isset($_POST) || empty($_POST)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/addcityview'));
        }
        if($err == 0 && (!isset($_POST['zip_code']) || empty($_POST['zip_code']))){
            $err = 1;
            $errMsg = 'Provide a zipcode';
        }
        else if($err == 0 && (!isset($_POST['location']) || empty($_POST['location']))){
            $err = 1;
            $errMsg = 'Provide a location';
        }
        
        $status = $this->City_model->updatecity($city_id,$_POST);
        if($status == 1){
            $flashMsg['class'] = 'success';
            $flashMsg['message'] = 'City Updated';
            
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/listcities'));
        }
        $this->session->set_flashdata('message',$flashMsg);
        redirect(base_url('city/editcity/'.$city_id));
    }
    
    function changestatus($city_id = '',$status = '1'){
        $flashMsg = array('message'=>'Something went wrong, please try again..!','class'=>'error');
        if(empty($city_id)){
            $this->session->set_flashdata('message',$flashMsg);
            redirect(base_url('city/listcities'));
        }
        $city_id = decode_param($city_id);
        $status = $this->City_model->changeStatus($city_id,$status);
        if(!$status){
            $this->session->set_flashdata('message',$flashMsg);
        }
        redirect(base_url('city/listcities'));
    }
    
}
?>
