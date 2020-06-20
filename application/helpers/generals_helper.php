<?php
	function set_upload_service($path){
	    $config = array();
	    $config['upload_path'] = $path;
	    $config['allowed_types'] = '*';
	    $config['overwrite'] = FALSE;
	    return $config;
	}

	function remove_html(&$item, $key){
	    $item = strip_tags($item);
	}

	function set_log($class,$method,$postdata,$auth){
		$CI = & get_instance();
		$url = $class.'/'.$method;
		$data = array('url'=>$url,
					  'parameter'=>$postdata,
					  'auth'=>$auth,
					  'time'=>date('Y-m-d h:i:s'));

		$CI->db->insert('service_log',$data);
		return $CI->db->insert_id();
	}

	function getSettings(){
		$CI = & get_instance();
		$settings = $CI->db->get('settings');
		return (!empty($settings))?$settings->row_array():'';
	}

	function pr($val){
		echo (is_array($val))?'<pre>':'';
		print_r($val);
		echo (is_array($val))?'</pre>':'';
		exit;
	}

	function pre($val){
		echo (is_array($val))?'<pre>':'';
		print_r($val);
		echo (is_array($val))?'</pre>':'';
		echo '<br>';
	}

	function encode_param($param = ''){
		if(empty($param)){
			return;
		}
		$encode = base64_encode('{*}'.$param.'{*}');
		$encode = base64_encode('a%a'.$encode.'a%a');
		$encode = base64_encode('b'.$encode.'b');
		$encode = base64_encode('Ta7K'.$encode.'eyRq');
		return urlencode($encode);
	}

	function decode_param($param = ''){
		if(empty($param)){
			return;
		}
		$decode = urldecode(trim($param));
		$decode = trim(base64_decode(urldecode($decode)),'Ta7K');
		$decode = trim($decode,'eyRq');
		$decode = trim(base64_decode(urldecode($decode)),'b');
		$decode = trim(base64_decode(urldecode($decode)),'a%a');
		$decode = trim(base64_decode(urldecode($decode)),'{*}');
		return $decode;
	}

	function getLocationLatLng($location = ''){
	  	$settings = getSettings();

		if(empty($location) || empty($settings) || !isset($settings['google_api_key']) || 
		   empty($gKey = $settings['google_api_key'])){
			return 0;
		}
		$thisObj = & get_instance();
		$locData = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=".
									 urlencode($location)."&sensor=false&key=".$gKey);
		if(empty($locData))
			return 0;
	    $loc_data = json_decode($locData);
	    if(empty($loc_data) || !isset($loc_data->status) || $loc_data->status != 'OK')
			return 0;

		$locArr['lat'] = $loc_data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$locArr['lng'] = $loc_data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

	    if(empty($locArr['lat']) || empty($locArr['lng']))
			return 0;
		return $locArr;
	}

	// function generate_unique() {
	// 	$unique = md5(uniqid(time().mt_rand(), true));
	// 	return $unique;
	// }

	function getNotifTemplate(){
		$CI = & get_instance();
		$settings = $CI->db->get('notification_templates');
		return (!empty($settings))?$settings->row_array():'';
	}

	function send_mail($subject,$email,$message,$attach=null) {
	    $headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: no-reply@carfixxers.com \r\n";
		$headers .= "Reply-To: ". $email. "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		$headers .= "X-Priority: 1" . "\r\n"; 

	    mail($email, $subject, $message, $headers);
 	}
?>