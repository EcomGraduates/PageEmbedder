@foreach($pages as $page)
<li class="dropdown">
    @if(isset($type) && $type == 'links')
        <a href="{{ $page['external_url'] }}" title="{{ $page['title'] }}" {{ isset($page['open_in_new_tab']) && $page['open_in_new_tab'] ? 'target="_blank" rel="noopener"' : '' }}>
            <i class="glyphicon {{ !empty($page['icon_class']) ? $page['icon_class'] : 'glyphicon-bookmark' }}"></i> {{ $page['title'] }}
            @if(isset($page['open_in_new_tab']) && $page['open_in_new_tab'])
                <i class="glyphicon glyphicon-new-window" style="font-size: 0.8em; margin-left: 2px;"></i>
            @endif
        </a>
    @else
        <a href="{{ url($page['path']) }}" title="{{ $page['title'] }}">
            <i class="glyphicon {{ !empty($page['icon_class']) ? $page['icon_class'] : 'glyphicon-bookmark' }}"></i> {{ $page['title'] }}
        </a>
    @endif
</li>
@endforeach 