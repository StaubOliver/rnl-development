<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Model for the profile API
  *  
  */
  
class ProfileModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getUserProfile() {
    	// Get the users profile
    	$userId = $this->ion_auth->get_user_id();
    	
    	// Get the next image in the queue
    	$query = $this->db->query('SELECT id, username, email, last_login, location, first_name, last_name, queue_total, review_total, share_total FROM users WHERE id = '.$userId);
    	
    	// Check data is returned
    	if($query->num_rows() > 0) {
    		// Profile data
    		$data['profile'] = $query->row_array(); 
    		
    		// Add the badges data
    		$query = $this->db->query('SELECT * FROM badges WHERE user_id = '.$userId);
    		
    		if($query->num_rows() > 0) {
    			$data['profile']['badge'] = $query->row_array();
    		}
    	} else {
    		// Something went wrong
    		$data = false;
    	}
    	
    	return $data;
    }
    
    function setUserProfile($id, $first_name, $last_name, $email) {
    	// Update the users profile
    	$data = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'email' => $email
		);
		
		$this->db->where('id', $id);
		$this->db->update('users', $data);
    	
    	return TRUE;
    }
    
    function isAdmin() {
    	$is_admin = 0;
    	$user_id = $this->ion_auth->get_user_id();
    	$query = $this->db->query('SELECT is_admin FROM users WHERE id = '.$user_id.'');
    	if($query->num_rows() > 0) {
    		// Profile data
    		foreach($query->result() as $row) {
    			$is_admin = $row->is_admin;
    		}
    	}
    	
    	return $is_admin;
    }
}

/* End of file profilemodel.php */
/* Location: ./application/models/profilemodel.php */