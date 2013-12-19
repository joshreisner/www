$(function(){
	$("section#articles a[href^='http://']").attr("target","_blank");
	//width = Math.round($("section.articles article").first().outerWidth(true));
});

$(window).load(function() {

	$container = $('section#articles');

	$container.isotope({
		itemSelector: 'article',
		layoutMode: 'masonry'
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

		$container.isotope({filter:filtr.join(",")});
	});

	$("#more button").click(function() {
		$.getJSON("/more/" + $("#articles article").size(), function(data) {
			$.each(data, function(time, article) {
				time = moment.unix(time);
				item =
		            $('<article class="' + article.type + '">' +
		                '<header>' + article.header + '</header>' +
		                article.content +
		                '<footer>' +
		                    article.source + '&nbsp;' +
		                    '<time datetime="' + time.format() + '">' + time.format("MMM D, YYYY") + '</time>' +
		                '</footer>' +
		            '</article>');
				$container.append(item).isotope('appended', item);
			});
		});
	});
});