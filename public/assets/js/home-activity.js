/* Activity feed for the home page */
//var app = angular.module('app', ['angularMoment']);
var app = angular.module('app', []);

app.controller('activityFeed', function($scope, $http) {
	// Load the initial data
	$http.get('/api/game/activity/').success(function(data, status, headers, config) {
		// Set the activity feed
		$scope.update = {
			'name': data[1].name,
			'activity': data[1].activity,
			'time': data[1].time
		};

	}).error(function(data, status, headers, config) {
    	console.log('Feed failed.');
	});
	
	// Create the polling function
	var polltheActivity = function() {
		$http.get('/api/game/activity/').success(function(data, status, headers, config) {
			// Update the activity list
			$scope.update = {
				'name': data[1].name,
				'activity': data[1].activity,
				'time': data[1].time
			};

		}).error(function(data, status, headers, config) {
			// Log any errors
			console.log('polltheActivity failed.');
		});
	};
	
	// Poll the activity every 5 seconds
	setInterval(function() {
		polltheActivity();
	}, 2000);
});

