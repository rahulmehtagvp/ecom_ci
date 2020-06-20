<?php

class Web_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function generateUnique() {
        $unqe = md5(uniqid(time() . mt_rand(), true));
        return $unqe;
    }

    public function EncryptedPatientKey($unique_id, $user_id) {
        $this->db->insert('auth_table', array('user_id' => $user_id, 'unique_id' => $unique_id, 'status' => 1));
    }

    public function EncryptedPatientKey_select($auth = array()) {
        if (!isset($auth) || empty($auth)) {
            return false;
        }
        $this->db->select('user_id');
        $this->db->where('unique_id', $auth);
        $auth_token = $this->db->get('auth_table');
        $re = $auth_token->row_array();
        //print_r($this->db->last_query());exit;
        return $re;
    }

    public function pdid_select($data = array(), $user_id = array()) {
        if (!isset($data) || empty($data) || !isset($user_id) || empty($user_id)) {
            return false;
        }
        $this->db->select('product_id');
        $this->db->where('user_id', $user_id['user_id']);
        $this->db->where('product_id', $data['product_id']);
        $message = $this->db->get('cart');
        $re = $message->row_array();
        return $re;
    }

    public function advertisement() {
        $date = date('Y-m-h');
        $qry = $this->db->query("SELECT * from advertisement where  ending_time>$date and status=1");
        if ($qry) {
            if ($qry->num_rows() > 0) {
                $val = $qry->result();
                return $result = array('status' => 'success', 'data' => $val);
            } else {
                return $result = array('status' => 'success', 'data' => "No current data available");
            }
        }
        return false;
    }

    public function user_signup($postdata = array()) {
        if (!isset($postdata['fullname']) || empty($postdata['fullname']) || !isset($postdata['email']) || empty($postdata['email']) || !isset($postdata['phone_no']) || empty($postdata['phone_no']) || !isset($postdata['district']) || empty($postdata['district']) || !isset($postdata['password']) || empty($postdata['password'])) {
            return false;
        }
        $status = $this->db->insert('user_profile', array('fullname' => $postdata['fullname'], 'email' => $postdata['email'], 'district' => $postdata['district'], 'phone_no' => $postdata['phone_no'], 'password' => md5($postdata['password']), 'status' => 1));
        $this->db->where("phone_no = '" . $postdata['phone_no'] . "'");
        $this->db->where('password', md5($postdata['password']));
        $query = $this->db->get('user_profile');
        $unique_id = $this->generateUnique();
        $rs = $query->row();
        //print_r($rs);exit;
        $this->EncryptedPatientKey($unique_id, $rs->user_id);
        //print_r($rs->user_id);exit;
        $qry = $this->db->query("SELECT a.unique_id as auth_token,u.user_id,u.phone_no from auth_table a join user_profile u 
                                 where a.user_id=$rs->user_id and u.user_id=$rs->user_id");
        if ($qry->num_rows() > 0) {
            $val = $qry->row();
            return $result = array('status' => 'success', 'data' => $val);
        }
    }

    public function phoneno_select($request = array()) {
        if (!isset($request) || empty($request)) {
            return false;
        }
        $this->db->select('phone_no');
        $this->db->where('phone_no', $request['phone_no']);
        $phone_no = $this->db->get('user_profile');
        $re = $phone_no->row_array();
        //print_r($this->db->last_query());exit;
        return $re;
    }

    public function email_select($request = array()) {
        if (!isset($request) || empty($request)) {
            return false;
        }
        $this->db->select('email');
        $this->db->where('email', $request['email']);
        $email = $this->db->get('user_profile');
        $re = $email->row_array();
        return $re;
    }

    public function zipcode_select($data = array()) {
        if (!isset($data) || empty($data)) {
            return false;
        }
        $this->db->select('zip_code');
        $this->db->where('zip_code', $data['zip_code']);
        $zip_code = $this->db->get('cities');
        $re = $zip_code->row_array();
        //print_r($this->db->last_query());exit;
        return $re;
    }

    public function addressid_select($user_id = array(), $data = array()) {
        if (!isset($data) || empty($data) || !isset($user_id) || empty($user_id)) {
            return false;
        }
        $this->db->select('address_id');
        $this->db->where('address_id', $data['address_id']);
        $this->db->where('user_id', $user_id['user_id']);
        $zip_code = $this->db->get('address');
        $re = $zip_code->row_array();
        //print_r($this->db->last_query());exit;
        return $re;
    }

    public function storeid_select($data = array()) {
        if (!isset($data) || empty($data)) {
            return false;
        }
        $this->db->select('store_id');
        $this->db->where('store_id', $data['store_id']);
        $zip_code = $this->db->get('stores');
        $re = $zip_code->row_array();
        //print_r($this->db->last_query());exit;
        return $re;
    }

    public function cityid_select($data = array()) {
        if (!isset($data) || empty($data)) {
            return false;
        }
        $this->db->select('city_id');
        $this->db->where('city_id', $data['city_id']);
        $zip_code = $this->db->get('cities');
        $re = $zip_code->row_array();
        //print_r($this->db->last_query());exit;
        return $re;
    }

    public function user_forgot($request = array()) {
        if (!isset($request) || empty($request)) {
            return false;
        }
        $this->db->where("phone_no = '" . $request['phone_no'] . "'");
        $this->db->set('password', md5($request['password']));
        $status = $this->db->update('user_profile');
        if ($status) {
            return 1;
        }
        return 0;
    }

    public function search_all_categories($request = array()) {
        if (!empty($request)) {
            $result = $this->db->query("SELECT category_id,category_name,category_image from categories where store_id=$request[store_id] and status=1");
            if ($result->num_rows() > 0) {
                $val = $result->result();
                return $res = array('status' => 'success', 'data' => $val);
            }
        } else {
            $result = $this->db->query("SELECT category_id,category_name,category_image from categories where status=1");
            if ($result->num_rows() > 0) {
                $val = $result->result();
                return $res = array('status' => 'success', 'data' => $val);
            }
            return 0;
        }
    }

    public function search_category_store($postdata, $data = array()) {
        //print_r($data);exit;
        if (!isset($postdata['keyword']) || empty($postdata['keyword']) ||
                !isset($data['city_id']) || empty($data['city_id'])) {
            return 0;
        }
        $query = $this->db->query("SELECT s.store_id,s.store_name,group_concat(p.product_name) as product_name,p.product_price,cz.zip_code,cz.location,s.description 
                                   FROM stores s 
                                   LEFT JOIN products p on s.store_id=p.store_id or product_name LIKE '%" . $postdata['keyword'] . "%'
                                   LEFT JOIN categories c ON c.category_id=p.category_id
                                   LEFT JOIN cities cz ON s.city_id=cz.city_id
                                   WHERE store_name LIKE '%" . $postdata['keyword'] . "%' OR 
                                   product_name LIKE '%" . $postdata['keyword'] . "%' AND cz.city_id='" . $data['city_id'] . "' 
                                   and s.store_id=c.store_id AND c.status=1 group by s.store_id");
        //print_r($this->db->last_query());exit;
        if ($query->num_rows() > 0) {
            $res = $query->result();
            //print_r($res->store_name);exit;
            return $result = array('status' => 'success', 'data' => $res);
        }
        return 0;
    }

    public function cart_add($postdata = array(), $user_id) {
        if (!isset($user_id) || empty($user_id) || !isset($postdata['product_id']) || empty($postdata['product_id']) || !isset($postdata['quantity']) || empty($postdata['quantity'])) {
            return false;
        }
        $result = $this->db->query("SELECT cart_id,product_id,quantity from cart where user_id = '" . $user_id['user_id'] . "' and product_id = '" . $postdata['product_id'] . "' and status=1");
        if ($result->num_rows() > 0) {
            $val = $result->result();
            return $res = array('status' => 'success', 'data' => 'Product already exist in cart');
        }
        $status = $this->db->insert('cart', array('user_id' => $user_id['user_id'], 'product_id' => $postdata['product_id'], 'quantity' => $postdata['quantity'], 'status' => 1));
        $last_id = $this->db->insert_id();
        if ($status) {
            $result = $this->db->query("SELECT cart_id,product_id,quantity from cart where cart_id=$last_id");
            $val = $result->row();
            return $res = array('status' => 'success', 'data' => $val);
        }
        return 0;
    }

    public function cart_update($user_id = array(), $postdata = array()) {
        if (!isset($user_id) || empty($user_id) || !isset($postdata) || empty($postdata)) {
            return false;
        }
        $result = $this->db->query("SELECT * from cart where user_id = '" . $user_id['user_id'] . "' and product_id = '" . $postdata['product_id'] . "' and status=1");
        if ($result->num_rows() > 0) {
            $this->db->where("product_id = '" . $postdata['product_id'] . "'");
            $this->db->where("user_id = '" . $user_id['user_id'] . "'");
            $this->db->where('status =', 1);
            $this->db->set('quantity', $postdata['quantity']);
            $status = $this->db->update('cart');
            if ($status) {
                $result = $this->db->query("SELECT cart_id,product_id,quantity from cart where user_id = '" . $user_id['user_id'] . "' and product_id = '" . $postdata['product_id'] . "' and status=1");
                $val = $result->row();
                return $res = array('status' => 'success', 'data' => $val);
            }
            return $res = array('status' => 'error');
        }
        return $res = array('status' => 'success', 'data' => 'no such item in cart');
    }

    public function item_delete($user_id = array(), $productid = array()) {
        if (!isset($user_id) || empty($user_id) || !isset($productid) || empty($productid)) {
            return false;
        }
        $this->db->where('product_id', $productid['product_id']);
        $this->db->where('user_id', $user_id['user_id']);
        $this->db->set('status', 2);
        $status = $this->db->update('cart');
        if ($status) {
            return $res = array('status' => 'success');
        }
        return 0;
    }

    public function addressDelete($user_id = array(), $addressid = array()) {
        if (!isset($user_id) || empty($user_id) || !isset($addressid) || empty($addressid)) {
            return false;
        }
        $this->db->where('address_id', $addressid['address_id']);
        $this->db->where('user_id', $user_id['user_id']);
        $this->db->set('status', 2);
        $status = $this->db->update('address');
        if ($status) {
            return $result = array('status' => 'success');
        }
        return 0;
    }

    public function user_order($user_id = array(), $start, $per_page) {
        if (!isset($user_id) || empty($user_id)) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query = $this->db->query("SELECT booking_id,booking_date,total_amount,scheduled_date,status FROM orders 
                                   where user_id=" . $user_id['user_id'] . " order by scheduled_date DESC $lmt");
        if ($query->num_rows() > 0) {
            $orderData = $query->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $orderData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function find_location($zipcode = array(), $user_id = array()) {
        if (!isset($zipcode) || empty($zipcode) ||
                !isset($user_id) || empty($user_id)) {
            return false;
        }
        $query = $this->db->query("SELECT city_id,zip_code,location FROM cities where zip_code='" . $zipcode['zip_code'] . "' and status=1");
        //print_r($val = $query->result());exit;
        if ($query->num_rows() > 0) {
            $val = $query->row();
            //print_r($val->city_id);exit;
            $query = $this->db->query("UPDATE user_profile set city_id=$val->city_id where user_id=$user_id[user_id]");
            return $result = array('status' => 'success', 'data' => $val);
        }
        return 0;
    }

    public function find_product($data = array()) {
        if (!isset($data) || empty($data)) {
            return false;
        }
        $query = $this->db->query("SELECT c.category_id,category_name,s.product_name,s.product_image,s.product_id,s.product_price from stores m 
                                   join products s join categories c on s.category_id=c.category_id 
                                   WHERE m.store_id=" . $data['store_id'] . " and c.category_id=" . $data['category_id'] . " and m.store_id=s.store_id and m.status=1");
        if ($query->num_rows() > 0) {
            $val = $query->result();
            return $result = array('status' => 'success', 'data' => $val);
        }
        return 0;
    }

    public function select_cart($user_id = array()) {
        if (!isset($user_id) || empty($user_id)) {
            return false;
        }
        $query = $this->db->query("SELECT s.product_id,s.product_image, s.product_name,m.quantity,s.product_price from cart m 
                                   join products s 
                                   WHERE m.user_id=" . $user_id['user_id'] . " and m.product_id=s.product_id and m.status=1");
        if ($query) {
            if ($query->num_rows() > 0) {
                $val = $query->result();
                return $result = array('status' => 'success', 'data' => $val);
            } else {
                return $result = array('status' => 'success', 'data' => 'no data');
            }
        }
        return 0;
    }

    public function find_city($city_id = array()) {
        if (!isset($city_id) || empty($city_id)) {
            return false;
        }
        $query = $this->db->query("SELECT city_id,zip_code,location FROM cities  where city_id=$city_id[city_id]");
        if ($query->num_rows() > 0) {
            $val = $query->row();
            return $result = array('status' => 'success', 'data' => $val);
        }
        return 0;
    }

    public function get_userid($auth_token = array()) {
        if (!isset($auth_token) || empty($auth_token)) {
            return 0;
        }
        $user_id = $this->db->query("SELECT user_id FROM auth_table WHERE unique_id ='" . $auth_token . "'AND status=1");
        return $user_id->row_array();
        if ($user_id) {
            return 1;
        }
        return 0;
    }

    public function get_user($user_id = array()) {
        if (!isset($user_id['user_id']) || empty($user_id['user_id'])) {
            return 0;
        }
        $query = $this->db->query("SELECT city_id,user_id,email,phone_no,fullname FROM user_profile WHERE user_id='" . $user_id['user_id'] . "'AND status=1");
        if ($query->num_rows() > 0) {
            $res = $query->row();
            return $result = array('status' => 'success',
                'user_id' => $res->user_id,
                'email' => $res->email, 'phone_no' => $res->phone_no,
                'city_id' => $res->city_id, 'fullname' => $res->fullname);
        } else {
            return 0;
        }
    }

    public function addAddress($user_id = array(), $request = array()) {
        $query = $this->db->insert('address', array('street_address' => $request['street'],
            'landmark' => $request['landmark'],
            'zip_code' => $request['zipcode'],
            'user_id' => $user_id['user_id'],
            'status' => 1
                )
        );
        $last_id = $this->db->insert_id();
        //print_r($last_id);exit;
        $result = $this->db->query("select user_id,address_id,street_address,landmark,zip_code from address where address_id=$last_id");
        if ($result) {
            $val = $result->row();
            return $res = array("status" => 'success', "data" => array('user_id' => $val->user_id, 'address_id' => $val->address_id, 'address' => array($val->street_address, $val->landmark, $val->zip_code)));
        }
        return 0;
    }

    public function selectAddress($user_id, $request = array()) {
        $query = $this->db->query("SELECT up.user_id,up.fullname,ad.address_id,ad.street_address,ad.landmark,ad.zip_code
                                   FROM address ad
                                   LEFT JOIN user_profile up ON up.user_id=ad.user_id
                                   WHERE ad.address_id='" . $request['address_id'] . "'AND ad.user_id='" . $user_id['user_id'] . "' and ad.status=1");
        if ($query->num_rows() > 0) {
            $res = $query->row();
            return $result = array('status' => 'success', 'data' => $res);
        } else {
            return 0;
        }
    }

    public function showAllAddress($user_id) {
        if (empty($user_id) || !isset($user_id)) {
            return 0;
        }
        $query = $this->db->query("SELECT address_id,street_address,landmark,zip_code FROM `address` where user_id= '" . $user_id['user_id'] . "' and `status`=1");
        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function editProfile($user_id = array(), $request = array()) {
        if (!isset($user_id) || empty($user_id) || !isset($request) || empty($request)) {
            return 0;
        }
        $query = $this->db->query("UPDATE user_profile SET fullname='" . $request['fullname'] . "',
                                   email='" . $request['email'] . "',phone_no='" . $request['phone_no'] . "'
                                   WHERE user_id='" . $user_id['user_id'] . "' AND status=1");
        if ($query) {
            $result = $this->db->query("select user_id,fullname,email,phone_no from user_profile where user_id='" . $user_id['user_id'] . "'");
            $res = $result->row();
            return $result = array('status' => 'success', 'message' => "Your profile has updated", 'data' => $res);
        } else {
            return 0;
        }
    }

    public function check_user($user_id = array(), $request = array()) {
        $this->db->select('phone_no');
        $this->db->where("status=1");
        $this->db->where("user_id='" . $user_id['user_id'] . "'");
        $this->db->where("phone_no ='" . $request['phone_no'] . "'");
        $query = $this->db->get('user_profile');
        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            $respArr['status'] = "success";
            return $respArr;
        } else {
            return 0;
        }
    }

    public function changePassword($user_id = array(), $request = array()) {
        if (empty($request)) {
            return 0;
        }
        $this->db->where("status=1");
        $this->db->where("user_id='" . $user_id['user_id'] . "'");
        $this->db->where("phone_no ='" . $request['phone_no'] . "'");
        $this->db->set('password', md5($request['npassword']));
        $status = $this->db->update('user_profile');
        if ($status) {
            return 1;
        }
        return 0;
    }

    public function userImage($user_id = array(), $uploadfile = array()) {
        if (!isset($user_id) || empty($user_id) || !isset($uploadfile) || empty($uploadfile)) {
            return false;
        }
        $this->db->where("status=1");
        $this->db->where("user_id='" . $user_id['user_id'] . "'");
        $status = $this->db->update('user_profile', array('image' => $uploadfile));
        if ($status) {
            $result = $this->db->query("select user_id,fullname,image from user_profile where user_id='" . $user_id['user_id'] . "'");
            $val = $result->row();
            return $result = array('status' => 'success', 'data' => $val);
        } else {
            return 0;
        }
    }

    public function searchStore($request = array(), $data = array(), $start, $per_page) {
        //print_r($data);exit;
        if (!isset($request['store_name']) || empty($request['store_name']) ||
                !isset($data['city_id']) || empty($data['city_id'])) {
            return 0;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query = $this->db->query("SELECT s.store_id,s.store_name ,s.description,s.start_time,s.end_time,s.store_image
                                   FROM stores s LEFT JOIN categories c ON  c.store_id=s.store_id 
                                   WHERE s.store_name 
                                   LIKE '%" . $request['store_name'] . "%' AND s.status=1 
                                   GROUP BY s.store_id $lmt");
        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            return $result = array('status' => 'success', 'data' => $userData);
        } else {
            return 0;
        }
    }

    public function searchProduct($request = array(), $data = array(), $start, $per_page) {
        if (!isset($request['product_name']) || empty($request['product_name']) || !isset($data['city_id']) || empty($data['city_id'])) {
            return 0;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query = $this->db->query("SELECT s.store_id,p.product_id,c.category_id,p.product_name,p.product_price,p.product_image,s.store_name,s.description,s.start_time,s.end_time
                                   FROM stores s LEFT JOIN products p ON p.store_id=s.store_id 
                                   LEFT JOIN categories c ON p.category_id=c.category_id
                                   LEFT JOIN cities cz ON cz.city_id=s.city_id 
                                   WHERE p.product_name 
                                   LIKE '%" . $request['product_name'] . "%' 
                                   AND cz.city_id='" . $data['city_id'] . "' 
                                   AND p.status=1
                                   group by s.store_id $lmt");
        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        }
        return 0;
    }

    public function searchCategory($request, $data = array(), $start, $per_page) {
        if (!isset($request['category_name']) || empty($request['category_name'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query = $this->db->query("SELECT s.store_id,s.store_name,s.store_image,s.description,s.starting_time as start_time,s.starting_end as end_time,c.category_name 
                                   FROM stores s 
                                   JOIN products p 
                                   LEFT JOIN categories c ON c.category_id=p.category_id
                                   LEFT JOIN cities cz ON s.city_id=cz.city_id
                                   WHERE category_name LIKE '%" . $request['category_name'] . "%' and s.city_id='" . $data['city_id'] . "'
                                   AND c.status=1 
                                   group by s.store_id $lmt");
        // echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        }
        return false;
    }

    public function productsByCategory($data = array(), $start, $per_page) {
        if (!isset($data['category_id']) || empty($data['category_id']) || !isset($data['store_id']) || empty($data['store_id'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query = $this->db->query("SELECT p.product_id,p.store_id,p.category_id,p.product_name,p.product_image,p.product_price 
                                   from products as p 
                                   where p.store_id='" . $data['store_id'] . "' and p.category_id='" . $data['category_id'] . "' and p.status=1 "
                . "                group by p.store_id $lmt");
        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function searchProductOrStore($data = array(), $start, $per_page) {
        if (isset($data['store_name'])) {
            $lmt = '';
            if ($start != 0 || $per_page != 0) {
                $lmt .= "LIMIT $start,$per_page";
            }
            $query = $this->db->query("SELECT s.store_id,s.store_name ,s.description,s.start_time,s.end_time,s.store_image
                                       FROM stores s LEFT JOIN categories c ON  c.store_id=s.store_id 
                                       WHERE s.store_name 
                                       LIKE '%" . $data['store_name'] . "%' AND s.status=1 
                                       GROUP BY s.store_id $lmt");
            if ($query->num_rows() > 0) {
                $userData = $query->result_array();
                $respArr['status'] = "success";
                $respArr['data'] = $userData;
                return $respArr;
            } else {
                return 0;
            }
        }if (isset($data['product_name'])) {
            $lmt = '';
            if ($start != 0 || $per_page != 0) {
                $lmt .= "LIMIT $start,$per_page";
            }
            $query = $this->db->query("SELECT p.product_id,p.product_name,p.product_image,p.product_price, s.store_id,s.store_name,s.description,s.store_image,s.start_time,s.end_time
                                       FROM stores s LEFT JOIN products p ON p.store_id=s.store_id 
                                       LEFT JOIN categories c ON p.category_id=c.category_id
                                       WHERE p.product_name 
                                       LIKE '%" . $data['product_name'] . "%' AND p.status=1 $lmt");
            if ($query->num_rows() > 0) {
                $userData = $query->result_array();
                $respArr['status'] = "success";
                $respArr['data'] = $userData;
                return $respArr;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function storeProductSearch($data = array(), $start, $per_page) {
        if (!isset($data['store_id']) || empty($data['store_id']) || !isset($data['product_name']) || empty($data['product_name'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query1 = $this->db->query("SELECT s.store_id,p.product_id,c.category_id,p.product_name,p.product_image,p.product_price
                                    FROM stores s join products p on p.store_id=s.store_id
                                    join categories c on c.category_id=p.category_id
                                    where p.product_name LIKE '%" . $data['product_name'] . "%'
                                    and s.store_id='" . $data['store_id'] . "' and p.status=1 $lmt");
        if ($query1->num_rows() > 0) {
            $userData = $query1->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function peopleFavoriteList($data = array(), $start, $per_page) {
        if (!isset($data['city_id']) || empty($data['city_id'])) {
            return false;
        }
        $lmt = '';
        $lmtto = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $qry = $this->db->query("SELECT s.store_id,s.store_name,s.description,s.store_image,s.start_time,s.end_time 
                                FROM stores s LEFT JOIN orders op on op.store_id=s.store_id 
                                LEFT JOIN order_product p ON p.order_id=op.order_id 
                                WHERE s.city_id= '" . $data['city_id'] . "'  AND p.status=1 
                                GROUP BY s.store_name ORDER BY count(*)DESC $lmt ");
        if ($qry->num_rows() > 0) {
            $userData = $qry->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function peopleFavoriteStoresByCategory($data = array(), $start, $per_page) {
        if (!isset($data['city_id']) || empty($data['city_id'])) {
            return false;
        }
        $lmt = '';
        $lmtto = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $qry = $this->db->query("SELECT s.store_id,s.store_name,s.description,s.store_image,s.start_time,s.end_time  from orders 
                                left join stores as s on s.store_id=orders.store_id 
                                left join products as pd on pd.product_id=orders.order_id 
                                left join categories as cg on cg.category_id=pd.category_id WHERE   s.city_id='" . $data['city_id'] . "'  and cg.category_id= '" . $data['category_id'] . "'AND s.status=1 
                                GROUP BY s.store_name ORDER BY count(*)DESC $lmt");
        if ($qry->num_rows() > 0) {
            $userData = $qry->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function myFavoriteStores($request, $data = array(), $start, $per_page) {
        if (!isset($data['city_id']) || empty($data['city_id']) || empty($data['user_id']) || !isset($data['user_id'])) {
            return false;
        }
        $lmt = '';
        $lmtto = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $qry = $this->db->query("SELECT s.store_id,s.store_name,s.description,s.store_image,s.start_time,s.end_time,pf.city_id  
                                FROM stores s 
                                LEFT JOIN orders op on op.store_id=s.store_id 
                                left join user_profile as pf on pf.user_id=op.user_id 
                                WHERE pf.city_id= '" . $data['city_id'] . "' and pf.user_id= '" . $data['user_id'] . "' AND s.status=1 
                                GROUP BY s.store_name 
                                ORDER BY count(*) DESC $lmt");
        if ($qry->num_rows() > 0) {
            $userData = $qry->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function myFavoriteStoresByCategory($request, $data = array(), $start, $per_page) {
        if (!isset($data['city_id']) || empty($data['city_id']) || !isset($request['category_id']) || empty($request['category_id'])) {
            return false;
        }
        $lmt = '';
        $lmtto = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $qry = $this->db->query("SELECT s.store_id,s.store_name,cg.category_id,cg.category_name,pf.city_id,s.description,s.store_image,s.start_time,s.end_time  
                                from orders 
                                left join stores as s on s.store_id=orders.store_id 
                                left join products as pd on pd.product_id=orders.order_id 
                                left join user_profile as pf on pf.user_id=orders.user_id 
                                left join categories as cg on cg.category_id=pd.category_id 
                                WHERE pf.city_id= '" . $data['city_id'] . "' and cg.category_id='" . $request['category_id'] . "' and pf.user_id='" . $data['user_id'] . "' AND s.status=1 
                                GROUP BY s.store_name 
                                ORDER BY count(*) DESC $lmt");
        if ($qry->num_rows() > 0) {
            $userData = $qry->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function generate_bookingid() {
        $bid = md5(uniqid(time() . mt_rand(), true));
        return $bid;
    }

    public function obtain_userid($request = array()) {

        if (!isset($request['auth']) || empty($request['auth'])) {
            return false;
        }

        $this->db->where("unique_id = '" . $request['auth'] . "'");
        $this->db->where('status !=', 0);
        $query = $this->db->get('auth_table');

        if ($query->num_rows() > 0) {
            $rs = $query->row();
            return $output = array('status' => 'success', 'user_id' => $rs->user_id);
        } else {
            return false;
        }
    }

    public function EncryptPatientKey($unique_id, $user_id) {
        $this->db->insert('auth_table', array('user_id' => $user_id, 'unique_id' => $unique_id, 'status' => 1));
    }

    public function login($request = '') {

        if (!isset($request['email']) || empty($request['email']) || !isset($request['password']) || empty($request['password'])) {
            return false;
        }

        $this->db->where("email = '" . $request['email'] . "'");
        $this->db->where('password', md5($request['password']));
        $this->db->where('status !=', 0);
        $query = $this->db->get('user_profile');

        if ($query->num_rows() > 0) {

            $unique_id = $this->generateUnique();
            $rs = $query->row();
            //print_r($rs);exit;

            $this->EncryptPatientKey($unique_id, $rs->user_id);

            $this->db->where('city_id', $rs->city_id);
            $this->db->where('status !=', 0);
            $que = $this->db->get('cities');

            $re = $que->row();
            return $result = array('status' => 'success', 'data' => array('auth_id' => $unique_id, 'user_id' => $rs->user_id, 'fullname' => $rs->fullname, 'email' => $rs->email, 'phone_no' => $rs->phone_no, 'image' => $rs->image, 'district' => $rs->district, 'city_id' => $rs->city_id, 'city_name' => ($re->location) ? $re->location : ""));
        } else {
            return false;
        }
    }

    public function get_userdata($output = array()) {

        if (!isset($output['user_id']) || empty($output['user_id'])) {
            return false;
        }

        $this->db->where("user_id = '" . $output['user_id'] . "'");
        $this->db->where('status !=', 0);
        $query = $this->db->get('user_profile');

        if ($query->num_rows() > 0) {
            $rs = $query->row();
            return $result = array('status' => 'success', 'data' => $rs);
        } else {
            return false;
        }
    }

    public function get_storelist($request = array(), $start, $per_page) {

        if (!isset($request['city_id']) || empty($request['city_id'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }

        if (!isset($request['category_id']) || empty($request['category_id'])) {
            $qry = $this->db->query("SELECT store_id,store_name,store_image,description,starting_time as start_time,starting_end as end_time,city_id 
                                    from stores
                                    where city_id = '" . $request['city_id'] . "' and status!=0 $lmt");
        }

        if (isset($request['category_id']) && !empty($request['category_id'])) {
            $qry = $this->db->query("SELECT s.store_id,s.store_name,p.product_name,s.store_image,s.description,s.start_time,s.end_time,s.city_id,c.category_id,c.category_name 
                                     from stores as s 
                                     left join products as p on s.store_id=p.store_id 
                                     join categories as c on c.category_id=p.category_id 
                                     where s.city_id='" . $request['city_id'] . "' and p.category_id='" . $request['category_id'] . "' and p.status=1 group by p.store_id $lmt");
        }

        if ($qry->num_rows() > 0) {
            $userData = $qry->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function get_categorylist($request = array()) {
        if (isset($request['store_id']) && !empty($request['store_id'])) {
            $qry = $this->db->query("SELECT category_name,category_id,category_image AS Images from categories WHERE status=1");

            if ($qry->num_rows() > 0) {
                $val = $qry->result();
                return $result = array('status' => 'success', 'data' => $val);
            } else {
                return false;
            }
        } else {
            $qry = $this->db->query("SELECT category_name,category_id,category_image AS Images from categories WHERE status=1");
            if ($qry->num_rows() > 0) {
                $val = $qry->result();
                return $result = array('status' => 'success', 'data' => $val);
            } else {
                return false;
            }
        }
    }

    public function pastorder_list($output = array()) {

        if (!isset($output['user_id']) || empty($output['user_id'])) {
            return false;
        }

        $qry = $this->db->query("SELECT PD.product_id,PD.product_name, PD.product_price, PD.product_image
                                FROM order_product AS OP
                                LEFT JOIN products AS PD ON (OP.product_id = PD.product_id AND PD.status = 1)
                                JOIN orders AS OD ON (OP.order_id = OD.order_id AND OD.user_id='" . $output['user_id'] . "' AND OD.status=1)
                                WHERE OP.status=1");
        if ($qry) {
            if ($qry->num_rows() > 0) {
                $val = $qry->result();
                return $result = array('status' => 'success', 'data' => $val);
            } else {
                return $result = array('status' => 'success', 'data' => []);
            }
        } else
            return false;
    }

    public function get_orderdetails($output = array(), $order = array()) {
        if (!isset($output['user_id']) || empty($output['user_id']) || !isset($order['order_id']) || empty($order['order_id'])) {
            return false;
        }

        $today = date('H:i');
        $query = $this->db->query("SELECT *, UP.fullname, AD.street_address, AD.landmark, AD.zip_code, UP.phone_no 
                                   FROM orders AS OD
                                   LEFT JOIN address AS AD ON (AD.address_id = OD.address_id AND AD.status = 1)
                                   JOIN stores AS STO ON (STO.store_id = OD.store_id AND STO.status = 1)
                                   JOIN user_profile AS UP ON (UP.user_id = OD.user_id AND UP.status = 1)
                                   WHERE OD.order_id = '" . $order['order_id'] . "'AND OD.user_id='" . $output['user_id'] . "' AND OD.status=1");

        $val = $query->row();
        if ($val) {
            $query = $this->db->query("SELECT PD.product_name, PD.product_price, PD.product_image
                                       FROM order_product AS OP
                                       LEFT JOIN products AS PD ON (OP.product_id = PD.product_id AND PD.status = 1)
                                       WHERE OP.order_id = '" . $val->order_id . "' AND OP.status=1");

            if ($query->num_rows() > 0) {
                $prod = $query->result();
                return $result = array('status' => 'success', 'Scheduled Date' => $val->scheduled_date, 'Booking ID' => $val->booking_id, 'Booking Date' => $val->booking_date, 'Store' => $val->store_name, 'Total Amount' => $val->total_amount, 'Delivery Address' => array('Name' => $val->fullname, 'Street Address' => $val->street_address, 'Landmark' => $val->landmark, 'Zip Code' => $val->zip_code, 'Phone Number' => $val->phone_no), 'Groceries' => $prod);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_promocode($output = array(), $pcode = array()) {
        if (!isset($output['user_id']) || empty($output['user_id']) || !isset($pcode['promo_code']) || empty($pcode['promo_code']) || !isset($pcode['amount']) || empty($pcode['amount'])) {
            return false;
        }

        $today = date('Y-m-d');
        $qry = $this->db->query("SELECT PR.promo_id,UP.user_id
                                FROM promocodes AS PR 
                                LEFT JOIN user_promo AS UP ON (PR.promo_id = UP.promo_id AND UP.user_id = '" . $output['user_id'] . "')
                                WHERE PR.promo_code = '" . $pcode['promo_code'] . "' AND 
                                PR.starting_date<= DATE_FORMAT(NOW(),'%Y-%m-%d %h:%i:%s') AND 
                                PR.ending_date >= DATE_FORMAT(NOW(),'%Y-%m-%d %h:%i:%s')");

        $val = $qry->row();
        //print_r($this->db->last_query());exit;
        if (empty($val->promo_id)) {
            return $result = array('status' => 'Invalid Promocode..!!');
        }

        if (isset($val->promo_id) && !empty($val->promo_id) && isset($val->user_id) || !empty($val->user_id)) {
            return $result = array('status' => 'Promocode Already Used..!!');
        }

        if (isset($val->promo_id) || !empty($val->promo_id) || empty($val->user_id)) {
            $this->db->insert('user_promo', array('user_id' => $output['user_id'], 'promo_id' => $val->promo_id, 'date' => $today, 'status' => 1));
            $this->db->where("user_id = '" . $output['user_id'] . "'");
            $this->db->where('status !=', 0);
            $query = $this->db->get('wallet');

            if ($query->num_rows() > 0) {
                $rs = $query->row();
                $status = $this->db->update('wallet', array('wallet_balance' => $rs->wallet_balance + $pcode['amount']), array('user_id' => $output['user_id']));
            }
            return $result = array('status' => 'Coupon Applied.. Wallet Credited..!!');
        }
    }

    public function get_favouritestore($output = array()) {
        if (!isset($output['user_id']) || empty($output['user_id'])) {
            return false;
        }

        $qry = $this->db->query("SELECT ST.store_id,ST.store_image, ST.store_name	
                                FROM orders AS OD
                                LEFT JOIN stores AS ST ON (OD.store_id = ST.store_id AND ST.status = 1)
                                WHERE OD.user_id='" . $output['user_id'] . "' AND OD.status = 1");

        if ($qry->num_rows() > 0) {
            $val = $qry->result();
            return $result = array('status' => 'success', 'data' => $val);
        } else {
            return $result = array('status' => 'error', 'data' => array());
        }
    }

    public function addmoney($output = array(), $amount = array()) {

        if (!isset($output['user_id']) || empty($output['user_id']) || !isset($amount['money']) || empty($amount['money'])) {
            return false;
        }

        $this->db->where("user_id = '" . $output['user_id'] . "'");
        $this->db->where('status !=', 0);
        $query = $this->db->get('user_profile');

        if ($query->num_rows() > 0) {
            $rs = $query->row();
            $this->db->insert('wallet', array('user_id' => $rs->user_id, 'wallet_balance' => $amount['money'], 'status' => 1));
            return $result = array('status' => 'success');
        } else {
            return false;
        }
    }

    public function updatewallet($output = array(), $amount = array()) {

        if (!isset($output['user_id']) || empty($output['user_id']) || !isset($amount['money']) || empty($amount['money']) || !isset($amount['action'])) {
            return false;
        }

        $act = $amount['action'];
        $this->db->where("user_id = '" . $output['user_id'] . "'");
        $this->db->where('status !=', 0);
        $query = $this->db->get('wallet');

        if ($query->num_rows() > 0) {

            $rs = $query->row();
            switch ($act) {
                case 1:
                    $status = $this->db->update('wallet', array('wallet_balance' => $rs->wallet_balance + $amount['money']), array('user_id' => $output['user_id']));
                    if ($status) {
                        $status = array('status' => 'success');
                        return $status;
                    }
                    break;
                case 2:
                    $status = $this->db->update('wallet', array('wallet_balance' => $rs->wallet_balance - $amount['money']), array('user_id' => $output['user_id']));
                    if ($status) {
                        $status = array('status' => 'success');
                        return $status;
                    }
                    break;
            }
        }
    }

    public function allStoresByCategory($data = array(), $start, $per_page) {
        if (!isset($data['city_id']) || empty($data['city_id']) || !isset($data['category_id']) || empty($data['category_id'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $qry = $this->db->query("SELECT s.store_id, s.store_name,s.store_image,cg.category_name,s.start_time,s.end_time 
                                 FROM stores as s left join categories as cg on cg.store_id=s.store_id 
                                 WHERE city_id=$data[city_id] and category_id=$data[category_id] and s.status=1 $lmt");
        if ($qry->num_rows() > 0) {
            $userData = $qry->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $userData;
            return $respArr;
        } else {
            return false;
        }
    }

    public function help() {
        $query = $this->db->query("SELECT identifier,data from cms_data where status!=0");
        if ($query->num_rows() > 0) {
            $query1 = $query->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $query1;
            return $respArr;
        } else {
            return false;
        }
    }

    public function searchPlace($request, $start, $per_page) {
        if (!isset($request['location']) || empty($request['location'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query1 = $this->db->query("SELECT city_id,location,zip_code from cities where location LIKE '%" . $request['location'] . "%' and `status`=1 $lmt");
        if ($query1->num_rows() > 0) {
            $placeData = $query1->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $placeData;
            return $respArr;
        }
        return 0;
    }

    public function searchLocation($request, $start, $per_page) {
        if (!isset($request['zip_code']) || empty($request['zip_code'])) {
            return false;
        }
        $lmt = '';
        if ($start != 0 || $per_page != 0) {
            $lmt .= "LIMIT $start,$per_page";
        }
        $query1 = $this->db->query("SELECT city_id,location,zip_code from cities where zip_code LIKE '%" . $request['zip_code'] . "%' and `status`=1 $lmt");
        if ($query1->num_rows() > 0) {
            $placeData = $query1->result_array();
            $respArr['status'] = "success";
            $respArr['data'] = $placeData;
            return $respArr;
        }
        return 0;
    }

    public function reportAProblem($data, $request = array(), $user_id) {
        if (!isset($request['prblm_desc']) || empty($request['prblm_desc'])) {
            return false;
        }
        $this->db->insert('problem_report', array('user_id' => $user_id['user_id'], 'fullname' => $data['fullname'], 'prblm_desc' => $request['prblm_desc'], 'status' => 1));

        $query1 = $this->db->query("SELECT * FROM problem_report WHERE user_id = $user_id[user_id] AND status=1");
        if ($query1->num_rows() > 0) {
            $val = $query1->row();
            return $result = array('status' => 'success');
        } else {
            return false;
        }
    }

    public function user_logout($request = array()) {
        if (!isset($request['auth']) || empty($request['auth'])) {
            return false;
        }
        $uid = $request['auth'];
        $this->db->set('status', 0);
        $this->db->where('unique_id', $uid);
        $status = $this->db->update('auth_table');
        if ($status) {
            $status = array('status' => 'success', 'message' => 'logged out successfully');
            return $status;
        } else {
            $status = array('status' => 'error', 'message' => 'logout error');
            return $status;
        }
    }

}

?> 