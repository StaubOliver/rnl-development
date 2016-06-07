<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  *	Controller for the open data api
  * 
  */

class Data extends MY_Controller {
	function __construct() {
        parent::__construct();
        
        
    }

	public function index() {
		//echo 'Looking for the open data API? Try here <a href="/data/api/">/data/api/</a>';
		//exit;
		
		redirect('/data/api/');
	}
	
	public function api() {
		$type = $this->uri->segment(3);
		$project_id = ($this->uri->segment(4) == 1) ? $this->uri->segment(4) : FALSE;
		
		if(!$type || !$project_id) {
			// If no URI segment, show the options page
			$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
			$data['project_id_error'] = '';
			
			$this->load->view('data_api', $data);

		} else {
			if(isset($type) && $project_id != 1) {
				$data['analytics'] = $this->load->view('analytics', NULL, TRUE);
				$data['project_id_error'] = '<div id="project-id-error">Project ID not found.</div>';
				$this->load->view('data_api', $data);
				exit;
			}
		
			// Get the data
			$sql = '
				SELECT DISTINCT(d.image_id), i.filename, d.genus, d.species, d.age, d.country, d.place, d.collector, i.image_url
				FROM project_'.$project_id.'_images AS i
				INNER JOIN project_'.$project_id.'_data AS d
				ON i.image_id = d.image_id	
				ORDER BY d.image_id ASC
			';
			
			$query = $this->db->query($sql);
			
			// Output the type
			switch($type) {
				case "json":
					$this->outputJSON($query, $project_id);
					break;
					
				case "csv":
					$this->outputCSV($query, $project_id);
					break;
					
				case "xml":
					$this->outputXML($query, $project_id);
					break;
					
				default:
					echo 'Data format not found.';
					exit;
					
					break;
			}
		}
	}
	
	public function outputJSON($query, $project_id) {
		
		header("Content-Type:application/json"); 
		echo json_encode($query->result());
	}
	
	public function outputCSV($query, $project_id) {
	
		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv"); 
		header("Content-Disposition:attachment;filename=project".$project_id.".csv"); 
		
		fputcsv($output, array('Image ID', 'Filename', 'Genus', 'Species', 'Age', 'Country', 'Place', 'Collector', 'URL'));
		
		foreach($query->result() as $row) {
			fputcsv($output, (array)$row);
		}
		
		fclose($output) or die("Can't close php://output");
	}
	
	public function outputXML($query, $project_id) {
		header("Content-Type:text/xml"); 
		header("Content-Disposition:attachment;filename=project".$project_id.".xml"); 
	
		$xml = new XMLWriter();
		$xml->openURI('php://output');
		$xml->startDocument("1.0");
		$xml->setIndent(true);

		$xml->startElement('project_data');

		foreach($query->result() as $row) {
			$xml->startElement('image_data');

			  $xml->startElement("image_id");
			  	$xml->writeRaw($row->image_id);
			  $xml->endElement();
			  
			  $xml->startElement("filename");
			  	$xml->writeRaw($row->filename);
			  $xml->endElement();
			  
			  $xml->startElement("genus");
			  	$xml->writeRaw($row->genus);
			  $xml->endElement();
			  
			  $xml->startElement("species");
			  	$xml->writeRaw($row->species);
			  $xml->endElement();
			  
			  $xml->startElement("age");
			  	$xml->writeRaw($row->age);
			  $xml->endElement();
			  
			  $xml->startElement("country");
			  	$xml->writeRaw($row->country);
			  $xml->endElement();
			  
			  $xml->startElement("place");
			  	$xml->writeRaw($row->place);
			  $xml->endElement();
			  
			  $xml->startElement("collector");
			  	$xml->writeRaw($row->collector);
			  $xml->endElement();
			  
			  $xml->startElement("url");
			  	$xml->writeRaw($row->image_url);
			  $xml->endElement();
		
			$xml->endElement();
		}
		
		$xml->endElement();
		
		$xml->openURI('php://output');
	}
}
