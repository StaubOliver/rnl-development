<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
  *	Model for the AB groups users for the map part
  *  
  */
  
class MapABModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model("LoggerModel");
    }


    function getABGroup($unique_id){
    	$query_ab_group = $this->db->query("SELECT ab_group FROM map_ab WHERE unique_id='".$unique_id."'");

    	if ($query_ab_group->num_rows() > 0){
    		$result = $query_ab_group->row_array();
    		return $result['ab_group'];
    	}
    	else
    	{
    		return $this->setABGroup();
    	}
    }

    function firstVisit($unique_id){
        $query_ab_group = $this->db->query("SELECT ab_group FROM map_ab WHERE unique_id='".$unique_id."'");
        if ($query_ab_group->num_rows() > 0){
            $result = $query_ab_group->row_array();
            return false;
        }
        else
        {
            return true;
        }
    }


    function setABGroup(){
    	$group = 'C';
    	rand(1,2) == 2 ? $group = 'A' : $group = 'B';

    	$data = array(
    		'unique_id' => $this->LoggerModel->getUniqueID(),
    		'ab_group' => $group,
    		'date_time' => date('Y-m-d H:i:s')
		);
    	$this->db->insert('map_ab', $data);
    	return $this->getABGroup($this->LoggerModel->getUniqueID());
    }

































}