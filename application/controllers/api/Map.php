<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
  *	API callback for serving the map
  *  
  */

class Map extends MY_Controller {
	    function __construct() {
        parent::__construct();
        
         $this->load->model('MapModel');
    }

	public function index() {
		echo 'Nothing to see here. Move along now...';
	}

	public function loadfossils(){

		$project = "-1";
		$genus = $this->uri->segment(4);
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
		$data = $this->MapModel->loadFossils($data);

		//return data as json
		echo json_encode($data);
	}

	public function updatelocation(){

		$project = "-1";
		$genus = $this->uri->segment(4);
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

		$genus = $this->uri->segment(4);
		$species = $this->uri->segment(5);
		$age_min = $this->uri->segment(6);
		$age_max = $this->uri->segment(7);
		$collector = $this->uri->segment(8);
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

		$data = $this->MapModel->loadFeedbacks($filter);

		echo json_encode($data);

	}

	public function submitfeedback(){
		//get the data
		$user_id = $this->input->post('user_id');
		$time = date('Y-m-d H:i:s');
		$message = $this->input->post('message');

		$data = array(
			'user_id' => $user_id,
			'time' => $time,
			'message' => $message
		);

		$genus = $this->uri->segment(4);
		$species = $this->uri->segment(5);
		$age_min = $this->uri->segment(6);
		$age_max = $this->uri->segment(7);
		$collector = $this->uri->segment(8);
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

		$this->MapModel->submitFeedback($data, $filter);

		echo json_encode($data);
	}

	/**
	 *  Upvote a feedback message
	 */
	public function upvote(){
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

		if($this->db->insert('upvote', $data)){
			//insert successful
			echo json_encode($data);
		} else {
			//else return an error
			echo 0;
		}

	}

	public function logmapactivity(){
		//get the data
		$user_id = ($this->input->post('user_id')) ? $this->input->post('user_id'): 0;
		$time = date('Y-m-d H:i:s');
		$activity = $this->input->post('activity');

		$data = array(
			'user_id' => $user_id,
			'time' => $time,
			'activity' => $activity
		);

		//insert the data in the database

		$this->db->insert('map_activity', $data);
	}




}