/**
*	Share JS file for logged out view
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('shareImage', function($scope, $http) {
	// Hide the projects list
	$('.project-wrapper').hide();

	// Load image
	$scope.loadImage = function(id) {
		// Do the request
		$http.get('/api/project/loadshare/'+id+'/'+loadImageID).success(function(data, status, headers, config) {
			console.log(data);
			
			// Show the parts
			$('#project-selection').hide();
			$('#project-image').show();
			$('#project-progress').show();
			$('#project-data-entry').show();

			// Image not found
			/*
			if(data.image.image_id == 0) {
				// Show an error message
				alert('An error occurred. The image you are trying to view was not found.');
				
				setTimeout(function() {
					window.location.href = '/';
				}, 10000);
			}
			*/
			
			if(data.image.image_id != 0) {
				// Image loaded from queue
				
				// The image requested has been completed however, so here's a random one from the queue
				if(data.complete == 1) {
					showErrorMessage('The image you requested has already been completed. Here\'s another from the collection.');
					
					setTimeout(function() {
						$('#project-error').hide();
					}, 30000);
				}
				
				// Load the relevant form
				$scope.project.queueform = '/api/project/loadform/queue/'+id;
				$scope.showQueueForm = true;
				$scope.showReviewForm = false;
				
				// Update the titles
				$scope.title = data.project;
				var file = data.image.filename;
				$scope.filename = file.substring(0, file.length-4);
				
				// Add the image
				$scope.imageurl = data.image.image_url;
				
				// Update the project and image data
				$scope.entry.project_id = id;
				$scope.entry.image_id = data.image.image_id;
				
				$scope.queue.id = id;
				$scope.hash = data.image.hash;
				
				// Add the image plugin
				setTimeout(function() {
					$("#project-image img").elevateZoom({
						zoomType: "lens"
					});
				}, 1000);
			} else {
				// No images left in the queue, display a message and load review queue
				// Load the review queue
				$scope.loadReviews(id);
			}
			
		}).error(function(data, status, headers, config) {
			showErrorMessage('An error occurred loading this data.');
		});
	};
	
	$scope.loadReviews = function(id) {
		// Close any previous error messages
		$scope.closeErrorMessage();
		
		showErrorMessage('It looks like all the images have been completed for this project. You can review previous entries below.');
				
		setTimeout(function() {
			$('#project-error').hide();
		}, 30000);
		
		// Resize the sidebar
		//$('#activity').height('150vh');
		
		// Load the relevant form
		$scope.project.reviewform = '/api/project/loadform/review/'+id+'/0';
		$scope.showReviewForm = true;
		$scope.showQueueForm = false;
		
		// Do the request
		$http.get('/api/project/loadreview/'+id+'/0').success(function(data, status, headers, config) {
			console.log(data);

			if(data.image.image_id != 0) {
				// Image found in the queue
				
				// Update the titles
				$scope.title = data.project;
				var file = data.image.filename;
				$scope.filename = file.substring(0, file.length-4);
				
				// Add the image
				$scope.imageurl = data.image.image_url;
				
				// Update the project and image data
				$scope.entry.project_id = id;
				$scope.entry.image_id = data.image.image_id;
				$scope.hash = data.image.hash;
				
				// Add the image plugin
				setTimeout(function() {
					$("#project-image img").elevateZoom({
						zoomType: "lens"
					});
				}, 1000);
				
				// Show the parts
				$('#project-selection').hide();
				$('#project-image').show();
				$('#project-progress').show();
				$('#project-data-entry').show();

				// Update the form; form needs to be populated with previous data
				$scope.entry.genus = (data.entry.genus != 0) ? data.entry.genus : '';
				$scope.entry.genuscustom = (data.entry.genus == "Not listed") ? data.entry.genuscustom : '';
				$scope.entry.species = (data.entry.species != 0) ? data.entry.species : '';
				$scope.entry.age = (data.entry.age != 0) ? data.entry.age : '';
				$scope.entry.country = (data.entry.country != 0) ? data.entry.country : '';
				$scope.entry.place = (data.entry.place != 0) ? data.entry.place : '';
				$scope.entry.collector = (data.entry.collector != 0) ? data.entry.collector : '';
				
				// Make sure the scope is updated
				//$scope.$apply();
				
				console.log($scope.entry);
				
			} else {
				// No images left in the queue, display a message
				showErrorMessage('Hmm, there are no entries available for review. Sorry about that.');
			}
			
		}).error(function(data, status, headers, config) {
			showErrorMessage('An error occurred loading this data.');
		});
	};
	
	/**
	 * Error handling
	 */
	function showErrorMessage(text) {
		$scope.error = text;
		$('#project-error').show();
	};
	
	$scope.closeErrorMessage = function() {
		$('#project-error').hide();
	};
	
	// Load image. Project id hard coded as 1, needs modifying for further projects
	$scope.loadImage(1);
});