<?php

class Dashboard_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function gettotalorder() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->get('orders')->result();
            return count($result);
        } else {
            $id = $this->session->userdata('id');
            $result = $this->db->query("SELECT od.* FROM shopper as sh 
                                        join stores as st on st.store_id = sh.store_id
                                        join orders as od on od.store_id = st.store_id 
                                        where sh.shopper_id=$id");
            $res = $result->result();
            return count($res);
        }
        /* $this->db->join('orders','orders.store_id = stores.store_id','left');
          $this->db->join('stores','stores.store_id = shopper.store_id','left');
          $result = $this->db->get_where('shopper',array('shopper.shopper_id'=>$id,'shopper.status'=>'3'))->result(); */
    }

    public function getcompleteorder() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->query("SELECT * from orders where status=1");
            $res = $result->result();
            return count($res);
        } else {
            $id = $this->session->userdata('id');
            $result = $this->db->query("SELECT od.* FROM shopper as sh 
                                        join stores as st on st.store_id = sh.store_id
                                        join orders as od on od.store_id = st.store_id 
                                        where od.status=1 and  sh.shopper_id=$id");
            $res = $result->result();
            return count($res);
        }
        /* $this->db->join('orders','orders.store_id = stores.store_id','left');
          $this->db->join('stores','stores.store_id = shopper.store_id','left');
          $result = $this->db->get_where('shopper',array('shopper.shopper_id'=>$id,'shopper.status'=>'3'))->result(); */
    }

    public function getpendingorder() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->query("SELECT * from orders where status!=1");
            $res = $result->result();
            return count($res);
        } else {
            $id = $this->session->userdata('id');
            $result = $this->db->query("SELECT od.* FROM shopper as sh 
                                        join stores as st on st.store_id = sh.store_id
                                        join orders as od on od.store_id = st.store_id 
                                        where od.status!=1 and  sh.shopper_id=$id");
            $res = $result->result();
            return count($res);
        }
        /* $this->db->join('orders','orders.store_id = stores.store_id','left');
          $this->db->join('stores','stores.store_id = shopper.store_id','left');
          $result = $this->db->get_where('shopper',array('shopper.shopper_id'=>$id,'shopper.status'=>'3'))->result(); */
    }

    public function getstoreCount() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->get_where('stores', array('status' => '1'))->result();
            return count($result);
        }
    }

    public function getcustomerCount() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->get_where('user_profile', array('status' => '1'))->result();
            return count($result);
        }
    }

    public function getcategoryCount() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->get_where('categories', array('status' => '1'))->result();
            return count($result);
        }
    }

    public function getsubcategoryCount() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->get_where('sub_category', array('status' => '1'))->result();
            return count($result);
        }
    }

    public function getproductCount() {
        if ($this->session->userdata('user_type') == 1) {
            $result = $this->db->get_where('products', array('status' => '1'))->result();
            return count($result);
        } else {
            $id = $this->session->userdata('id');
            $result = $this->db->query("SELECT od.* FROM shopper as sh 
                                        join stores as st on st.store_id = sh.store_id
                                        join products as od on od.store_id = st.store_id 
                                        where sh.shopper_id=$id");
            $res = $result->result();
            //print_r($res);exit;
            return count($res);
        }
    }

    public function getSalesReportCount() {
        $query = $this->db->query("SELECT COUNT(ORDS.order_id) AS count FROM `orders` AS `ORDS` WHERE `ORDS`.`status` = '1'");
        $result = array();
        if (empty($query) || $query->num_rows < 0 || empty($query = $query->result_array())) {
            return $result;
        }
        foreach ($query as $value) {
            $result[] = array('item1' => $value['count']);
        }
        return json_encode($result);
    }

}

?>