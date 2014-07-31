<header><i class="glyphicon glyphicon-briefcase"></i> Project</header>

<a class="image" href="{{ $project->url }}"><img src="{{ $project->image->url }}" width="{{ $project->image->width }}" height="{{ $project->image->height }}" class="img-responsive"></a>

<h4><a href="{{ $project->url }}">{{ $project->title }}</a></h4>

{{ $project->description }}

<footer>
    {{ $project->url }}
    <time datetime=" {{ date(DATE_W3C, $time) }}">{{ date('M d, Y', $time) }}</time>
</footer>
