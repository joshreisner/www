<header>Article</header>

<h4><a href="{{ $article->url }}">{{ $article->title }}</a></h4>

<p>{{ $article->excerpt }}</p>

<footer>
    {{ $article->domain }}
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
