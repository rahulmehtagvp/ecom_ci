<?php

class Promocode_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    function getpromocode() {
        $result = $this->db->query("SELECT * FROM promocodes where status=1");
        if (empty($result)) {
            return;
        }
        $ret_data = $result->result();
        return $ret_data;
    }

    function get_promoData($promo_id = '', $view_all = 0) {
        $cond = ($view_all != 0) ? ' status IN (0,1) ' : ' status IN (1) ';
        $cond .= (!empty($promo_id)) ? " AND promo_id = '$promo_id'" : "";
        $result = $this->db->query("SELECT * FROM promocodes WHERE $cond");
        $val = $result->result();
        //print_r($val);exit;
        //print_r($this->db->last_query());exit;
        if (empty($val)) {
            return;
        }
        return (empty($promo_id)) ? $result->result() : $result->row();
    }

    function createpromocode($promo_data = array()) {
        //print_r($promo_data);exit;
        if (empty($promo_data)) {
            return 0;
        }
        if (!empty($promo_data['promo_code'])) {
            $promo_code = $this->db->get_where('promocodes', array('promo_code' => $promo_data['promo_code'], 'status=' => '1'));
            if (!empty($promo_code) && $promo_code->num_rows() > 0) {
                return 2;
            }
            $promo_data['status'] = 1;
            $status = $this->db->insert('promocodes', $promo_data);
            $res = array('status' => 1, 'data' => '');
            return ($status) ? 1 : 0;
            ;
        }
    }

    function updatepromocode($customerIdDec = '', $promo_data) {
        $status = $this->db->update('promocodes', $promo_data, array('promo_id' => $customerIdDec));
        return ($status) ? 1 : 0;
        ;
    }

    function get_promocode($promo_id = '', $view_all = 0) {
        $cond = ($view_all != 0) ? ' status IN (0,1) ' : ' status IN (1) ';
        $cond .= (!empty($promo_id)) ? " AND promo_id = '$promo_id'" : "";
        //print_r($cond);exit;
        $result = $this->db->query("SELECT * FROM promocodes WHERE $cond");
        $val = $result->result();
        if (empty($val)) {
            return;
        }

        //print_r($val);exit;
        return (empty($promo_id)) ? $result->result() : $result->row();
    }

    function changeStatus($promo_id = '', $status = '0') {
        if (empty($promo_id)) {
            return 0;
        }
        $status = $this->db->update('promocodes', array('status' => $status), array('promo_id' => $promo_id));
        return $status;
    }

}

?>