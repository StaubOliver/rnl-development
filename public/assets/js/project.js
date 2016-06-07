/**
*	Project JS file
*	@author rob.dunne@manchester.ac.uk
*/

app.controller('projectSelection', function($scope, $http) {
	/*
	$scope.title = 'Select a project';
	$scope.progress = 0;
	$scope.imageurl = '';
	$scope.hash = 0;
	$scope.entry = {};
	$scope.queue = {};
	$scope.review = {};
	$scope.project = {};
	$scope.queue.end = false;
	$scope.queue.id = 0;
	$scope.review.end = false;
	$scope.showQueueForm = false;
	$scope.showReviewForm = false;
	$scope.project.queueform = '/assets/templates/nothing.html';
	$scope.project.reviewform = '/assets/templates/nothing.html';
	*/
	$scope.loadProject = function(id) {
		// Close any previous error messages
		$scope.closeErrorMessage();
		
		// Resize the sidebar
		//$('#activity').height('150vh');

		// Load the relevant form
		$scope.project.queueform = '/api/project/loadform/queue/'+id;
		$scope.showQueueForm = true;
		$scope.showReviewForm = false;
		
		// Show the share button on the form
		$('.share-image-link').show();
		
		// Do the request
		$http.get('/api/project/loadqueue/'+id).success(function(data, status, headers, config) {
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
				$scope.queue.id = id;
				$scope.hash = data.image.hash;
				$scope.entry.request_time = data.request_time;
				
				console.log('request time is: '+data.request_time);
				
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
				
			} else {
				// No images left in the queue, display a message
				showErrorMessage('It looks like all the images have been completed for this project. Please check the review queue.');
				
				// Change the button colours
				$('#manchester-fossils-'+id+' .join-project-button').removeClass('pink-button').addClass('grey-button');
				$('#manchester-fossils-'+id+' .review-project-button').removeClass('grey-button').addClass('pink-button');
			}
			
		}).error(function(data, status, headers, config) {
			showErrorMessage('An error occurred loading this data.');
		});
	};
	
	$scope.loadReviews = function(id) {
		// Close any previous error messages
		$scope.closeErrorMessage();
		
		// Resize the sidebar
		//$('#activity').height('150vh');
		
		// Load the relevant form
		$scope.project.reviewform = '/api/project/loadform/review/'+id+'/0';
		$scope.showReviewForm = true;
		$scope.showQueueForm = false;
		
		// Hide the share button on the form
		$('.share-image-link').hide();
		
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
				$scope.entry.request_time = data.request_time;
				
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
				showErrorMessage('Hmm, there are no entries available for review. Try the other button?');
			}
			
		}).error(function(data, status, headers, config) {
			showErrorMessage('An error occurred loading this data.');
		});
	};
	
	$scope.closeCurrentProject = function() {
		// Smoothly scroll to the top
		$('html').animate({
			scrollTop: 0
		}, 1500);

		// Update the title
		$scope.title = 'Select a project';

		// Resize the sidebar
		//$('#activity').attr('style', '');

		// Show the parts
		$('#project-selection').show();
		$('#project-image').hide();
		$('#project-data-entry').hide();
		$('#project-progress').hide();
		
		// Remove the zoom container
		$('.zoomContainer').remove();
		
		// Clear the form inputs
		$('.form-button-wrapper input[type=reset]').trigger('click');
		
		// Clear the model
		$scope.entry.genus = '';
		$scope.entry.genuscustom = '';
		$scope.entry.species = '';
		$scope.entry.age = '';
		$scope.entry.country = '';
		$scope.entry.place = '';
		$scope.entry.collector = '';
	};
	
	/**
	 *  Submit the entry form
	 */
	$scope.submitEntry = function() {
		// Show the loader
		$('#project-image-loader').show();
		
		// Clear any previous custom genus data
		$scope.entry.genuscustom = ($scope.entry.genus == "Not listed") ? $scope.entry.genuscustom : '';
		
		// Update the last image url for the share link
		$scope.share.lastimageurl = window.location.hostname+'/view/index/'+$scope.entry.project_id+'/'+$scope.hash;
		
		// Do the ajax call
		$http({
            method : 'POST',
            url: '/api/project/submitdata',
            data: $.param($scope.entry),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).
			success(function(data, status, headers, config) {
				console.log('Returned data is:');
				console.log(data);
				
				if(data == 0) {
					// Error handle
					showErrorMessage('An error occurred submitting this data, please try again.');
				} else {
					// Show the badge and update the points
					//alert('Image complete');
					$scope.progress = $scope.progress+1;
					
					// Log points total
					$scope.pointsLog('queue_total', 3);

					// Success
					showTrophy();
					
					// Clear the form inputs
					$('.form-button-wrapper input[type=reset]').trigger('click');
					
					// Show the last image tweet link
					$scope.share.lastimage = true;
				
					/**
					 *	Check if we are at the end of the image queue for the project
					 */
					if(data.image.image_id > 0) {
						// Update the image
						$scope.imageurl = data.image.image_url;
				
						// Update the image zoom plugin
						$('.zoomLens').stop().css("background-image", "url("+$scope.imageurl+")");
				
						// Update the model and image id
						var file = data.image.filename;
						$scope.filename = file.substring(0, file.length-4);

						$scope.entry.image_id = data.image.image_id;
						$scope.hash = data.image.hash;
						$scope.entry.request_time = data.request_time;
					} else {
						// Show a prompt to review images for this project, or try another project
						$scope.queue.end = true;
						
						$scope.closeCurrentProject();				
					}
					
					// Hide the image loader
					//setTimeout(function() {
						$('#project-image-loader').hide();
					//}, 1000);
				}
			}).
			error(function(data, status, headers, config) {
				console.log('An error occurred: '+data);
				
				// Show an error message
				showErrorMessage('Oh dear, an error occurred sending this data. Please try again, or check back later.');
			});
	};
	
	/**
	 *  Submit the review form
	 */
	$scope.submitReview = function() {
		// Show the loader
		$('#project-image-loader').show();
		
		// Clear any previous custom genus data
		$scope.entry.genuscustom = ($scope.entry.genus == "Not listed") ? $scope.entry.genuscustom : '';
		
		// Do the ajax call
		$http({
            method : 'POST',
            url: '/api/project/submitreview',
            data: $.param($scope.entry),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).
			success(function(data, status, headers, config) {
				console.log('Returned data is:');
				console.log(data);
				
				if(data == 0) {
					// Error handle
					showErrorMessage('An error occurred submitting this data, please try again.');
				} else {
					// Show the badge and update the points
					//alert('Image complete');
					$scope.progress = $scope.progress+1;
					
					// Log points total
					$scope.pointsLog('review_total', 2);
					
					// Clear the form inputs
					$('.form-button-wrapper input[type=reset]').trigger('click');
					
					// Success
					showTrophy();
					
					/**
					 *	Check if we are at the end of the review queue for the project
					 */
					if(data.image.image_id > 0) {
						// Update the image
						$scope.imageurl = data.image.image_url;
				
						// Update the image zoom plugin
						$('.zoomLens').stop().css("background-image", "url("+$scope.imageurl+")");
				
						// Update the model and image id
						var file = data.image.filename;
						$scope.filename = file.substring(0, file.length-4);

						$scope.entry.image_id = data.image.image_id;
						$scope.entry.request_time = data.request_time;
						
						// Update the form; form needs to be populated with previous data
						$scope.entry.genus = (data.entry.genus != 0) ? data.entry.genus : '';
						$scope.entry.genuscustom = (data.entry.genus == "Not listed") ? data.entry.genuscustom : '';
						$scope.entry.species = (data.entry.species != 0) ? data.entry.species : '';
						$scope.entry.age = (data.entry.age != 0) ? data.entry.age : '';
						$scope.entry.country = (data.entry.country != 0) ? data.entry.country : '';
						$scope.entry.place = (data.entry.place != 0) ? data.entry.place : '';
						$scope.entry.collector = (data.entry.collector != 0) ? data.entry.collector : '';
					} else {
						// Show a prompt to review images for this project, or try another project
						$scope.review.end = true;
						
						$scope.closeCurrentProject();				
					}
					
					// Hide the image loader
					//setTimeout(function() {
						$('#project-image-loader').hide();
					//}, 1000);
				}
			}).
			error(function(data, status, headers, config) {
				console.log('An error occurred: '+data);
				
				// Show an error message
				showErrorMessage('Oh dear, an error occurred sending this data. Please try again, or check back later.');
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
	
	/**
	 * Show a trophy when a task is completed
	 */
	 function showTrophy() {
	 	$('body').append('<div id="success-trophy"><i class="fa fa-trophy"></i><h2>Great work!</h2></div>');
	 	
	 	setTimeout(function() {
	 		// Make big then remove
			$('#success-trophy').transition({
				rotateY: '360deg'
			})
			.transition({ scale: 1.5 }, function() { $(this).remove(); });
			
	 	}, 1000);
	 };
});
