<?php

class User_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    function getUserData($user_id = '', $user_type = '') {
        $user_id = (empty($user_id)) ? $this->session->userdata('id') : $user_id;
        $user_type = (empty($user_type)) ? $this->session->userdata('user_type') : $user_type;

        $result = $this->db->get_where('admin_users', array('status' => '1', 'id' => $user_id));
        if (empty($result)) {
            return 0;
        }
        $result = $result->row();
        $result->shopper_data = '';
        $result->mechanic_shop_data = '';
        if ($user_type == 2) {
            $this->load->model('Shopper_model');
            $result->shopper_data = $this->Shopper_model->getShopper($user_id);

            if (!empty($result->shopper_data->shop_id)) {
                $this->load->model('Store_model');
                $shop_data = $this->Store_model->getStore($result->shopper_data->shop_id);

                if (!empty($shop_data)) {
                    $result->mechanic_shop_data = $shop_data;
                }
            }
        }
        return $result;
    }

    function updateUser($user_id = '', $user_type = '', $user_data = array()) {
        if (empty($user_id) || empty($user_type) || empty($user_data)) {
            return 0;
        }
        $userIdChk = $this->db->query("SELECT * FROM shopper AS MECH 
                                       INNER JOIN admin_users AS AU ON (AU.id = MECH.shopper_id)
                                       WHERE AU.status!='2' AND AU.id!='" . $user_id . "' AND 
                                       AU.username='" . $shopper_data['username'] . "'");
        if (!empty($userIdChk) && $userIdChk->num_rows() > 0) {
            return 4;
        }
        if ($user_type == 2) {
            $emailChk = $this->db->query("SELECT * FROM shopper AS MECH 
                                          INNER JOIN admin_users AS AU ON (AU.id = MECH.shopper_id)
                                          WHERE AU.status!='2' AND AU.id!='" . $user_id . "' AND 
                                          MECH.email='" . $user_data['email_id'] . "'");
            if (!empty($emailChk) && $emailChk->num_rows() > 0) {
                return 2;
            }
            $phoneChk = $this->db->query("SELECT * FROM shopper AS MECH 
                                          INNER JOIN admin_users AS AU ON (AU.id = MECH.shopper_id)
                                          WHERE AU.status!='2' AND AU.id!='" . $user_id . "' AND 
                                          MECH.phone_no='" . $user_data['phone'] . "'");
            if (!empty($phoneChk) && $phoneChk->num_rows() > 0) {
                return 3;
            }
        }
        $admUpArr = array('username' => $user_data['username'], 'display_name' => $user_data['display_name']);
        if (!empty($user_data['profile_image'])) {
            $admUpArr['profile_image'] = $user_data['profile_image'];
        }
        if (!empty($user_data['password'])) {
            $admUpArr['password'] = $user_data['password'];
        }
        $status = $this->db->update('admin_users', $admUpArr, array('id' => $user_id));
        if (!$status) {
            return 0;
        }

        if ($user_type == 2) {
            $insertArr = array('shopper_name' => $user_data['display_name'], 'email' => $user_data['email_id'],
                'store_id' => $user_data['store'], 'phone_no' => $user_data['phone']);
            $status = $this->db->update('shopper', $insertArr, array('shopper_id' => $user_id));
        }
        $usrData = $this->getUserData($user_id, $user_type);
        if (!empty($usrData)) {
            $this->session->set_userdata('user', $usrData);
            $this->session->set_userdata('shopper_data', $usrData->shopper_data);
            $this->session->set_userdata('mechanic_shop_data', $usrData->mechanic_shop_data);
        }
        return $status;
    }

}

?>