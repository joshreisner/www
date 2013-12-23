$(function(){
	//open in new win
	$("section#articles a[href^='http']").attr("target","_blank");

	filter = "*"; //default string
	$("li.all").hide();
	$("li.divider").hide();			
	if (window.location.hash.length) {
		filter = "." + window.location.hash.substr(1);
		$.cookie('filter', filter, { expires: 365 });
		$("#filter li:not(.all) a").addClass("inactive");
		$("#filter a" + filter).removeClass("inactive");
	} else if ($.cookie('filter') !== undefined) {
		//console.log($.cookie('filter'));

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

	if ($("#filter a.inactive").size()) {
		$("li.all").show();
		$("li.divider").show();			
	}


});

$(window).load(function() {

	limit 		= 25;
	$container  = $('section#articles');

	$container.isotope({
		itemSelector: 'article',
		layoutMode: 'masonry',
		filter: filter
	}).addClass("loaded");

	$("#filter a").click(function(e) {

		if ($(this).parent().hasClass("all")) {
			filter = "*";
			$.removeCookie('filter');
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
			$("#filter a:not(.inactive)").each(function(){
				filter[filter.length] = "." + $(this).parent().attr("class");
			});
			if (filter.length == 1) {
				window.location.hash = filter[0].substr(1);
			} else if (window.location.hash.length) {
				window.location.hash = "";
			}
			filter = filter.join(",");

			//save filter to query
			$.cookie('filter', filter, { expires: 365 });
		}

		if ($("#filter a.inactive").size()) {
			$("li.all").show();
			$("li.divider").show();			
		} else {
			$("li.all").hide();
			$("li.divider").hide();			
		}

		//run filter
		$container.isotope({ filter:filter });

	});

	$("#more button").click(function() {
		$.getJSON("/more/" + $("#articles article").size(), function(data) {
			$.each(data, function(time, article) {
				var time = moment.unix(article.time);
				$container.append('<article class="' + article.type + ' unadded">' +
		                '<header>' + article.header + '</header>' +
		                article.content +
		                '<footer>' +
		                    article.source + '&nbsp;' +
		                    '<time datetime="' + time.format() + '">' + time.format("MMM D, YYYY") + '</time>' +
		                '</footer>' +
		            '</article>');
			});

			//open in new win (again, ajax)
			$("section#articles a[href^='http://']").attr("target","_blank");

			//wait until images are loaded to add to isotope
			$("article.unadded").imagesLoaded(function(){

				//have to add them one by one?
				$("article.unadded").each(function(){
					$container.isotope('appended', $(this));
					$(this).removeClass("unadded");
				});

				//re-initialize isotope because added articles were frozen
				$container.isotope();
			});
		});
	});
});