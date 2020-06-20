<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Order_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
    }

    public function listorders() {
        $template['page'] = 'Orders/orders_list';
        $template['pTitle'] = "View Orders";
        $template['pDescription'] = "View and Manage Orders";
        $template['menu'] = "Order Management";
        $template['smenu'] = "View Orders";
        $shopper_id = ($this->session->userdata['user_type'] == 2) ? $this->session->userdata['id'] : '';
        //print_r($shopper_id);exit;
        $template['orderData'] = $this->Order_model->getOrders($shopper_id);
        //print_r($template1);exit;

        $this->load->view('template', $template);
    }

    public function getorderdata() {
        $return_arr = array('status' => '0');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['order_id']) || empty($_POST['order_id']) || empty(decode_param($_POST['order_id']))) {
            echo json_encode($return_arr);
            exit;
        }
        $order_id = decode_param($_POST['order_id']);
        $return_arr['order_data'] = $this->Order_model->getOrderDetails($order_id);
        $return_arr['product_image'] = $this->Order_model->getProductImage($order_id);
        if (!empty($return_arr)) {
            $return_arr['status'] = 1;
            echo json_encode($return_arr);
            exit;
        }
        echo json_encode($return_arr);
        exit;
    }

    public function changeorderstatus() {
        $status = 0;
        $return_arr = array('status' => '10');
        if (!isset($_POST) || empty($_POST) || !isset($_POST['order_id']) || empty($_POST['order_id']) || empty(decode_param($_POST['order_id']))) {
            echo json_encode($return_arr);
            exit;
        }
        $status = $this->Order_model->changeOrderStatus($order_id, $_POST);
        $return_arr['status'] = $status;
        echo json_encode($return_arr);
        exit;
    }

}

?>
