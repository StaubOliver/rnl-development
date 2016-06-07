var app = angular.module('app', ['angularMoment', 'offClick']).run(function($rootScope) {
	$rootScope.profile = {
		'id': 0,
		'username': '',
		'email': '',
		'last_login': '',
		'location': '',
		'first_name': '',
		'last_name': '',
		'show': false,
		'summary': {
			'show': false
		},
		'badge': {
			'signup': false,
			'image1': false,
			'image10': false,
			'image25': false,
			'review1': false,
			'review10': false,
			'review25': false,
			'share1': false,
			'share10': false,
			'share25': false
		},
		'queue_total': 0,
		'review_total': 0,
		'share_total': 0
	};
	
	$rootScope.share = {
		'show': false,
		'image': 0,
		'hash': 0,
		'url': window.location.hostname+'/share/index/',
		'lastimage': false,
		'lastimageurl': 0
	};
	
	$rootScope.title = 'Select a project';
	$rootScope.progress = 0;
	$rootScope.imageurl = '';
	$rootScope.hash = 0;
	$rootScope.entry = {
		'genus': '',
		'genuscustom': '',
		'age': '',
		'species': '',
		'collector': '',
		'country': '',
		'place': '',
		'image_id': 0
	};
	$rootScope.queue = {};
	$rootScope.review = {};
	$rootScope.project = {};
	$rootScope.queue.end = false;
	$rootScope.queue.id = 0;
	$rootScope.review.end = false;
	$rootScope.showQueueForm = false;
	$rootScope.showReviewForm = false;
	$rootScope.project.queueform = '/assets/templates/nothing.html';
	$rootScope.project.reviewform = '/assets/templates/nothing.html';
	
	$rootScope.sharepoints = {
		'queue_total': 0,
		'review_total': 0
	};
	
	$rootScope.info = {
		'panel': false
	};
	
	// Share an image
	$rootScope.showSharePanel = function(hash, image_name) {
		$rootScope.share.show = true;
		
		if(hash == 0) {
			// Sharing a random image
			$rootScope.share.image = 'a random image';
			
		} else {
			// Sharing a specific image that the user currently has open
			$rootScope.share.hash = hash;
			$rootScope.share.image = image_name;
		}
	};
	
	// Log points for anon users
	$rootScope.pointsLog = function(section, point) {
		// Add the points to the existing points
		var points = $.parseJSON(sessionStorage.getItem('rnlpoints'));
		$rootScope.sharepoints[section] = +point+points[section];		
		sessionStorage.setItem('rnlpoints', JSON.stringify($rootScope.sharepoints));
	}
	sessionStorage.setItem('rnlpoints', JSON.stringify($rootScope.sharepoints));
});