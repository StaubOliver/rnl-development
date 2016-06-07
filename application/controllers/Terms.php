<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Controller for displaying the terms and conditions of the app
  * 
  */

class Terms extends MY_Controller {
    function __construct() {
        parent::__construct();
        
        //$this->load->model('LoggerModel');
    }
    
	public function index() {		
		// Serve the view
		$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
		$this->load->view('terms', $data);
		
		// Log the data
        //$this->LoggerModel->logData();
	}
}

/* End of file terms.php */
/* Location: ./application/controllers/terms.php */