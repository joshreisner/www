<header>Book</header>

@if (isset($book->cover->url))
<a href="{{ $book->url }}"><img src="{{ $book->cover->url }}" width="{{ $book->cover->width }}" height="{{ $book->cover->height }}"></a>
@endif

<h4>
	<a href="{{ $book->url }}">{{ $book->title }}</a>
</h4>

<p>{{ $book->author }}, {{ $book->published }}</p>

<p><!--
	@for ($i = 0; $i < $book->rating; $i++)
		--><i class="glyphicon glyphicon-star"></i><!--
	@endfor
	@for ($i = 0; $i < 5 - $book->rating; $i++)
		--><i class="glyphicon glyphicon-star-empty"></i><!--
	@endfor
--></p>

<footer>
    Goodreads
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
