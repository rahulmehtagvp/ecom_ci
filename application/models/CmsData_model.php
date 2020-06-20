<?php

class CmsData_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function createCms($cms_data) {
        if (!isset($cms_data) || empty($cms_data)) {
            return 0;
        }
        $cms_data['status'] = 1;
        $notifData = $this->db->insert('cms_data', $cms_data);
        if ($notifData) {
            return 1;
        }
        return 0;
    }

    function get_CmsData($cms_id = '', $view_all = 0) {
        //print_r($cms_id);exit;
        $cond = ($view_all != 0) ? ' status IN (0,1) ' : ' status IN (1) ';
        $cond .= (!empty($cms_id)) ? " AND cms_id = '$cms_id'" : "";
        $result = $this->db->query("SELECT * FROM cms_data WHERE $cond");
        $val = $result->result();
        if (empty($val)) {
            return;
        }
        return (empty($cms_id)) ? $result->result() : $result->row();
    }

    public function updateNotif($cms_id, $notifData) {
        //print_r($cms_id);exit;
        if (empty($notifData)) {
            return 0;
        }
        $status = $this->db->update('cms_data', $notifData, array('cms_id' => $cms_id));
        //print_r($this->db->last_query());exit;
        return $status;
    }

    function changeStatus($cms_id = '', $status = '0') {
        if (empty($cms_id)) {
            return 0;
        }
        $status = $this->db->update('cms_data', array('status' => $status), array('cms_id' => $cms_id));
        return $status;
    }

}

?>