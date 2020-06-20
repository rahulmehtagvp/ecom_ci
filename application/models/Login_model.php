<?php

class Login_model extends CI_Model {

    public function _construct() {
        parent::_construct();
    }

    public function login($username, $password) {
        $query = $this->db->get_where('admin_users', array('username' => $username,
            'password' => $password
        ));
        if ($query->num_rows() > 0 && !empty($query)) {
            $result = $query->row();
            $result->shopper_data = '';
            $result->shop_data = '';
            if ($result->user_type == 2) {
                $this->load->model('Shopper_model');
                $result->shopper_data = $this->Shopper_model->getShopper($result->id);
                if (!empty($result->shopper_data->shop_id)) {
                    $this->load->model('Shop_model');
                    $shop_data = $this->Shop_model->getShop($result->shopper_data->shop_id);
                    if (!empty($shop_data)) {
                        $result->shop_data = $shop_data;
                    }
                }
            }
        } else {
            $result = 0;
        }
        return $result;
    }

}

?>