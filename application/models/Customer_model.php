<?php

class Customer_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    function getCustomer() {
        $this->db->select("user_id, fullname, email, phone_no, district, status");
        $this->db->from('user_profile');
        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result;
        }
    }

    function get_customer($customer_id = '', $view_all = 0) {
        $cond = ($view_all != 0) ? ' status IN (0,1) ' : ' status IN (1) ';
        $cond .= (!empty($customer_id)) ? " AND user_id = '$customer_id[customer_id]'" : "";
        $result = $this->db->query("SELECT * FROM user_profile WHERE $cond");
        $val = $result->result();
        //print_r($val);exit;
        if (empty($val)) {
            return;
        }
        return (empty($customer_id)) ? $result->result() : $result->row();
    }

    function createCustomer($customer_data = array()) {
        if (empty($customer_data))
            return 0;
        if (isset($customer_data['email']) && !empty($customer_data['email'])) {
            $emailChk = $this->db->get_where('user_profile', array('email' => $customer_data['email'], 'status !=' => '2'));
            if (!empty($emailChk) && $emailChk->num_rows() > 0) {
                return 2;
            }
        }
        if (isset($customer_data['phone_no']) && !empty($customer_data['phone_no'])) {
            $phoneChk = $this->db->get_where('user_profile', array('phone_no' => $customer_data['phone_no'], 'status !=' => '2'));
            if (!empty($phoneChk) && $phoneChk->num_rows() > 0) {
                return 3;
            }
        }
        $customer_data['status'] = 1;
        $status = $this->db->insert('user_profile', $customer_data);
        return ($status) ? 1 : 0;
        ;
    }

    function updateCustomer($customer_id = '', $customer_data = array()) {
        if (empty($customer_id) || empty($customer_data))
            return 0;
        $emailChk = $this->db->get_where('user_profile', array('email' => $customer_data['email'],
            'user_id !=' => $customer_id,
            'status !=' => '2'));
        if (!empty($emailChk) && $emailChk->num_rows() > 0) {
            return 2;
        }
        $phoneChk = $this->db->get_where('user_profile', array('phone_no' => $customer_data['phone_no'],
            'user_id !=' => $customer_id,
            'status !=' => '2'));
        if (!empty($phoneChk) && $phoneChk->num_rows() > 0) {
            return 3;
        }
        $status = $this->db->update('user_profile', $customer_data, array('user_id' => $customer_id));
        return ($status) ? 1 : 0;
        ;
    }

    function checkCustomerLogin($userLogData) {
        $respArr = array('status' => 0);
        if (empty($userLogData)) {
            return $returnStatus;
        }
        $result = $this->db->get_where('customers', array('email' => $userLogData['email'], 'status' => '1'));
        if (empty($result) || $result->num_rows() < 1 || empty($custData = $result->row())) {
            $respArr['status'] = 2;
            return $respArr;
        }
        $result = $this->db->get_where('customers', array('email' => $userLogData['email'],
            'password' => $userLogData['password'],
            'status' => '1'));
        $respArr['status'] = 3;
        if (!empty($result) && $result->num_rows() == 1 && !empty($custData = $result->row())) {
            $respArr['data'] = $custData;
            $respArr['status'] = 1;
        }
        return $respArr;
    }

    function changeStatus($customer_id = '', $status = '0') {
        if (empty($customer_id)) {
            return 0;
        }
        $status = $this->db->update('user_profile', array('status' => $status), array('user_id' => $customer_id));
        return $status;
    }

}

?>