<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
  *	Model for the map API
  *  
  */
  
class MapModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // function to geocode address, it will return false if unable to geocode address
    function geocode($address){
     
        // url encode the address
        $address = urlencode($address);
         
        // google map geocode api url
        $url = "https://maps.google.com/maps/api/geocode/json?address=".$address."&key=AIzaSyBUmXudJDRuXR6ZiUxuiskGnf13pwTvAa0";
     
        // get the json response
        $resp_json = file_get_contents($url);
         
        // decode the json
        $resp = json_decode($resp_json, true);
     
        // response status will be 'OK', if able to geocode given address 
        if($resp['status']=='OK'){
     
            // get the important data
            $lati = $resp['results'][0]['geometry']['location']['lat'];
            $longi = $resp['results'][0]['geometry']['location']['lng'];
            $formatted_address = $resp['results'][0]['formatted_address'];
             
            // verify if data is complete
            if($lati && $longi && $formatted_address){
             
                // put the data in the array
                $data_arr = array();            
                 
                array_push(
                        $data_arr,
                        $lati, 
                        $longi, 
                        $formatted_address
                    );
                 
                return $data_arr;
                 
            }else{
                return false;
            }
             
        }else{
            return false;
        }
    }

    function age_criteria($age_min, $age_max){
        //using the data from the filter we create the where statement for the age criterai

        $min = intval($age_min);
        $max = intval($age_max);

        $temp = [];

        $list = "";

        if (0>=$min and 0<=$max){
            $temp[] = 'Quaternary';
            $temp[] = 'Holocene';
            $temp[] = 'Pleistone';
        }

        if (1>=$min and 1<=$max){
            $temp[] = 'Pleicene';
            $temp[] = 'Miocene';
        }

        if (2>=$min and 2<=$max){
            $temp[] = 'Oligocene';
            $temp[] = 'Eocene';
            $temp[] = 'Paleocene';
        }

        if (3>=$min and 3<=$max){
            $temp[] = 'Cretaceous';
            $temp[] = 'Cretaceous, Upper';
            $temp[] = 'Cretaceous, Lower';
        }

        if (4>=$min and 4<=$max){
            $temp[] = 'Jurassic';
            $temp[] = 'Jurassic, Upper';
            $temp[] = 'Jurassic, Middle';
            $temp[] = 'Jurassic, Lower (Lias)';
        }

        if (5>=$min and 5<=$max){
            $temp[] = 'Triassic';
            $temp[] = 'Triassic, Upper';
            $temp[] = 'Triassic, Middle';
            $temp[] = 'Triassic, lower';
        }

        if (6>=$min and 6<=$max){
            $temp[] = 'Permian';
        }

        if (7>=$min and 7<=$max){
            $temp[] = 'Carboniferous';
            $temp[] = 'Carboniferous Upper (Coal Measeures)';
            $temp[] = 'Carboniferous Lower (Limestone)';
        }

        if (8>=$min and 8<=$max){
            $temp[] = 'Devonian';
            $temp[] = 'Devonian, Upper';
            $temp[] = 'Devonian, Middle';
            $temp[] = 'Devonian, Lower';
        }

        if (9>=$min and 9<=$max){
            $temp[] = 'Silurian';
            $temp[] = 'Silurian, Pridoli';
            $temp[] = 'Silurian, Ludlow';
            $temp[] = 'Silurian, Wenlock';
            $temp[] = 'Silurian, Llandovery';
        }

        if (10>=$min and 10<=$max){
            $temp[] = 'Ordovician';
            $temp[] = 'Ordovician, Upper';
            $temp[] = 'Ordovician, Middle';
            $temp[] = 'Ordovician, Lower';
        }

        if (11>=$min and 11<=$max){
            $temp[] = 'Cambrian';
        }

        if (12>=$min and 12<=$max){
            $temp[] = 'Precambrian';
        }

        $temp[] = 'Illegible';
        $temp[] = 'Missing';
        $temp[] = '';

        for ($i = 0; $i < count($temp)-1; ++$i){
            $list .= "'".$temp[$i]."', ";
        }
        $list .= "'".$temp[count($temp)-1]."'";

        return "age IN (".$list.")";
    }


    /**
    *   Load all fossils given the informations of genus, species, age and collector form every project
    */
    function loadFossils($data){

    	//using the data from the filter we create the where statement for querying the database
    	$where = [];
    	$i = 0;
    	
    	if ($data['genus'] != "-1"){
    		$where[$i] = "genus = '" . $data['genus']."'";
			$i += 1;
    	}

        /*
    	$where[$i] = "age_min = " . $data['age_min'];
        $i += 1;

        $where[$i] = "age_max = " . $data['age_max'];
        $i += 1;*/

        $where[$i] = $this->age_criteria($data["age_min"], $data["age_max"]);
        $i += 1;
      

    	if ($data['collector'] != "-1"){
    		$where[$i] = "collector = '" . $data['collector']."'";
    		$i += 1;
    	}

        $where_string = "";

        if ($i != 0)
        {
        	for ($j=0; $j<$i-1; $j++)
        	{
    			$where_string .= $where[$j] . " AND ";
        	}
        	
            $where_string .= $where[$i-1]; 
        }
        else {
            $where_string = " 1";
        }

        $query=NULL;

    	//Now we look at the projects_master table to give us the data_table foreach project
        if($data['project']=="-1"){
            $query = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');
        }

    	$return = array();
        
    	if($query->num_rows() > 0) {
    		foreach($query->result_array() as $row)
    		{
    			//we retrieve the data from each fossil from each project
    			$query2 = $this->db->query('SELECT data_id, image_id, genus, species, age, country, place, collector, lat, lng FROM ' . $row["data_table"].' WHERE '.$where_string.' and lat!="0" and lng!="0"');

                //return $query2->result_array(); 
                $image_table = $row['image_table'];
				//if($query2->num_rows>0){
			    //$return[] = $query2->result_array();
				//}
                foreach ($query2->result_array() as $row)
                {
                    //$temp = $this->geocode($row['country'].' '.$row['place']);
                    
                    /*if ($temp != false) {
                        $row['lattitude'] = $temp[0];
                        $row['longitude'] = $temp[1];
                    
                    }*/

                    $query3 = $this->db->query("SELECT image_url FROM ".$image_table." WHERE image_id=".$row["image_id"]);

                    
                    if($query3->num_rows() > 0) {
                        foreach ($query3->result() as $u) {
                            $row['url'] = $u->image_url;
                        }
                    }
                    //return $row;
                    if ($row['lat'] != "") {
                        $return[] = $row; 
                    }
                }		
    		}
            //return the data
            return $return;
    	}
    }

    function getFeedbackDetails($row, $user_id){
        $res = $row;
         //fetching user information related to each feedback
        $query_user = $this->db->query('SELECT first_name, last_name FROM users WHERE id = '.$row['user_id']);
        
        if ($query_user->num_rows()>0){
            $result_query_user = array();
            $user = $query_user->row_array();
            $res['first_name'] = $user['first_name'];
            $res['last_name'] = $user['last_name'];
        } else {
            $res['first_name'] = "John";
            $res['last_name'] = "Smith";
        }

        //querying upvote information for each feedback
        $query_upvote = $this->db->query('SELECT upvote_id, user_id  FROM up_vote WHERE feedback_id = '.$row['feedback_id']);
        
        $res['upvote'] = $query_upvote->num_rows();
        $res['user_has_upvote'] = false;
        
        if ($query_upvote->num_rows() > 0){
            foreach ($query_upvote->result_array() as $up) 
            {
                if ($up['user_id'] == $user_id){
                    $res['user_has_upvote'] = true;
                }
            }
        }

        //querying map information for each feedback
        $query_map_coord = $this->db->query("SELECT map_center_lat, map_center_lng, map_lat_ne, map_lng_ne, map_lat_sw, map_lng_sw, map_zoom FROM map_coordinates WHERE map_coordinates_id='".$row["map_coordinates_id"]."'");
        if ($query_map_coord->num_rows()>0){
            $coor = $query_map_coord->row_array();
            $res["map_center_lat"] = $coor['map_center_lat'];
            $res["map_center_lng"] = $coor['map_center_lng'];
            $res["map_lat_ne"] = $coor["map_lat_ne"];
            $res["map_lng_ne"] = $coor["map_lng_ne"];
            $res["map_lat_sw"] = $coor["map_lat_sw"];
            $res["map_lng_sw"] = $coor["map_lng_sw"];
            $res["map_zoom"] = $coor["map_zoom"];
        }

        //querying selected fossils for each feedback
        $query_selection = $this->db->query("SELECT data_table, data_id FROM feedback_fossil WHERE feedback_id='".$row['feedback_id']."'");
        $res["selection"] = [];
        if($query_selection->num_rows()>0){
            foreach ($query_selection->result_array() as $select) 
            {
                //$row['selection'][] = $select;
                $query_fossil = $this->db->query("SELECT lat, lng FROM ".$select["data_table"]." WHERE data_id='".$select["data_id"]."'");
                if ($query_fossil->num_rows()>0)
                {
                    $temp = $query_fossil->row_array();
                    $temp['id'] = $select["data_id"];
                    $res['selection'][] = $temp;
                }
            }
        }
        return $res;
    }


    function loadFeedbacks($data, $user_id){
    	//using the data from the filter we create the where statement for querying the database
    	$where = [];
    	$i = 0;
    	
		$where[$i] = "genus = '" . $data['genus']."'";
        $i += 1;


        $where[$i] = "age_min = " . $data['age_min'];
        $i += 1;

        $where[$i] = "age_max = " . $data['age_max'];
        $i += 1;
      

        $where[$i] = "collector = '" . $data['collector']."'";
        $i += 1;

    	$where_string = "";

    	if ($i != 0)
        {
            for ($j=0; $j<$i-1; $j++)
            {
                $where_string .= $where[$j] . " AND ";
            }
            
            $where_string .= $where[$i-1]; 
        }
        else {
            $where_string = " 1";
        }


    	//querying the database to find the filter id
    	$query = $this->db->query('SELECT filter_id FROM filter WHERE '.$where_string);

    	if ($query->num_rows() > 0)
    	{
            $return = array();
    		//if the filter is found we use it to retrieve the feedabcks
    		$row = $query->row_array();
    		$filter_id = $row['filter_id'];

    		$query2 = $this->db->query('SELECT feedback_id, user_id, time, message, map_coordinates_id FROM feedback WHERE filter_id='.$filter_id.' AND replyto=0 AND hidden=0 ORDER BY time DESC');

    		if ($query2->num_rows() > 0)
    		{
    			//we found some feedbacks related to that filter
    			foreach ($query2->result_array() as $row){
                    
                    $new_row = $this->getFeedbackDetails($row, $user_id);
                    $new_row['replyto'] = 0;
                    
                    $new_row['replies'] = array();

                    //query of replies for each feedback
                    $query_replies = $this->db->query('SELECT feedback_id, user_id, time, message, map_coordinates_id FROM feedback WHERE filter_id='.$filter_id.' AND replyto='.$new_row['feedback_id'].' AND hidden=0 ORDER BY time ASC');

                    if ($query_replies->num_rows() > 0){
                        //for each replies, we get their details
                        foreach ($query_replies->result_array() as $rep){
                            $temp = $this->getFeedbackDetails($rep, $user_id);
                            $temp["selection"] = $new_row["selection"];
                            $new_row["replies"][] = $temp;
                        }

                    }

                    $return[] = $new_row;
                }
                return $return;
    		} else {
    			//if we didn't we return an empty array
    			return $return;
    		}
    	} else {
    		//if the filter is not found then no feedbacks are recorded. We return an emty array
    		return $return;
    	}
    }

    function getAdminFeedbacksDetails($row){

        $new_row = $row;
        //for each feedback we query its user information
        $query_user = $this->db->query('SELECT first_name, last_name FROM users WHERE id = '.$row['user_id']);
            
        if ($query_user->num_rows()>0){
            $result_query_user = array();
            $user = $query_user->row_array();
            $new_row['first_name'] = $user['first_name'];
            $new_row['last_name'] = $user['last_name'];
        } else {
            $new_row['first_name'] = "John";
            $new_row['last_name'] = "Smith";
        }

        //for each feedback we query its upvote information
        $query_upvote = $this->db->query('SELECT upvote_id  FROM up_vote WHERE feedback_id = '.$row['feedback_id']);
        $new_row['upvote'] = $query_upvote->num_rows();

        //for each feedback we query its filter information
        $query_filter= $this->db->query("SELECT genus, collector, age_min, age_max, collector  FROM filter WHERE filter_id=".$row["filter_id"]);

        if ($query_filter->num_rows()>0){
            $coor = $query_filter->row_array();
            $new_row["genus"] = $coor['genus'];

            if ($coor['age_min']=="0") {$new_row["age_min"] = 'Quaternary';} 
            if ($coor['age_min']=="1") {$new_row["age_min"] = 'Neogene';} 
            if ($coor['age_min']=="2") {$new_row["age_min"] = 'Paleogene';} 
            if ($coor['age_min']=="3") {$new_row["age_min"] = 'Cretaceous';} 
            if ($coor['age_min']=="4") {$new_row["age_min"] = 'Jurassic';} 
            if ($coor['age_min']=="5") {$new_row["age_min"] = 'Triassic';} 
            if ($coor['age_min']=="6") {$new_row["age_min"] = 'Permian';} 
            if ($coor['age_min']=="7") {$new_row["age_min"] = 'Carboniferous';} 
            if ($coor['age_min']=="8") {$new_row["age_min"] = 'Devonian';} 
            if ($coor['age_min']=="9") {$new_row["age_min"] = 'Silurian';} 
            if ($coor['age_min']=="10") {$new_row["age_min"] = 'Ordovician';} 
            if ($coor['age_min']=="11") {$new_row["age_min"] = 'Cambrian';} 
            if ($coor['age_min']=="12") {$new_row["age_min"] = 'Precambrian';} 

            if ($coor['age_max']=="0") {$new_row["age_max"] = 'Quaternary';} 
            if ($coor['age_max']=="1") {$new_row["age_max"] = 'Neogene';} 
            if ($coor['age_max']=="2") {$new_row["age_max"] = 'Paleogene';} 
            if ($coor['age_max']=="3") {$new_row["age_max"] = 'Cretaceous';} 
            if ($coor['age_max']=="4") {$new_row["age_max"] = 'Jurassic';} 
            if ($coor['age_max']=="5") {$new_row["age_max"] = 'Triassic';} 
            if ($coor['age_max']=="6") {$new_row["age_max"] = 'Permian';} 
            if ($coor['age_max']=="7") {$new_row["age_max"] = 'Carboniferous';} 
            if ($coor['age_max']=="8") {$new_row["age_max"] = 'Devonian';} 
            if ($coor['age_max']=="9") {$new_row["age_max"] = 'Silurian';} 
            if ($coor['age_max']=="10") {$new_row["age_max"] = 'Ordovician';} 
            if ($coor['age_max']=="11") {$new_row["age_max"] = 'Cambrian';} 
            if ($coor['age_max']=="12") {$new_row["age_max"] = 'Precambrian';} 

            $new_row["age_min_filter"] = $coor["age_min"];
            $new_row["age_max_filter"] = $coor["age_max"];

            $new_row["collector"] = $coor["collector"];
        }


        //for each feedback we query its map coordinates information
        $query_map_coord = $this->db->query("SELECT map_center_lat, map_center_lng, map_lat_ne, map_lng_ne, map_lat_sw, map_lng_sw, map_zoom FROM map_coordinates WHERE map_coordinates_id='".$row["map_coordinates_id"]."'");
        if ($query_map_coord->num_rows()>0){
            $coor = $query_map_coord->row_array();
            $new_row["map_center_lat"] = $coor['map_center_lat'];
            $new_row["map_center_lng"] = $coor['map_center_lng'];
            $new_row["map_lat_ne"] = $coor["map_lat_ne"];
            $new_row["map_lng_ne"] = $coor["map_lng_ne"];
            $new_row["map_lat_sw"] = $coor["map_lat_sw"];
            $new_row["map_lng_sw"] = $coor["map_lng_sw"];
            $new_row["map_zoom"] = $coor["map_zoom"];
        }


        //for each feedback we query its selected fossils
        $query_selection = $this->db->query("SELECT data_table, data_id FROM feedback_fossil WHERE feedback_id='".$row['feedback_id']."'");
        
        $new_row['selection'] = [];

        if($query_selection->num_rows()>0)
        {
            foreach ($query_selection->result_array() as $select) 
            {
                //$row['selection'][] = $select;
                $query_fossil = $this->db->query("SELECT lat, lng, age, collector, place, genus, species  FROM ".$select["data_table"]." WHERE data_id='".$select["data_id"]."'");

                if ($query_fossil->num_rows()>0)
                {
                    $temp = $query_fossil->row_array();
                    $temp['id'] = $select["data_id"];
                    $new_row['selection'][] = $temp;
                }
            }
        } 

        return $new_row;
    }

    

    function adminFeedbacks(){

        $return = [];

        $query_feedbacks = $this->db->query('SELECT feedback_id, user_id, filter_id, time, message, map_coordinates_id, rating_correctness, rating_discovery, rating_relevance, hidden  FROM feedback WHERE replyto=0 ORDER BY time DESC');

        if ($query_feedbacks->num_rows() > 0){
            foreach ($query_feedbacks->result_array() as $row) {
                
                $new_row = $this->getAdminFeedbacksDetails($row);

                $new_row['replyto'] = 0;
                $new_row['replies'] = array();

                //query of replies for each feedback
                $query_replies = $this->db->query('SELECT feedback_id, user_id, filter_id, time, message, map_coordinates_id, rating_correctness, rating_discovery, rating_relevance, hidden FROM feedback WHERE replyto='.$new_row['feedback_id'].' ORDER BY time ASC');

                if ($query_replies->num_rows() > 0){
                    //for each replies, we get their details
                    foreach ($query_replies->result_array() as $rep){
                        $new_row["replies"][] = $this->getAdminFeedbacksDetails($rep);
                    }

                }

                //we add the completed feedbaack to the result array
                $return[] = $new_row;
            }
        }
        return $return;

    }

    function adminEvaluateFeedback($data){

        if ($data['rating'] == 1){
            $rating = array('rating_correctness'=>$data['rate']);
        }
        if ($data['rating'] == 2){
            $rating = array('rating_discovery'=>$data['rate']);
        }
        if ($data['rating'] == 3){
            $rating = array('rating_relevance'=>$data['rate']);
        }

        $this->db->where("feedback_id", $data['feedback_id']);
        $this->db->update('feedback', $rating);
    }

    function submitFeedback($data, $filter, $map_coordinates, $fossil_selection){
        //using the data from the filter we create the where statement for querying the database
        $where = [];
        $i = 0;
        
        $where[$i] = "genus = '" . $filter['genus']."'";
        $i += 1;


        $where[$i] = "age_min = " . $filter['age_min'];
        $i += 1;

        $where[$i] = "age_max = " . $filter['age_max'];
        $i += 1;
      

        $where[$i] = "collector = '" . $filter['collector']."'";
        $i += 1;

        $where[$i] = "";

        $where_string = "";

        if ($i != 0)
        {
            for ($j=0; $j<$i-1; $j++)
            {
                $where_string .= $where[$j] . " AND ";
            }
            
            $where_string .= $where[$i-1]; 
        }
        else {
            $where_string = " 1";
        }

        $filter_id = -1;
        //$map_coordinates_id = -1;

        //querying the database to find the filter id
        $query_filter = $this->db->query('SELECT filter_id FROM filter WHERE '.$where_string);


        if ($query_filter->num_rows() > 0)
        {
            //if we found a matching filter we get its id to save the new feddback
            $temp = $query_filter->row_array();
            $filter_id = $temp['filter_id'];

        } else {
            //if not we create this new filter before inserting the new feedback
            if($this->db->insert('filter', $filter))
            {
                $query_filter_second = $this->db->query('SELECT filter_id FROM filter WHERE '.$where_string);
                if ($query_filter_second->num_rows() > 0)
                {
                    $temp = $query_filter_second->row_array();
                    $filter_id = $temp['filter_id'];
                }
            }

        }

        $data['filter_id'] = $filter_id;
        $data['map_coordinates_id'] = 0;

        //adding the map_coordinates
        if($this->db->insert('map_coordinates', $map_coordinates)){
            
            $query_map_coord = $this->db->query("SELECT map_coordinates_id FROM map_coordinates WHERE map_lat_ne='".$map_coordinates['map_lat_ne']."' and map_lng_ne='".$map_coordinates['map_lng_ne']."' and map_lat_sw='".$map_coordinates['map_lat_sw']."' and map_lng_sw='".$map_coordinates['map_lng_sw']."' and map_center_lat='".$map_coordinates['map_center_lat']."' and map_center_lng='".$map_coordinates['map_center_lng']."'and map_zoom='".$map_coordinates['map_zoom']."'");
            if($query_map_coord->num_rows() > 0){
                $temp=$query_map_coord->row_array();
                $data['map_coordinates_id'] = $temp["map_coordinates_id"];
            }
        }

        if($this->db->insert('feedback', $data))
        {
            $query_feedback = $this->db->query("SELECT feedback_id FROM feedback WHERE user_id='".$data['user_id'] . "' and time='" . $data['time']."' and message='" . $data['message']. "' and filter_id='" . $data['filter_id']."' and map_coordinates_id='" . $data['map_coordinates_id']."'");

            if ($query_feedback->num_rows() > 0)
            {
                $temp = $query_feedback->row_array();
                $feedback_id = $temp['feedback_id'];

                foreach ($fossil_selection as $fossil) {
                    $data = array(
                          'feedback_id' => $feedback_id,
                          'data_table' => "project_1_data",
                          'data_id' => $fossil
                    );
                    $this->db->insert("feedback_fossil", $data);
                }
            }
        }
    }

    function deleteFeedback($data){
        $query_feedback = $this->db->query("SELECT user_id, filter_id, map_coordinates_id FROM feedback WHERE feedback_id=".$data['feedback_id']);
        if ($query_feedback->num_rows()>0){
            $temp = $query_feedback->row_array();

            if(($temp['user_id']==$data['user_id']) || ($data['admin']==1))
            {
                $query_delete = $this->db->delete('map_coordinates', array('map_coordinates_id'=>$data["map_coordinates_id"]));
                $query_delete = $this->db->delete('feedback', array('feedback_id'=>$data['feedback_id']));
            }
        }
    }

    function loadGenuses(){

        $query = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');

        $return = array();

        if($query->num_rows() > 0) {
            foreach($query->result_array() as $row)
            {
                //we retrieve the data from each fossil from each project
                $query2=$this->db->query('SELECT distinct genus, COUNT(*) as nb FROM '.$row['data_table'].' GROUP BY genus ORDER BY nb DESC LIMIT 15');

                foreach($query2->result_array() as $row){
                    $return[] = array(
                        'genus' => $row['genus'],
                        'count' => $row['nb']
                    );
                }
                
            }

            //return the data
            return $return;
        }
    }

    function loadCollector(){
        $query = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');

        $return = array();

        if($query->num_rows() >0) {
            foreach($query->result_array() as $row)
            {
                //we retrieve the data from each fossil from each project
                $query2=$this->db->query('SELECT distinct  collector FROM '.$row['data_table'].' order by collector asc');

                foreach($query2->result_array() as $row){
                    $return[] = $row['collector'];
                }
                
            }

            //return the data
            return $return;
        }
    }

    function loadProject() {
        $query = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');

        $return = array();

        if($query->num_rows() >0) {
            foreach($query->result_array() as $row)
            {
                $return[] = $row['name'];
            }

            //return the data
            return $return;
        }
    }

    /*function submitFeedback($data, $filter){
    	//the data contains the time, message and user id information.
    	//we need to find the filter associated with it. 
    	//if such a filter does not exist we need to create it



    }*/


    public function updatelocationestimate()
    {
        $res = 0;

        $query_projects = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');

        if ($query_projects->num_rows() > 0)
        {
            foreach($query_projects->result_array() as $row)
            {
                $query_fossils = $this->db->query('SELECT * FROM '.$row['data_table'].' WHERE ((country!="Missing" and place!="") or (country!="") or (place!="")) and lat IS NULL and lng IS NULL');

                $res += $query_fossils->num_rows();
            }
        }
        else 
        {
            $res = 0;
        }

        return $res;
    }

    function changeLocation($coord)
    {
        return array('lat'=>$coord['lat']-0.01+rand(0,10)*0.002, 'lng'=>$coord['lng']-0.02+rand(0,20)*0.002);

    }

    function updatelocation()
    {
        $query = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');
        
        $return = array();
        
        if($query->num_rows() > 0) 
        {
            foreach($query->result_array() as $row)
            {
                $table = $row['data_table'];
                //we retrieve the data from each fossil from each project
                $query2 = $this->db->query('SELECT * FROM ' . $table.' WHERE ((country!="Missing" and place!="") or (country!="") or (place!="")) and lat IS NULL and lng IS NULL');

                //return $query2->result_array(); 
    
                //if($query2->num_rows>0){
                //$return[] = $query2->result_array();
                //}
                $i = 0;
                foreach ($query2->result_array() as $row)
                {
                    $i++;
                    if ($i > 950){
                        return "limit reached";
                    }

                    if ($row["country"] == "Missing") {$row["country"] = "";}
                    $temp = $this->geocode($row['country'].' '.$row['place']);

                    
                    if ($temp != false) 
                    {
                        $coord = array(
                            'lat' => $temp[0],
                            'lng' => $temp[1]
                        );
                    
                        $query_already_exist = $this->db->query('SELECT data_id, lat, lng  FROM ' . $table.' WHERE lat='.$coord['lat'].' AND lng='.$coord['lng']);

                        if ($query_already_exist->num_rows() > 0) {
                            /*$test = $this->changeLocation($coord);
                            $query_already_exist = $this->db->query('SELECT data_id, lat, lng  FROM ' . $table.' WHERE lat='.$test['lat'].' AND lng='.$test['lng']);*/
                           // $coord = array('lat'=>$coord['lat']+0.01, 'lng'=>$coord['lng']+0.01); 
                            $coord = $this->changeLocation($coord);
                        }
                        /*
                        for ($i=0; $i < 100 ; $i++) { 
                            $test = $this->changeLocation($coord);
                            $query_already_exist = $this->db->query('SELECT data_id, lat, lng  FROM ' . $table.' WHERE lat='.$test['lat'].' AND lng='.$test['lng']);
                        }

                        for ($query_already_exist->num_rows() != 0) {
                            $test = $this->changeLocation($coord);
                            
                            
                        }*/

                       /* if ($query_already_exist->num_rows() == 0)
                        {*/
                            $this->db->where('data_id',$row['data_id']);

                            $this->db->update($table, $coord);
                       // }

                    }
                    else
                    {
                        $this->db->where('data_id',$row['data_id']);

                        $coord = array(
                            'lat' => 0,
                            'lng' => 0
                            );

                        $this->db->update($table, $coord);
                    }

                }       
            }


        }
    }

    /*
    public function decluster(){

        $query_projects = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');

        if($query_projects->num_rows()>0){
            foreach ($query_projects->result_array() as $project) 
            {
                $query_fossil_ref = $this->db->query("SELECT lat, lng FROM ". $project['data_table']);
                foreach ($query_fossil_ref as $fossil) 
                {
                    $query_fossil = $this->db->query("SELECT data_id, image_id, genus, species, age, country, place, collector FROM ". $project["data_table"]." WHERE lat = '".$fossil["lat"]. "'' AND lng = '". $fossil['lng']."'");

                    if ($query_fossil->num_rows() > 1){
                        $i = 0;
                        foreach ($query_fossil as $temp) 
                        {
                            
                        }
                    }
                }
            }
        }
    }
    */

    public function where_clause()
    {
        return "unique_id!='12f3bdd3b95558e788f1a602a1412e3d07e5f74a' and unique_id!='1618315f0f87047126d4d684950537ef2ce69bd5' and unique_id!='25a0288f2636eefb53dc1b4ad28b7da44f91ca90' and unique_id!='5504539e6c4db715a72a5a6b8875be5e5f443390' and unique_id!='898850774d78fdf45cacf3239c132a76a7bcd572' and unique_id!='db57dc7ed8fac52c3688c3f74f96be93386408f1' and unique_id!='4977e5ac01ae154eb77ff732d622848696f7ff72' and unique_id!='c083abdd99a03add5752e91738d0c5c5c6ed5311' and unique_id!='8a4861977edbcea94cf95bb17efdea2aaaee036c' and unique_id!='62de74b7572008d00211723826b123759d0333ad'";
    }


    public function adminStats(){

        $res = array();

        $query_unique_visits = $this->db->query("select distinct unique_id from map_activity where ".$this->where_clause());
        $res['uniqueVisits'] = $query_unique_visits->num_rows();

        $query_actions = $this->db->query("select * from map_activity where ".$this->where_clause());
        $res['nbActions'] = $query_actions->num_rows();

        $res['visits'] = array();

        foreach ($query_unique_visits->result_array() as $unique)
        {
            $res['visits'][] = $unique;
            
        }
        return $res;
    }


    function secondsToTime($inputSeconds) {

        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // extract days
        $days = floor($inputSeconds / $secondsInADay);

        // extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        // return the final array
        $obj = array(
            'd' => (int) $days,
            'h' => (int) $hours,
            'm' => (int) $minutes,
            's' => (int) $seconds,
        );
        return $obj;
    }


    public function nb_visit($unique_id)
    {
        $query_open_close = $this->db->query("SELECT activity_id, action FROM map_activity where action='Open Page' and unique_id='".$unique_id."'");
        if ($query_open_close->num_rows() > 0)
        {
            return $query_open_close->num_rows();
        }
        else 
        {
            return -1;
        }
    }

    public function session_details($unique_id, $activity_id_inf, $activity_id_sup, $inf_equal)
    {
        if ($inf_equal)
        {
            $query_actions = $this->db->query("SELECT * FROM map_activity where unique_id='".$unique_id."' and activity_id >= '".$activity_id_inf."' and activity_id <= '".$activity_id_sup."' order by activity_id asc");
        }
        else
        {
            $query_actions = $this->db->query("SELECT * FROM map_activity where unique_id='".$unique_id."' and activity_id >= '".$activity_id_inf."' and activity_id < '".$activity_id_sup."' order by activity_id asc");
        }

        $res = array();
        $res["actions"] = $query_actions->result_array();
        $res["nb_action"] = $query_actions->num_rows();
        
        $temp = date_parse_from_format('Y-m-d H:i:s', $res["actions"][0]["time"]);
        $s = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);

        
        $temp = date_parse_from_format('Y-m-d H:i:s', $res["actions"][count($res["actions"])-1]["time"]);
        $e = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);

        
        $interval =  $e-$s;

        $res["visit_time"] = $this->secondsToTime($interval);
        $res["dwell"] = $interval;

        return $res;
    }


    public function visit_details($unique_id)
    {
        $nb_visit = $this->nb_visit($unique_id);

        $return = array();

        if ($nb_visit > 0)
        {
            $query_open = $this->db->query("SELECT activity_id, unique_id, action FROM map_activity where action='Open Page' and unique_id='".$unique_id."' order by activity_id asc");
            $query_close = $this->db->query("SELECT activity_id, unique_id, action FROM map_activity where action='Close Page' and unique_id='".$unique_id."' order by activity_id asc");
            $query_last_id = $this->db->query("SELECT activity_id, unique_id, action FROM map_activity WHERE unique_id='".$unique_id."' order by activity_id desc limit 1");

            $nb_open = $query_open->num_rows();
            $nb_close = $query_close->num_rows();

            $open = $query_open->result_array();
            $close = $query_close->result_array();

            $last_id = $query_last_id->result_array();
            $last_id = $last_id[0]["activity_id"];

            if (($nb_open - $nb_close) == 0)
            {
                for ($i=0; $i < $nb_open; $i++) 
                { 
                    $return[] = $this->session_details($unique_id, $open[$i]["activity_id"], $close[$i]["activity_id"], true);
                }
            }
            else
            {
                if ($nb_close == 0)
                {
                    for ($i=0; $i < $nb_open-1; $i++) 
                    { 
                        $return[] = $this->session_details($unique_id, $open[$i]["activity_id"], $open[$i+1]["activity_id"], false);
                    }
                    $return[] = $this->session_details($unique_id, $open[$nb_open-1]["activity_id"], $last_id, true);
                }
                else 
                {
                    /*
                    $ids = array();
                    for ($i=0; $i < $nb_open; $i++) { 
                         
                    } 
                    */
                    if ($nb_open > $nb_close)
                    {
                        $ids = array();
                        $i = 0;
                        $j = 0;

                        while ($i < $nb_open) 
                        {

                            if ($j = $nb_close){
                                $ids[] = $open[$i]["activity_id"];
                                $i++;
                            }
                            else
                            {
                                if ($open[$i]["activity_id"] < $close[$j]["activity_id"]){
                                    $ids[] = $open[$i]["activity_id"];
                                    $i++;
                                }
                                else
                                {
                                    $ids[] = $close[$j]["activity_id"];
                                    $j++;
                                }
                            }

                        }

                        if ($last_id > $open[$nb_open-1]["activity_id"] and $last_id > $close[$nb_close-1]["activity_id"])
                        {
                            $ids[] = $last_id;
                        }

                        for ($i=0; $i < count($ids)-2; $i++) { 
                             $return[] = $this->session_details($unique_id, $ids[$i], $ids[$i+1], false);
                         } 

                         $return[] = $this->session_details($unique_id, $ids[count($ids)-2], $ids[count($ids)-1], true);

                    }

                }
            }

        }
        else
        {

            $query_bounds = $this->db->query("SELECT activity_id FROM map_activity where unique_id='".$unique_id."' order by activity_id asc");

            $bounds = $query_bounds->result_array();

            $return[] = $this->session_details($unique_id, $bounds[0]["activity_id"], $bounds[count($bounds)-1]["activity_id"], true);

            /*
            $query_actions = $this->db->query("SELECT * FROM map_activity where unique_id='".$unique_id."' order by activity_id asc");
            $res = array();
            $res["actions"] = $query_actions->result_array();
            $res["nb_action"] = $query_actions->num_rows();
            
            $temp = date_parse_from_format('Y-m-d H:i:s', $res["actions"][0]["time"]);
            $s = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);
            $start = new DateTime();
            $start->setTimestamp($s);
            
            $temp = date_parse_from_format('Y-m-d H:i:s', $res["actions"][count($res["actions"])-1]["time"]);
            $e = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);
            $end = new DateTime();
            $end->setTimestamp($e);
            
            $interval =  $end->diff($start);
            $res["visit_time"] = $interval->format("%d days %H hours %i minutes %s seconds");
            $return[] = $res;
            */
        }

        return $return;
    }


    public function visiteDetails($unique_id)
    {
        $res = array();
        $query_visit_details = $this->db->query("SELECT * FROM map_activity where unique_id='".$unique_id."' order by activity_id asc");

        $nb_visits = 0;
        if ($query_visit_details->num_rows() > 0)
        {
            $nb_visits = $this->nb_visit($unique_id);
            for ($i=0; $i < $nb_visits; $i++) 
            { 
                
            }
            //$res = $query_visit_details->result_array();
        }
        $res["unique_id"] = $unique_id;
        $res["nb_tot_action"] = $query_visit_details->num_rows();

        if ($nb_visits == -1) $nb_visits = 1;

        $res["nb_visits"] = $nb_visits;
        $res["visits"] = $this->visit_details($unique_id);

        $temp_tot_time = 0;
        for ($i=0; $i < count($res["visits"]); $i++) { 
            $temp_tot_time += $res["visits"][$i]["dwell"];
        }

        $res["tot_dwell"] = $temp_tot_time;
        $res["tot_time"] = $this->secondsToTime($temp_tot_time);

/*
        $temp = date_parse_from_format('Y-m-d H:i:s', $res[0]["time"]);
        $s = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);
        $start = new DateTime();
        $start->setTimestamp($s);
        
        $temp = date_parse_from_format('Y-m-d H:i:s', $res[count($res)-1]["time"]);
        $e = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);
        $end = new DateTime();
        $end->setTimestamp($e);

        $interval =  $end->diff($start);
        $res[] = $interval->format("%d days %H hours %i minutes %s seconds");

        
*/

/*

        $end = strtotime($res[count($res)-1]["time"]);
        $interval =  $end->diff($start);
        $res["time"] = $interval->format("%H hours %i minutes %s seconds");
*/
        return $res;
    }

    public function calculate_median($arr) {
        $count = count($arr); //total numbers in array
        $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if($count % 2) { // odd number, middle is the median
            $median = $arr[$middleval];
        } else { // even number, calculate avg of 2 medians
            $low = $arr[$middleval];
            $high = $arr[$middleval+1];
            $median = (($low+$high)/2);
        }
        return $median;
    }

    public function calculate_average($arr) {
        $count = count($arr); //total numbers in array
        $total = 0;
        foreach ($arr as $value) {
            $total = $total + $value; // total value of array numbers
        }
        $average = ($total/$count); // get average value
        return $average;
    }

    function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return sqrt($carry / $n);
    }




    public function generalStats()
    {
        $query_total = $this->db->query("select * from map_activity where ".$this->where_clause()." and action!='Open Page' and action!='Close Page' and action!='Open Help' and action!='Close Help'");

        $total = $query_total->num_rows();

        /* percentages */
        $query_map_pan = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Map Pan'");
        $nb_map_pan = $query_map_pan->num_rows();
        $p_map_pan = floatval($nb_map_pan)/floatval($total) * 100;

        $query_map_click = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Map Click'");
        $nb_map_click = $query_map_click->num_rows();
        $p_map_click = floatval($nb_map_click)/floatval($total) * 100;

        $query_map_zoom_in = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Map Zoom in'");
        $nb_map_zoom_in = $query_map_zoom_in->num_rows();
        $p_map_zoom_in = floatval($nb_map_zoom_in)/floatval($total) * 100;

        $query_map_zoom_out = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Map Zoom out'");
        $nb_map_zoom_out = $query_map_zoom_out->num_rows();
        $p_map_zoom_out = floatval($nb_map_zoom_out)/floatval($total) * 100;

        $query_click_on_fossil = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Click on Fossil'");
        $nb_click_on_fossil = $query_click_on_fossil->num_rows();
        $p_click_on_fossil = floatval($nb_click_on_fossil)/floatval($total) * 100;

        $query_enlarge_image = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Enlarge Image'");
        $nb_enlarge_image = $query_enlarge_image->num_rows();
        $p_enlarge_image = floatval($nb_enlarge_image)/floatval($total) * 100;

        $query_fossil_selected = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Fossil selected'");
        $nb_fossil_selected = $query_fossil_selected->num_rows();
        $p_fossil_selected = floatval($nb_fossil_selected)/floatval($total) * 100;

        $query_fossil_deselected = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Fossil deselected'");
        $nb_fossil_deselected = $query_fossil_deselected->num_rows();
        $p_fossil_deselected = floatval($nb_fossil_deselected)/floatval($total) * 100;

        $query_clear_fossil_selection = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Clear Fossil selection'");
        $nb_clear_fossil_selection = $query_clear_fossil_selection->num_rows();
        $p_clear_fossil_selection = floatval($nb_clear_fossil_selection)/floatval($total) * 100;

        $filter_geological_change = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Filter Geological Age changed'");
        $nb_filter_geological_change = $filter_geological_change->num_rows();
        $p_filter_geological_change = floatval($nb_filter_geological_change)/floatval($total) * 100;

        $filter_collector_change = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Filter Collector Selector change'");
        $nb_filter_collector_change = $filter_collector_change->num_rows();
        $p_filter_collector_change = floatval($nb_filter_collector_change)/floatval($total) * 100;
        
        $filter_collector_hover = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Collector Selector Hover'");
        $nb_filter_collector_hover = $filter_collector_hover->num_rows();
        $p_filter_collector_hover = floatval($nb_filter_collector_hover)/floatval($total) * 100;

        $filter_genus_change = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Filter Genus Selector change'");
        $nb_filter_genus_change = $filter_genus_change->num_rows();
        $p_filter_genus_change = floatval($nb_filter_genus_change)/floatval($total) * 100;

        $filter_genus_hover = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Genus Selector Hover'");
        $nb_filter_genus_hover = $filter_genus_hover->num_rows();
        $p_filter_genus_hover = floatval($nb_filter_genus_hover)/floatval($total) * 100;

        $feedback_hover = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Feedback mouse over'");
        $nb_feedback_hover = $feedback_hover->num_rows();
        $p_feedback_hover = floatval($nb_feedback_hover)/floatval($total) * 100;

        $feedback_click = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Click on feedback'");
        $nb_feedback_click = $feedback_click->num_rows();
        $p_feedback_click = floatval($nb_feedback_click)/floatval($total) * 100;

        $upvote = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Upvote'");
        $nb_upvote = $upvote->num_rows();
        $p_upvote = floatval($nb_upvote)/floatval($total) * 100;

        $click_reply = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Click reply'");
        $nb_click_reply = $click_reply->num_rows();
        $p_click_reply = floatval($nb_click_reply)/floatval($total) * 100;

        $write_comment = $this->db->query("select * from map_activity where ".$this->where_clause()."and map_activity.action='Writing comment'");
        $nb_write_comment = $write_comment->num_rows();
        $p_write_comment = floatval($nb_write_comment)/floatval($total) * 100;

        $submit_feedback = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Submit feedback'");
        $nb_submit_feedback = $submit_feedback->num_rows();
        $p_submit_feedback = floatval($nb_submit_feedback)/floatval($total) * 100;

        $sharing = $this->db->query("select * from map_activity where ".$this->where_clause()." and map_activity.action='Sharing'");
        $nb_sharing = $sharing->num_rows();
        $p_sharing = floatval($nb_sharing)/floatval($total) * 100;


        /* time */

        $query_unique_id = $this->db->query("select distinct unique_id from map_activity where ".$this->where_clause());
        $nb_visitors = $query_unique_id->num_rows();

        $hist = array();
        $hist[] = array("Visitor", "Number of Actions");

        
        $nb_action_fct_dwell = array();
        $nb_action_fct_dwell[] = array("Nb Action per Visitor", "Dwell");


        $action_per_visitor = array();
        $visits_per_visitor = array();
        $dwell_per_visitor = array();
        $action_per_visit = array();
        $dwell_per_visit = array();

        $tot_action_per_visitor = 0;
        $tot_visits_per_visitor = 0;
        $tot_dwell_per_visitor = 0;


        foreach ($query_unique_id->result_array() as $unique) 
        {

            $visitor_details = $this->visiteDetails($unique["unique_id"]);

            $action_per_visitor[] = $visitor_details["nb_tot_action"];
            $tot_action_per_visitor += $visitor_details["nb_tot_action"];

            $visits_per_visitor[] = $visitor_details["nb_visits"];
            $tot_visits_per_visitor += $visitor_details["nb_visits"];

            $dwell_per_visitor[] = $visitor_details["tot_dwell"];
            $tot_dwell_per_visitor += $visitor_details["tot_dwell"];

            foreach ($visitor_details["visits"] as $visit) 
            {
                $action_per_visit[] = $visit["nb_action"];
                $dwell_per_visit[] = $visit["dwell"];
            }


            /*
            $query_visit_start = $this->db->query("SELECT activity_id, time from map_activity where unique_id='".$unique["unique_id"]."' order by activity_id asc limit 1");
            $s = $query_visit_start->row_array();
            $query_visit_end = $this->db->query("SELECT activity_id, time from map_activity where unique_id='".$unique["unique_id"]."' order by activity_id desc limit 1");
            $e = $query_visit_end->row_array();

            $temp = date_parse_from_format('Y-m-d H:i:s', $s["time"]);
            $s = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);
            
            $temp = date_parse_from_format('Y-m-d H:i:s', $e["time"]);
            $e = mktime($temp["hour"], $temp["minute"], $temp["second"], $temp["month"], $temp["day"], $temp["year"]);

            $interval = $e-$s;

            $visitor_dwell[] = $interval;
            

            $query_nb_actions = $this->db->query("SELECT action from map_activity where unique_id='".$unique["unique_id"]."'");

            */
        
            $hist[] = array($unique["unique_id"], $visitor_details["nb_tot_action"]);

            $nb_action_fct_dwell[] = array($visitor_details["nb_tot_action"], $visitor_details["tot_dwell"]);
            
        }
        //$avg_time = date_parse_from_format("s", intval($avg_time));
        //$avg_time = mktime($temp_time["hour"], $temp_time["minute"], $temp_time["second"], $temp_time["month"], $temp_time["day"], $temp_time["year"]);


        sort($action_per_visitor);
        sort($visits_per_visitor);
        sort($dwell_per_visitor);

        sort($action_per_visit);
        sort($dwell_per_visit);

        $stat_action_per_visitor = array(
            "min" => $action_per_visitor[0],
            "max" => $action_per_visitor[count($action_per_visitor)-1],
            "total" => $tot_action_per_visitor,
            "avg" => round($this->calculate_average($action_per_visitor),2),
            "med" => round($this->calculate_median($action_per_visitor), 2),
            "std" => round($this->stats_standard_deviation($action_per_visitor), 2)
        );

        $stat_visits_per_visitor = array(
            "min" => $visits_per_visitor[0],
            "max" => $visits_per_visitor[count($visits_per_visitor)-1],
            "total" => $tot_visits_per_visitor,
            "avg" => round($this->calculate_average($visits_per_visitor),2),
            "med" => round($this->calculate_median($visits_per_visitor), 2),
            "std" => round($this->stats_standard_deviation($visits_per_visitor), 2)
        );


        $stats_dwell_per_visitor = array(
            "min" => $this->secondsToTime($dwell_per_visitor[0]),
            "max" => $this->secondsToTime($dwell_per_visitor[count($dwell_per_visitor)-1]),
            "total" => $this->secondsToTime($tot_dwell_per_visitor),
            "avg" => $this->secondsToTime(intval($this->calculate_average($dwell_per_visitor))),
            "med" => $this->secondsToTime(intval($this->calculate_median($dwell_per_visitor))),
            "std" => $this->secondsToTime(intval($this->stats_standard_deviation($dwell_per_visitor)))
        );

        $stat_action_per_visit = array(
            "min" => $action_per_visit[0],
            "max" => $action_per_visit[count($action_per_visit)-1],
            "avg" => round($this->calculate_average($action_per_visit),2),
            "med" => round($this->calculate_median($action_per_visit), 2),
            "std" => round($this->stats_standard_deviation($action_per_visit), 2)
        );



        $stat_dwell_per_visit = array(
            "min" => $this->secondsToTime($dwell_per_visit[0]),
            "max" => $this->secondsToTime($dwell_per_visit[count($dwell_per_visit)-1]),
            "avg" => $this->secondsToTime(intval($this->calculate_average($dwell_per_visit))),
            "med" => $this->secondsToTime(intval($this->calculate_median($dwell_per_visit))),
            "std" => $this->secondsToTime(intval($this->stats_standard_deviation($dwell_per_visit)))
        );



/*

        $avg_visitor_dwell = $this->calculate_average($visit_dwell);
        sort($visitor_dwell);
        $med_visitor_dwell = $this->calculate_median($visit_dwell);
        $std_dev_visitor_dwell = $this->stats_standard_deviation($visit_dwell);

        $avg_visitor_dwell = $this->secondsToTime(intval($avg_visit_dwell));
        $med_visitor_dwell = $this->secondsToTime(intval($med_visit_dwell));
        $min_visitor_dwell = $this->secondsToTime($visit_dwell[0]);
        $max_visitor_dwell = $this->secondsToTime($visit_dwell[count($visit_dwell)-1]);
        $std_dev_visitor_dwell = $this->secondsToTime($std_dev_visit_dwell);
        
    
        $avg_action_per_visit = $this->calculate_average($avg_med);
        sort($avg_med);
        $med_action_per_visit = $this->calculate_median($avg_med);
        $std_dev_action_per_visit = $this->stats_standard_deviation($avg_med);

        */


        $hist_class = array_fill(0,($action_per_visitor[count($action_per_visitor)-1]/10)+1,0);
        for ($i=0; $i < count($action_per_visitor); $i++) 
        { 
            $hist_class[$action_per_visitor[$i]/10] ++;
        }

        $query_latest_activity = $this->db->query("SELECT * FROM map_activity where  ".$this->where_clause()." order by activity_id desc LIMIT 10");
        $latest_activity = $query_latest_activity->result_array();

        return array(
            "total"=>$total, 
            "map_pan"=>round($p_map_pan,2), 
            "nb_map_pan"=>$nb_map_pan,
            "map_click"=>round($p_map_click,2),
            "nb_map_click"=>round($nb_map_click,2),
            "map_zoom_in"=>round($p_map_zoom_in,2),
            "nb_map_zoom_in"=>round($nb_map_zoom_in,2),
            "map_zoom_out"=>round($p_map_zoom_out,2),
            "nb_map_zoom_out"=>round($nb_map_zoom_out,2),
            "click_on_fossil"=>round($p_click_on_fossil,2),
            "nb_click_on_fossil"=>round($nb_click_on_fossil,2),
            "enlarge_image"=>round($p_enlarge_image,2),
            "nb_enlarge_image"=>round($nb_enlarge_image,2),
            "fossil_selected"=>round($p_fossil_selected,2),
            "nb_fossil_selected"=>round($nb_fossil_selected,2),
            "fossil_deselected"=>round($p_fossil_deselected,2),
            "nb_fossil_deselected"=>round($nb_fossil_deselected,2),
            "clear_fossil_selection"=>round($p_clear_fossil_selection,2),
            "nb_clear_fossil_selection"=>round($nb_clear_fossil_selection,2),
            "filter_geological_change"=>round($p_filter_geological_change,2),
            "nb_filter_geological_change"=>round($nb_filter_geological_change,2),
            "filter_collector_change"=>round($p_filter_collector_change,2),
            "nb_filter_collector_change"=>round($nb_filter_collector_change,2),
            "filter_collector_hover"=>round($p_filter_collector_hover,2),
            "nb_filter_collector_hover"=>round($nb_filter_collector_hover,2),
            "filter_genus_change"=>round($p_filter_genus_change,2),
            "nb_filter_genus_change"=>round($nb_filter_genus_change,2),
            "filter_genus_hover"=>round($p_filter_genus_hover,2),
            "nb_filter_genus_hover"=>round($nb_filter_genus_hover,2),
            "feedback_hover"=>round($p_feedback_hover,2),
            "nb_feedback_hover"=>round($nb_feedback_hover,2),
            "feedback_click"=>round($p_feedback_click,2),
            "nb_feedback_click"=>round($nb_feedback_click,2),            
            "upvote"=>round($p_upvote,2),
            "nb_upvote"=>round($nb_upvote,2),
            "click_reply"=>round($p_click_reply,2),
            "nb_click_reply"=>round($nb_click_reply,2),
            "write_comment"=>round($p_write_comment,2),
            "nb_write_comment"=>round($nb_write_comment,2),
            "submit_feedback"=>round($p_submit_feedback,2),
            "nb_submit_feedback"=>round($nb_submit_feedback,2),
            "sharing"=>round($p_sharing,2),
            "nb_sharing"=>round($nb_sharing,2),



            "action_distribution"=>array(
                array("Actions", "Distribution"),
                array("Map Pan", round($nb_map_pan,2)), 
                array("Map Zoom in", round($nb_map_zoom_in,2)),
                array("Map Zoom out", round($nb_map_zoom_out,2)),
                array("Map Click", round($nb_map_click,2)),
                array("Click on Fossil", round($nb_click_on_fossil,2)),
                array("Enlarge Image", round($nb_enlarge_image,2)),
                array("Fossil Selected", round($nb_fossil_selected,2)),
                array("Fossil Deselected", round($nb_fossil_deselected,2)),
                array("Clear Fossil Selection", round($nb_clear_fossil_selection,2)),
                array("Filter Geological Age Change", round($nb_filter_geological_change,2)),
                array("Filter Collector Hover", round($nb_filter_collector_hover,2)),
                array("Filter Collector Change", round($nb_filter_collector_change, 2)),
                array("Filter Genus Hover", round($nb_filter_genus_hover, 2)),
                array("Filter Genus Change", round($nb_filter_genus_change, 2)),
                array("Feedback Hover", round($nb_feedback_hover,2)),
                array("Feedback Click", round($nb_feedback_click, 2)),
                array("Upvote", round($nb_upvote, 2)),
                array("Click Reply", round($nb_click_reply,2)),
                array("Write Comment", round($nb_write_comment, 2)),
                array("Submit Comment", round($nb_submit_feedback, 2)),
                array("Sharing", round($nb_sharing, 2))
            ),
            

            "hist_actions"=>$hist, 
            "data_hist_actions"=>$hist_class,
           
            "nb_action_fct_dwell"=>$nb_action_fct_dwell,

            "latest_activity"=>$latest_activity, 


            "stat_action_per_visitor" => $stat_action_per_visitor,
            "stat_visits_per_visitor" => $stat_visits_per_visitor,
            "stats_dwell_per_visitor" => $stats_dwell_per_visitor,
            "stat_action_per_visit" => $stat_action_per_visit,
            "stat_dwell_per_visit" => $stat_dwell_per_visit

        );



    }














}