<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Model for the project API
  *  
  */
  
class ProjectModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
     *	Load an shared image entry for the project ID from the incomplete queue
     */
    function getShareImage($id, $hash) {
    	// Set vars
    	$data['complete'] = 0;
    	$data['section'] = 0;
    
    	// Get the name and image_table from projects_master
    	$query = $this->db->query('SELECT name, image_table FROM projects_master WHERE id='.$id.' LIMIT 1');
		$data['project'] = $query->row_array();

		// If hash is zero load a random image, otherwise check the hash and try to load it. If the hashed image is complete, load a random one
		$is_hash = (strlen($hash) > 1) ? true: false;
		if($is_hash) {
			$data['section'] = 1;
			 
			// Check if the image has already been completed
			$query = $this->db->query('SELECT image_id, image_url, filename, queue_count, hash FROM '.$data['project']['image_table'].' WHERE hash="'.$hash.'" AND queue_count=0 LIMIT 1');
			if($query->num_rows() == 0) {
				$data['section'] = 2;
			
				// The image has already been completed
				$data['complete'] = 1;
				
				// Load random image
				$sql = 'SELECT image_id, image_url, filename, hash FROM '.$data['project']['image_table'].' WHERE queue_count=0 LIMIT 1';
			} else {
				$data['section'] = 3;
				// Image identified by hash not completed, load it
				// Load image by hash id
				$sql = 'SELECT image_id, image_url, filename, queue_count, hash FROM '.$data['project']['image_table'].' WHERE hash="'.$hash.'"  LIMIT 1';
			}
    	} else {
    		$data['section'] = 4;
    		
    		// Load random image
			$sql = 'SELECT image_id, image_url, filename, hash FROM '.$data['project']['image_table'].' WHERE queue_count=0 LIMIT 1';
    	}
    	
    	// Get the next image in the queue
    	$query = $this->db->query($sql);
    	
    	// Check there is an image left in the queue
    	if($query->num_rows() > 0) {
    		// Image returned
    		$data['image'] = $query->row_array();
    		
    	} else {
    		// No images in the queue
    		$data['image']['image_id'] = 0;
    		
    	}
    	
    	// Put the data together
    	$return = array(
    		'project' => $data['project']['name'],
    		'image' => $data['image'],
    		'complete' => $data['complete'],
    		'section' => $data['section']
    	);
    	
    	// Return the data
    	return $return;
    }
    
    /**
     *	Load an entry for the project ID from the incomplete queue
     */
    function getProjectEntry($id) {
    	// Get the name and image_table from projects_master    	
    	$query = $this->db->query('SELECT name, image_table FROM projects_master WHERE id='.$id.' LIMIT 1');
		$data['project'] = $query->row_array();
    	
    	// Get the next image in the queue
    	$query = $this->db->query('SELECT image_id, image_url, filename, hash FROM '.$data['project']['image_table'].' WHERE queue_count=0 LIMIT 1');
    	
    	// Check there is an image left in the queue
    	if($query->num_rows() > 0) {
    		// Image returned
    		$data['image'] = $query->row_array();
    		
    	} else {
    		// No images in the queue
    		$data['image']['image_id'] = 0;
    	}
    	
    	// Put the data together
    	$return = array(
    		'project' => $data['project']['name'],
    		'image' => $data['image'],
    		'request_time' => date('Y-m-d H:i:s')
    	);
    	
    	// Return the data
    	return $return;
    }
    
    /**
     *	Load an entry for the project ID from the review queue
     */
    function getReviewEntry($id, $last_image) {
    	// Get the name and image_table from projects_master    	
    	$query = $this->db->query('SELECT name, image_table, data_table FROM projects_master WHERE id='.$id.' LIMIT 1');
		$data['project'] = $query->row_array();
    	
    	// Get the next image in the review queue
    	// http://akinas.com/pages/en/blog/mysql_random_row/
    	$query = $this->db->query('SELECT image_id, image_url, filename, hash FROM '.$data['project']['image_table'].' WHERE queue_count=1 AND complete=0 AND image_id != '.$last_image.' ORDER BY RAND() LIMIT 1');
    	
    	// Check there is an image left in the queue
    	if($query->num_rows() > 0) {
    		// Image returned
    		$data['image'] = $query->row_array();
    		
    		// Get the data entry for the image
    		$query = $this->db->query('SELECT * FROM '.$data['project']['data_table'].' WHERE image_id='.$data['image']['image_id'].' LIMIT 1');
			$data['entry'] = $query->row_array();
		
    	} else {
    		// No images in the queue
    		$data['image']['image_id'] = 0;
    		$data['entry'] = 0;
    	}
    	
    	// Put the data together
    	$return = array(
    		'project' => $data['project']['name'],
    		'image' => $data['image'],
    		'entry' => $data['entry'],
    		'request_time' => date('Y-m-d H:i:s')
    	);
    	
    	// Return the data
    	return $return;
    }
    
    /**
     *	Update the user points for a successul entry
     */
    function updateUserPoints($user_id, $points) {
		$this->db->where('user_id', $user_id)->set('points', 'points+'.$points, FALSE)->update('points');
    }
    
    /**
     *	Update the activtity just completed
     */
    function updateActivityEntry($user_id, $activity) {
    	$location = 'GB';
    	$time = date("c");;
    	
    	$data = array(
		   	'user_id' => $user_id,
		   	'activity' => $activity,
		   	'location' => $location,
		    'time' => $time
		);

		$this->db->insert('activity', $data); 
    }
    
    /**
     *	List projects
     */
    function listProjects() {
    	$data = $this->db->query('SELECT id, name, image, blurb FROM projects_master WHERE display = 1');
    	
    	return $data;
    }
    
    /**
     *	Update totals and badges for image queue
     */
    function updateQueueBadgesTotals($user_id) {
    	// Increment the reviewed images count
    	$this->db->where('id', $user_id)->set('queue_total', 'queue_total+1', FALSE)->update('users');
    	
    	//// Check badge count and update if needed    	
    	// Get current total
    	$query = $this->db->query('SELECT queue_total FROM users WHERE id = "'.$user_id.'"');
		
		foreach($query->result() as $row) {
			$queue_total = $row->queue_total;
		}
    	
    	// Check the result and update badges table
    	switch($queue_total) {
    		case 1:
    			// First image
    			$this->db->where('user_id', $user_id)->set('image1', '1', FALSE)->update('badges');
    			
    			break;
    		case 10:
    			// Tenth image
    			$this->db->where('user_id', $user_id)->set('image10', '1', FALSE)->update('badges');
    			
    			break;
    		case 25:
    			// Twenty-fifth image
    			$this->db->where('user_id', $user_id)->set('image25', '1', FALSE)->update('badges');
    			
    			break;
    		default:
    			break;
    	}
    }
    
    /**
     *	Update totals and badges for review queue
     */
    function updateReviewBadgesTotals($user_id) {
    	// Increment the reviewed images count
    	$this->db->where('id', $user_id)->set('review_total', 'review_total+1', FALSE)->update('users');
    	
    	//// Check badge count and update if needed    	
    	// Get current total
    	$query = $this->db->query('SELECT review_total FROM users WHERE id = "'.$user_id.'"');
		
		foreach($query->result() as $row) {
			$review_total = $row->review_total;
		}
    	
    	// Check the result and update badges table
    	switch($review_total) {
    		case 1:
    			// First review
    			$this->db->where('user_id', $user_id)->set('review1', '1', FALSE)->update('badges');
    			
    			break;
    		case 10:
    			// Tenth review
    			$this->db->where('user_id', $user_id)->set('review10', '1', FALSE)->update('badges');
    			
    			break;
    		case 25:
    			// Twenty-fifth review
    			$this->db->where('user_id', $user_id)->set('review25', '1', FALSE)->update('badges');
    			
    			break;
    		default:
    			break;
    	}
    }
    
    /**
     *	List the projects for the admin view
     */
    function adminProjects() {
    	$query = $this->db->query('SELECT id, name, image, blurb FROM projects_master WHERE display = 1');
    	$data = array();
    	$i = 0;
    	
    	foreach($query->result() as $row) {
			$project_id = $row->id;
			
			$total_images = $this->db->query('SELECT count(*) as total FROM project_'.$project_id.'_images');
			$total_complete = $this->db->query('SELECT count(*) as total FROM project_'.$project_id.'_images WHERE queue_count = 1');
			$total_reviewed = $this->db->query('SELECT count(*) as total FROM project_'.$project_id.'_images WHERE review_count > 0');
			
			$data[$i]['id'] = $row->id;
			$data[$i]['name'] = $row->name;
			$data[$i]['image'] = $row->image;
			$data[$i]['blurb'] = $row->blurb;
			
			foreach($total_images->result() as $row) {
				$data[$i]['total_images'] = $row->total; 
			}
			
			foreach($total_complete->result() as $row) {
				$data[$i]['total_complete'] = $row->total; 
			}
			
			foreach($total_reviewed->result() as $row) {
				$data[$i]['total_reviewed'] = $row->total; 
			}
			
			$i++;
		}
    	
    	return $data;
    }
    
    /**
     *	List the project entries for the admin view
     */
    function adminProjectsComplete($project_id) {
    	$query = $this->db->query('SELECT 
										*
									FROM
										project_'.$project_id.'_data AS pd
											INNER JOIN
										project_'.$project_id.'_images AS pi 
											ON pd.image_id = pi.image_id
									ORDER BY pd.image_id ASC');
    	
    	return $query->result();
    }
    
    /**
     *	Mark image as complete
     */
    function adminProjectsCompleteImage($project_id, $image_id) {
    	$data = array(
               'complete' => 1
            );

		$this->db->where('image_id', $image_id); 
    	
		if($this->db->update('project_'.$project_id.'_images', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}    	
    }
}

/* End of file projectmodel.php */
/* Location: ./application/models/projectmodel.php */