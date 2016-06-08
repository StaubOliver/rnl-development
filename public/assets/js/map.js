var map = angular.module('map', [])
.controller('GoogleMap', function($scope, $http){
	
	var actualmap;

	var markers = [];
	var selected_markers = [];

	var infoWindow;
	var user_id;

	var filter = [];
	filter['project'] = "-1";
	filter['genus'] = "-1";
	filter['ageMin'] = "Quaternary";
	filter['ageMax'] = 'Precambrian';
	filter['collector'] = '-1';

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

	function deselect_all_marker(){
		for (var i=0; i < selected_markers.length; i++){
			selected_markers[i].setIcon(getPin("009900"));
		}
	}

	function select_marker(marker){
		selected_markers.push(marker);
		marker.setIcon(getPin("ff4d79"));
	}

	function deselect_marker(marker, index){
		selected_markers.splice(index,1);
		marker.setIcon(getPin("212a33"));
	}

	function createMarkers(info, http){

		var marker = new google.maps.Marker({
			map: actualmap,
			position: new google.maps.LatLng(info['lat'], info['lng']),
			title: info['title'],
			/*icon: {
		        path: google.maps.SymbolPath.CIRCLE,
		        scale: 6,
		        fillColor: "#F00",
		        fillOpacity: 1,
		        strokeWeight: 0.4
		    }, */
		    //icon: pinSymbol('#fff'),
		    icon: getPin("212a33"),
		    text:"false"
		});
		
		marker.addListener("click", function(){

			//log activity
			logActivity(http, "Hover on fossil "+info['id']+" "+info['title'], user_id);

			marker_clicked_for_selection = marker;
			//info window
			infoWindow.close;
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
								+ "<a class='infowindow-text' href='#'>Wrong spot ?</a>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<button type='button' class='btn btn-primary' ng-click='click_on_marker_for_selection()'>Select this fossil</button>"
							+ "</div>"
						+"</div>"

					+ "</div>"
				+ "</div>"
			+ "</div>"
			;
			infoWindow.setContent(content);
			
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

	$scope.click_on_marker_for_selection = function(){
		//marker selection
		var index = selected_markers.indexOf(marker);
		if (index==-1){
			select_marker(marker);
			logActivity(http, "Fossil selected "+info['id']+" "+info['title'], user_id)
		} else {
			deselect_marker(marker, index);
			logActivity(http, "Fossil deselected "+info['id']+" "+info['title'], user_id)

		}
		console.log(index);
		console.log(selected_markers.length);
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

		var feedbacks = "";
		http.get('/api/map/loadfossils/'+filter['genus']+'/-1/ee/ee/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
			data.forEach(function(item, index){
				//feedbacks += 
			});
		});
	}


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
			console.log("success");
		});
	}

	function show_img(url){
		console.log(url);
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
		console.log('map dragged');
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

	//Filter Section
	$scope.selectedProject = filter['project'];
	$scope.selectedGenus = filter['genus'];
	$scope.selectedAgeMin = filter['ageMin'];
	$scope.selectedAgeMax = filter['ageMax'];
	$scope.selectedCollector = filter['collector'];	

	$scope.newProject = function (){
		filter['project'] = $scope.selectedProject;
		refresh($http);
		deselect_all_marker();
	}

	$scope.newGenus = function(){
		filter['genus'] = $scope.selectedGenus;
		refresh($http);
		logActivity($http, "Filter Genus Selector Change Value "+$scope.selectedGenus, user_id);
		deselect_all_marker();
	}

	$scope.newAgeMin = function(){
		filter['ageMin'] = $scope.selectedAgeMin;
		refresh($http);
		deselect_all_marker();
	}

	$scope.newAgeMax = function(){
		filter['ageMax'] = $scope.selectedAgeMax;
		refresh($http);
		deselect_all_marker();
	}

	$scope.newCollector = function(){
		filter['collector'] = $scope.selectedCollector;
		refresh($http);
		logActivity($http, "Filter Collector Selector Change Value "+$scope.selectedCollector, user_id);
		deselect_all_marker();
	}

	$scope.recordActivity = function($a){
		logActivity($http, $a, user_id);
	};


});


