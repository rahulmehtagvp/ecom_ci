<?php

class Order_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function getOrders($shopper_id = '', $view_all = '') {
        //print_r($shopper_id);exit;
        if (empty($shopper_id)) {
            $this->db->select("ORD.*,PRD.product_name,CUST.fullname");
            $this->db->from('orders AS ORD');
            $this->db->join('products AS PRD', 'PRD.product_id = ORD.product_id');
            $this->db->join('user_profile AS CUST', 'CUST.user_id = ORD.user_id');
            $result = $this->db->get()->result();
            return $result;
        }
        $this->db->select('store_id');
        $this->db->where('shopper_id', $shopper_id);
        $message = $this->db->get('shopper');
        $re = $message->row_array();
        $store = $re['store_id'];

        $this->db->select('user_id');
        $this->db->where('store_id', $store);
        $message = $this->db->get('orders');
        $re = $message->row_array();
        $user_id = $re['user_id'];

        $result = $this->db->query("SELECT orders.*,products.*,stores.*,user_profile.fullname FROM shopper 
                                    JOIN stores on stores.store_id = $store
                                    JOIN orders on orders.store_id = stores.store_id
                                    JOIN user_profile on user_profile.user_id=$user_id
                                    join products
                                    WHERE shopper.shopper_id=$shopper_id group by order_id");
        if (empty($result)) {
            return;
        }
        return $result->result();
        if (!empty($result)) {
            return $result;
        }
    }

    public function getOrderDetails($order_id) {
        if ($order_id == '') {
            return 0;
        }
        $result = $this->db->query("SELECT ORD.*,PRD.product_name,CUST.fullname
                                    FROM orders AS ORD 
                                    JOIN products AS PRD on PRD.product_id = ORD.product_id 
                                    JOIN user_profile AS CUST on CUST.user_id = ORD.user_id 
                                    WHERE ORD.order_id = $order_id");
        //print_r($this->db->last_query());exit;
        if (empty($result)) {
            return;
        }
        return (empty($order_id)) ? $result->result() : $result->row();
    }

    public function getProductImage($order_id) {
        $result = $this->db->query("SELECT PRDI.product_image FROM orders AS ORD join products AS PRDI on PRDI.product_id = ORD.product_id WHERE ORD.order_id = $order_id");

        return (empty($result)) ? '' : $result->result();
    }

    public function changeOrderStatus($order_id, $data = array()) {
        $order_id = decode_param($order_id);
        if (empty($order_id) || $data['status'] == '' || empty($data['scheduled_date'])) {
            return 0;
        }
        if ($data['status'] == '3' || $data['status'] == '4') {
            $data['scheduled_date'] = $data['scheduled_date'];
        } else if ($data['status'] == '5') {
            $data['delivered'] = $data['scheduled_date'];
        }
        $status = 10;
        if ($this->db->update('orders', $data, array('order_id' => $order_id))) {
            $status = 1;
        }
        return $status;
    }

}

?>