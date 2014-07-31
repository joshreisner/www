<header><i class="glyphicon glyphicon-book"></i> Place Visited</header>

<a class="image" href="{{ $checkin->url }}"><img src="http://maps.googleapis.com/maps/api/staticmap?center={{ $checkin->latitude }},{{ $checkin->longitude }}&zoom=13&maptype=terrain&size=640x380&sensor=false" width="640" height="380" class="img-responsive"></a>

<p>{{ $checkin->name }}</p>

<footer>
    Foursquare
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
