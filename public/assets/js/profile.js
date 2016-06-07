/**
*	Profile JS file
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('profileListing', function($scope, $http) {
	// Vars set at app scope
	
	// Load the initial data
	$http.get('/api/profile/getdetails/').success(function(data, status, headers, config) {
		// Update the profile page and taskbar
		$scope.profile.id = data.profile.id;
		$scope.profile.username = data.profile.username;
		$scope.profile.email = data.profile.email;
		$scope.profile.last_login = data.profile.last_login;
		$scope.profile.location = data.profile.location;
		$scope.profile.first_name = data.profile.first_name;
		$scope.profile.last_name = data.profile.last_name;
		
		// Totals
		$scope.profile.queue_total = data.profile.queue_total;
		$scope.profile.review_total = data.profile.review_total;
		$scope.profile.share_total = data.profile.share_total;
		
		// Badges
		$scope.profile.badge.signup = (data.profile.badge.signup == 1) ? true: false;
		$scope.profile.badge.image1 = (data.profile.badge.image1 == 1) ? true: false;
		$scope.profile.badge.image10 = (data.profile.badge.image10 == 1) ? true: false;
		$scope.profile.badge.image25 = (data.profile.badge.image25 == 1) ? true: false;
		$scope.profile.badge.review1 = (data.profile.badge.review1 == 1) ? true: false;
		$scope.profile.badge.review10 = (data.profile.badge.review10 == 1) ? true: false;
		$scope.profile.badge.review25 = (data.profile.badge.review25 == 1) ? true: false;
		$scope.profile.badge.share1 = (data.profile.badge.share1 == 1) ? true: false;
		$scope.profile.badge.share10 = (data.profile.badge.share10 == 1) ? true: false;
		$scope.profile.badge.share25 = (data.profile.badge.share25 == 1) ? true: false;
		
		// If the location isn't set try to set it
		if(data.profile.location == 'anon') {
			setLocation(data.profile.id);
		}
	}).error(function(data, status, headers, config) {
    	$('#profile-listing').text('An error occurred loading this data.');
	});
	
	// Create the polling function
	var polltheProfile = function() {
		$http.get('/api/profile/getdetails/').success(function(data, status, headers, config) {
			// Totals
			$scope.profile.queue_total = data.profile.queue_total;
			$scope.profile.review_total = data.profile.review_total;
			$scope.profile.share_total = data.profile.share_total;
		
			// Badges
			$scope.profile.badge.signup = (data.profile.badge.signup == 1) ? true: false;
			$scope.profile.badge.image1 = (data.profile.badge.image1 == 1) ? true: false;
			$scope.profile.badge.image10 = (data.profile.badge.image10 == 1) ? true: false;
			$scope.profile.badge.image25 = (data.profile.badge.image25 == 1) ? true: false;
			$scope.profile.badge.review1 = (data.profile.badge.review1 == 1) ? true: false;
			$scope.profile.badge.review10 = (data.profile.badge.review10 == 1) ? true: false;
			$scope.profile.badge.review25 = (data.profile.badge.review25 == 1) ? true: false;
			$scope.profile.badge.share1 = (data.profile.badge.share1 == 1) ? true: false;
			$scope.profile.badge.share10 = (data.profile.badge.share10 == 1) ? true: false;
			$scope.profile.badge.share25 = (data.profile.badge.share25 == 1) ? true: false;

		}).error(function(data, status, headers, config) {
			// Log any errors
			console.log('polltheProfile failed.');
		});
	};
	
	// Poll the profile data every 30 seconds
	setInterval(function() {
		polltheProfile();
	}, 30000);
	
	// Update the user location
	function setLocation(id) {
		$http({
            method : 'POST',
            url: '/api/profile/setiplocation/',
            data: $.param({user_id: id}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			// Location updated.
			
		}).error(function(data, status, headers, config) {
			console.log('Cannot set location');
		});
	};
	
	$scope.updateProfile = function() {
		$http({
            method : 'POST',
            url: '/api/profile/update/',
            data: $.param($scope.profile),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			// Show success message
			$('#profile-message').remove();
			$('#profile-update .pink-button').after('<div id="profile-message">Your profile has been saved.</div>');
			removeProfileMessage();
			
		}).error(function(data, status, headers, config) {
			console.log('Cannot update profile');
			
			$('#profile-message').remove();
			$('#profile-update .pink-button').after('<div id="profile-message">Unable to update profile at this time.</div>');
			removeProfileMessage();
		});
	};
	
	function removeProfileMessage() {
		$('#profile-message').click(function() {
			$(this).remove();
		});
		
		setTimeout(function() {
			$('#profile-message').remove();
		}, 20000);
	};
	
	// Share an image
	function showSharePanel(hash, image_name) {
		$scope.shareimage.show = true;
		
		if(hash == 0) {
			// Sharing a random image
			$scope.share.image = 'a random image';
			
		} else {
			// Sharing a specific image that the user currently has open
			$scope.share.hash = hash;
			$scope.share.image = image_name;
		}
	}
});
