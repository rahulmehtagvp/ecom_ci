<?php

class Store_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    function getstore($store_id) {
        //print_r($shopper_id);exit;
        if (empty($store_id)) {
            $result = $this->db->query("SELECT ST.*,GROUP_CONCAT(DISTINCT PRD.product_name)AS product_name  
                                        FROM store_product as SP  LEFT JOIN stores AS ST ON (SP.store_id=ST.store_id) 
                                        left join products as PRD  on (SP.product_id=PRD.product_id ) and PRD.status=1
                                        where ST.status!=0 and SP.status!=0 and SP.delete_status=1 GROUP BY SP.store_id");
            if (empty($result)) {
                return;
            }
            $res_data = $result->result();
            return $res_data;
        }
        if (!empty($store_id)) {
            $result = $this->db->query("SELECT ST.*,GROUP_CONCAT(DISTINCT PRD.product_name)AS product_name  
                                        FROM store_product as SP  LEFT JOIN stores AS ST ON (SP.store_id=ST.store_id) 
                                        left join products as PRD  on (SP.product_id=PRD.product_id ) and PRD.status=1
                                        where SP.store_id=$store_id and ST.status=1 and SP.status=1 and SP.delete_status=1 GROUP BY ST.store_id");
            if (empty($result)) {
                return;
            }
            $res_data = $result->result();
            return $res_data;
        }
    }

    function getproduct($store_id = '', $view_all = 0) {
        //print_r($store_id);exit;
        $query = $this->db->query("SELECT * from products");
        if (!empty($store_id)) {
            $query = $this->db->query("SELECT SP.*,PRD.product_name
                                    FROM store_product as SP 
                                    left join products as PRD  on (SP.product_id=PRD.product_id ) and 
                                        PRD.status=1
                                    where SP.store_id=$store_id  and SP.status=1 and SP.delete_status=1 
                                    GROUP BY PRD.product_id");
            //print_r($this->db->last_query());exit;
            return $store_data = $query->result();
        }
        return $store_data = $query->result();
    }

    function changeStatus($store_id = '', $status = '0') {
        if (empty($store_id)) {
            return 0;
        }
        //print_r($store_id);exit;
        $result = $this->db->update('stores', array('status' => $status), array('store_id' => $store_id));
        return $result;
    }

    public function insertstore_product($store_id) {
        $result = $this->db->query("SELECT * from store_product where store_id=$store_id[store_id] and 
                                        product_id=$store_id[product_id] and status=1");
        if ($result->num_rows() > 0) {
            $resut = $this->db->update('store_product', array('delete_status' => 1), array('store_id' => $store_id['store_id'], 'product_id' => $store_id['product_id']));
            //print_r($this->db->last_query());exit;
            return $result;
        }
        $store_id['status'] = 1;
        $store_id['delete_status'] = 1;
        $result = $this->db->insert('store_product', $store_id);
    }

    public function deletestore_product($store_id) {
        $result = $this->db->update('store_product', array('delete_status' => 0), array('store_id' => $store_id['store_id'], 'product_id' => $store_id['product_id']));
    }

    function get_storeData($store_id, $view_all = 0) {
        $cond = ($view_all != 0) ? ' SP.status IN (0,1) ' : ' SP.status IN (1) ';
        $cond .= (!empty($store_id)) ? " AND SP.store_id = '$store_id[store_id]'" : "";
        $result = $this->db->query("SELECT ST.*,GROUP_CONCAT(DISTINCT PRD.product_name)AS product_name  
                                    FROM store_product as SP  LEFT JOIN stores AS ST ON (SP.store_id=ST.store_id) 
                                    left join products as PRD  on (SP.product_id=PRD.product_id ) and PRD.status=1 WHERE $cond");
        $val = $result->result();
        //print_r($this->db->last_query());exit;
        //print_r($val);exit;
        if (empty($val)) {
            return;
        }
        return (empty($store_id)) ? $result->result() : $result->row();
    }

}

?>