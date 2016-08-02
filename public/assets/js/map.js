
var getPin = function(color){
	return "http://www.googlemapsmarkers.com/v1/"+color+"/"
}

//var pin_standard = getPin("51ccca")
var pin_standard = getPin("909090");
var pin_highlight = getPin("FFFFFF");
var pin_selected = getPin("ff4d79");

var pin_quaternary = getPin("F9F97F");
var pin_neogene = getPin("FFE619");
var pin_paleogene = getPin("FD9A52");
var pin_cretaceous = getPin("7FC64E");
var pin_jurassic = getPin("34B2C9");
var pin_triassic = getPin("C72B92");
var pin_permian = getPin("F04028");
var pin_carboniferous = getPin("67A599");
var pin_devonian = getPin("CB8C37");
var pin_silurian = getPin("B3E1B6");
var pin_ordovician = getPin("009270");
var pin_cambrian = getPin("7F1056");
var pin_precambrian = getPin("934370");


var getPinColor = function(age){
	//quaternary
	if (age=="Quaternary") { return pin_quaternary; }

	//neogene
	if (age=="Neogene") { return pin_neogene; }
	if (age=="Pliocene") { return pin_neogene; }
	if (age=="Miocene") { return pin_neogene; }

	//paleogene
	if (age=="Paleogene") { return pin_paleogene; }
	if (age=="Oligocene") { return pin_paleogene; }
	if (age=="Eocene") { return pin_paleogene; }
	if (age=="Paleocene") { return pin_paleogene; }

	//cretaceous
	if (age=="Cretaceous") { return pin_cretaceous; }
	if (age=="Cretaceous, Upper") { return pin_cretaceous; }
	if (age=="Cretaceous, Lower") { return pin_cretaceous; }

	//jurassic
	if (age=="Jurassic") { return pin_jurassic; }
	if (age=="Jurassic, Upper") { return pin_jurassic; }
	if (age=="Jurassic, Middle") { return pin_jurassic; }
	if (age=="Jurassic, Lower (Lias)") { return pin_jurassic; }

	//triassic
	if (age=="Triassic") { return pin_triassic; }
	if (age=="Triassic, Upper") { return pin_triassic; }
	if (age=="Triassic, Middle") { return pin_triassic; }
	if (age=="Triassic, lower") { return pin_triassic; }

	//permian
	if (age=="Permian") { return pin_permian; }

	//carboniferous
	if (age=="Carboniferous") { return pin_carboniferous; }
	if (age=="Carboniferous Upper (Coal Measeures") { return pin_carboniferous; }
	if (age=="Carboniferous Lower (Limestone)") { return pin_carboniferous; }

	//denovian
	if (age=="Devonian") { return pin_devonian; }
	if (age=="Devonian, Upper") { return pin_devonian; }
	if (age=="Devonian, Middle") { return pin_devonian; }
	if (age=="Devonian, Lower") { return pin_devonian; }

	//silurian
	if (age=="Silurian") { return pin_silurian; }
	if (age=="Silurian, Pridoli") { return pin_silurian; }
	if (age=="Silurian, Ludlow") { return pin_silurian; }
	if (age=="Silurian, Wenlock") { return pin_silurian; }
	if (age=="Silurian, Llandovery") { return pin_silurian; }

	//ordovician
	if (age=="Ordovician") { return pin_ordovician; }
	if (age=="Ordovician, Upper") { return pin_ordovician; }
	if (age=="Ordovician, Middle") { return pin_ordovician; }
	if (age=="Ordovician, Lower") { return pin_ordovician; }

	//cambrian
	if (age=="Cambrian") { return pin_cambrian; }

	//precambrian
	if (age=="Precambrain") { return pin_precambrian; }

	return pin_standard;
}




