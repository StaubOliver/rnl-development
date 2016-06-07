$(function() {
	$('.pink-button').click(function() {
		var button = $(this);
	
		var project_id = $(this).data('project-id');
		var image_id = $(this).data('image-id');
		
		$.get('/admin/completeimage/'+project_id+'/'+image_id, function(data) {
		  	if(data) {
		  		button.after('<div class="admin-projects-list-item-complete">Image removed from the project queue.</div>');
		  		button.remove();
		  		
		  	} else {
		  		button.after('<div id="admin-projects-list-item-complete-error" class="admin-projects-list-item-complete">An error occurred, please try again.</div>');
		  		
		  		setTimeout(function() {
		  			$('.admin-projects-list-item-complete-error').remove();
		  		}, 7000);
		  	}
		});
	});
});