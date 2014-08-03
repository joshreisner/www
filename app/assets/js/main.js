//= include ../../../bower_components/jquery/dist/jquery.js
//= include ../../../bower_components/isotope/dist/isotope.pkgd.js
//= include ../../../bower_components/bootstrap-sass/dist/js/bootstrap.js
//= include ../../../bower_components/jquery.cookie/jquery.cookie.js
//= include ../../../bower_components/imagesloaded/imagesloaded.pkgd.js

$(document).ready(function(){

	$container = $("section#articles");

	//set up some vars
	limit 		= 25;
	filter		= "*"; //default

	//get current filter and set up dropdown filter menu
	$("li.all").hide();
	$("li.divider").hide();

	if (window.location.hash.length > 1) {
		filter = ".about,." + window.location.hash.substr(1);
		$.cookie('filter', filter, { expires: 365 });
		$("#filter li:not(.all) a").addClass("inactive");
		$("#filter li" + filter).find("a").removeClass("inactive");
	} else if ($.cookie('filter') !== undefined) {

		//deactivate filter items
		filter = $.cookie('filter').split(",");
		$("#filter li:not(.all) a").each(function(){
			if (filter.indexOf("." + $(this).parent().attr("class")) == -1) {
				$(this).addClass("inactive");
			}
		});

		if (filter.length == 1) window.location.hash = filter[0].substr(1);

		//save filter string
		filter = filter.join(",");
	}

	//open in new win, prob overkill
	$("section#articles").on("click", "a", function(e){
		var a = new RegExp('/' + window.location.host + '/');
		var href = $(this).attr("href");
		if (!$(this).hasClass("btn") && href && !a.test(href)) {
			e.preventDefault();
			e.stopPropagation();
			window.open(href, '_blank');
		}
	});

	//dropdown show all option
	if ($("#filter a.inactive").size()) {
		$("li.all").show();
		$("li.divider").show();			
	}

	//init
	$container = $container.isotope({ 
		itemSelector: "article:not(.loading)",
		layoutMode: "masonry",
		sortBy: "data-timestamp",
		filter: filter
	});

	//add in elements when they're loaded
	$('article.loading').each(function(){
		var $this = $(this);
		$this.imagesLoaded(function(){
			$this.removeClass("loading");
			$container.isotope("insert", $this);
			//$container.isotope( 'updateSortData', elements )
		});
	});

	$('#contact').on('shown.bs.modal', function(e) {
		console.log('hi');
		$(this).find("input[name=email]").focus();
	});

	//contact form
	$("#contact form").submit(function(){

		//simple validation
		var errors = false;
		var $email = $(this).find("input[name=email]");
		var $message = $(this).find("textarea[name=message]");

		if (!$email.val()) {
			$email.parent().addClass("has-error");
			errors = true;
		} else {
			$email.parent().removeClass("has-error");
		}

		if (!$message.val()) {
			$message.parent().addClass("has-error");
			errors = true;
		} else {
			$message.parent().removeClass("has-error");
		}

		if (!errors) {
			$('#contact').modal('hide');
			$.post("/contact", $(this).serializeArray());
			$message.val("");
		}

		return false;
	});

	//handle filter menu click
	$("#filter").on("click", "a", function(e) {

		if ($(this).parent().hasClass("all")) {
			filter = "*";
			$.removeCookie('filter');
			window.location.hash = '';
			$("#filter a.inactive").removeClass("inactive");
		} else {
			if (e.altKey) {
				//option is down, show this one only
				$("#filter a").addClass("inactive");
				$(this).removeClass("inactive");
			} else {
				$(this).toggleClass("inactive");

				//check to make sure there are any still active
				if (!$("#filter a:not(.inactive)").size()) {
					$("#filter a").removeClass("inactive");
				}
			}

			//build filter string
			filter = new Array;
			filter[filter.length] = '.about';
			$("#filter a:not(.inactive)").each(function(){
				filter[filter.length] = "." + $(this).parent().attr("class");
			});
			if (filter.length == 2) {
				window.location.hash = filter[1].substr(1);
			} else if (window.location.hash.length) {
				window.location.hash = "";
			}
			filter = filter.join(",");

			//save filter to query
			$.cookie('filter', filter, { expires: 365 });

		}

		$container = $container.isotope({ 
			itemSelector: "article:not(.loading)",
			layoutMode: "masonry",
			filter: filter
		});

		if ($("#filter a.inactive").size()) {
			$("li.all").show();
			$("li.divider").show();			
		} else {
			$("li.all").hide();
			$("li.divider").hide();			
		}

	});
});