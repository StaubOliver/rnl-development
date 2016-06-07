<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	 Controller for the sharing view
  *  Share images to be completed by non-logged in users
  *  Images indentified by a hash
  *  Shows a complete message if done, with the option to load another incomplete image.
  *  Save points to localStorage for registration
  */

class Share extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('ProjectModel');
        $this->load->model('ShareModel');
        //$this->load->model('LoggerModel');
    }
    
	public function index() {
		// Show the view (Angular application)
		$data['is_admin'] = 0;
		$data['hashid'] = $this->uri->segment(3);
		$data['projects'] = $this->ProjectModel->listProjects();
		$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
		
		$this->load->view('share', $data);
		
		// Log the data
        //$this->LoggerModel->logData();
	}
}

/* End of file share.php */
/* Location: ./application/controllers/share.php */