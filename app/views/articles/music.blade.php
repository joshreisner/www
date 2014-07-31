<header><i class="glyphicon glyphicon-video"></i> Music</header>

<a class="image" href="{{ $music->url }}"><img src="{{ $music->img }}" width="300" height="300" class="img-responsive"></a>

<p>{{ $music->artist }}: <a class="track" href="{{ $music->url }}">{{ $music->song }}</a></p>

<footer>
    Last.fm
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
