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
            $temp[] = 'Carboniferous, Upper (Coal Measeures)';
            $temp[] = 'Carboniferous, Lower (Limestone)';
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
    			$query2 = $this->db->query('SELECT data_id, image_id, genus, species, age, country, place, collector, lat, lng FROM ' . $row["data_table"].' WHERE '.$where_string.' and lat!="" and lng!=""');

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

        if($query->num_rows() >0) {
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
                $query_fossils = $this->db->query('SELECT * FROM '.$row['data_table'].' WHERE ((country!="Missing" and place!="") or (country!="" and place!="")) and (lat=0  or lat IS NULL) and (lng=0 or lng IS NULL)');

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
        return array('lat'=>$coord['lat'], 'lng'=>$coord['lng']);
    }

    function updatelocation()
    {
        $query = $this->db->query('SELECT id, name, image, blurb, data_table, image_table FROM projects_master');
        
        $return = array();
        
        if($query->num_rows() > 0) {
            foreach($query->result_array() as $row)
            {
                //we retrieve the data from each fossil from each project
                $query2 = $this->db->query('SELECT * FROM ' . $row['data_table'].' WHERE ((country!="Missing" and place!="") or (country!="" and place!="")) and (lat=0  or lat IS NULL) and (lng=0 or lng IS NULL)');

                //return $query2->result_array(); 
    
                //if($query2->num_rows>0){
                //$return[] = $query2->result_array();
                //}
                foreach ($query2->result_array() as $row)
                {
                    $temp = $this->geocode($row['country'].' '.$row['place']);
                    $coord = array(
                        'lat' => $temp[0],
                        'lng' => $temp[1]
                    );
                    
                    if ($temp != false) 
                    {

                        $query_already_exist = $this->db->query('SELECT data_id, lat, lng  FROM ' . $row["data_table"].' WHERE lat='.$coord['lat'].' AND lng='.$coord['lng']);

                        while ($query_already_exist->num_rows() != 0) {
                            $query_already_exist = $this->db->query('SELECT data_id, lat, lng  FROM ' . $row["data_table"].' WHERE lat='.$coord['lat'].' AND lng='.$coord['lng']);
                        }

                        if ($query_already_exist->num_rows() == 0)
                        {
                            $this->db->where('data_id',$row['data_id']);

                            $this->db->update($row['data_table'], $coord);
                        }
                                       
                    }
                    else
                    {
                        $this->db->where('data_id',$row['data_id']);

                        $coord = array(
                            'lat' => -42,
                            'lng' => -42
                            );

                        $this->db->update($table, $coord);
                    }

                    //return $row;
                    $return[] = $row; 
                }       
            }
            //return the data
            return $return;
        }
    }

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


}