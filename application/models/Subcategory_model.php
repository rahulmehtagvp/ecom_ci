<?php

class Subcategory_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    function getsubcategory() {
        $result = $this->db->query("SELECT CT.*,ST.category_name FROM sub_category CT join categories ST on ST.category_id=CT.category_id where CT.status!=2");
        if (empty($result)) {
            return;
        }
        $ret_data = $result->result();
        //print_r($ret_data);exit;
        return $ret_data;
    }

    function createsubcategory($subcategory_data = array(), $status = '1') {
        $subcategory_data['status'] = 1;
        $status = $this->db->insert('sub_category', $subcategory_data);
        $last_id = $this->db->insert_id();
        $res = array('status' => 1, 'data' => '');
        //print_r($res);exit;
        return ($status) ? 1 : 0;
    }

    function updatesubcategory($customerIdDec = '', $subcategory_data) {
        //print_r($customerIdDec);exit;
        $status = $this->db->update('sub_category', $subcategory_data, array('subcat_id' => $customerIdDec));
        return ($status) ? 1 : 0;
    }

    function get_subcategory($subcat_id = '', $view_all = 0) {
        $cond = ($view_all != 0) ? ' CT.status IN (0,1) ' : ' CT.status IN (1) ';
        $cond .= (!empty($subcat_id)) ? " AND CT.subcat_id = '$subcat_id'" : "";

        $result = $this->db->query("SELECT CT.*,ST.category_name FROM sub_category CT 
                                    join categories ST on ST.category_id=CT.category_id
                                    WHERE $cond");

        $val = $result->result();
        //print_r($val);exit;
        if (empty($val)) {
            return;
        }
        return (empty($subcat_id)) ? $result->result() : $result->row();
    }

    function changestatus($category_id = '', $status = '0') {
        if (empty($category_id)) {
            return 0;
        }
        $status = $this->db->update('sub_category', array('status' => $status), array('subcat_id' => $category_id));
        return $status;
    }

}

?>