<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	A basic view for displaying an image
  */

class View extends CI_Controller {
    function __construct() {
        parent::__construct();
        
       // $this->load->model('LoggerModel');
    }
    
	public function index() {
		// Show the view
		$hashid = $this->uri->segment(4);
		$projectid = $this->uri->segment(3);
		
		$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
		
		$sql = 'SELECT image_url FROM project_'.$projectid.'_images WHERE hash = "'.$hashid.'"';
		$query = $this->db->query($sql);
    		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data['image'] = $row->image_url;
			}
		} else {
			$data['image'] = '/assets/img/example.jpg';
		}
		
		$this->load->view('view', $data);
		
		// Log the data
        //$this->LoggerModel->logData();
	}
}

/* End of file view.php */
/* Location: ./application/controllers/view.php */