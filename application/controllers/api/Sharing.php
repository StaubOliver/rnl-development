<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Controller for the sharing view
  *  
  */
  
class Sharing extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('ShareModel');
    }
    
	public function index() {
		echo 'test';
	}
}

/* End of file sharing.php */
/* Location: ./application/controllers/api/sharing.php */