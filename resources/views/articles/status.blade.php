<header>Status</header>

<p>
	{!! $status->text !!}
</p>

<footer>
    Twitter
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
