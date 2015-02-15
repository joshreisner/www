<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="I make websites.">
        <title>Josh Reisner</title>
        <link rel="canonical" href="http://joshreisner.com/">
        <link rel="stylesheet" href="/assets/css/main.min.css">
        <link rel="icon" href="/assets/img/favicon.ico" type="image/ico">
	    <link rel="icon" sizes="128x128" href="/assets/img/favicon-128.png" type="image/png">
	    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/assets/img/favicon-57.png" type="image/png">
	    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/img/favicon-114.png" type="image/png">
	    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/img/favicon-72.png" type="image/png">
	    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/img/favicon-144.png" type="image/png">
    </head>
    <body>
        <section id="head" role="banner">
            <h1>Josh Reisner</h1>
	        <nav id="filter">
	        	<a href="#projects">Projects</a>
	        	<span>&middot;</span>
	        	<a href="#videos">Videos</a>
	        	<span>&middot;</span>
	        	<a href="#all">All</a>
	        </nav>
        </section>
        <section id="articles">
            <article class="about" data-timestamp="{{ time() + 100000 }}">
                <p>I make websites. With <a href="http://katehowemakesthings.com/">Kate Howe</a>, I formed <a href="http://left-right.co/">Left&ndash;Right</a>, a web-development practice serving social-purpose clients. Formerly I was Director of Web Development at <a href="http://www.bureaublank.com/">Bureau Blank</a>, a branding agency in New York City, where I supervised work for clients such as Living Cities, the Harvard Kennedy School of Government, and PolicyLink.</p>
                <p>This site merges my info from sites like 
                    <a href="https://www.facebook.com/joshreisner">Facebook</a>, 
                    <a href="https://twitter.com/joshreisner">Twitter</a> and 
                    <a href="http://instagram.com/joshreisner">Instagram</a> 
                    with info I enter into <a href="https://github.com/joshreisner/avalon">a custom CMS</a>. 
                    The <a href="https://github.com/joshreisner/www">source code</a> is on Github.
                </p>
                <p>
                    <a class="btn" data-toggle="modal" data-target="#contact">Contact</a>
                </p>
            </article>
            @foreach ($articles as $time=>$article)
	            <article class="{{ $article['type'] }} loading" data-timestamp="{{ $time }}">
            		@include('articles.' . $article['type'], $article)
            	</article>
            @endforeach
        </section>
        @include('contact')
        <script src="/assets/js/main.min.js"></script>
        @if (App::environment('production'))
        <script>
	        var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-80350-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
		@endif
    </body>
</html>