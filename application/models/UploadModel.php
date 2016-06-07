<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Model for the image upload callback
  *  
  */
  
class UploadModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->library('Hashids');
    }
    
    /**
     *	Save the image data
     */
    function saveImageData($name, $url) {
    	// Table name
    	$table = 'project_1_images';
    	
    	// Insert the new row
    	$data = array(
		   'image_url' => $url,
		   'filename' => $name
		);

		$this->db->insert($table, $data);
		
		// Update the hash from the ID
		$id = $this->db->insert_id();
		
		if($id) {
			$hashids = new Hashids('abracadabra is my salt for today', 6);
			$hash = $hashids->encode($id);
			$hash = (strlen($hash) < 1 || !$hash) ? $hashids->encode($id) : $hash;
		
			$dataUpdate = array(
			   'hash' => $hash
			);
		
			$this->db->where('image_id', $id);
			$this->db->update($table, $dataUpdate); 
		} else {
			$message = 'Failed row id: '.$id;
			file_put_contents('./msc.log', $message."\n", FILE_APPEND);
		}
    }
    
    /**
     *	Correct any null hash values
     */
    function rehashNullValues() {
    	$table = 'project_1_images';
		$i = 0;
		
    	$rows = $this->db->query('select * from '.$table.' where hash is null');
    	
    	foreach($rows->result() as $row) {
    		// Update the hash values
    		$hashids = new Hashids('abracadabra', 5);
			$hash = $hashids->encode($row->image_id);
			
    		$dataUpdate = array(
			   'hash' => $hash
			);
    		
    		$this->db->where('image_id', $row->image_id);
			$this->db->update($table, $dataUpdate);
			
			$i++;
    	}
    	
    	return $i;
    }
}

/* End of file uploadmodel.php */
/* Location: ./application/models/uploadmodel.php */