<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	API for the gamification
  *  
  */
  
//class Game extends MY_Controller {
class Game extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('GameModel');
    }
    
	public function index() {
		echo 'Nothing to see here. Move along now...';
	}
	
	public function leaderboard() {	
		// Fetch the data
		$data = $this->GameModel->getLeaderboardEntries();
		
		// Return the json
		echo json_encode($data);
	}
	
	public function activity() {
		// Fetch the data
		$data = $this->GameModel->getActivityEntries();
		
		// Return the json
		echo json_encode($data);
	}
	
	public function sharePointsBadges() {
		$user_id = $this->input->post('user_id');
	
		// Update the points +5 for sharing
		$this->GameModel->addSharePoints($user_id, 5);
		
		// See if a new share badge is available
		$this->GameModel->checkShareBadges($user_id);
	}
}

/* End of file game.php */
/* Location: ./application/controllers/api/game.php */