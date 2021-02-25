{{-- Stored in /resources/views/components/boostrap/flex.blade.php --}}
<div id="{{ $id ?? uniqid('wv-flex-') }}" class="md-flex d-flex {{ (isset($reversed) && $reversed === true)? 'flex-reverse' : 'flex-row' }} justify-content-{{ $justify?? 'start' }} align-items-{{ $align_items?? 'start' }} align-self-{{ $align_self?? 'start' }} @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
    {!! $slot !!}
</div>