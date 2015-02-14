//= include ../../../bower_components/jquery/dist/jquery.js
//= include ../../../bower_components/isotope/dist/isotope.pkgd.js
//= include ../../../bower_components/bootstrap-sass/assets/javascripts/bootstrap.js
//= include ../../../bower_components/jquery.cookie/jquery.cookie.js
//= include ../../../bower_components/imagesloaded/imagesloaded.pkgd.js
//= include ../../../bower_components/lavalamp/js/jquery.lavalamp.js
//= include ../../../bower_components/swipebox/src/js/jquery.swipebox.js

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

		//set location and save cookie
		window.location.hash = mode;
		$.cookie('filter', mode, { expires: 365 });

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
		$('#filter a[href=#' + window.location.hash.substr(1) + ']').addClass('active');
		$('#filter').data('active', $('#filter a.active')).lavalamp('update');
	}

	//open in new win, prob overkill
	$container.on('click', 'a', function(e){
		var a = new RegExp('/' + window.location.host + '/');
		var href = $(this).attr('href');
		/*
		if (!$(this).hasClass('btn') && href && !a.test(href)) {
			e.preventDefault();
			e.stopPropagation();
			window.open(href, '_blank');
		}
		*/
	});

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
		init();
	});

	document.onreadystatechange = function() {
		if (document.readyState === 'complete') {
			$('#filter').lavalamp({duration: 200});
		}
	};
	
	$(window).resize(function(){
		$('#filter').lavalamp({duration: 200});
	});

	//video swipebox
	$('.swipebox').swipebox();

	//moving donw here
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
			$.post('/contact', $(this).serializeArray());
			$message.val('');
		}

		return false;
	});

});