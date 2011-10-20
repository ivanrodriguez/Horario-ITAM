	$(document).ready(function() {
		
		$('.ask').click(function(e) {
			
			e.preventDefault();
			thisHref	= $(this).attr('href');
			
			if($(this).next('div.question').length <= 0)
				$(this).after('<div class="question">Are you sure?<br/> <span class="yes">Yes</span><span class="cancel">Cancel</span></div>');
			
			$('.question').animate({opacity: 1}, 300);
			
			$('.yes').live('click', function(){
				window.location = thisHref;
			});
			
			$('.cancel').live('click', function(){
				$(this).parents('div.question').fadeOut(300, function() {
					$(this).remove();
				});
			});
			
		});
		
	});