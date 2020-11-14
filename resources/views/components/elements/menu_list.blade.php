{{-- Stored in /resources/views/components/elements/menu_list.blade.php --}}
@if(isset($menu) && $menu instanceof App\Models\System\Menu)
    @if(isset($heading) && $heading === true)
        <h4 class="mt-3">{{ __($menu->label) }}</h4>
    @endif
    @if($items = $menu->menuItems()->orderBy('position', 'asc')->cursor())
        @if($items->count() != 0)
            <ul class="{{ $menu->css_classes?? 'links'}} @if(isset($classes) && filled($classes) && is_array($classes)) {{ implode(' ', $classes) }} @endif">
                @foreach($items as $item)
                    <li><a class="{{ $item->css_classes?? '' }}" target="{{ $item->target?? '' }}" href="{{ $item->url?? '#' }}" title="{{ $item->title?? ''}}">{!! $item->label !!}</a></li>
                @endforeach
            </ul>
        @endif
    @endif
@endif