<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Model for logging user data
  *  
  */
  
class LoggerModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function logData() {
    	// Log the users data
    	$unique_id = $this->getUniqueID();
    	$user_id = $this->ion_auth->get_user_id();
    	
    	// Use external api to get the ip country code
		$ip = $this->input->ip_address();
		//$ip_data = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip), TRUE);
		//$ip_data = json_decode(file_get_contents('http://api.ipinfodb.com/v3/ip-country/?key=8e7d3385dbdb0817b6b41306ad562a5a7b7435fedc124f29f6bb5b956ebfbeb5&format=json&ip='.$ip), TRUE);
		
		//$country_code = ($ip != '0.0.0.0' && isset($ip_data)) ? $ip_data['countryCode'] : '00';
    	
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    	$start_time = date('Y-m-d H:i:s');
    	$start_point = $_SERVER['REQUEST_URI'];
    	$entry_url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER']: 'none';
    	//$ab_group = $this->input->cookie('rnl_testing', TRUE);
    	
    	// Get AB group
    	$q = $this->db->query('SELECT group FROM ab_data WHERE unique_id="'.$unique_id .'"');
    	foreach($q->result() as $row) {
    		$ab_group = $row->group;
    	}
    	
    	$data = array(
		   'unique_id' => $unique_id,
		   'user_id' => $user_id,
		   'ip' => $ip,
		   //'country_code' => $country_code,
		   'user_agent' => $user_agent,
		   'datetime' => $start_time,
		   'location' => $start_point,
		   'entry_url' => $entry_url,
		   'ab_group' => $ab_group
		);

		$this->db->insert('logger', $data); 
    }
    
    function logImage($interface, $project_id, $image_id, $request_time, $submit_time, $queue, $review) {
    	// Log the image data
    	$unique_id = $this->getUniqueID();
    	$user_id = $this->ion_auth->get_user_id();
		$ip = $this->input->ip_address();
    	//$ab_group = $this->input->cookie('rnl_testing', TRUE);
    	
    	// Get AB group
    	$q = $this->db->query('SELECT group FROM ab_data WHERE unique_id="'.$unique_id .'"');
    	foreach($q->result() as $row) {
    		$ab_group = $row->group;
    	}
    	
    	$data = array(
		   'unique_id' => $unique_id,
		   'user_id' => $user_id,
		   'ip' => $ip,
		   'interface' => $interface,
		   'project_id' => $project_id,
		   'image_id' => $image_id,
		   'request_time' => $request_time,
		   'submit_time' => $submit_time,
		   'queue' => $queue,
		   'review' => $review,
		   'ab_group' => $ab_group
		);

		$this->db->insert('logger_images', $data); 
    }
    
    function getUniqueID() {
    	// Check for the unique_id in the tracking cookie
    	if($this->input->cookie('rnl_tracking', TRUE)) {
			// Get the unique_id for the logger table
			$unique_id = $this->input->cookie('rnl_tracking', TRUE);
		} else {
			// New user, create unique ID
			$unique_id = $this->setUniqueID();
		}
		
		return $unique_id;
    }
    
    function setUniqueID() {
    	// Set the expire date for 10 years from now
    	$expire = strtotime('+365 days');

		//// Check if the cookies exist
		
    	// Set a unique_id in the tracking cookie
    	$unique_id = sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].time());
    	setcookie("rnl_tracking", $unique_id, $expire, '/');
    	
    	// Set a message cookie too
    	setcookie("rnl_info", "Please visit https://natureslibrary.co.uk/terms for further details of these cookies.", $expire, '/');
    	
    	return $unique_id;
    }
}

/* End of file loggermodel.php */
/* Location: ./application/models/loggermodel.php */