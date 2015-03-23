<header>Video</header>

<a class="image swipebox" rel="videos" href="{{ $video->url }}" title="{{ $video->title }}">
	<img src="{{ $video->image->url }}" width="{{ $video->image->height }}" height="{{ $video->image->height }}" class="img-responsive" alt="{{ $video->title }}">
	<i class="glyphicon glyphicon-play"></i>
</a>

<h4><a href="{{ $video->url }}">{{ $video->title }}</a> <small>by {{ $video->author }}</small></h4>

<footer>
    {{ $video->source }}
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
