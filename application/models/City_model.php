<?php

class City_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function createCity($city_data = array()) {
        if (empty($city_data)) {
            return 0;
        }
        $city_data['status'] = 1;
        $status = $this->db->insert('cities', $city_data);
        return ($status) ? 1 : 0;
    }

    public function getCities($city_id = '') {
        //print_r($city_id);exit;
        if (!empty($city_id)) {
            $cond = (!empty($city_id)) ? " city_id = '$city_id'" : "";
            $result = $this->db->query("SELECT * from cities  where status!=2 and $cond");
            $re = $result->result();
            //print_r($re);exit;
            if (!empty($re)) {
                return (empty($city_id)) ? $result->result() : $result->row();
            }
        }
        $result = $this->db->query("SELECT * from cities where status!=2");
        $re = $result->result();
        return $re;
    }

    function updatecity($city_id, $city_data) {
        if (empty($city_id)) {
            return 0;
        }
        $status = $this->db->update('cities', $city_data, array('city_id' => $city_id));
        return ($status) ? 1 : 0;
    }

    public function changeStatus($city_id = '', $status = '0') {
        if (empty($city_id)) {
            return 0;
        }
        $status = $this->db->update('cities', array('status' => $status), array('city_id' => $city_id));
        return $status;
    }

}
