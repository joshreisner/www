<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="I make websites.">
        <title>Josh Reisner</title>
        <link rel="canonical" href="http://joshreisner.com/">
        <link rel="stylesheet" href="/assets/css/main.min.css">
    </head>
    <body>
        <section id="head" role="banner">
            <h1>Josh Reisner</h1>
            <div id="filter" class="btn-group pull-right">
                <button type="button" class="btn btn-transparent btn-lg dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-arrow">
                    <li class="all">
                        <a>
                            <i class="glyphicon glyphicon-check"></i> 
                            <i class="glyphicon glyphicon-unchecked"></i> 
                            Show All
                        </a>
                    </li>
                    <li class="divider"></li>
                    @foreach ($types as $class=>$type)
                    <li class="{{ $class }}">
                        <a class="clearfix">
                            <i class="pull-left glyphicon glyphicon-check"></i> 
                            <i class="pull-left glyphicon glyphicon-unchecked"></i> 
                            <span class="pull-left">{{ $type['title'] }}</span>
                            <span class="badge pull-right">{{ $type['count'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>                
            </div>
        </section>
        <section id="articles">
            <article class="about">
                <p>I make websites. With <a href="http://katehowemakesthings.com/">Kate Howe</a>, I formed <a href="http://left-right.co/">Left&ndash;Right</a>, a web-development practice serving social-purpose clients. Formerly I was Director of Web Development at <a href="http://www.bureaublank.com/">Bureau Blank</a>, a branding agency in New York City, where I supervised work for clients such as Living Cities, the Harvard Kennedy School of Government, and PolicyLink.</p>
                <p>This site merges my info from sites like 
                    <a href="https://www.facebook.com/joshreisner">Facebook</a>, 
                    <a href="https://twitter.com/joshreisner">Twitter</a> and 
                    <a href="http://instagram.com/joshreisner">Instagram</a> 
                    with info I enter into <a href="https://github.com/joshreisner/avalon">a custom CMS</a>. 
                    The <a href="https://github.com/joshreisner/www">source code</a> is on Github.
                </p>
                <form id="contact">
                	<input type="email" placeholder="Email Address">
                	<textarea placeholder="Message"></textarea>
                </form>
                <p>
                    <a class="btn" href="tel:9172848483">Call</a>
                    <a class="btn" href="mailto:josh@joshreisner.com">Email</a>
                </p>
            </article>
            @foreach ($articles as $time=>$article)
	            <article class="{{ $article['type'] }} loading">
            		@include('articles.' . $article['type'], $article)
            	</article>
            @endforeach
        </section>
        <script src="/assets/js/main.min.js"></script>
    </body>
</html>
