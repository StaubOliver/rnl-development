

var map = angular.module('map', [])
.controller('GoogleMap', function($scope, $http, $compile){
	
	var actualmap;

	var markers = [];
	$scope.selected_markers = [];

	$scope.feedbacks = [];

	var infoWindow;
	var user_id;

	var filter = [];
	filter['project'] = "-1";
	filter['genus'] = $scope.selectedGenus;
	filter['ageMin'] = "Quaternary";
	filter['ageMax'] = 'Precambrian';
	filter['collector'] = $scope.selectedCollector;

	$scope.selectedProject = filter['project'];
	//$scope.selectedGenus = filter['genus'];
	$scope.selectedAgeMin = filter['ageMin'];
	$scope.selectedAgeMax = filter['ageMax'];
	//$scope.selectedCollector = filter['collector'];	

	var marker_clicked_for_selection = {};

	// Sets the map on all markers in the array.
	function setMapOnAll(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	function clearMarkers() {
	 	setMapOnAll(null);
	}

	function deleteMarkers() {
		clearMarkers();
		markers = [];
	}

	var getPin = function(color){
		return "http://www.googlemapsmarkers.com/v1/"+color+"/"
	}

	$scope.highlight_marker = function(marker){
		marker.setIcon(getPin("FFFFFF"));
	}

	$scope.remove_highlight = function(marker){
		marker.setIcon(getPin("ff4d79"));
	}

	function deselect_all_marker(){
		for (var i=0; i < $scope.selected_markers.length; i++){
			$scope.selected_markers[i].setIcon(getPin("51ccca"));
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
		logActivity($http, "Fossil deselected "+marker['title'].split("-")[0]+" "+marker_clicked_for_selection['title'], user_id);
	}

	function select_marker(marker){
		$scope.selected_markers.push(marker);
		marker.setIcon(getPin("ff4d79"));
		
	}

	function deselect_marker(marker, index){
		$scope.selected_markers.splice(index,1);
		marker.setIcon(getPin("51ccca"));
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
		    icon: getPin("51ccca"),
		    text:"false", 
		});
		
		marker.addListener("click", function(){

			//log activity
			logActivity(http, "Click on fossil "+info['id']+" "+info['title'], user_id);

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
						+ "<img data-toggle='modal' data-target='#Modal-lg-image' src='"+info["url"]+"' class='map-infowindow-img' onclick='show_img(\""+info['url']+"\")'>"
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
								+ "<p class='infowindow-text'> <strong> Location : </strong>" + info["place"] + " " + info["country"] + "</p>"
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
	}

	
	function refresh(http)
	{
		deleteMarkers();

		infoWindow = new google.maps.InfoWindow({maxWidth:400});

		//retrieve the fossils and put them as marker in the map
		http.get('/api/map/loadfossils/'+filter['genus']+'/-1/ee/ee/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
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
				
				createMarkers(info, http);	
			});
		});
	}

	function refreshFeedback(http){
		$scope.feedbacks = [];
		http.get('/api/map/loadfeedbacks/'+filter['genus']+'/-1/ee/ee/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
			data.forEach(function(item, index){
				$scope.feedbacks.push(item);
			});
		});
	}

	refreshFeedback($http);


	function logActivity(http, message, user_id){
		activity = {};
		activity.activity = message;
		activity.user_id = user_id;
		// Do the ajax call
		http({
	        method : 'POST',
	        url: '/api/map/logmapactivity',
	        data: $.param(activity),
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    	
		}).success(function(data, status, headers, config) {
			console.log(message);
		});
	}

	$scope.feedback_form_text = "";
	$scope.feedback_form_error = "";

	$scope.submitfeedback = function(){
		if ($scope.feedback_form_text){
			data = {};
			data.message = $scope.feedback_form_text;
			data.user_id = user_id;
			data.genus = filter['genus'];
			data.age_min = filter['age_min'];
			data.age_max = filter['age_max'];
			data.collector = filter['collector'];

			data.map_lat_ne = actualmap.getBounds().getNorthEast().lat();
			data.map_lng_ne = actualmap.getBounds().getNorthEast().lng();
			data.map_lat_sw = actualmap.getBounds().getSouthWest().lat();
			data.map_lng_sw = actualmap.getBounds().getSouthWest().lng();
			data.map_zoom = actualmap.getZoom();
			data.map_center_lat = actualmap.getCenter().lat();
			data.map_center_lng = actualmap.getCenter().lng();

			data.fossil_selection = [];
			$scope.selected_markers.forEach(function(item, index){
				data.fossil_selection.push(item['title'].split("-")[0]);
			});

			// Do the ajax call
			$http({
		        method : 'POST',
		        url: '/api/map/submitfeedback',
		        data: $.param(data),
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    	
			}).success(function(data, status, headers, config) {
				console.log("success");
				refreshFeedback($http);
				$scope.feedback_form_text = "";
				$scope.clear_selected_markers();
				recordActivity('Submit feedback');

			}).error(function(data, status, headers, config){
				console.log(data);
			});
		}
		else {
			$scope.feedback_form_error = "You must write some comments before saving"
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
			logActivity($http, "Fossil selected "+id+" "+marker_clicked_for_selection['title'], user_id);
			$scope.text_select_btn = "Deselect this fossil";
		} else {
			deselect_marker(marker_clicked_for_selection, index);
			logActivity($http, "Fossil deselected "+id+" "+marker_clicked_for_selection['title'], user_id);
			$scope.text_select_btn = "Select this fossil";

		}
		console.log(index);
		console.log($scope.selected_markers.length);
		console.log("yeah");
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
		logActivity($http, "Map Click", user_id);
		infoWindow.close;
	});

	actualmap.addListener("dragend", function(){
		logActivity($http, "Map Pan", user_id);
		console.log(actualmap.getCenter().toString());
	});

	actualmap.addListener("zoom_changed", function(){
		new_zoom = actualmap.getZoom();
		if (new_zoom>$scope.map_zoom){
			console.log("zoom in");
			logActivity($http, "Map Zoom in", user_id);
		}
		else {
			console.log("zoom out");
			logActivity($http, "Map Zoom out", user_id);
		}
		$scope.map_zoom=new_zoom;
	});

	refresh($http);


	//Nav bar
	$scope.profile = {};
	$scope.profile.first_name = 'stranger, do you fancy a login ?';

	$http.get('/api/profile/getdetails/').success(function(data, status, headers, config) {
		// Update the profile page and taskbar
		user_id = data.profile.id;
		$scope.profile.username = data.profile.username;
		$scope.profile.email = data.profile.email;
		$scope.profile.last_login = data.profile.last_login;
		$scope.profile.location = data.profile.location;
		$scope.profile.first_name = data.profile.first_name;
		$scope.profile.last_name = data.profile.last_name;

	});

	//filter Section
	$scope.newProject = function (){
		filter['project'] = $scope.selectedProject;
		refreshFeedback($http);
		refresh($http);
		deselect_all_marker();
	}

	$scope.newGenus = function(){
		filter['genus'] = $scope.selectedGenus;
		refreshFeedback($http);
		refresh($http);
		logActivity($http, "Filter Genus Selector Change Value "+$scope.selectedGenus, user_id);
		deselect_all_marker();
	}

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
	}

	$scope.newCollector = function(){
		filter['collector'] = $scope.selectedCollector;
		refreshFeedback($http);
		refresh($http);
		logActivity($http, "Filter Collector Selector Change Value "+$scope.selectedCollector, user_id);
		deselect_all_marker();
	}

	$scope.recordActivity = function($a){
		logActivity($http, $a, user_id);
	};


});


