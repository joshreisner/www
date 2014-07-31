<header><i class="glyphicon glyphicon-photo"></i> Photo</header>

<a class="image" href="{{ $photo->url }}"><img src="{{ $photo->image->url }}" width="{{ $photo->image->width }}" height="{{ $photo->image->height }}" class="img-responsive"></a>

<p>{{ $photo->location }}</p>

<footer>
    Instagram
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
