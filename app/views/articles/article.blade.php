<article class="{{ $type }}">
    <header><i class="glyphicon glyphicon-book"></i> Recommended Reading</header>
	<h4><a href="{{ $article->url }}">{{ $article->title }}</a></h4>
	<p>{{ $article->excerpt }}</p>
    <footer>
        Twitter
        <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
    </footer>
</article>
