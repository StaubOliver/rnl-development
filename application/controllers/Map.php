<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
  *	Controller for the map
  * 
  */

class map extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('ProjectModel');
        $this->load->model('ProfileModel');
        $this->load->model('MapModel');
        $this->load->model('LoggerModel');
    }
    
	public function index() {	
		$data['projects'] = $this->MapModel->loadProject();
		$data['genuses'] = $this->MapModel->loadGenuses();
		$data['collectors'] = $this->MapModel->loadCollector();

		if ($this->ion_auth->logged_in()){
			$data['logged_in'] = true;
			$data['is_admin'] = $this->ProfileModel->isAdmin();
		} else{
			$data['logged_in'] = false;
			$data['is_admin'] = "0";
		}
		
		
		if($this->uri->segment(2)){
			$data['genus'] = urldecode($this->uri->segment(2));
		}
		else
		{
			$data['genus'] = "-1";
		}

		if($this->uri->segment(3)){
			$data['collector'] = urldecode($this->uri->segment(3));
		}
		else
		{
			$data['collector'] = "-1";
		}

		if($this->uri->segment(4)){
			$data['agemin'] = urldecode($this->uri->segment(3));
		}
		else
		{
			$data['agemin'] = 0;
		}

		if($this->uri->segment(5)){
			$data['agemax'] = urldecode($this->uri->segment(3));
		}
		else
		{
			$data['agemax'] = 12;
		}


		
		$data['test'] = urldecode($this->uri->segment(2)).urldecode($this->uri->segment(3)).urldecode($this->uri->segment(4)).urldecode($this->uri->segment(5));


		$this->load->view('map2', $data);
	}
	

	
	public function map_admin(){
		if ($this->ion_auth->logged_in() && ($this->ProfileModel->isAdmin() == 1)){
			$data['feedbacks'] = $this->MapModel->adminFeedbacks();
			$this->load->view('map_admin', $data);

		}
		else
		{
			redirect('/map');
		}
	}

	
/*
	public function old() {		
		// Check if logged in
		//if(!$this->ion_auth->logged_in()) {
			// Not logged in, show the home page
			//$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
		//	$this->load->view('home', $data);
			
			// Log the data
        	//$this->LoggerModel->logData();
		//} else {
			// Logged in show the app (Angular application)
			//$data['user_id'] = $this->ion_auth->get_user_id();
			//$data['projects'] = $this->ProjectModel->listProjects();
			//$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
			//$data['is_admin'] = $this->ProfileModel->isAdmin();

			$data['projects'] = $this->MapModel->loadProject();
			$data['genuses'] = $this->MapModel->loadGenuses();
			$data['collectors'] = $this->MapModel->loadCollector();

			if ($this->ion_auth->logged_in()){
				$data['logged_in'] = true;
				$data['is_admin'] = $this->ProfileModel->isAdmin();
			} else{
				$data['logged_in'] = false;
				$data['is_admin'] = "0";
			}

			$this->load->view('map', $data);
			
			// Log the data
        	//$this->LoggerModel->logData();
		//}
	}
	*/
}

/* End of file map.php */
/* Location: ./application/controllers/map.php */