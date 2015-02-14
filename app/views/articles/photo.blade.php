<header>Photo</header>

<a class="image swipebox" href="{{ $photo->image->url }}" title="{{ $photo->caption or $photo->location }}" rel="images"><img src="{{ $photo->image->url }}" width="{{ $photo->image->width }}" height="{{ $photo->image->height }}" class="img-responsive"></a>

@if ($photo->location)
<p>{{ $photo->location }}</p>
@elseif ($photo->caption)
<p>{{ $photo->caption }}</p>
@endif

<footer>
    Instagram
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
