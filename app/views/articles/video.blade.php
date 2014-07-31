<header><i class="glyphicon glyphicon-facetime-video"></i> Video</header>

<a class="image" href="{{ $video->url }}"><img src="{{ $video->image->url }}" width="{{ $video->image->height }}" height="{{ $video->image->height }}" class="img-responsive"><i class="glyphicon glyphicon-play"></i></a>

<p><a href="{{ $video->url }}">{{ $video->title }}</a> by {{ $video->author }}</p>

<footer>
    Vimeo
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
