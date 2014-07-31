<article class="{{ $type }}">
    <header><i class="glyphicon glyphicon-video"></i> Video</header>
	<a class="image" href="{{ $photo->url }}"><img src="{{ $photo->image->url }}" width="{{ $photo->image->width }}" height="{{ $photo->image->height }}" class="img-responsive"></a>
	{{ $photo->location }}
    <footer>
        Instagram
        <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
    </footer>
</article>
