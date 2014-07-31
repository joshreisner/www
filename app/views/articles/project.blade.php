<header><i class="glyphicon glyphicon-briefcase"></i> Project</header>

<a class="image" href="{{ $project->url }}"><img src="{{ $project->img }}" width="640" height="400" class="img-responsive"></a>
{{ $project->description }}

<footer>
    {{ $project->url }}
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
