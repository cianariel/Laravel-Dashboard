{{-- Stored in /resources/views/components/boostrap/container.blade.php --}}
<div id="{{ $id?? uniqid('gb-container-', false) }}" class="{{ (isset($fluid) && $fluid == true)? 'container-fluid' : 'container' }} @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
    {!! $slot !!}
</div>