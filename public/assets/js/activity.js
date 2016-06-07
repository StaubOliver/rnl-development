/**
*	Activity JS file
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('activityListing', function($scope, $http) {
	// Load the initial data
	$http.get('/api/game/activity/').success(function(data, status, headers, config) {
		// Populate the activity list
		// Have a model with properties for each listing
		
		// Convert to an array so ng-repeat orderby works
		var arr = $.map(data, function(el) { return el; });
		
		$scope.activity = arr;
			 
	}).error(function(data, status, headers, config) {
    	$('#activity-listing').text('An error occurred loading this data.');
	});
	
	// Create the polling function
	var polltheActivity = function() {
		$http.get('/api/game/activity/').success(function(data, status, headers, config) {
			// Update the activity list
			// Check the server regularly and update the list
			
			// Convert to an array so ng-repeat orderby works
			var arr = $.map(data, function(el) { return el; });
			
			$scope.activity = arr;

		}).error(function(data, status, headers, config) {
			// Log any errors
			console.log('polltheActivity failed.');
		});
	};
	
	// Poll the activity every 5 seconds
	setInterval(function() {
		polltheActivity();
	}, 3000);
});