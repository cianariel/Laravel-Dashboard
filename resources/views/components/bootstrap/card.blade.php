{{-- Stored in /resources/views/components/boostrap/card.blade.php --}}
@if(isset($layout) && $layout == 'card-group')
<div class="gb-card-group card-group @if(isset($classes) && is_array($classes) && filled($classes)) {{ implode(' ', $classes) }}@endif">
@endif
@if(isset($layout) && $layout == 'card-deck')
<div class="gb-card-deck card-deck @if(isset($classes) && is_array($classes) && filled($classes)) {{ implode(' ', $classes) }}@endif">
@endif
@if(!isset($layout))
<div id="{{ $id?? uniqid('gb-card-', false) }}" class="card @if(isset($classes) && is_array($classes) && filled($classes)) {{ implode(' ', $classes) }}@endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
    {!! $slot !!}
</div>
@else
{!! $slot !!}
@endif
@if(isset($layout) && filled($layout))
</div>
@endif