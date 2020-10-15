{{-- Stored in /resources/views/components/elements/style.blade.php --}}
@if(isset($type) && $type == 'link')
<link href="{{ $href?? '' }}" type="text/css" rel="stylesheet" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
@else
<style type="text/css" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
{!! $slot !!}
</style>
@endif