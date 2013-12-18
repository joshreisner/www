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
                    <li class="{{ $medium['class'] }}">
                        <a>
                            <i class="glyphicon glyphicon-check"></i> 
                            <i class="glyphicon glyphicon-unchecked"></i> 
                            {{ $medium['title'] }}
                            <span class="badge pull-right">{{ $medium['count'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>                
            </div>
        </section>
        <section class="articles">
            @foreach ($articles as $article)
            <article class="{{ $article['type'] }}">
                <header>{{ $article['header'] }}</header>
                {{ $article['content'] }}
                <footer>{{ $article['footer'] }}</footer>
            </article>
            @endforeach
        </section>
        <div id="more">
            <button type="button" class="btn btn-default">
                Load More
            </button>
        </div>
        <script src="/assets/js/jquery-1.10.2.min.js"></script>
        <script src="/assets/js/jquery.cookie.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/isotope.pkgd.min.js"></script>
        <script src="/assets/js/javascript.js"></script>
    </body>
</html>
