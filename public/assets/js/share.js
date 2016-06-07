/**
*	Share JS file
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('shareImage', function($scope, $http) {
	// Highlight URL text on click
	$scope.onTextClick = function($event) {
		$event.target.select();
	};
	
	// Increase share points
	$scope.addPoints = function(id) {
		$http({
            method : 'POST',
            url: '/api/game/sharepointsbadges',
            data: 'user_id='+id,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			// Points updated
			
		});
	};
	
	// Hover effect for share button
	function shareHover() {
		$('#share-tab').mouseenter(function() {
			$(this).transition({ scale: 1.2 }).transition({ scale: 1.0 });
		});
	};
	
	shareHover();
});