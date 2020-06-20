<?php

class Shopper_model extends CI_Model {

    public function _consruct() {
        parent::_construct();
    }

    public function addShopper($shopper_data = array()) {
        if (empty($shopper_data))
            return 0;
        $userIdChk = $this->db->query("SELECT * FROM shopper AS SHOP 
                                       INNER JOIN admin_users AS AU ON (AU.id = SHOP.shopper_id)
                                       WHERE AU.status!='2' AND 
                                       AU.username='" . $shopper_data['username'] . "'");
        if (!empty($userIdChk) && $userIdChk->num_rows() > 0)
            return 4;
        $emailChk = $this->db->query("SELECT * FROM shopper AS SHOP 
                                      INNER JOIN admin_users AS AU ON (AU.id = SHOP.shopper_id)
                                      WHERE AU.status!='2' AND 
                                      SHOP.email='" . $shopper_data['email_id'] . "'");
        if (!empty($emailChk) && $emailChk->num_rows() > 0)
            return 2;
        $phoneChk = $this->db->query("SELECT * FROM shopper AS SHOP 
                                      INNER JOIN admin_users AS AU ON (AU.id = SHOP.shopper_id)
                                      WHERE AU.status!='2' AND 
                                      SHOP.phone_no='" . $shopper_data['phone'] . "'");
        if (!empty($phoneChk) && $phoneChk->num_rows() > 0)
            return 3;
        $status = $this->db->insert('admin_users', array('username' => $shopper_data['username'],
            'password' => $shopper_data['password'],
            'display_name' => $shopper_data['display_name'],
            'profile_image' => $shopper_data['profile_image'],
            'user_type' => '2', 'status' => '1'));
        if (!$status) {
            return 0;
        }
        $shopper_id = $this->db->insert_id();
        $status = $this->db->insert('shopper', array('shopper_id' => $shopper_id,
            'shopper_name' => $shopper_data['display_name'],
            'phone_no' => $shopper_data['phone'],
            'password' => $shopper_data['password'],
            'shopper_image' => $shopper_data['profile_image'],
            'store_id' => $shopper_data['store_id'],
            'type' => '2',
            'status' => '1',
            'email' => $shopper_data['email_id']));
        return $status;
    }

    function getShopper($shopper_id = '', $view_all = 0) {
        $cond = (!empty($shopper_id)) ? " SHOP.shopper_id = '" . $shopper_id . "' " : "";
        $cond .= (!empty($cond)) ? " AND " : $cond;
        $cond .= (!empty($view_all)) ? " ADMN.status IN (0,1) " : " ADMN.status IN (1) ";
        $sql = "SELECT ADMN.username,ADMN.user_type,SHOP.shopper_name as display_name,ADMN.profile_image,ADMN.status as admin_status, 
				       SH.store_name, SH.store_image,SHOP.* 
		        FROM shopper AS SHOP 
			    INNER JOIN admin_users AS ADMN ON (ADMN.id = SHOP.shopper_id)
			    LEFT JOIN stores AS SH ON (SH.store_id = SHOP.store_id AND SH.status = '1')
			    WHERE " . $cond;
        $result = $this->db->query($sql);
        if (empty($result)) {
            return;
        }
        return (empty($shopper_id)) ? $result->result() : $result->row();
    }

    function getShopperData($shopper_id = '', $view_all = 0) {
        $cond = ($view_all != 0) ? ' SHOP.status IN (0,1) ' : ' SHOP.status IN (1) ';
        $cond .= (!empty($shopper_id)) ? " AND SHOP.shopper_id = '$shopper_id'" : "";
        $result = $this->db->query("SELECT ADMN.username,ADMN.user_type,SHOP.shopper_name as display_name,ADMN.profile_image,ADMN.status, 
						SH.store_name, SH.store_image,SHOP.* 
					FROM shopper AS SHOP 
					INNER JOIN admin_users AS ADMN ON (ADMN.id = SHOP.shopper_id)
					LEFT JOIN stores AS SH ON (SH.store_id = SHOP.store_id AND SH.status = '1')
					WHERE $cond");
        $val = $result->result();
        //print_r($val);exit;
        if (empty($val)) {
            return;
        }
        return (empty($shopper_id)) ? $result->result() : $result->row();
    }

    function updateShopper($shopper_id = '', $shopper_data = array()) {
        //print_r($shopper_data);exit;
        if (empty($shopper_id) || empty($shopper_data))
            return 0;
        $userIdChk = $this->db->query("SELECT * FROM shopper AS MECH 
                                       INNER JOIN admin_users AS AU ON (AU.id = MECH.shopper_id)
                                       WHERE AU.status!='2' AND AU.id!='" . $shopper_id . "' AND 
                                       AU.username='" . $shopper_data['username'] . "'");
        if (!empty($userIdChk) && $userIdChk->num_rows() > 0) {
            return 4;
        }
        $emailChk = $this->db->query("SELECT * FROM shopper AS MECH 
                                      INNER JOIN admin_users AS AU ON (AU.id = MECH.shopper_id)
                                      WHERE AU.status!='2' AND AU.id!='" . $shopper_id . "' AND 
                                      MECH.email='" . $shopper_data['email_id'] . "'");
        if (!empty($emailChk) && $emailChk->num_rows() > 0) {
            return 2;
        }
        $phoneChk = $this->db->query("SELECT * FROM shopper AS MECH 
                                      INNER JOIN admin_users AS AU ON (AU.id = MECH.shopper_id)
                                      WHERE AU.status!='2' AND AU.id!='" . $shopper_id . "' AND 
                                      MECH.phone_no='" . $shopper_data['phone'] . "'");
        if (!empty($phoneChk) && $phoneChk->num_rows() > 0) {
            return 3;
        }
        $admUpdateArr = array('username' => $shopper_data['username'],
            'display_name' => $shopper_data['display_name']);
        if (isset($shopper_data['profile_image']) && !empty($shopper_data['profile_image']))
            $admUpdateArr['profile_image'] = $shopper_data['profile_image'];
        $status = $this->db->update('admin_users', $admUpdateArr, array('id' => $shopper_id));
        if (!$status) {
            return 0;
        }
        $upMecArr = array(
            'shopper_name' => $shopper_data['display_name'],
            'shopper_image' => $shopper_data['profile_image'],
            'phone_no' => $shopper_data['phone'],
            'email' => $shopper_data['email_id'],
            'store_id' => (!empty($shopper_data['store_id']) ? $shopper_data['store_id'] : '0'));

        if (isset($shopper_data['licence']) && !empty($shopper_data['licence']))
            $upMecArr['licence'] = $shopper_data['licence'];

        $status = $this->db->update('shopper', $upMecArr, array('shopper_id' => $shopper_id));
        return $status;
    }

    function changeStatus($shopper_id = '', $status = '0') {
        if (empty($shopper_id)) {
            return 0;
        }
        $resp = $this->db->update('admin_users', array('status' => $status), array('id' => $shopper_id));
        if ($status == '1') {
            $shopData = $this->db->get_where('shopper', array('	shopper_id' => $shopper_id))->row();
            if (count($shopData) > 0) {

                $subject = "Profile Activation";
                $email_id = $shopData->email;
                $message = "<html>
                                                            <body>
                                                              Hi,\n\r Welcome to Clikkart. \r\n Your account for the Username " . $email_id . " is now Activated.
                                                            </body>
                                                       </html>";

//                $template = getNotifTemplate();
//                if (isset($template['shopper_activation_mail']) && !empty($template['shopper_activation_mail'])) {
//                    $message = str_replace(array('{:user_name}'), array($email_id), $template['shopper_activation_mail']);
//                }
//                send_mail($subject, $email_id, $message);
//                $res = array('status' => 1, 'data' => '');
            }
        }
        return $resp;
    }

    function getNearByShopper($location_data = array(), $sub_issues = array()) {
        if (empty($location_data) || empty($sub_issues)) {
            return 0;
        }

        $current_lat = $location_data['pickup_lat'];
        $current_lng = $location_data['pickup_lng'];
        $issue_cat_id = implode(',', $sub_issues);

        $sql = "SELECT AU.display_name,AU.profile_image,SP.*,MS.store_name,MS.address AS shop_address,
        			   MS.phone AS shop_phone,MS.email_id AS shop_email_id,
        			   3956*2*ASIN(SQRT(POWER(SIN(($current_lat-ME.location_lat)*pi()/180/2),2)+
        			   COS($current_lat*pi()/180 )*COS(ME.location_lat*pi()/180)*
        			   POWER(SIN(($current_lng-MS.location_lng)*pi()/180/2),2) )) AS distance
    			FROM shopper AS SP
    			INNER JOIN admin_users AS AU ON (AU.id=MS.mechanic_id)
    			LEFT JOIN store AS MS ON (MS.store_id=ME.store_id AND MS.status='1')
                WHERE AU.status='1'
               -- GROUP BY ME.shopper_id
                -- HAVING distance<30";

        $shopData = $this->db->query($sql);
        if (empty($shopData) || empty($shopData = $shopData->result_array())) {
            return 0;
        }

        $estimate = 0;
        $shopDataArr = array();
        foreach ($shopData AS $index => $data) {
            $data['distance'] = (int) $data['distance'];
            if (empty($data['start_time']) || empty($data['end_time'])) {
                $scheduleTiming = array(
                    strtotime(date('Y-m-d 09:00')) * 1000,
                    strtotime(date('Y-m-d 10:00')) * 1000,
                    strtotime(date('Y-m-d 11:00')) * 1000,
                    strtotime(date('Y-m-d 12:00')) * 1000,
                    strtotime(date('Y-m-d 13:00')) * 1000,
                    strtotime(date('Y-m-d 14:00')) * 1000,
                    strtotime(date('Y-m-d 15:00')) * 1000,
                    strtotime(date('Y-m-d 16:00')) * 1000,
                    strtotime(date('Y-m-d 17:00')) * 1000,
                    strtotime(date('Y-m-d 18:00')) * 1000,
                    strtotime(date('Y-m-d 19:00')) * 1000,
                    strtotime(date('Y-m-d 20:00')) * 1000,
                    strtotime(date('Y-m-d 21:00')) * 1000,
                );
            } else {
                $endTime = strtotime($data['end_time']);
                $schTime = strtotime($data['start_time']);
                $scheduleTiming = array();

                for (; $schTime <= ($endTime - 3600); $schTime += 3600) {
                    $scheduleTiming[] = $schTime * 1000;
                }
            }

            $rating = $this->db->query("SELECT round(avg(rate),2) AS rating 
    									FROM mechanic_rating 
    									WHERE mechanic_id='" . $data['mechanic_id'] . "'");
            $rating = (!empty($rating) && !empty($rating = $rating->row_array())) ? $rating['rating'] : '0';

            $mechanic_id = $data['mechanic_id'];
            $sql = "SELECT ISS.*, IC.*, MI.*
        			FROM issues_category AS IC
        			INNER JOIN issues AS ISS ON (IC.issue_id=ISS.issue_id)
        			LEFT JOIN mechanic_issues AS MI ON (MI.issue_cat_id=IC.issue_cat_id AND 
								MI.mechanic_id='$mechanic_id' AND MI.status='1')
        			WHERE ISS.status='1' AND IC.status='1' AND IC.issue_cat_id IN ($issue_cat_id)";

            $subIssData = $this->db->query($sql);

            $sIssueData = array();
            if (!empty($subIssData) && !empty($subIssData = $subIssData->result_array())) {
                $sIssueData = $subIssData;
            }

            $estimate = 0;
            foreach ($sIssueData AS $sIndex => $sIssue) {
                if (!empty($sIssue['custom_service_fee'])) {
                    $estimate += $sIssue['custom_service_fee'];
                    $sIssueData[$sIndex]['service_fee'] = $sIssue['custom_service_fee'];
                } else {
                    $estimate += $sIssue['default_service_fee'];
                    $sIssueData[$sIndex]['service_fee'] = $sIssue['default_service_fee'];
                }
            }

            $shopData[$index]['rating'] = $rating;
            $shopData[$index]['estimate'] = $estimate;
            $shopData[$index]['sub_issues'] = $sIssueData;
            $shopData[$index]['scheduleTiming'] = $scheduleTiming;
        }
        return $shopData;
    }

}

?>