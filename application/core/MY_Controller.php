<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Master controller handling auth and a few other things.
  */
  
class MY_Controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        // A/B testing routing; ignore for API calls
        /*
        if($this->uri->segment(1) != 'api') {
        	$this->routeAB();
        }
        */
    }
    
    public function auth() {
    	// Check if logged in 	
    	if(!$this->ion_auth->logged_in()) {
			// Not logged in, show the home page
			$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
			$this->load->view('home', $data);
		}
    }
    
    public function routeAB() {
    	// Check for existing cookie assigning the route
    	$route = $this->input->cookie('rnl_testing', TRUE);
    	
    	if(isset($route) && $route != FALSE) {
			// if B (anonymous) - not A (registered) or C (converted to registered) then redirect
    		// Redirect if B
    		if($route == 'B') {
    			redirect('/share/index/0');
    		}
    		
    		// else continue to homepage to login 	
    		
    	} else {
    		// If cookie doesn't exist assign a route randomly
    		$route = (rand(0, 1) == 1) ? 'A': 'B';
    		
    		// Save the cookie
    		$expire = strtotime('+365 days');
    		setcookie("rnl_testing", $route, $expire, '/');
    		
    		// Update the database
    		$this->load->model('LoggerModel');
    		$uid = $this->LoggerModel->getUniqueID();
    		
    		$data = array(
    			'group' => $route,
    			'unique_id' => $uid,
    			'ip' => $this->input->ip_address(),
    			'datetime' => date('Y-m-d H:i:s')
    		);
    		
    		$this->db->insert('ab_data', $data);
    		
    		// Redirect if B
    		if($route == 'B') {
    			redirect('/share/index/0');
    		}
    	}
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */