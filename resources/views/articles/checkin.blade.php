<header>Check-In</header>

@if (isset($checkin->image->url))
<a class="image" href="{{ $checkin->url }}">
	<img src="{{ $checkin->map->url }}" width="{{ $checkin->map->width }}" height="{{ $checkin->map->height }}" class="img-responsive">
	<i class="glyphicon glyphicon-map-marker"></i>
</a>
@endif

<p>{{ $checkin->name }}</p>

<footer>
    Foursquare
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
