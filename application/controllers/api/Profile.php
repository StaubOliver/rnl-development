<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	API for the user profile
  *  
  */

class Profile extends MY_Controller {
    function __construct() {
        parent::__construct();
        
         $this->load->model('ProfileModel');
    }
    
	public function index() {
		echo 'Nothing to see here. Move along now...';
	}
	
	public function getDetails() {
		// Get the user's profile details
		$data = $this->ProfileModel->getUserProfile();
		
		// Return as json
		echo json_encode($data);
	}
	
	function update() {
		// Update the profile from the UI
		$id 			= $this->input->post('id');
		$first_name 	= $this->input->post('first_name');
		$last_name 		= $this->input->post('last_name');
		$email 			= $this->input->post('email');
		
		if($this->ProfileModel->setUserProfile($id, $first_name, $last_name, $email)) {
			echo 'Updated';
		}
	}
	
	// IP to location (country code)
	function setIPLocation() {
		// Get user id
		$user_id = $this->input->post('user_id');
		
		// Use external api to get the ip country code
		$ip = $this->input->ip_address();
		//$ip_data = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip), TRUE);
		$ip_data = json_decode(file_get_contents('http://api.ipinfodb.com/v3/ip-country/?key=8e7d3385dbdb0817b6b41306ad562a5a7b7435fedc124f29f6bb5b956ebfbeb5&format=json&ip='.$ip), TRUE);
		
		$country_code = ($ip != '0.0.0.0' && isset($ip_data)) ? $ip_data['countryCode'] : 'anon';
		$this->db->query("UPDATE users SET location = '$country_code' WHERE id = $user_id");
		
		$data = array('location', $country_code);
	}
}

/* End of file profile.php */
/* Location: ./application/controllers/api/profile.php */