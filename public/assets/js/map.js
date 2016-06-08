var actualmap;
var markers = [];

var infoWindow;
var user_id;

var filter = [];
filter['project'] = "-1";
filter['genus'] = "-1";
filter['ageMin'] = "Quaternary";
filter['ageMax'] = 'Precambrian';
filter['collector'] = '-1';

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
/*
function pinSymbol(color) {
    return {
        path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
        fillColor: color,
        fillOpacity: 1,
        strokeColor: '#000',
        strokeWeight: 2,
        scale: 1,
   };
}*/
var pinColor = "FE7569";
var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 34),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 34));

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
	    icon: pinImage,
	    text:"false"
	});

	marker.addListener("mouseover", function(){
		infoWindow.close;
		infoWindow.setContent(info["content"]);
		logActivity(http, "Hover on fossil "+info['id']+" "+info['title'], user_id);
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

	marker.addListener("click", function(){
			marker.setIcon({
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10,
            fillColor: "#00F",
            fillOpacity: 0.8,
            strokeWeight: 1
        });
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

			var info_window_genus = "";

			if (item['genus'] == 'Not listed')
			{
				info['title'] = item['genuscustom'] + " " + item['species'];
				info_window_genus = item['genuscustom'];
			}
			else{
				info['title'] = item['genus'] + " " + item['species'];
				info_window_genus = item['genus'];
			}
			
			info['content'] = 
			"<div class='container-fluid map-infowindow'>"
				+ "<div class='row'>"

					+ "<div class='col-md-6'>"
						+ "<img data-toggle='modal' data-target='#Modal-lg-image' src='"+item["url"]+"' class='map-infowindow-img' onclick='show_img(\""+item['url']+"\")'>"
					+ "</div>"

					+ "<div class='col-md-6'>"

						+ "<div class='row'>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'><strong> Genus : </strong> " + info_window_genus + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Species : </strong> " + item["species"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Age : </strong>" + item['age'] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Collector : </strong>"+ item["collector"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<p class='infowindow-text'> <strong> Location : </strong>" + item["place"] + " " + item["country"] + "</p>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<a class='infowindow-text' href='#'>Wrong spot ?</a>"
							+ "</div>"
							+ "<div class='col-xs-12'>"
								+ "<button type='button' class='btn btn-primary'>Select this fossil</button>"
							+ "</div>"
						+"</div>"

					+ "</div>"
				+ "</div>"
			+ "</div>"
			;

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

var map = angular.module('map', [])
.controller('GoogleMap', function($scope, $http){
	
	$scope.loading = false;

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
});

map.controller('navbarSection', function($scope, $http){

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
});

map.controller('filterSection', function($scope, $http){

	$scope.selectedProject = filter['project'];
	$scope.selectedGenus = filter['genus'];
	$scope.selectedAgeMin = filter['ageMin'];
	$scope.selectedAgeMax = filter['ageMax'];
	$scope.selectedCollector = filter['collector'];	

	$scope.newProject = function (){
		filter['project'] = $scope.selectedProject;
		refresh($http);
	}

	$scope.newGenus = function(){
		filter['genus'] = $scope.selectedGenus;
		refresh($http);
		logActivity($http, "Filter Genus Selector Change Value "+$scope.selectedGenus, user_id);
	}

	$scope.newAgeMin = function(){
		filter['ageMin'] = $scope.selectedAgeMin;
		refresh($http);
	}

	$scope.newAgeMax = function(){
		filter['ageMax'] = $scope.selectedAgeMax;
		refresh($http);
	}

	$scope.newCollector = function(){
		filter['collector'] = $scope.selectedCollector;
		refresh($http);
		logActivity($http, "Filter Collector Selector Change Value "+$scope.selectedCollector, user_id);
	}

	$scope.recordActivity = function($a){
		logActivity($http, $a, user_id);
	};
});


