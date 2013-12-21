$(function(){
	//open in new win
	$("section#articles a[href^='http://']").attr("target","_blank");
});

$(window).load(function() {

	limit = 25;
	$container = $('section#articles');

	$container.isotope({
		itemSelector: 'article',
		layoutMode: 'masonry',
		columnWidth: $('section#articles article').first().width()
	}).addClass("loaded");

	$("#filter a").click(function(e) {

		if (e.altKey) {
			//option is down, show this one only
			$("#filter a").addClass("inactive");
			$(this).removeClass("inactive");
		} else {
			$(this).toggleClass("inactive");

			//check to make sure one is clicked
			if (!$("#filter a:not(.inactive)").size()) {
				$("#filter a").removeClass("inactive");
			}
		}

		var filtr = new Array;
		$("#filter a:not(.inactive)").each(function(){
			filtr[filtr.length] = "." + $(this).parent().attr("class");
		});

		//rebuild isotope after filtering

		$container.isotope({
			filter:filtr.join(",")
		})

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
				$container.isotope({
					itemSelector: 'article',
					layoutMode: 'masonry'
				});
			});
		});
	});
});