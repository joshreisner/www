<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Josh Reisner</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <section class="head">
            <h1>Josh Reisner</h1>
            <div id="filter" class="btn-group pull-right">
                <button type="button" class="btn btn-transparent dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-list"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-arrow">
                    @foreach ($media as $medium)
                    <li class="{{ $medium['class'] }}"><a><i class="glyphicon glyphicon-check"></i> {{ $medium['title'] }}<span class="badge pull-right">{{ $medium['count'] }}</span></a></li>
                    @endforeach
                </ul>                
            </div>
        </section>
        <section class="articles">
            <article class="about">
                <p>I make websites. With <a href="http://katehowemakesthings.com/">Kate Howe</a>, I formed <a href="http://left-right.co/">Left&ndash;Right</a>, a web-development practice focused on non-profit clients. Formerly I was Director of Web Development at <a href="http://www.bureaublank.com/">Bureau Blank</a>, a branding agency in New York City, where I supervised work for clients such as Living Cities, the Harvard Kennedy School of Government, and PolicyLink.</p>
                <p>This site merges my activity on 
                    <a href="http://instagram.com/joshreisner">Instagram</a>, 
                    <a href="https://twitter.com/joshreisner">Twitter</a>, 
                    <a href="http://last.fm/user/joshreisner">Last.fm</a> and
                    <a href="https://foursquare.com/user/44810174">Foursquare</a> with some info I enter into a custom CMS. I made it in PHP using Laravel, Isotope, and Bootstrap. <a href="http://github.com/joshreisner/www">View source on Github</a>.</p>
                <p>
                    <button type="button" class="btn btn-default"><a href="tel:9172848483"><i class="glyphicon glyphicon-earphone"></i></a></button>
                    <button type="button" class="btn btn-default"><a href="mailto:josh@joshreisner.com"><i class="glyphicon glyphicon-send"></i></a></button>
                </p>
            </article>
            @foreach ($articles as $article)
            <article class="{{ $article['type'] }}">
                <header>{{ $article['header'] }}</header>
                {{ $article['content'] }}
                <footer>{{ $article['footer'] }}</footer>
            </article>
            @endforeach
        </section>
        <script src="/assets/js/jquery-1.10.2.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/isotope.pkgd.min.js"></script>
        <script src="/assets/js/javascript.js"></script>
    </body>
</html>
