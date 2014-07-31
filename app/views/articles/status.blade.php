<header><i class="glyphicon glyphicon-book"></i> Status Update</header>

<p>
	{{ $status->text }}
</p>

<footer>
    Twitter
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
