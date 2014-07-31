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

	//open in new win
	$("section#articles a:not(.btn)").attr("target","_blank");

	//filter
	if ($("#filter a.inactive").size()) {
		$("li.all").show();
		$("li.divider").show();			
	}

	//temp init
	$container = $container.isotope({ 
		itemSelector: "article",
		layoutMode: "masonry",
		filter: filter
	});

	$('section#source article').each(function(){
		
		var $this = $(this).detach().imagesLoaded(function(){
			$this.removeClass("loading").appendTo($container);
			$container.isotope("appended", $this);
		});

	});

	$("#filter a").click(function(e) {

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

		$container.isotope({ filter: filter })

		if ($("#filter a.inactive").size()) {
			$("li.all").show();
			$("li.divider").show();			
		} else {
			$("li.all").hide();
			$("li.divider").hide();			
		}

	});
});

$(window).load(function(){
	//real filter
	//$container.isotope({ filter: filter });
});

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-80350-2']);
_gaq.push(['_trackPageview']);

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();