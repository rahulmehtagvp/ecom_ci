<?php

class Advertisement_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function createAdvertisement($add_data) {
        if (!isset($add_data) || empty($add_data)) {
            return 0;
        }
        $add_data['status'] = 1;
        $notifData = $this->db->insert('advertisement', $add_data);
        if ($notifData) {
            return 1;
        }
        return 0;
    }

    function get_Advertisement($add_id = '', $view_all = 0) {
        $cond = (!empty($add_id)) ? " AND add_id = '$add_id'" : "";
        $result = $this->db->query("SELECT * FROM advertisement WHERE status!=2 $cond");
        $val = $result->result();
        if (empty($val)) {
            return;
        }
        return (empty($add_id)) ? $result->result() : $result->row();
    }

    public function updateNotif($add_id, $notifData) {
        //print_r($add_id);exit;
        if (empty($notifData)) {
            return 0;
        }
        $status = $this->db->update('advertisement', $notifData, array('add_id' => $add_id));
        //print_r($this->db->last_query());exit;
        return $status;
    }

    function changeStatus($add_id = '', $status = '0') {
        if (empty($add_id)) {
            return 0;
        }
        $status = $this->db->update('advertisement', array('status' => $status), array('add_id' => $add_id));
        return $status;
    }

}

?>