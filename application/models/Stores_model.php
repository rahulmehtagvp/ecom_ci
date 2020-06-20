<?php

class Stores_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function createStore($store_data = array()) {
        if (empty($store_data)) {
            return 0;
        }
        $store_data['status'] = 1;
        $status = $this->db->insert('stores', $store_data);
        $last_id = $this->db->insert_id();
        if (!empty($last_id)) {
            $this->db->set('status', 1);
            $this->db->set('store_id', $last_id);
            $sts = $this->db->insert('store_product');
        }
        return ($status) ? 1 : 0;
    }

    function updatestore($customerIdDec = '', $store_data) {

        $status = $this->db->update('stores', $store_data, array('store_id' => $customerIdDec));
        return ($status) ? 1 : 0;
    }

    function get_stores($store_id = '', $view_all = 0) {

        $cond = (!empty($store_id)) ? " store_id = $store_id[store_id]" : "";
        $result = $this->db->query("SELECT * FROM stores WHERE $cond and status=1");
        $val = $result->result();
        //print_r($val);exit;
        if (empty($val)) {
            return;
        }
        return (empty($store_id)) ? $result->result() : $result->row();
    }

    public function getStores($store_id = '') {
        //print_r($store_id);exit;
        if (!empty($shopper_id)) {
            $this->db->select('store_id');
            $this->db->where('shopper_id', $shopper_id);
            $message = $this->db->get('shopper');
            $re = $message->row_array();
            $store = $re['store_id'];
            //
            $result = $this->db->query("SELECT stores.* FROM stores WHERE store_id=$store");
            return $result->result();
        }
        if (!empty($store_id)) {
            $result = $this->db->query("SELECT stores.* FROM stores WHERE store_id=$store_id");
            return $result->result();
        }
        $this->db->where('status!=', 0);
        $this->db->select("store_id, store_name,store_image, description, status");
        $this->db->from('stores');
        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result;
        }
    }

    public function changeStatus($store_id = '', $status = '0') {
        if (empty($store_id)) {
            return 0;
        }
        $result = $this->db->update('stores', array('status' => $status), array('store_id' => $store_id));
        //print_r($this->db->last_query());exit;
        $status = $this->db->update('store_product', array('status' => $status), array('store_id' => $store_id));
        return $status;
    }

}
