<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Controller for the logged in user view
  * 
  */

class App extends MY_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('ProjectModel');
        $this->load->model('ProfileModel');
        //$this->load->model('LoggerModel');
    }
    
	public function index() {
		// Check if logged in
		if(!$this->ion_auth->logged_in()) {
			// Not logged in, show the home page
			$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
			$this->load->view('home', $data);
			
			// Log the data
        	//$this->LoggerModel->logData();
		} else {
			// Logged in show the app (Angular application)
			$data['user_id'] = $this->ion_auth->get_user_id();
			$data['projects'] = $this->ProjectModel->listProjects();
			$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
			$data['is_admin'] = $this->ProfileModel->isAdmin();
		
			$this->load->view('app', $data);
			
			// Log the data
        	//$this->LoggerModel->logData();
		}
	}
	
	public function logout() {
		// Log the user out
		$this->ion_auth->logout();
		redirect('/auth/login');
	}
}

/* End of file app.php */
/* Location: ./application/controllers/app.php */