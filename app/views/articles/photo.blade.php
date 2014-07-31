<header><i class="glyphicon glyphicon-camera"></i> Photo</header>

<a class="image" href="{{ $photo->url }}"><img src="{{ $photo->image->url }}" width="{{ $photo->image->width }}" height="{{ $photo->image->height }}" class="img-responsive"></a>

@if ($photo->location)
<p>{{ $photo->location }}</p>
@elseif ($photo->caption)
<p>{{ $photo->caption }}</p>
@endif

<footer>
    Instagram
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
