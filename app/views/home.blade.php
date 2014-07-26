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
                <button type="button" class="btn btn-transparent dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-list"></i> <span class="caret"></span>
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
                    @foreach ($types as $type)
                    <li class="{{ $type['class'] }}">
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
        <section id="articles" class="loading">
            <article class="about">
                <p>I make websites. With <a href="http://katehowemakesthings.com/">Kate Howe</a>, I formed <a href="http://left-right.co/">Left&ndash;Right</a>, a web-development practice serving social-purpose clients. Formerly I was Director of Web Development at <a href="http://www.bureaublank.com/">Bureau Blank</a>, a branding agency in New York City, where I supervised work for clients such as Living Cities, the Harvard Kennedy School of Government, and PolicyLink.</p>
                <p>This site merges my info from sites like 
                    <a href="https://www.facebook.com/joshreisner">Facebook</a>, 
                    <a href="https://twitter.com/joshreisner">Twitter</a> and 
                    <a href="http://instagram.com/joshreisner">Instagram</a> 
                    with info I enter into <a href="https://github.com/joshreisner/avalon">a custom CMS</a>. 
                    I made it in PHP using <a href="http://laravel.com/">Laravel</a>, 
                    <a href="http://isotope.metafizzy.co/">Isotope</a>, and 
                    <a href="http://getbootstrap.com/">Bootstrap</a>.
                </p>
                <p>
                    <a class="btn btn-default" href="tel:9172848483"><i class="glyphicon glyphicon-earphone"></i></a>
                    <a class="btn btn-default" href="mailto:josh@joshreisner.com"><i class="glyphicon glyphicon-send"></i></a>
                </p>
            </article>
            @foreach ($articles as $time=>$article)
                <article class="{{ $article['type'] }}">
                    <header>{{ $article['header'] }}</header>
                    {{ $article['content'] }}
                    <footer>
                        {{ $article['source'] }}
                        <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
                    </footer>
                </article>
            @endforeach
        </section>
        <script src="/assets/js/main.min.js"></script>
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
    </body>
</html>
