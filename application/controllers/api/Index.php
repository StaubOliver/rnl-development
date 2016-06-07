<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	API for open data
  *  
  */
  
class Index extends Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('FeedModel');
    }
    
	public function index() {
		echo 'test';
	}
}

/* End of file feed.php */
/* Location: ./application/controllers/api/feed.php */