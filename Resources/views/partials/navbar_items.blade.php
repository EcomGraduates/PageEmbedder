@foreach($pages as $page)
<li class="dropdown">
    <a href="{{ url($page['path']) }}" title="{{ $page['title'] }}">
        <i class="glyphicon {{ !empty($page['icon_class']) ? $page['icon_class'] : 'glyphicon-bookmark' }}"></i> {{ $page['title'] }}
    </a>
</li>
@endforeach 