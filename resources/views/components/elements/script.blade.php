{{-- Stored in /resources/views/components/elements/script.blade.php --}}
<script type="text/javascript" @if(isset($src)) @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif src="{{ $src }}" @endif>
<!--
    {!! $slot !!}
//-->
</script>