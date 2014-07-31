<article class="{{ $type }}">
    <header><i class="glyphicon glyphicon-video"></i> Video</header>
    <a class="image" href="{{ $video->url }}"><img src="{{ $video->img }}" width="640" height="{{ $video->height }}" class="img-responsive"><i class="glyphicon glyphicon-play"></i></a>
	<p><a href="{{ $video->url }}">{{ $video->title }}</a> by {{ $video->author }}</p>
    <footer>
        Vimeo
        <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
    </footer>
</article>
