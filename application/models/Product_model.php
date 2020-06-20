<?php 
class Product_model extends CI_Model {
	
	public function _consruct(){
		parent::_construct();
	}
	
	public function createProduct($product_data = array()){
		//print_r($product_data);exit;
		$product_data['status']=1;
		$result=$this->db->insert('products',$product_data);
		$last_id= $this->db->insert_id();
		$res=$this->db->query("SELECT product_id from products where product_id=$last_id");
		$pro=$res->row();
		//print_r($pro->product_id);exit;
		$res=$this->db->insert('store_product',array('store_id'=>$product_data['store_id'],'product_id'=>$pro->product_id,
								'delete_status'=>1,'status'=>1));
		//print_r($this->db->last_query());exit;
		return $result;
	}
	
	function getProduct($shopper_id='',$product_id=''){
		//print_r($product_id);exit;
		if(!empty($shopper_id)){
		$this->db->select('store_id');
		$this->db->where('shopper_id', $shopper_id);
		$message= $this->db->get('shopper');
		$re= $message->row_array();
		$store=$re['store_id'];
		$result = $this->db->query("SELECT PRD.*,CG.*,ST.* FROM products as PRD 
                                            join categories as CG on CG.category_id=PRD.category_id 
                                            join stores as ST on ST.store_id=$store
                                            where PRD.store_id=$store group by PRD.product_id");
		if(empty($result)){return;}
		return $result->result();
		}
		if(empty($shopper_id) && empty($product_id)){	
			//print_r($product_id);exit;
			$sql =$this->db->query("SELECT PRD.*,CG.category_name,ST.store_name
                                                FROM products AS PRD
                                                LEFT JOIN categories AS CG ON (PRD.category_id=CG.category_id AND CG.status='1')
                                                LEFT JOIN stores AS ST ON (PRD.store_id=ST.store_id AND ST.status='1')
                                                WHERE  PRD.status='1'");
			$productData = $sql->result();
			return $productData;
		}
		if(!empty($product_id)){
			//print_r($product_id);exit;
			$result =$this->db->query("SELECT PRD.*,CG.category_name,ST.store_name
                                                    FROM products AS PRD
                                                    LEFT JOIN categories AS CG ON (PRD.category_id=CG.category_id AND CG.status='1')
                                                    LEFT JOIN stores AS ST ON (PRD.store_id=ST.store_id AND ST.status='1')
                                                    WHERE PRD.product_id=$product_id and PRD.status='1'");
			$val=$result->result();
			//print_r($val);exit;
			if(empty($val)){
				return $val;
			}
	
			//print_r($val);exit;
			return (empty($product_id))?$result->result():$result->row();
	
		}
		
	}
	public function getProudctData($product_id){
		$result =$this->db->query("SELECT PRD.*
		FROM products AS PRD
		WHERE PRD.product_id=$product_id and PRD.status='1'");
		return $val=$result->result();
		
	}
	
	function changeStatus($product_id,$status = '0'){
		if(empty($product_id)){
			return 0;
		}
		$status = $this->db->update('products',array('status'=>$status), array('product_id'=>decode_param($product_id)));
		return $status;
	}
	
	function updateProduct($product_id = '', $product_data = array()){
		if(empty($product_id) || empty($product_data)){
			return 0;
		}
		$status = $this->db->update('products',$product_data,array('product_id'=>$product_id));
		return ($status)?1:0;
	}
	function get_product_data($product_id,$view_all = 0){
		$cond = ($view_all != 0)?' PRD.status IN (0,1) ':' PRD.status IN (1) ';
		$cond .= (!empty($product_id))?" AND PRD.product_id = '$product_id[product_id]'":"";
		$result = $this->db->query("SELECT PRD.*,CG.category_name,ST.store_name
                                            FROM products AS PRD
                                            LEFT JOIN categories AS CG ON (PRD.category_id=CG.category_id AND CG.status='1')
                                            LEFT JOIN stores AS ST ON (PRD.store_id=ST.store_id AND ST.status='1') WHERE $cond");
        $val=$result->result();
		//print_r($this->db->last_query());exit;
		//print_r($val);exit;
		if(empty($val)){
			return;
		}
		return (empty($product_id))?$result->result():$result->row();
	}
	
}
?>