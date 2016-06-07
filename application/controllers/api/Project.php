<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	API for the individual projects
  *  
  */
  
class Project extends MY_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('ProjectModel');
        //$this->load->model('LoggerModel');
    }
    
	public function index() {
		exit('No access to index.');
	}
	
	/**
	 *  Return the next image in the queue for the project id
	 */
	public function loadqueue() {
		// Get the id from the URL
		$project_id = $this->uri->segment(4);
		
		// Fetch the data
		$data = $this->ProjectModel->getProjectEntry($project_id);
		
		// Return data as json
		echo json_encode($data);
	}
	
	/**
	 *  Return the next image for review for the project id
	 */
	public function loadreview() {
		// Get the id from the URL
		$project_id = $this->uri->segment(4);
		$last_image = $this->uri->segment(5);
		
		// Fetch the data
		$data = $this->ProjectModel->getReviewEntry($project_id, $last_image);
		
		// Return data as json
		echo json_encode($data);
	}
	
	/**
	 *  Return the shared image in the queue for the project id and image hash id
	 */
	public function loadshare() {
		// Get the id from the URL
		$project_id = $this->uri->segment(4);
		$image_hash = $this->uri->segment(5);
		
		// Fetch the data
		$data = $this->ProjectModel->getShareImage($project_id, $image_hash);
		
		// Return data as json
		echo json_encode($data);
	}
	
	/**
	 *  Get the data from the input form
	 */
	public function submitdata() {
		// Get the data
		$user_id = ($this->input->post('user_id')) ? $this->input->post('user_id'): 0;
		$project_id = $this->input->post('project_id');
		$image_id = $this->input->post('image_id');
				
		$age = $this->input->post('age');
		$collector = $this->input->post('collector');
		$species = $this->input->post('species');
		$genus = $this->input->post('genus');
		$genuscustom = $this->input->post('genuscustom');
		$country = $this->input->post('country');
		$place = $this->input->post('place');
		
		$interface = $this->input->post('interface');
		$request_time = $this->input->post('request_time'); 
		
		// Check if a custom entry was made for genus
		/*
		if($genus == 'Not listed') {
			$genus = $genuscustom;
		}
		*/
		
		// Update database
		$data = array(
			'image_id' => $image_id,
			'genus' => $genus,
			'genuscustom' => $genuscustom,
			'species' => $species,
			'age' => $age,
			'country' => $country,
			'place' => $place,
			'collector' => $collector
		);

		if($this->db->insert('project_'.$project_id.'_data', $data)) {
			// insert successful...
			
			// Set the image as done for the queue
			$data = array('queue_count' => 1);
			
			$this->db->where('image_id', $image_id);
			$this->db->update('project_'.$project_id.'_images', $data);
	
			// Update image logger
			//$this->LoggerModel->logImage($interface, $project_id, $image_id, $request_time, date('Y-m-d H:i:s'), 1, 0);
			
			// Update review total and badges
			$this->ProjectModel->updateQueueBadgesTotals($user_id);
		
			//Update user points
			$this->ProjectModel->updateUserPoints($user_id, 3);
		
			// Update activity feed
			$this->ProjectModel->updateActivityEntry($user_id, 'completed an image');
			
			// Return the next image
			$data = $this->ProjectModel->getProjectEntry($project_id);

			echo json_encode($data);
		} else {
			// Or return an error
			echo 0;
		}	
	}
	
	/**
	 *  Update the data from the review form
	 */
	function submitreview() {
		// Get the data
		$user_id = ($this->input->post('user_id')) ? $this->input->post('user_id'): 0;
		$project_id = $this->input->post('project_id');
		$image_id = $this->input->post('image_id');
				
		$age = $this->input->post('age');
		$collector = $this->input->post('collector');
		$species = $this->input->post('species');
		$genus = $this->input->post('genus');
		$genuscustom = $this->input->post('genuscustom');
		$country = $this->input->post('country');
		$place = $this->input->post('place');
		
		$interface = $this->input->post('interface');
		$request_time = $this->input->post('request_time'); 
		
		// Check if a custom entry was made for genus
		/*
		if($genus == 'Not listed') {
			$genus = $genuscustom;
		}
		*/
		
		// Update database
		$data = array(
			'genus' => $genus,
			'genuscustom' => $genuscustom,
			'species' => $species,
			'age' => $age,
			'country' => $country,
			'place' => $place,
			'collector' => $collector
		);

		$this->db->where('image_id', $image_id);
		if($this->db->update('project_'.$project_id.'_data', $data)) {
			// update successful...
			
			// Set the image as done for the queue
			$this->db->where('image_id', $image_id);
			$this->db->set('review_count', 'review_count+1', FALSE);
			$this->db->update('project_'.$project_id.'_images');

			// Update image logger
			//$this->LoggerModel->logImage($interface, $project_id, $image_id, $request_time, date('Y-m-d H:i:s'), 0, 1);
						
			// Update review total and badges
			$this->ProjectModel->updateReviewBadgesTotals($user_id);

			//Update user points
			$this->ProjectModel->updateUserPoints($user_id, 2);

			// TODO: Update activity feed
			$this->ProjectModel->updateActivityEntry($user_id, 'reviewed an image');
			
			// Return the next image
			$data = $this->ProjectModel->getReviewEntry($project_id, $image_id);

			echo json_encode($data);
		} else {
			// Or return an error
			echo 0;
		}	
	}
	
	/**
	 *  Load the data entry form
	 */
	function loadform() {
		$form = '';
		$project_id = $this->uri->segment(5);
		$data['interface'] = ($this->ion_auth->logged_in()) ? 'loggedin': 'share';
		$data['user_id'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->get_user_id(): 0;

		if($this->uri->segment(4) == 'queue') {
			$this->load->view('forms/project-queue-'.$project_id, $data);
		}
		
		elseif($this->uri->segment(4) == 'review') {
			$this->load->view('forms/project-review-'.$project_id, $data);
		}
	}
}

/* End of file project.php */
/* Location: ./application/controllers/api/project.php */