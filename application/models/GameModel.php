<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Model for the game API
  *  
  */
  
class GameModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
     *	Get the leaderboard entries
     */
    function getLeaderboardEntries() {
    	$query = $this->db->query("SELECT p.user_id, p.points, u.location, CONCAT(u.first_name,' ', u.last_name) AS name FROM points p, users u WHERE u.id = p.user_id ORDER BY points DESC LIMIT 9"); 	

    	$i = 1;
    	$data = array();
    	foreach($query->result_array() as $row) {
    		$newID = array('id' => $i);
    		$data[$i] = array_merge($newID, $row); 
    		
    		$i++;
    	}
    	
    	return $data;
    }
    
    /**
     *	Get the activity entries
     */
    function getActivityEntries() {
    	$query = $this->db->query("SELECT a.user_id, a.activity, a.location, a.time, u.location, CONCAT(u.first_name,' ', u.last_name) AS name FROM activity a, users u WHERE u.id = a.user_id ORDER BY a.activity_id DESC LIMIT 20"); 	

    	$i = 1;
    	$data = array();
    	foreach($query->result_array() as $row) {
    		$newID = array('id' => $i);
    		$data[$i] = array_merge($newID, $row); 
    		
    		$i++;
    	}
    	
    	return $data;
    }
    
    /**
     *	Update points for sharing
     */
    function addSharePoints($user_id, $points) {
    	$this->db->where('user_id', $user_id)->set('points', 'points+'.$points, FALSE)->update('points');
    }
	
	/**
     *	See if a new share badge is available
     */	
	function checkShareBadges($user_id) {
		// Increment the shared images count
    	$this->db->where('id', $user_id)->set('share_total', 'share_total+1', FALSE)->update('users');
    	
		//// Check badge count and update if needed    	
    	// Get current total
    	$query = $this->db->query('SELECT share_total FROM users WHERE id = "'.$user_id.'"');
		
		foreach($query->result() as $row) {
			$share_total = $row->share_total;
		}
    	
    	// Check the result and update badges table
    	switch($share_total) {
    		case 1:
    			// First image
    			$this->db->where('user_id', $user_id)->set('share1', '1', FALSE)->update('badges');
    			
    			break;
    		case 10:
    			// Tenth image
    			$this->db->where('user_id', $user_id)->set('share10', '1', FALSE)->update('badges');
    			
    			break;
    		case 25:
    			// Twenty-fifth image
    			$this->db->where('user_id', $user_id)->set('share25', '1', FALSE)->update('badges');
    			
    			break;
    		default:
    			break;
    	}
	}
}

/* End of file gamemodel.php */
/* Location: ./application/models/gamemodel.php */