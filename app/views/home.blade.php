<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Josh Reisner</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <section id="head">
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
        <section id="articles" class="unready">
            @foreach ($articles as $time=>$article)
            <article class="{{ $article['type'] }}">
                <header>{{ $article['header'] }}</header>
                {{ $article['content'] }}
                <footer>
                    {{ $article['source'] }}&nbsp;
                    <time datetime="{{ date(DATE_W3C, $time) }}">{{ date('M j, Y', $time) }}</time>
                </footer>
            </article>
            @endforeach
        </section>
        <section id="more">
            <button type="button" class="btn btn-default">
                Load More
            </button>
        </section>
        <script src="/assets/js/jquery-1.10.2.min.js"></script>
        <script src="/assets/js/jquery.cookie.js"></script>
        <script src="/assets/js/moment.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/imagesloaded.pkgd.min.js"></script>
        <script src="/assets/js/isotope.pkgd.js"></script>
        <script src="/assets/js/javascript.js"></script>
    </body>
</html>
