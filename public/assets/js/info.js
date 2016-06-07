/**
*	Information panel JS file
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('informationPanel', function($scope, $http) {
	$scope.showInformationPanel = function() {
		// If this is the first visit, show the information panel
		if(localStorage.getItem('rnl_info') === null) {
			// Show the info panel
			$scope.info.panel = true;
			
			// Turn down the opacity of the background elements
			$('#project, #leaderboard, #activity').css('opacity', 0.3);
			
			// Set the marker
			localStorage.setItem('rnl_info', 1);
		}
	};
	
	$scope.closeInformationPanel = function() {
		// Show the info panel
		$scope.info.panel = false;
		
		// Turn up the opacity of the background elements
		$('#project, #leaderboard, #activity').css('opacity', 1);
	};
	
	$scope.showInformationPanel();
});