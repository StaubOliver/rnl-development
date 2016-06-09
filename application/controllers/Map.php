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
    }
    
	public function index() {		
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

			$this->load->view('map2', $data);
			
			// Log the data
        	//$this->LoggerModel->logData();
		//}
	}

	public function map_admin(){
		$genus = '-1';
		$species = '-1';
		$age_min = '-1';
		$age_max = '-1';
		$collector = '-1';
		$map_lat_ne = '-1';
		$map_lng_ne = '-1';
		$map_lat_sw = '-1';
		$map_lng_sw = '-1';
		$map_zoom = '-1';

		$filter = array(
			'genus' => '-1',
			'species' => '-1',
			'age_min' => '-1'
			'age_max' => '-1',
			'collector' => '-1',
			'map_lat_ne' => '-1',
			'map_lng_ne' => '-1',
			'map_lat_sw' => '-1',
			'map_lng_sw' => '-1',
			'map_zoom' => '-1'
		);

		$data['feedbacks'] = $this->MapModel->loadFeedbacks($filter);
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