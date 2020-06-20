<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Dashboard_model');
		if(!$this->session->userdata('logged_in')) {
			redirect(base_url('login'));
		}
 	}
	
	public function index() {
		$template['page'] = 'Dashboard';
        $template['page_desc'] = "Control Panel";
        $template['page_title'] = "Dashboard";
		$template['totalorder'] = $this->Dashboard_model->gettotalorder();
		$template['storeCount'] = $this->Dashboard_model->getstoreCount();
		$template['productCount'] = $this->Dashboard_model->getproductCount();
		$template['customerCount'] = $this->Dashboard_model->getcustomerCount();
		$template['categoryCount'] = $this->Dashboard_model->getcategoryCount();
		$template['subcategoryCount'] = $this->Dashboard_model->getsubcategoryCount();
		$template['completeorderCount'] = $this->Dashboard_model->getcompleteorder();
		$template['pendingorderCount'] = $this->Dashboard_model->getpendingorder();
		$this->load->view('template',$template);
	}
	public function getOrderSalesReportCount(){
		$result = $this->Dashboard_model->getSalesReportCount();
		if(count($result) > 0){	
			echo $result;
		}else{
			echo 1;
		}
	}
}
?>