var map = angular.module('map', ['rzModule'])
.controller('GoogleMap', function($scope, $http, $compile){
	
	var actualmap;

	var markers = [];
	$scope.nbfossils = 0;

	$scope.firstVisit;

	var markers_age = [];
	$scope.selected_markers = [];

	$scope.feedbacks = [];

	var infoWindow;
	var user_id;
	$scope.user_id;
	$scope.logged_in;
	$scope.admin;

	var filter = [];
	filter['project'] = "-1";
	filter['genus'] = $scope.selectedGenus;
	filter['collector'] = $scope.selectedCollector;

	$scope.selectedProject = filter['project'];
	//$scope.selectedGenus = filter['genus'];
	//$scope.selectedAgeMin = 0;
	//$scope.selectedAgeMax = 12;
	//$scope.selectedCollector = filter['collector'];	

	$scope.show_legend = true;

	var marker_clicked_for_selection = {};

	$scope.section_filter_title = "Filter what's being plotted on the map";
	$scope.section_feedback_form_section_title = "Share your discoveries";
	$scope.section_social_sharing_title = "Share on your networks";
	$scope.section_feedbacks_title = "See what others have found";

	//$scope.replyto = {'reply':false};

	$scope.feedback_form_text_reply = [];
	$scope.show_feedback_reply = [];

	$scope.share_url = window.location.hostname;

	// Sets the map on all markers in the array.
	function setMapOnAll(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
			markers[i] = null;
		}
	}

	function clearMarkers() {
	 	setMapOnAll(null);
	}

	function deleteMarkers() {
		clearMarkers();
		markers = [];
	}


	$scope.highlight_marker = function(marker){
		marker.setIcon(pin_highlight);
	}

	$scope.remove_highlight = function(marker){
		marker.setIcon(pin_selected);
	}

	function deselect_all_marker(){
		for (var i=0; i < $scope.selected_markers.length; i++){
			index = markers.indexOf($scope.selected_markers[i])
			markers[index].setIcon(getPinColor(markers_age[index]));
			//$scope.selected_markers[i].setIcon(pin_standard);
		}
		$scope.selected_markers = [];
	}

	$scope.clear_selected_markers = function(){
		deselect_all_marker();
		$scope.text_select_btn = "Select this fossil"
	}

	$scope.remove_marker = function(marker, index){
		if (index == $scope.selected_markers.indexOf(marker_clicked_for_selection)){
			$scope.text_select_btn = "Select this fossil";
		}$
		deselect_marker(marker, index);
		logActivity($http, "Fossil deselected ", ""+marker['title'].split("-")[0]+" "+marker_clicked_for_selection['title'], user_id);
	}

	function select_marker(marker){
		$scope.selected_markers.push(marker);
		marker.setIcon(pin_selected);
	}

	function deselect_marker(marker, index){
		$scope.selected_markers.splice(index,1);
		marker.setIcon(getPinColor(markers_age[markers.indexOf(marker)]));
	}

	$scope.text_select_btn = "";

	function createMarkers(info, http){

		var marker = new google.maps.Marker({
			map: actualmap,
			position: new google.maps.LatLng(info['lat'], info['lng']),
			title: info['id'] + "-" +info['title'],
			/*icon: {
		        path: google.maps.SymbolPath.CIRCLE,
		        scale: 6,
		        fillColor: "#F00",
		        fillOpacity: 1,
		        strokeWeight: 0.4
		    }, */
		    //icon: pinSymbol('#fff'),
		    icon: getPinColor(info['age'])
		});
		
		marker.addListener("click", function(){

			//log activity
			logActivity(http, "Click on fossil",  ""+info['id']+" "+info['title'], user_id);

			marker_clicked_for_selection = marker;
			//info window
			infoWindow.close;
			var text_select_btn = "";
			var index = $scope.selected_markers.indexOf(marker_clicked_for_selection);
			if (index==-1){
				$scope.text_select_btn = "Select this fossil";
			} else {
				$scope.text_select_btn = "Deselect this fossil";
			}
			var content =
			"<div class='container-fluid map-infowindow'>"
				+ "<div class='row'>"

					+ "<div class='col-md-6'>"
						+ "<img data-toggle='modal' data-target='#ModalImage' src='"+info["url"]+"' class='map-infowindow-img' onclick='show_img(\""+info['url']+"\")'>"
						+ "</br> [+] Click to enlarge"
					+ "</div>"

					+ "<div class='col-md-6'>"

						+ "<div class='row'>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'><strong> Genus : </strong> " + info['title'] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Species : </strong> " + info["species"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Age : </strong>" + info['age'] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Collector : </strong>"+ info["collector"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Location : </strong>" + info["location"] + "</p>"
							+ "</div>"

							+ "<div class='col-xs-12'>"
								+ "<div class='btn btn-custom-primary btn-sm' ng-click='click_on_marker_for_selection("+info['id']+");'>{{text_select_btn}}</div>"
							+ "</div>"
						+"</div>"

					+ "</div>"
				+ "</div>"
			+ "</div>"
			;

			var compiled = $compile(content)($scope);

			infoWindow.setContent(compiled[0]);
			
			//modal for large image view
			document.getElementById('modal-image-title').innerHTML = "Fossil details";
			document.getElementById('modal-image-body').innerHTML =
			"<div class='row'>"
				+ "<div class='col-md-12'>"
					+ "<img class='img-responsive' src= "+info["url"]+"></img>"
				+ "</div>"
				+ "<div class='col-md-4'>"
					+ "<p><strong>Genus : </strong>" + info['title'] + "</p>"
				+ "</div>"
				+ "<div class='col-md-4'>"
					+ "<p><strong>Species : </strong>" + info['species'] + "</p>"
				+ "</div>"
				+ "<div class='col-md-4'>"
					+ "<p><strong>Age : </strong>" + info['age'] + "</p>"
				+ "</div>"
				+ "<div class='col-md-8'>"
					+ "<p><strong>Location : </strong>" + info['location'] + "</p>"
				+ "</div>"
				+ "<div class='col-md-4'>"
					+ "<p><strong>Collector : </strong>" + info['collector'] + "</p>"
				+ "</div>"
			+ "</div>";

			infoWindow.open('actualmap', marker);

		});
		markers.push(marker);
		markers_age.push(info['age']);	
	}
	
	function refresh(http)
	{
		deleteMarkers();
		markers_age = [];
		infoWindow = new google.maps.InfoWindow({maxWidth:400});

		console.log("host: "+window.location.hostname);
		//retrieve the fossils and put them as marker in the map
		http.get('/api/map/loadfossils/'+filter['genus']+'/-1/'+$scope.selectedAgeMin+'/'+$scope.selectedAgeMax+'/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
			data.forEach(function(item, index){
				var info = [];
				info['lat'] = item['lat'];
				info['lng'] = item['lng'];
				info['id'] = item['data_id'];
				info['location'] = item['place']+ ' ' + item['country'];
				info['url'] = item['url'];
				info['age'] = item['age'];
				info['species'] = item['species'];
				info['collector'] = item['collector'];
				info['id'] = item['data_id'];

				var info_window_genus = "";

				if (item['genus'] == 'Not listed')
				{
					info['title'] = item['genuscustom'] + " " + item['species'];
				}
				else{
					info['title'] = item['genus'] + " " + item['species'];
				}

				//create the markers to plot on the map
				createMarkers(info, http);	
			});
			//refresh the legend
			refreshLegend();
			$scope.nbfossils = markers.length;
		});

	}

	function LegendItem(color, age){
		return "<div class='col-md-3'>"
				+ "<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/"+color+"/' alt='Hello there'>"
			+ "</div>"
			+ "<div class='col-md-9'>"
				+ "<p class='map-legend-content-title'>"+age+"</p>"
			+ "</div>";
	}

	function refreshLegend(){
		console.log("refresh legend");
		legend = "";
		if (markers_age.indexOf('Quaternary')!=-1){ legend += LegendItem('F9F97F', 'Quaternary'); }
		if (markers_age.indexOf('Pliocene')!=-1 || markers_age.indexOf('Miocene')!=-1){ legend += LegendItem('FFE619', 'Neogene'); }
		if (markers_age.indexOf('Oligocene')!=-1 || markers_age.indexOf('Eocene')!=-1 || markers_age.indexOf('Palocene')!=-1 ){ legend += LegendItem('FD9A52', 'Paleogene'); }
		if (markers_age.indexOf('Cretaceous')!=-1 || markers_age.indexOf('Cretaceous, Upper')!=-1 || markers_age.indexOf('Cretaceous, Lower')!=-1){ legend += LegendItem('7FC64E', 'Cretaceous'); }
		if (markers_age.indexOf('Jurassic')!=-1 || markers_age.indexOf('Jurassic, Upper')!=-1 || markers_age.indexOf('Jurassic, Middle')!=-1 || markers_age.indexOf('Jurassic, Lower (Lias)')!=-1){ legend += LegendItem('34B2C9', 'Jurassic'); }
		if (markers_age.indexOf('Triassic')!=-1 || markers_age.indexOf('Triassic, Upper')!=-1 || markers_age.indexOf('Triassic, Middle')!=-1 || markers_age.indexOf('Triassic, lower')!=-1){ legend += LegendItem('C72B92', 'Triassic'); }
		if (markers_age.indexOf('Permian')!=-1){ legend += LegendItem('F04028', 'Permian'); }
		if (markers_age.indexOf('Carboniferous')!=-1 || markers_age.indexOf('Carboniferous, Upper (Coal Measeures)')!=-1 || markers_age.indexOf('Carboniferous, Lower (Limestone)')!=-1){ legend += LegendItem('67A599', 'Carboniferous'); }
		if (markers_age.indexOf('Devonian')!=-1 || markers_age.indexOf('Devonian, Upper')!=-1 || markers_age.indexOf('Devonian, Middle')!=-1 || markers_age.indexOf('Devonian, Lower')!=-1){ legend += LegendItem('CB8C37', 'Devonian'); }
		if (markers_age.indexOf('Silurian')!=-1 || markers_age.indexOf('Silurian, Pridoli')!=-1 || markers_age.indexOf('Silurian, Ludlow')!=-1 || markers_age.indexOf('Silurian, Wenlock')!=-1 || markers_age.indexOf('Silurian, Llandovery')!=-1){ legend += LegendItem('B3E1B6', 'Silurian'); }
		if (markers_age.indexOf('Ordovician')!=-1 || markers_age.indexOf('Ordovician, Upper')!=-1 || markers_age.indexOf('Ordovician, Middle')!=-1 || markers_age.indexOf('Ordovician, Middle')!=-1 || markers_age.indexOf('Ordovician, Lower')!=-1){ legend += LegendItem('009270', 'Ordovician'); }
		if (markers_age.indexOf('Cambrian')!=-1){ legend += LegendItem('7FA056', 'Cambrian'); }
		if (markers_age.indexOf('Precambrain')!=-1){ legend += LegendItem('934370', 'Precambrain'); }

		legend += LegendItem('909090', 'Data Missing');

		document.getElementById('map-legend-content').innerHTML = legend;
	}

	function refreshFeedback(http){
		$scope.feedbacks = [];
		$scope.replyto = {'reply': false};
		$scope.section_feedback_form_section_title = 'Share your discoveries';
		http.get('/api/map/loadfeedbacks/'+filter['genus']+'/-1/'+$scope.selectedAgeMin+'/'+$scope.selectedAgeMax+'/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
			data.forEach(function(item, index){
				/*
				currentBounds = actualmap.getBounds();

				var view = [
				    {lat: currentBounds.getNorthEast().lat(), lng: currentBounds.getNorthEast().lng()},
				    {lat: currentBounds.getSouthWest().lat(), lng: currentBounds.getNorthEast().lng()},
				    {lat: currentBounds.getSouthWest().lat(), lng: currentBounds.getSouthWest().lng()},
				    {lat: currentBounds.getNorthEast().lat(), lng: currentBounds.getSouthWest().lng()},
				    {lat: currentBounds.getNorthEast().lat(), lng: currentBounds.getNorthEast().lng()}
				];

				center = new google.maps.LatLng(item['map_center_lat'], item['map_center_lng']);

				if (google.maps.geometry.poly.containsLocation(center, view)){
					
				}*/

				$scope.feedbacks.push(item);

				$scope.feedback_form_text_reply[item.feedback_id]="";
				$scope.show_feedback_reply[item.feedback_id] = false;

			});
		});
		console.log($scope.show_feedback_reply);
	}

	$scope.feedback_selection_marker = [];

	$scope.mouseoverFeedback = function(feedback){
		console.log(feedback['selection']);
		if(feedback['selection'].length > 0)
		{
			feedback['selection'].forEach(function(item, index){
				var marker = new google.maps.Marker({
					map: actualmap,
					position: new google.maps.LatLng(item['lat'], item['lng']),
					title: item['id'],
					/*icon: {
				        path: google.maps.SymbolPath.CIRCLE,
				        scale: 6,
				        fillColor: "#F00",
				        fillOpacity: 1,
				        strokeWeight: 0.4
				    }, */
				    //icon: pinSymbol('#fff'),
				    icon: pin_highlight,
				    optimized: false, 
				    zIndex: markers.length
				});
				$scope.feedback_selection_marker.push(marker);
			});
		}
		/*
		rectangle.setMap(null);
		
		var triangleCoords = [
		    {lat: parseFloat(feedback['map_lat_ne']), lng: parseFloat(feedback['map_lng_ne'])},
		    {lat: parseFloat(feedback['map_lat_sw']), lng: parseFloat(feedback['map_lng_ne'])},
		    {lat: parseFloat(feedback['map_lat_sw']), lng: parseFloat(feedback['map_lng_sw'])},
		    {lat: parseFloat(feedback['map_lat_ne']), lng: parseFloat(feedback['map_lng_sw'])},
		    {lat: parseFloat(feedback['map_lat_ne']), lng: parseFloat(feedback['map_lng_ne'])}
		  ];

		// Construct the polygon.
		rectangle = new google.maps.Polygon({
		paths: triangleCoords,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35
		});
		rectangle.setMap(actualmap);
		*/
	}

	$scope.mouseleaveFeedback = function(){
		$scope.feedback_selection_marker.forEach(function(item,index){
			item.setMap(null);
			item = null;
		})
		$scope.feedback_selection_marker = [];	}

	$scope.upvoteFeedback = function(id){
		$scope.recordActivity("Upvote", "Feedback "+id);
		data = {};
		data.feedback_id = id;
		data.user_id = user_id;
		// Do the ajax call
		$http({
	        method : 'POST',
	        url: '/api/map/upvotefeedback',
	        data: $.param(data),
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    	
		}).success(function(data, status, headers, config) {
			//refreshFeedback($http);
			for(var i = 0; i < $scope.feedbacks.length; i++)
			{
				if ($scope.feedbacks[i]['feedback_id'] == parseInt(id)){
					console.log('update');
					$scope.feedbacks[i]['upvote'] += 1;
					$scope.feedbacks[i]['user_has_upvote'] = true;
				}
				for (var j = 0; j < $scope.feedbacks[i]['replies'].length; j++){

					if ($scope.feedbacks[i]['replies'][j]['feedback_id'] == parseInt(id))
					{
						$scope.feedbacks[i]['replies'][j]['upvote'] += 1;
						$scope.feedbacks[i]['replies'][j]['user_has_upvote'] = true;
					}
				}
			}
		});
	}

	$scope.replyFeedback = function(id){
		$scope.recordActivity("Click reply", "Feedback "+id);
		//$scope.section_feedback_form_section_title = "You are replying to:";
		//deselect_all_marker();
		$scope.show_feedback_reply[id] = !$scope.show_feedback_reply[id];
		for (var i = 0; i < $scope.feedbacks.length; i++)
		{
			if ($scope.feedbacks[i]['feedback_id']==parseInt(id))
			{
				//$scope.replyto = $scope.feedbacks[i];
				//$scope.replyto['reply'] = true;
				//console.log("replyto;")
				//console.log($scope.replyto);
				/*
				for (var j = 0; j < markers.length; j++){
					for (var k = 0; k < $scope.replyto.selection.length; k++){

						if (markers[j]['title'].split('-')[0] == $scope.replyto.selection[k]['id']){
							
							select_marker(markers[j]);
							//logActivity($http, "Fossil selected "+id+" "+marker_clicked_for_selection['title'], user_id);
							$scope.text_select_btn = "Deselect this fossil";
						}

					}
				}*/
			}
		}
		
	}

	$scope.cancelReplyFeedback = function(id){
		console.log('cancel reply');
		$scope.recordActivity("Cancel reply", "Feedback "+id);
		$scope.section_feedback_form_section_title = "Share your discoveries";
		//$scope.replyto = {'reply' : false};
		deselect_all_marker();
		$scope.replyto = {'reply':false};
	}


	function logActivity(http, action, details, user_id){
		data = {};
		data.action = action;
		data.details = details;
		data.user_id = user_id;
		// Do the ajax call
		http({
	        method : 'POST',
	        url: '/api/map/logmapactivity',
	        data: $.param(data),
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    	
		}).success(function(data, status, headers, config) {		});
	}

	$scope.feedback_form_text = "";
	$scope.feedback_form_error = "";

	$scope.submitfeedback = function(feedback_reply){
		if ((($scope.feedback_form_text != "") && (typeof(feedback_reply) == 'undefined')) || ( (typeof(feedback_reply) != 'undefined') && ($scope.feedback_form_text_reply[feedback_reply] != "") )) {
			
			data = {};

			if (typeof(feedback_reply) == "undefined"){
				data.replyto = 0;
				data.message = $scope.feedback_form_text;
				
				data.fossil_selection = [];
				$scope.selected_markers.forEach(function(item, index){
				data.fossil_selection.push(item['title'].split("-")[0]);
			});
			}
			else {
				data.replyto = feedback_reply;
				data.message = $scope.feedback_form_text_reply[feedback_reply];
				
				data.fossil_selection = [];
				$scope.selected_markers.forEach(function(item, index){
				data.fossil_selection.push(item['title'].split("-")[0]);
			});
			}
			
			
			data.user_id = user_id;
			data.genus = filter['genus'];
			data.age_min = $scope.selectedAgeMin;
			data.age_max = $scope.selectedAgeMax;
			data.collector = filter['collector'];

			data.map_lat_ne = actualmap.getBounds().getNorthEast().lat();
			data.map_lng_ne = actualmap.getBounds().getNorthEast().lng();
			data.map_lat_sw = actualmap.getBounds().getSouthWest().lat();
			data.map_lng_sw = actualmap.getBounds().getSouthWest().lng();
			data.map_zoom = actualmap.getZoom();
			data.map_center_lat = actualmap.getCenter().lat();
			data.map_center_lng = actualmap.getCenter().lng();


			

			// Do the ajax call
			$http({
		        method : 'POST',
		        url: '/api/map/submitfeedback',
		        data: $.param(data),
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    	
			}).success(function(dat, status, headers, config) {
				console.log("success");
				
				$scope.feedback_form_text = "";
				$scope.clear_selected_markers();
				

				if (typeof(feedback_reply) == 'undefined'){
					refreshFeedback($http);
					$scope.recordActivity('Submit feedback', data.message);
				}
				else {
					$scope.recordActivity('Submit reply', feedback_reply+' '+data.message);
					$scope.feedback_form_text_reply[feedback_reply]  = "";
					refreshFeedback($http);
					console.log(data.message);
				}

				//$scope.replyto = {'reply':false};

			}).error(function(data, status, headers, config){
				console.log(data);
			});
		}
		else {
			if (typeof(feedback_reply) == 'undefined'){
				$scope.feedback_form_error = "Please write a comment before sending";
			}
			else {

			}
		}

	}

	show_img = function(url){
		console.log(url);
	}

	$scope.click_on_marker_for_selection = function(id){
		//marker selection
		var index = $scope.selected_markers.indexOf(marker_clicked_for_selection);
		if (index==-1){
			select_marker(marker_clicked_for_selection);
			logActivity($http, "Fossil selected ", ""+id+" "+marker_clicked_for_selection['title'], user_id);
			$scope.text_select_btn = "Deselect this fossil";
		} else {
			deselect_marker(marker_clicked_for_selection, index);
			logActivity($http, "Fossil deselected", ""+id+" "+marker_clicked_for_selection['title'], user_id);
			$scope.text_select_btn = "Select this fossil";

		}
		//console.log(index);
		//console.log($scope.selected_markers.length);
		//console.log("yeah");
		infoWindow.close;
	}



	// Map section
	$scope.map_zoom = 3;

	var mapProp = {
	    center:new google.maps.LatLng(31.42866248834942,-35.80444375000001),
	    zoom:$scope.map_zoom,
	    maxZoom: 12,
	    minZoom: 2,
	    mapTypeId:google.maps.MapTypeId.ROADMAP,
	    mapTypeControl:false,
	    streetViewControl:false
	};

	actualmap = new google.maps.Map(document.getElementById("googleMap"),mapProp);

	actualmap.addListener("click", function(){
		console.log('map clicked');
		logActivity($http, "Map Click", "", user_id);
		infoWindow.close;
	});

	actualmap.addListener("dragend", function(){
		logActivity($http, "Map Pan", "", user_id);
		//refreshFeedback($http);
		console.log(actualmap.getCenter().toString());
	});

	actualmap.addListener("zoom_changed", function(){
		new_zoom = actualmap.getZoom();
		if (new_zoom>$scope.map_zoom){
			console.log("zoom in");
			logActivity($http, "Map Zoom in", "New Zoom "+$scope.map_zoom, user_id);
		}
		else {
			console.log("zoom out");
			logActivity($http, "Map Zoom out", "New Zoom "+$scope.map_zoom, user_id);
		}
		$scope.map_zoom=new_zoom;
	});

	$scope.slider = {
			min: $scope.selectedAgeMin,
			max: $scope.selectedAgeMax,
			options: {
				floor: 0,
				ceil: 12, 
				step: 1,
				translate: function(value){
					if (value==0) return 'Quaternary';
					if (value==1) return 'Neogene';
					if (value==2) return 'Palogene';
					if (value==3) return 'Cretaceous';
					if (value==4) return 'Jurassic';
					if (value==5) return 'Triassic';
					if (value==6) return 'Permian';
					if (value==7) return 'Carboniferous';
					if (value==8) return 'Devonian';
					if (value==9) return 'Silurian';
					if (value==10) return 'Ordovician';
					if (value==11) return 'Cambrian';
					if (value==12) return 'Precambrain';
				}, 
				getPointerColor: function(value){
					if (value==0) return '#F9F97F';
					if (value==1) return '#FFE619';
					if (value==2) return '#FD9A52';
					if (value==3) return '#7FC64E';
					if (value==4) return '#34B2C9';
					if (value==5) return '#C72B92';
					if (value==6) return '#F04028';
					if (value==7) return '#67A599';
					if (value==8) return '#CB8C37';
					if (value==9) return '#B3E1B6';
					if (value==10) return '#009270';
					if (value==11) return '#7F1056';
					if (value==12) return '#934370';
				}, 
				showSelectionBar: true,
				getSelectionBarColor: function(value) {
					return '#909090';
				}, 
				onEnd: function(modelValue, highValue){
					$scope.clear_selected_markers(); 
					refresh($http);
					refreshFeedback($http);
					logActivity($http, "Filter Geological Age changed", "New range "+$scope.selectedAgeMin+" - "+$scope.selectedAgeMax, user_id);
				}	
			}
	};

	refresh($http);
	refreshFeedback($http);

	if($scope.firstVisit == true){
		document.getElementById('helpButton').click();
	}


	//Nav bar

	$scope.profile = {};

	if ($scope.logged_in)
	{
		$http.get('/api/profile/getdetails/').success(function(data, status, headers, config) {
			// Update the profile page and taskbar
			$scope.user_id = data.profile.id;
			user_id = data.profile.id;
			$scope.profile.username = data.profile.username;
			$scope.profile.email = data.profile.email;
			$scope.profile.last_login = data.profile.last_login;
			$scope.profile.location = data.profile.location;
			$scope.profile.first_name = data.profile.first_name;
			$scope.profile.last_name = data.profile.last_name;
		});
	} 
	else 
	{
		$scope.profile.first_name = 'stranger, do you fancy a login ?';
		$scope.section_feedback_form_section_title = "Share your discoveries (anonymously)";
	}
	

	//filter Section
	$scope.newProject = function (){
		filter['project'] = $scope.selectedProject;
		refreshFeedback($http);
		refresh($http);
	}

	$scope.newGenus = function(){
		filter['genus'] = $scope.selectedGenus;
		refreshFeedback($http);
		refresh($http);
		logActivity($http, "Filter Genus Selector Change", "Value "+$scope.selectedGenus, user_id);
	}

	/*
	$scope.newAgeMin = function(){
		filter['ageMin'] = $scope.selectedAgeMin;
		refreshFeedback($http);
		refresh($http);
		deselect_all_marker();
	}

	$scope.newAgeMax = function(){
		filter['ageMax'] = $scope.selectedAgeMax;
		refreshFeedback($http);
		refresh($http);
		deselect_all_marker();
	}*/

	$scope.newCollector = function(){
		filter['collector'] = $scope.selectedCollector;
		refreshFeedback($http);
		refresh($http);
		logActivity($http, "Filter Collector Selector Change", "Value "+$scope.selectedCollector, user_id);
	}

	$scope.recordActivity = function($action, $details){
		logActivity($http, $action, $details, user_id);
	};

	$scope.resetFilter = function()
	{
		console.log("reset");
		filter['genus'] = '-1';
		$scope.selectedGenus = '-1';
		filter['collector'] = '-1';
		$scope.selectedCollector = '-1';
		$scope.selectedAgeMin = 0;
		$scope.selectedAgeMax = 12;


		center = new google.maps.LatLng(31.42866248834942,-35.80444375000001);
		actualmap.setCenter(center);
		actualmap.setZoom(3);

		refreshFeedback($http);
		refresh($http);
	}


});

