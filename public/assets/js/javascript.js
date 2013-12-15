$(window).load(function() {

	var $container = $('section.articles');

	$container.isotope({

	  itemSelector: 'article',
	  layoutMode: 'masonry'
	});

	$("#filter a").click(function() {
		$(this).toggleClass("inactive");

		var filtr = new Array;
		$("#filter a:not(.inactive)").each(function(){
			filtr[filtr.length] = "." + $(this).parent().attr("class");
		});
		$container.isotope({filter:filtr.join(",")});
	});
});