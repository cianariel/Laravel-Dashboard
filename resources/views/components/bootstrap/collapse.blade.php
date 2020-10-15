{{-- Stored in /resources/views/components/boostrap/collapse.blade.php --}}
@if(isset($type) && $type == 'accordion')
<div id="{{ $id ?? uniqid('gb-accordion-')}}" class="gb-accordion accordion @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
@endif
{!! $slot !!}
@if(isset($type) && $type == 'accordion')
</div>
@endif
