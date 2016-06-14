<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
  *	API callback for serving the map
  *  
  */

class Map extends MY_Controller {
	    function __construct() {
        parent::__construct();
        
        $this->load->model('MapModel');
        $this->load->model('LoggerModel');
        $this->load->model('ProfileModel');
    }

	public function index() {
		echo 'Nothing to see here. Move along now...';
	}

	public function loadfossils(){

		$project = "-1";
		$genus = urldecode($this->uri->segment(4));
		$species = $this->uri->segment(5);
		$age_min = urldecode($this->uri->segment(6));
		$age_max = urldecode($this->uri->segment(7));
		$collector = urldecode($this->uri->segment(8));
		$map_lat_ne = $this->uri->segment(9);
		$map_lng_ne = $this->uri->segment(10);
		$map_lat_sw = $this->uri->segment(11);
		$map_lng_sw = $this->uri->segment(12);
		$map_zoom = $this->uri->segment(13);

		$data = array(
			'project' => $project,
			'genus' => $genus,
			'species' => $species,
			'age_min' => $age_min,
			'age_max' => $age_max,
			'collector' => $collector,
			'map_lat_ne' => $map_lat_ne,
			'map_lng_ne' => $map_lng_ne,
			'map_lat_sw' => $map_lat_sw,
			'map_lng_sw' => $map_lng_sw,
			'map_zoom' => $map_zoom
		);

		//fetch the data from the database
		$data = $this->MapModel->loadFossils($data);

		//return data as json
		echo json_encode($data);
	}

	public function updatelocation(){

		$project = "-1";
		$genus = urldecode($this->uri->segment(4));
		$species = $this->uri->segment(5);
		$age_min = $this->uri->segment(6);
		$age_max = $this->uri->segment(7);
		$collector = urldecode($this->uri->segment(8));
		$map_lat_ne = $this->uri->segment(9);
		$map_lng_ne = $this->uri->segment(10);
		$map_lat_sw = $this->uri->segment(11);
		$map_lng_sw = $this->uri->segment(12);
		$map_zoom = $this->uri->segment(13);

		$data = array(
			'project' => $project,
			'genus' => $genus,
			'species' => $species,
			'age_min' => $age_min,
			'age_max' => $age_max,
			'collector' => $collector,
			'map_lat_ne' => $map_lat_ne,
			'map_lng_ne' => $map_lng_ne,
			'map_lat_sw' => $map_lat_sw,
			'map_lng_sw' => $map_lng_sw,
			'map_zoom' => $map_zoom
		);

		//fetch the data from the database
		$data = $this->MapModel->updatelocation($data);

		//return data as json
		echo json_encode($data);
	}

	public function loadfeedbacks(){

		$genus = urldecode($this->uri->segment(4));
		$species = $this->uri->segment(5);
		$age_min = $this->uri->segment(6);
		$age_max = $this->uri->segment(7);
		$collector = urldecode($this->uri->segment(8));
		$map_lat_ne = $this->uri->segment(9);
		$map_lng_ne = $this->uri->segment(10);
		$map_lat_sw = $this->uri->segment(11);
		$map_lng_sw = $this->uri->segment(12);
		$map_zoom = $this->uri->segment(13);

		$filter = array(
			'genus' => $genus,
			'species' => $species,
			'age_min' => $age_min,
			'age_max' => $age_max,
			'collector' => $collector,
			'map_lat_ne' => $map_lat_ne,
			'map_lng_ne' => $map_lng_ne,
			'map_lat_sw' => $map_lat_sw,
			'map_lng_sw' => $map_lng_sw,
			'map_zoom' => $map_zoom
		);

		if ($this->ion_auth->logged_in()){
			$user_id = $this->ion_auth->get_user_id();
		} 
		else
		{
			$user_id = 0;
		}

		$data = $this->MapModel->loadFeedbacks($filter, $user_id);

		echo json_encode($data);

	}

	public function submitfeedback(){
		//get the data
		$user_id = $this->input->post('user_id');
		$unique_id = $this->LoggerModel->getUniqueID();
		$time = date('Y-m-d H:i:s');
		$message = $this->input->post('message');

		$data = array(
			'user_id' => $user_id,
			'unique_id' => $unique_id,
			'time' => $time,
			'message' => $message
		);

		$filter = array(
			'genus' => $this->input->post('genus'),
			'age_min' => $this->input->post('age_min'),
			'age_max' => $this->input->post('age_max'),
			'collector' => $this->input->post('collector')
		);

		$map_coordinates = array(
			'map_lat_ne' => $this->input->post('map_lat_ne'),
			'map_lng_ne' => $this->input->post('map_lng_ne'),
			'map_lat_sw' => $this->input->post('map_lat_sw'),
			'map_lng_sw' => $this->input->post('map_lng_sw'),
			'map_center_lat' => $this->input->post('map_center_lat'),
			'map_center_lng' => $this->input->post('map_center_lng'),
			'map_zoom' => $this->input->post('map_zoom')
		);

		$fossil_selection = $this->input->post('fossil_selection');

		$this->MapModel->submitFeedback($data, $filter, $map_coordinates, $fossil_selection);

	}

	/**
	 *  Upvote a feedback message
	 */
	public function upvotefeedback(){
		//get the data
		$user_id = ($this->input->post('user_id')) ? $this->input->post('user_id'): 0;
		$feedback_id = $this->input->post('feedback_id');
		$time = date('Y-m-d H:i:s');

		//update the database
		$data = array(
			'feedback_id' => $feedback_id,
			'user_id'=> $user_id,
			'time'=> $time
		);

		if($this->db->insert('up_vote', $data)){
			//insert successful
			echo json_encode($data);
		} else {
			//else return an error
			echo 0;
		}
	}

	public function deletefeedback(){
		if ($this->ion_auth->logged_in())
		{
			$data = array(
				'feedback_id' => $this->uri->segment(4),
				'user_id' => $this->ion_auth->get_user_id(),
				'admin'=> $this->ProfileModel->isAdmin()
			);
			$this->MapModel->deleteFeedback($data);
		}
		echo 'You have to be logged in to delete feedbacks';
	}

	public function logmapactivity(){
		//get the data
		$user_id = ($this->input->post('user_id')) ? $this->input->post('user_id'): 0;
		$unique_id = $this->LoggerModel->getUniqueID();
		$time = date('Y-m-d H:i:s');
		$activity = $this->input->post('activity');

		$data = array(
			'user_id' => $user_id,
			'unique_id' => $unique_id,
			'time' => $time,
			'activity' => $activity
		);

		//insert the data in the database

		$this->db->insert('map_activity', $data);
	}




}