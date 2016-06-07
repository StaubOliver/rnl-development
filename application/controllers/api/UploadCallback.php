<?php
/**
  *	API callback for cloud images
  *  
  */

class UploadCallback extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('UploadModel');
    }
    
    /**
	 *  Update the database with the image details
	 */
	public function index() {
		// Get the data
		$data = json_decode(file_get_contents('php://input'),  TRUE);
		
		// Update the database
		$this->UploadModel->saveImageData($data['public_id'], $data['secure_url']);
	}
	
	/**
	 *  Do a final check for any hashes that are null
	 */
	public function rehash() {
		// Update the database
		$updated = $this->UploadModel->rehashNullValues();
		
		echo "Rows updated: ".$updated;
	}
}

/* End of file uploadcallback.php */
/* Location: ./application/controllers/api/uploadcallback.php */