var map_admin = angular.module('map_admin', []).controller('admin_map_feedbacks', function($scope, $http, $compile){

	$scope.feedbacks = [];
	$scope.rating_img = [];

	$scope.show_map = [];
	$scope.show_update_notice = false;

	var infoWindowadmin = new google.maps.InfoWindow({maxWidth:200});

	var actualmap;

	refreshFeedback();

	var url_empty = "/assets/img/star/circle_empty.png";
	var url_full = "/assets/img/star/circle_full.png";
	var url_hightlight = "/assets/img/star/circle_highlight.png";

	
	function refreshFeedback(){
		$scope.feedbacks = [];
		$scope.rating_img = [];
		$scope.show_map = [];

		$http.get('/api/map/loadAdminFeedback/').success(function(data, status, headers, config){
			data.forEach(function(item, index)
			{
				$scope.rating_img[item["feedback_id"]] = [];

				$scope.show_map[item["feedback_id"]] = false;

				
				//rating correctness
				if (item['rating_correctness'] == "0"){
					$scope.rating_img[item['feedback_id']][1] = url_empty;
					$scope.rating_img[item['feedback_id']][2] = url_empty;
				}
				if (item['rating_correctness'] == "1"){
					$scope.rating_img[item['feedback_id']][1] = url_hightlight;
					$scope.rating_img[item['feedback_id']][2] = url_empty;
				}
				if (item['rating_correctness'] == "2"){
					$scope.rating_img[item['feedback_id']][1] = url_empty;
					$scope.rating_img[item['feedback_id']][2] = url_hightlight;
				}

				//rating discovery
				if (item['rating_discovery'] == "0"){
					$scope.rating_img[item['feedback_id']][3] = url_empty;
					$scope.rating_img[item['feedback_id']][4] = url_empty;
				}
				if (item['rating_discovery'] == "1"){
					$scope.rating_img[item['feedback_id']][3] = url_hightlight;
					$scope.rating_img[item['feedback_id']][4] = url_empty;
				}
				if (item['rating_discovery'] == "2"){
					$scope.rating_img[item['feedback_id']][3] = url_empty;
					$scope.rating_img[item['feedback_id']][4] = url_hightlight;
				}

				//rating discovery
				if (item['rating_relevance'] == "0"){
					$scope.rating_img[item['feedback_id']][5] = url_empty;
					$scope.rating_img[item['feedback_id']][6] = url_empty;
				}
				if (item['rating_relevance'] == "1"){
					$scope.rating_img[item['feedback_id']][5] = url_hightlight;
					$scope.rating_img[item['feedback_id']][6] = url_empty;
				}
				if (item['rating_relevance'] == "2"){
					$scope.rating_img[item['feedback_id']][5] = url_empty;
					$scope.rating_img[item['feedback_id']][6] = url_hightlight;
				}


				// do the same for the replies
				for (var j = 0; j < item['replies'].length; j++){
					$scope.rating_img[item['replies'][j]['feedback_id']] = [];
					
					//rating correctness
					if (item['replies'][j]['rating_correctness'] == "0"){
						$scope.rating_img[item['replies'][j]['feedback_id']][1] = url_empty;
						$scope.rating_img[item['replies'][j]['feedback_id']][2] = url_empty;
					}
					if (item['replies'][j]['rating_correctness'] == "1"){
						$scope.rating_img[item['replies'][j]['feedback_id']][1] = url_hightlight;
						$scope.rating_img[item['replies'][j]['feedback_id']][2] = url_empty;
					}
					if (item['replies'][j]['rating_correctness'] == "2"){
						$scope.rating_img[item['replies'][j]['feedback_id']][1] = url_empty;
						$scope.rating_img[item['replies'][j]['feedback_id']][2] = url_hightlight;
					}

					//rating discovery
					if (item['replies'][j]['rating_discovery'] == "0"){
						$scope.rating_img[item['replies'][j]['feedback_id']][3] = url_empty;
						$scope.rating_img[item['replies'][j]['feedback_id']][4] = url_empty;
					}
					if (item['replies'][j]['rating_discovery'] == "1"){
						$scope.rating_img[item['replies'][j]['feedback_id']][3] = url_hightlight;
						$scope.rating_img[item['replies'][j]['feedback_id']][4] = url_empty;
					}
					if (item['replies'][j]['rating_discovery'] == "2"){
						$scope.rating_img[item['replies'][j]['feedback_id']][3] = url_empty;
						$scope.rating_img[item['replies'][j]['feedback_id']][4] = url_hightlight;
					}

					//rating discovery
					if (item['replies'][j]['rating_relevance'] == "0"){
						$scope.rating_img[item['replies'][j]['feedback_id']][5] = url_empty;
						$scope.rating_img[item['replies'][j]['feedback_id']][6] = url_empty;
					}
					if (item['replies'][j]['rating_relevance'] == "1"){
						$scope.rating_img[item['replies'][j]['feedback_id']][5] = url_hightlight;
						$scope.rating_img[item['replies'][j]['feedback_id']][6] = url_empty;
					}
					if (item['replies'][j]['rating_relevance'] == "2"){
						$scope.rating_img[item['replies'][j]['feedback_id']][5] = url_empty;
						$scope.rating_img[item['replies'][j]['feedback_id']][6] = url_hightlight;
					}


				}
				$scope.feedbacks.push(item);
			});
		});
	}

	/*$scope.rating_highlight = function(feedback_id, star){
		console.log(feedback_id+'-'+star);
		for (var i = 1; i <= star; i++){
			$scope.init_rating_img[feedback_id][i] = url_hightlight;
		}
	}

	$scope.rating_unhighlight = function(feedback_id, star){
		var rate = 0;
		for (var i = 0; i <  $scope.feedbacks.length; i++){
			if ($scope.feedbacks[i]['feedback_id'] == feedback_id){
				rate = $scope.feedbacks[i]['rating_correctness'];
			}
			for (var j = 0; j < $scope.feedbacks[i]['replies'].length; j++)
			{
				if ($scope.feedbacks[i]['replies'][j]['feedback_id'] == feedback_id)
				{
					rate = $scope.feedbacks[i]['replies'][j]['rating_correctness'];
				}
			}
		}
		for (var i = 1; i <= 6; i++){
			if (rate>=i){
				$scope.init_rating_img[feedback_id][i] = url_full;
			} else {
				$scope.init_rating_img[feedback_id][i] = url_empty;
			}
		}
	}*/

	$scope.update_location = function(){
		$scope.show_update_notice = true;
		http({
		        method : 'POST',
		        url: '/api/map/updateLocation',
		        data: $.param(data),
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    	
		}).success(function(data, status, headers, config) {



		}).error(function(data, status, headers, config){



		});
	}


	$scope.rating_click = function(feedback_id, rating, rate)
	{
		data = {};
		data.feedback_id = feedback_id;
		data.rating = rating;
		data.rate = rate;

		$http({
		        method : 'POST',
		        url: '/api/map/adminEvaluateFeedback',
		        data: $.param(data),
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    	
		}).success(function(data, status, headers, config) {
			console.log("rating success");
			for (var i = 0; i < $scope.feedbacks.length; i++) {
				if($scope.feedbacks[i]['feedback_id'] == feedback_id)
				{
					//rating correctness
					if (rating == 1){
						$scope.feedbacks[i]['rating_correctness'] = rate;
						if (rate == 1){
							$scope.rating_img[feedback_id][1] = url_hightlight;
							$scope.rating_img[feedback_id][2] = url_empty;
						} 
						else 
						{
							$scope.rating_img[feedback_id][1] = url_empty;
							$scope.rating_img[feedback_id][2] = url_hightlight;
						}
					}
					if (rating == 2){
						$scope.feedbacks[i]['rating_discovery'] = rate;
						if (rate == 1){
							$scope.rating_img[feedback_id][3] = url_hightlight;
							$scope.rating_img[feedback_id][4] = url_empty;
						} 
						else 
						{
							$scope.rating_img[feedback_id][3] = url_empty;
							$scope.rating_img[feedback_id][4] = url_hightlight;
						}
					}
					if (rating == 3){
						$scope.feedbacks[i]['rating_relevance'] = rate;
						if (rate == 1){
							$scope.rating_img[feedback_id][5] = url_hightlight;
							$scope.rating_img[feedback_id][6] = url_empty;
						} 
						else 
						{
							$scope.rating_img[feedback_id][5] = url_empty;
							$scope.rating_img[feedback_id][6] = url_hightlight;
						}
					}


				}
				else
				{
					for (var j = 0; j < $scope.feedbacks[i]['replies'].length; j++)
					{
						if ($scope.feedbacks[i]['replies'][j]['feedback_id'] == feedback_id)
						{

							//rating correctness
							if (rating == 1){
								$scope.feedbacks[i]['replies'][j]['rating_correctness'] = rate;
								if (rate == 1){
									$scope.rating_img[feedback_id][1] = url_hightlight;
									$scope.rating_img[feedback_id][2] = url_empty;
								} 
								else 
								{
									$scope.rating_img[feedback_id][1] = url_empty;
									$scope.rating_img[feedback_id][2] = url_hightlight;
								}
							}
							if (rating == 2){
								$scope.feedbacks[i]['replies'][j]['rating_discovery'] = rate;
								if (rate == 1){
									$scope.rating_img[feedback_id][3] = url_hightlight;
									$scope.rating_img[feedback_id][4] = url_empty;
								} 
								else 
								{
									$scope.rating_img[feedback_id][3] = url_empty;
									$scope.rating_img[feedback_id][4] = url_hightlight;
								}
							}
							if (rating == 3){
								$scope.feedbacks[i]['replies'][j]['rating_relevance'] = rate;
								if (rate == 1){
									$scope.rating_img[feedback_id][5] = url_hightlight;
									$scope.rating_img[feedback_id][6] = url_empty;
								} 
								else 
								{
									$scope.rating_img[feedback_id][5] = url_empty;
									$scope.rating_img[feedback_id][6] = url_hightlight;
								}
							}

						}
					}
				}
			}

		}).error(function(data, status, headers, config){
			console.log(data);
		});
	}

	$scope.hideComment = function(id){
		index = 0; 
		feedback = -1;
		for (var i = 0; i < $scope.feedbacks.length; i++)
		{
			if ($scope.feedbacks[i]['feedback_id'] == id)
			{
				index = i;
			}
			else {
				for (var j = 0; j < $scope.feedbacks[i]["replies"].length; j++){
					if ($scope.feedbacks[i]["replies"][j]["feedback_id"] == id){
						index = j;
						feedback = i;
					}
				}
			}
		}

		console.log(feedback+" "+index);

		data = {};
		data.feedback_id = id;

		if (feedback == -1){
			$scope.feedbacks[index]["hidden"] == 0 ? data.hidden = 1 : data.hidden = 0;
		}
		else{
			$scope.feedbacks[feedback]["replies"][index]["hidden"] == 0 ? data.hidden = 1 : data.hidden = 0;
		}

		$http({
	        method : 'POST',
	        url: '/api/map/hidefeedback',
	        data: $.param(data),
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    	
		}).success(function(data, status, headers, config) {
			
			if (feedback == -1){
				$scope.feedbacks[index]["hidden"] == 0 ? $scope.feedbacks[index]["hidden"] = 1 : $scope.feedbacks[index]["hidden"] = 0;
			}
			else{
				$scope.feedbacks[feedback]["replies"][index]["hidden"] == 0 ? $scope.feedbacks[feedback]["replies"][index]["hidden"] = 1 : $scope.feedbacks[feedback]["replies"][index]["hidden"] = 0;
			}
			console.log("un/hide comment")
		}).error(function(data, status, headers, config){
			console.log(data);
		});

	}


	function createMarker(info)
	{
		var marker = new google.maps.Marker({
			map: actualmap,
			position: new google.maps.LatLng(info['lat'], info['lng']),
			title: info['id'] + "-" + info['genus'],
		    icon: getPinColor(info['age'])
		});

		marker.addListener("click", function()
		{

			//info window
			infoWindowadmin.close;
			var content =
			"<div class='container-fluid map-infowindow'>"
				+ "<div class='row'>"

					+ "<div class='col-md-12'>"

						+ "<div class='row'>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'><strong> Genus : </strong> " + info['genus'] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Species : </strong> " + info["species"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Age : </strong>" + info['age'] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Collector : </strong>"+ info["collector"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Location : </strong>" + info["place"] + "</p>"
							+ "</div>"

						+"</div>"

					+ "</div>"
				+ "</div>"
			+ "</div>"
			;

			
			infoWindowadmin.setContent(content);

			infoWindowadmin.open('actualmap', marker);

		});



	}

	
	$scope.showMap = function(feedback_id)
	{
		$scope.show_map[feedback_id] = true;
		document.getElementById('map-'+feedback_id).setAttribute("style","height:500px");

		var temp;

		for (var i = 0; i < $scope.feedbacks.length; i++)
		{
			if ($scope.feedbacks[i]['feedback_id'] == feedback_id)
			{
				feedback = $scope.feedbacks[i];
			}
		}

		console.log(feedback);
		
		var mapOpt = {
		    center:new google.maps.LatLng(feedback['map_center_lat'],feedback['map_center_lng']),
		    zoom:parseInt(feedback['map_zoom']),
		    maxZoom: 12,
		    minZoom: 2,
		    mapTypeId:google.maps.MapTypeId.ROADMAP,
		    mapTypeControl:false,
		    streetViewControl:false
		};

		actualmap = new google.maps.Map(document.getElementById("map-"+feedback_id),mapOpt);
		
		console.log(feedback['map_lat_ne']);
		
		var ne = new google.maps.LatLng(feedback['map_lat_ne'], feedback['map_lng_ne']);
		var sw = new google.maps.LatLng(feedback['map_lat_sw'], feedback['map_lng_sw']);
		var bounds = new google.maps.LatLngBounds(sw, ne);
		

		actualmap.fitBounds(bounds);

		if (feedback.selection.length > 0){
			for (var i = 0; i < feedback.selection.length; i++)
			{
				//console.log(feedback.selection[i]);

				var info = {};
				info['lat'] = feedback.selection[i]['lat'];
				info['lng'] = feedback.selection[i]['lng'];
				info['id'] = feedback.selection[i]['id'];
				info['age'] = feedback.selection[i]['age'];
				info['genus'] = feedback.selection[i]['genus'];
				info['species'] = feedback.selection[i]['species'];
				info['collector'] = feedback.selection[i]['collector'];
				info['place'] = feedback.selection[i]['place'];

				createMarker(info);


			}
		}
		else 
		{
			console.log('/api/map/loadfossils/'+feedback['genus']+'/-1/'+feedback['age_min']+'/'+feedback['age_max']+'/'+feedback['collector']+'/-1/-1/-1/-1/-1');
			$http.get('/api/map/loadfossils/'+feedback['genus']+'/-1/'+feedback['age_min_filter']+'/'+feedback['age_max_filter']+'/'+feedback['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
				data.forEach(function(item, index){
					
					var info = {};
					info['lat'] = item['lat'];
					info['lng'] = item['lng'];
					info['id'] = item['data_id'];
					info['age'] = item['age'];
					info['species'] = item['species'];
					info['collector'] = item['collector'];
					info['place'] = item['place']+ ' ' + item['country'];


					//create the markers to plot on the map
					createMarker(info);	
				});
			});
		}
		
		//console.log($temp['map_center_lat'] + " "+ $temp['map_center_lng']+" "+$temp['map_zoom']);

	}

	$scope.hideMap = function(feedback_id)
	{
		$scope.show_map[feedback_id] = false;
		document.getElementById('map-'+feedback_id).setAttribute("style","height:-1px");
	}


});



