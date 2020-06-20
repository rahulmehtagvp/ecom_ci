<?php

class Category_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    function get_category($category_id = '', $view_all = 0) {
        $cond = ($view_all != 0) ? ' CA.status IN (0,1) ' : ' CA.status IN (1) ';
        $cond .= (!empty($category_id)) ? " AND category_id = '$category_id'" : "";
        $result = $this->db->query("SELECT CA.category_id,CA.category_name,CA.category_image,CA.status as category_status,ST.* FROM categories CA left join stores ST
                                     ON CA.store_id = ST.store_id WHERE $cond");

        $val = $result->result();
        //print_r($val);exit;
        if (empty($val)) {
            return;
        }
        return (empty($category_id)) ? $result->result() : $result->row();
    }

    function createcategory($category_data = array(), $status = '1') {
        //print_r($category_data);exit;
        $category_data['status'] = 1;
        $status = $this->db->insert('categories', $category_data);
        $last_id = $this->db->insert_id();
        $res = array('status' => 1, 'data' => '');
        //print_r($res);exit;
        return ($status) ? 1 : 0;
    }

    function updatecategory($customerIdDec = '', $category_data) {
        //print_r($category_data);exit;
        $status = $this->db->update('categories', $category_data, array('category_id' => $customerIdDec));
        return ($status) ? 1 : 0;
        ;
    }

    function changeStatus($category_id = '', $status = '0') {
        if (empty($category_id)) {
            return 0;
        }
        $status = $this->db->update('categories', array('status' => $status), array('category_id' => $category_id));
        return $status;
    }

}

?>