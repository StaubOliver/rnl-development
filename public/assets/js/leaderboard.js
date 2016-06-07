/**
*	Leaderboard JS file
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('leaderBoardListing', function($scope, $http) {
	// Load the initial data
	$http.get('/api/game/leaderboard/').success(function(data, status, headers, config) {
		// Populate the leaderboard list
		// Have a model with properties for each listing
		
		angular.forEach(data, function(value, key) {
			data.id = parseFloat(value.id);
		});
		
		$scope.leaderboard = data;
			 
	}).error(function(data, status, headers, config) {
    	$('#leaderboard-listing').text('An error occurred loading this data.');
	});
	
	// Create the polling function
	var polltheLeaderboard = function() {
		$http.get('/api/game/leaderboard/').success(function(data, status, headers, config) {
			// Update the leaderboard list
			// Check the server regularly and update the properties bound to the UI using track by
			
			angular.forEach(data, function(value, key) {
				data.id = parseFloat(value.id);
			});
			
			$scope.leaderboard = data;

		}).error(function(data, status, headers, config) {
			// Log any errors
			console.log('polltheLeaderboard failed.');
		});
	};
	
	// Poll the leaderboard every 5 seconds
	setInterval(function() {
		polltheLeaderboard();
	}, 3000);
});
