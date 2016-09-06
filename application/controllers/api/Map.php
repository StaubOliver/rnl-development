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
        $this->load->model('MapABModel');
    }

	public function index() {
		echo 'Nothing to see here. Move along now...';
	}

	public function loadfossils(){

		$data = array(
			'project' => "-1",
			'genus' => urldecode($this->uri->segment(4)),
			'species' => $this->uri->segment(5),
			'age_min' => urldecode($this->uri->segment(6)),
			'age_max' => urldecode($this->uri->segment(7)),
			'collector' => urldecode($this->uri->segment(8)),
			'map_lat_ne' => $this->uri->segment(9),
			'map_lng_ne' => $this->uri->segment(10),
			'map_lat_sw' => $this->uri->segment(11),
			'map_lng_sw' => $this->uri->segment(12),
			'map_zoom' => $this->uri->segment(13)
		);

		//fetch the data from the database
		$data = $this->MapModel->loadFossils($data);

		//return data as json
		echo json_encode($data);
	}

	public function updatelocation(){
		//fetch the data from the database
		echo json_encode($this->MapModel->updatelocation());
	}

	static function compare_feedbacks($a, $b){
		if (intval($a['upvote'])==intval($b["upvote"])){
			return 0;
		} else {
			return (intval($a['upvote']) < intval($b['upvote'])) ? 1 : -1;
		}
	}

	public function loadfeedbacks(){

		$filter = array(
			'genus' => urldecode($this->uri->segment(4)),
			'species' => $this->uri->segment(5),
			'age_min' => $this->uri->segment(6),
			'age_max' => $this->uri->segment(7),
			'collector' => urldecode($this->uri->segment(8)),
			'map_lat_ne' => $this->uri->segment(9),
			'map_lng_ne' => $this->uri->segment(10),
			'map_lat_sw' => $this->uri->segment(11),
			'map_lng_sw' => $this->uri->segment(12),
			'map_zoom' => $this->uri->segment(13)
		);


		$this->ion_auth->logged_in() ? $user_id = $this->ion_auth->get_user_id() : $user_id = 0;

		$unique_id = $this->LoggerModel->getUniqueID();

		$data = $this->MapModel->loadFeedbacks($filter, $user_id);

		if ((!$this->ion_auth->logged_in()) || (($this->ProfileModel->isAdmin() == 0) && ($this->ion_auth->logged_in())))
		{
			if($this->MapABModel->getABGroup($unique_id)=='A'){
				shuffle($data);
			}
			else
			{
				usort($data, array($this, 'compare_feedbacks'));
			}
		}

		echo json_encode($data);	
}

	public function submitfeedback(){

		$data = array(
			'user_id' => $this->input->post('user_id'),
			'unique_id' => $this->LoggerModel->getUniqueID(),
			'time' => date('Y-m-d H:i:s'),
			'message' => $this->input->post('message'),
			'rating_correctness' => 0, 
			'rating_discovery' => 0,
			'rating_relevance' => 0,
			'replyto' => $this->input->post('replyto'),
			'hidden' => 0
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
		$action = $this->input->post('action');
		$details = $this->input->post('details');

		$data = array(
			'user_id' => $user_id,
			'unique_id' => $unique_id,
			'time' => $time,
			'action' => $action,
			'details' => $details
		);

		//insert the data in the database

		$this->db->insert('map_activity', $data);
	}


	public function loadAdminFeedback(){
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				$data = $this->MapModel->adminFeedbacks($this->input->post("contributionIndex"));
				echo json_encode($data);
			}
		}
	}

	public function adminEvaluateFeedback(){
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				$data = array(
					'feedback_id' => $this->input->post('feedback_id'),
					'rating' => $this->input->post('rating'),
					'rate' => $this->input->post('rate')
				);
				$this->MapModel->adminEvaluateFeedback($data);
			}
		}
	}

	public function hidefeedback(){
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				$data = array(
					'hidden' => $this->input->post("hidden")
				);

				$this->db->where("feedback_id", $this->input->post("feedback_id"));
				$this->db->update("feedback", $data);
			}
		}
	}

	public function loadCollector(){
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				echo json_encode($this->MapModel->loadCollector());
			}
		}
	}

	public function adminUpdateCollector()
	{
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				
				$data = array('collector' => $this->input->post("newCollector"));

				$this->db->where("collector", $this->input->post("collector1"));
				$this->db->update("project_1_data", $data);

				if ($this->input->post("collector2") != "-1")
				{
					$this->db->where("collector", $this->input->post("collector2"));
					$this->db->update("project_1_data", $data);
				}
			}
		}
	}

	public function adminUpdateFossilCoordinates()
	{
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				$data = array('lat' => $this->input->post("lat"), 'lng' => $this->input->post("lng"));
				$this->db->where("data_id", $this->input->post("data_id"));
				$this->db->update("project_1_data", $data);
			}
		}	
	}

	public function loadListFossils()
	{
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				echo json_encode($this->MapModel->loadListFossils());
			}
		}
	}

	public function loadFossilDetails()
	{
		if ($this->ion_auth->logged_in()){
			if ($this->ProfileModel->isAdmin()==1){
				echo json_encode($this->MapModel->loadFossilDetails($this->uri->segment(4)));
			}
		}
	}

	public function decluster(){
		$this->MapModel->decluster();
	}



	public function visiteDetails(){
		if ($this->ion_auth->logged_in())
		{
			if ($this->ProfileModel->isAdmin()==1)
			{
				echo json_encode($this->MapModel->visiteDetails($this->input->post('unique_id')));

			}
		}
	}

	public function generalStats(){
		if ($this->ion_auth->logged_in())
		{
			if ($this->ProfileModel->isAdmin()==1)
			{
				echo json_encode($this->MapModel->generalStats());

			}
		}
	}

	public function outputSPMF()
	{
		$file = fopen('/var/www/html/public/outputSPFM.txt', 'w');

		foreach ($this->MapModel->outputSPMF() as $visitor) 
		{
			foreach($visitor as $action)
			{
				fwrite($file, $action." ");
			}
			fwrite($file, "\n");
		}

		fclose($file);

		echo "done";

	}

}