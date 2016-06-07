var actualmap;
var markers = [];

var infoWindow;

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



function createMarkers(info){
	var marker = new google.maps.Marker({
		map: actualmap,
		position: new google.maps.LatLng(info['lat'], info['lng']),
		title: info['title']
	});

	marker.addListener("click", function(){
		infoWindow.close;
		infoWindow.setContent(info["content"])
		infoWindow.open('actualmap', marker);
	});

	markers.push(marker);
}

function refresh(http)
	{
		deleteMarkers;

		infoWindow = new google.maps.InfoWindow({maxWidth:400});

		//retrieve the fossils and put them as marker in the map
		http.get('/api/map/loadfossils/'+filter['genus']+'/-1/ee/ee/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
			data.forEach(function(item, index){
				var info = [];
				info['lat'] = item['lat'];
				info['lng'] = item['lng'];
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
							+ "<img src='"+item["url"]+"' class='map-infowindow-img' onclick='show_img('"+item["url"]+"')'>"
						+ "</div>"

						+ "<div class='col-md-6'>"

							+ "<p> "
								+ "<strong> Genus : </strong> " + info_window_genus
								+ "</br> <strong> Species : </strong> " + item["species"]
								+ "</br> <strong> Age : </strong>" + item['age']
								+ "</br> <strong> Collector : </strong>"+ item["collector"]
							+ "</p>"

						+ "</div>"
					+ "</div>"
				+ "</div>"
				;

				createMarkers(info);	
			});
		});
	}

function show_img(url){
	console.log(url);
}
	

var map = angular.module('map', [])
.controller('GoogleMap', function($scope, $http){
	
	$scope.loading = false;

	var mapProp = {
	    center:new google.maps.LatLng(51.508742,-0.120850),
	    zoom:3,
	    mapTypeId:google.maps.MapTypeId.ROADMAP,
	    mapTypeControl:false,
	    streetViewControl:false
	};

	actualmap = new google.maps.Map(document.getElementById("googleMap"),mapProp);
	refresh($http);

});

map.controller('navbarSection', function($scope, $http){

	$scope.profile = {};
	$scope.profile.first_name = 'stranger, do you fancy a login ?';

	$http.get('/api/profile/getdetails/').success(function(data, status, headers, config) {
		// Update the profile page and taskbar
		$scope.profile.id = data.profile.id;
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

	$scope.user_id = "0";
	$http.get('/api/profile/getdetails/').success(function(data, status, headers, config) {
			// Update the profile page and taskbar
			$scope.user_id = data.profile.id;
			console.log(data.profile.id);
	});

	/*
	$scope.selectedProject = "-1";
	$scope.selectedGenus = "-1";
	$scope.selectedAgeMin = "Quaternary";
	$scope.selectedAgeMax = "Precambrian";
	$scope.selectedCollector = "-1";
	*/
	/*
	var refresh = function(){
		deleteMarkers();
		//retrieve the fossils and put them as marker in the map
		$http.get('/api/map/loadfossils/'+filter['genus']+'/-1/ee/ee/'+filter['collector']+'/-1/-1/-1/-1/-1').success(function(data, status, headers, config){
			data.forEach(function(item, index){
				var info = [];
				info['lat'] = item['lat'];
				info['lng'] = item['lng'];
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
							+ "<img src='"+item["url"]+"' class='map-infowindow-img' onclick='show_img('"+item["url"]+"')'>"
						+ "</div>"

						+ "<div class='col-md-6'>"

							+ "<p> "
								+ "<strong> Genus : </strong> " + info_window_genus
								+ "</br> <strong> Species : </strong> " + item["species"]
								+ "</br> <strong> Age : </strong>" + item['age']
								+ "</br> <strong> Collector : </strong>"+ item["collector"]
							+ "</p>"

						+ "</div>"
					+ "</div>"
				+ "</div>"
				;
				createMarkers(info);	
			});
		});
	}
	*/

	var logActivity = function($a){
		console.log($a);
		$scope.activity = {};
		$scope.activity.activity = $a;
		$scope.activity.user_id = $scope.user_id;
		// Do the ajax call
		$http({
            method : 'POST',
            url: '/api/map/logmapactivity',
            data: $.param($scope.activity),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        	
    	}).success(function(data, status, headers, config) {
			console.log("success");
		});
	}

	$scope.newProject = function (){
		filter['project'] = $scope.selectedProject;
		refresh($http);
	}

	$scope.newGenus = function(){
		filter['genus'] = $scope.selectedGenus;
		refresh($http);
		logActivity("Genus Selector Change Value");
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
		logActivity("Collector Selector Change Value");
	}

	$scope.recordActivity = function($a){
		logActivity($a);
	};
});


