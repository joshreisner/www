$(function(){
	//open in new win
	$("section#articles a[href^='http://']").attr("target","_blank");

	//get filter from cookie if possible
	filter = "*"; //default string
	if ($.cookie('filter') !== undefined) {
		console.log($.cookie('filter'));

		//deactivate filter items
		filter = $.cookie('filter').split(",");
		$("#filter li a").each(function(){
			if (filter.indexOf("." + $(this).parent().attr("class")) == -1) {
				$(this).addClass("inactive");
			}
		});

		//save filter string
		filter = filter.join(",");
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
		var filter = new Array;
		$("#filter a:not(.inactive)").each(function(){
			filter[filter.length] = "." + $(this).parent().attr("class");
		});
		filter = filter.join(",");

		//run filter on isotope
		$container.isotope({ filter:filter });

		//save filter to query
		$.cookie('filter', filter, { expires: 365 });

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