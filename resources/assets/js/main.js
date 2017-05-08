$(document).ready(function(){

	$container = $('section#articles');

	function init() {

		//figure out what mode we're in
		var mode, filter; 
		if (window.location.hash.length) {
			mode = window.location.hash.substr(1);
		} else if ($.cookie('filter') !== undefined) {
			mode = $.cookie('filter');
		}

		//check to make sure not some other value
		if (mode == 'all') {
			filter = '*';
		} else if (mode == 'videos') {
			filter = '.about, .video';
		} else {
			mode = 'projects';
			filter = '.about, .project';
		}

		//set location if necessary
		if (window.location.hash != mode) window.location.hash = mode;
		
		//save cookie if necessary
		if ($.cookie('filter') != mode) $.cookie('filter', mode, { expires: 365 });

		//run isotope
		$container.isotope({ 
			filter: filter,
			getSortData: {
				timestamp: function(element) {
					return $(element).attr('data-timestamp');
				},
			},
			itemSelector: 'article:not(.loading)',
			layoutMode: 'masonry',
			sortAscending: false,
			sortBy: 'timestamp'
		});

		//set active nav state
		$('#filter a').removeClass('active');
		$('#filter a[href="#' + mode + '"]').addClass('active');
	}

	//add in elements when they're loaded
	$('article.loading').each(function(){
		var $this = $(this);
		$this.imagesLoaded(function(){
			$this.removeClass('loading');
			$container.isotope('insert', $this);
		});
	});

	//navigation
	$('#filter').on('click', 'a', function(e){
		e.preventDefault();
		window.location.hash = $(this).attr('href').substr(1);
		$('#filter a').removeClass('active');
		$(this).addClass('active');
		init();
	});

	//video swipebox
	//$('.swipebox').swipebox({loopAtEnd:true});

	//start up
	init();

	//contact form
	$('#contact form').submit(function(){

		//simple validation
		var errors = false;
		var $email = $(this).find('input[name=email]');
		var $message = $(this).find('textarea[name=message]');

		if (!$email.val()) {
			$email.parent().addClass('has-error');
			errors = true;
		} else {
			$email.parent().removeClass('has-error');
		}

		if (!$message.val()) {
			$message.parent().addClass('has-error');
			errors = true;
		} else {
			$message.parent().removeClass('has-error');
		}

		if (!errors) {
			$('#contact').modal('hide');
			$.post('/contact-form', $(this).serializeArray());
			$message.val('');
		}

		return false;
	});

});