<header><i class="glyphicon glyphicon-book"></i> Book</header>

<a href="{{ $book->url }}"><img src="{{ $book->cover->url }}" width="{{ $book->cover->width }}" height="{{ $book->cover->height }}"></a>

<p>
	{{ $book->author }}<br>
	<a href="{{ $book->url }}">{{ $book->title }}</a><br>
	{{ $book->published }}
</p>

<footer>
    Goodreads
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
